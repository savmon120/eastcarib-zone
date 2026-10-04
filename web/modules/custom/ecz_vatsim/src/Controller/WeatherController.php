<?php

namespace Drupal\ecz_vatsim\Controller;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Airport weather dashboard: METAR/TAF from aviationweather.gov.
 */
class WeatherController extends ControllerBase {

  const METAR_URL = 'https://aviationweather.gov/api/data/metar';
  const AIRPORT_URL = 'https://aviationweather.gov/api/data/airport';
  const CACHE_TTL = 300;
  const AIRPORT_CACHE_TTL = 604800;
  const DEFAULT_PREFIXES = 'TNCA, TNCB, TNCC, TNCM, TQPF, TNCS, TNCE, TNCF, TBPB, TFFF, TFFR, TAPA, TGPY, TVSA, TLPL, TDPD, TKPK, TTPP';

  protected ClientInterface $httpClient;
  protected CacheBackendInterface $cache;

  public function __construct(ClientInterface $http_client, CacheBackendInterface $cache) {
    $this->httpClient = $http_client;
    $this->cache = $cache;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('cache.default')
    );
  }

  public function page(string $icao): array {
    $icao = $this->validateIcao($icao);

    return [
      '#theme' => 'ecz_vatsim_weather',
      '#icao' => $icao,
      '#airports' => $this->getPrefixes(),
      '#attached' => [
        'library' => ['east_caribbean/weather'],
        'drupalSettings' => [
          'eczWeather' => [
            'icao' => $icao,
            'refreshRate' => self::CACHE_TTL,
          ],
        ],
      ],
    ];
  }

  public function title(string $icao): string {
    return (string) $this->t('@icao Weather', ['@icao' => strtoupper($icao)]);
  }

  public function data(string $icao): JsonResponse {
    $icao = $this->validateIcao($icao);
    $cid = 'ecz_vatsim:weather:' . $icao;

    if ($cache = $this->cache->get($cid)) {
      return new JsonResponse($this->withSiteData($cache->data));
    }

    try {
      $raw = $this->fetchMetars([$icao]);
    }
    catch (GuzzleException $e) {
      $this->getLogger('ecz_vatsim')->error('Failed to fetch METAR for @icao: @error', [
        '@icao' => $icao,
        '@error' => $e->getMessage(),
      ]);
      // Serve the last good report rather than blanking the dashboard.
      $stale = $this->cache->get($cid, TRUE);
      return $stale
        ? new JsonResponse($this->withSiteData($stale->data))
        : new JsonResponse(['error' => 'Unable to load weather data.'], 502);
    }

    if (empty($raw[0]) || !is_array($raw[0])) {
      return new JsonResponse(['error' => "No METAR available for $icao."], 404);
    }

    $payload = $this->normalize($raw[0]);
    $payload['runway'] = $this->activeRunway($this->getRunways($icao), $payload['wind']);
    $this->cache->set($cid, $payload, time() + self::CACHE_TTL);

    return new JsonResponse($this->withSiteData($payload));
  }

  /**
   * Current conditions for every configured airport, for the overview table.
   */
  public function overview(): JsonResponse {
    $cid = 'ecz_vatsim:weather:overview';

    if ($cache = $this->cache->get($cid)) {
      return new JsonResponse($cache->data);
    }

    try {
      $raw = $this->fetchMetars($this->getPrefixes());
    }
    catch (GuzzleException $e) {
      $this->getLogger('ecz_vatsim')->error('Failed to fetch METAR overview: @error', ['@error' => $e->getMessage()]);
      $stale = $this->cache->get($cid, TRUE);
      return $stale
        ? new JsonResponse($stale->data)
        : new JsonResponse(['error' => 'Unable to load weather data.'], 502);
    }

    $rows = [];
    foreach ($raw as $metar) {
      if (!is_array($metar)) {
        continue;
      }
      $m = $this->normalize($metar);
      $rows[] = [
        'icao' => $m['icao'],
        'flight_category' => $m['flight_category'],
        'wind' => $m['wind'],
        'visibility' => $m['visibility'],
        'clouds' => $m['clouds'],
        'temperature' => $m['temperature'],
        'qnh_hpa' => $m['qnh_hpa'],
      ];
    }
    usort($rows, fn($a, $b) => strcmp($a['icao'], $b['icao']));

    $payload = ['airports' => $rows];
    $this->cache->set($cid, $payload, time() + self::CACHE_TTL);

    return new JsonResponse($payload);
  }

  protected function fetchMetars(array $icaos): array {
    $response = $this->httpClient->get(self::METAR_URL, [
      'query' => ['ids' => implode(',', $icaos), 'format' => 'json', 'taf' => 'true'],
      'timeout' => 10,
    ]);
    return json_decode($response->getBody()->getContents(), TRUE) ?: [];
  }

  /**
   * Flattens the aviationweather.gov record into what the dashboard shows.
   */
  protected function normalize(array $m): array {
    $raw_ob = $m['rawOb'] ?? '';
    $qnh = isset($m['altim']) ? (float) $m['altim'] : NULL;
    $elevation = isset($m['elev']) ? (float) $m['elev'] : NULL;

    // Visibility: prefer the metric group from the raw report (e.g. 9999),
    // since the API converts everything to statute miles.
    $vis_m = NULL;
    if (preg_match('/\bCAVOK\b/', $raw_ob)) {
      $vis_m = 9999;
    }
    elseif (preg_match('/KT(?:\s+\d{3}V\d{3})?\s+(\d{4})(?:NDV)?\s/', $raw_ob, $match)) {
      $vis_m = (int) $match[1];
    }

    $variable = NULL;
    if (preg_match('/KT\s+(\d{3})V(\d{3})\b/', $raw_ob, $match)) {
      $variable = ['from' => (int) $match[1], 'to' => (int) $match[2]];
    }

    $clouds = [];
    $ceiling = NULL;
    foreach ($m['clouds'] ?? [] as $layer) {
      $clouds[] = ['cover' => $layer['cover'] ?? '', 'base' => $layer['base'] ?? NULL];
      if ($ceiling === NULL && in_array($layer['cover'] ?? '', ['BKN', 'OVC', 'OVX'], TRUE)) {
        $ceiling = $layer['base'] ?? NULL;
      }
    }

    // QFE from QNH via the standard-atmosphere pressure/height relation.
    $qfe = ($qnh !== NULL && $elevation !== NULL)
      ? $qnh * pow(1 - 0.0065 * $elevation / 288.15, 5.255)
      : NULL;

    return [
      'icao' => $m['icaoId'] ?? '',
      'name' => $m['name'] ?? '',
      'lat' => $m['lat'] ?? NULL,
      'lon' => $m['lon'] ?? NULL,
      'elevation_m' => $elevation,
      'observed' => isset($m['obsTime']) ? (int) $m['obsTime'] : NULL,
      'flight_category' => $m['fltCat'] ?? NULL,
      'wind' => [
        'direction' => is_numeric($m['wdir'] ?? NULL) ? (int) $m['wdir'] : NULL,
        'variable' => ($m['wdir'] ?? NULL) === 'VRB',
        'variable_range' => $variable,
        'speed' => isset($m['wspd']) ? (int) $m['wspd'] : NULL,
        'gust' => isset($m['wgst']) ? (int) $m['wgst'] : NULL,
      ],
      'visibility' => [
        'meters' => $vis_m,
        'statute_miles' => $m['visib'] ?? NULL,
      ],
      'temperature' => $m['temp'] ?? NULL,
      'dewpoint' => $m['dewp'] ?? NULL,
      'qnh_hpa' => $qnh !== NULL ? round($qnh) : NULL,
      'qnh_inhg' => $qnh !== NULL ? round($qnh * 0.0295300, 2) : NULL,
      'qfe_hpa' => $qfe !== NULL ? round($qfe, 1) : NULL,
      'qfe_inhg' => $qfe !== NULL ? round($qfe * 0.0295300, 2) : NULL,
      'weather' => $m['wxString'] ?? NULL,
      'clouds' => $clouds,
      'ceiling' => $ceiling,
      'raw_metar' => $raw_ob,
      'raw_taf' => $m['rawTaf'] ?? NULL,
    ];
  }

  /**
   * Runway ends with their true headings, e.g. [['id' => '09', 'heading' => 91], ...].
   */
  protected function getRunways(string $icao): array {
    $cid = 'ecz_vatsim:airport:' . $icao;
    if ($cache = $this->cache->get($cid)) {
      return $cache->data;
    }

    try {
      $response = $this->httpClient->get(self::AIRPORT_URL, [
        'query' => ['ids' => $icao, 'format' => 'json'],
        'timeout' => 10,
      ]);
      $raw = json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (GuzzleException $e) {
      $this->getLogger('ecz_vatsim')->warning('Failed to fetch runways for @icao: @error', [
        '@icao' => $icao,
        '@error' => $e->getMessage(),
      ]);
      return [];
    }

    $ends = [];
    foreach ($raw[0]['runways'] ?? [] as $runway) {
      $ids = explode('/', $runway['id'] ?? '');
      $alignment = $runway['alignment'] ?? NULL;
      // Skip helipads and anything without a usable heading.
      if (count($ids) !== 2 || !is_numeric($alignment) || !preg_match('/^\d{2}/', $ids[0])) {
        continue;
      }
      $ends[] = ['id' => $ids[0], 'heading' => (int) $alignment];
      $ends[] = ['id' => $ids[1], 'heading' => ((int) $alignment + 180) % 360];
    }

    $this->cache->set($cid, $ends, time() + self::AIRPORT_CACHE_TTL);
    return $ends;
  }

  /**
   * Picks the runway end with the most headwind and its wind components.
   *
   * Crosswind is signed: positive from the right, negative from the left.
   */
  protected function activeRunway(array $ends, array $wind): ?array {
    if (!$ends) {
      return NULL;
    }
    $has_wind = $wind['direction'] !== NULL && $wind['speed'];

    $best = NULL;
    foreach ($ends as $end) {
      $angle = $has_wind ? deg2rad($wind['direction'] - $end['heading']) : 0;
      $headwind = $has_wind ? $wind['speed'] * cos($angle) : 0;
      if ($best === NULL || $headwind > $best['headwind']) {
        $best = $end + [
          'headwind' => $headwind,
          'crosswind' => $has_wind ? $wind['speed'] * sin($angle) : 0,
        ];
      }
    }

    $best['headwind'] = (int) round($best['headwind']);
    $best['crosswind'] = (int) round($best['crosswind']);
    return $best;
  }

  /**
   * Adds the name and FIR from the airport's Featured Airport node, if there is one.
   *
   * The FIR code is read from the term name, e.g. "Curaçao FIR (TNCF)" → TNCF,
   * so the dashboard can count that FIR's centre controllers as covering the
   * airport. Done after the cache so edits to the node show up immediately.
   */
  protected function withSiteData(array $payload): array {
    $payload['fir'] = NULL;

    $nids = $this->entityTypeManager()->getStorage('node')->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'featured_airport')
      ->condition('title', $payload['icao'])
      ->condition('status', 1)
      ->range(0, 1)
      ->execute();

    if ($nids) {
      $node = $this->entityTypeManager()->getStorage('node')->load(reset($nids));
      if ($node->hasField('field_airport_name') && !$node->get('field_airport_name')->isEmpty()) {
        $payload['name'] = $node->get('field_airport_name')->value;
      }
      if ($node->hasField('field_fir') && ($fir = $node->get('field_fir')->entity)) {
        if (preg_match('/\(([A-Z]{4})\)/', $fir->label(), $match)) {
          $payload['fir'] = $match[1];
        }
      }
    }
    return $payload;
  }

  /**
   * Only serves airports in the configured prefixes, so this isn't an open proxy.
   */
  protected function validateIcao(string $icao): string {
    $icao = strtoupper($icao);
    if (!preg_match('/^[A-Z]{4}$/', $icao)) {
      throw new NotFoundHttpException();
    }
    foreach ($this->getPrefixes() as $prefix) {
      if (strpos($icao, $prefix) === 0) {
        return $icao;
      }
    }
    throw new NotFoundHttpException();
  }

  protected function getPrefixes(): array {
    $raw = $this->config('ecz_vatsim.settings')->get('prefixes') ?: self::DEFAULT_PREFIXES;
    return array_values(array_filter(array_map(fn($p) => strtoupper(trim($p)), explode(',', $raw))));
  }

}
