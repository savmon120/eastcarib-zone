<?php

namespace Drupal\ecz_vatsim;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\State\StateInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Fetches, filters, and caches VATSIM division events.
 */
class EventsManager {

  const CACHE_ID = 'ecz_vatsim:filtered_events';
  const CACHE_TAG = 'ecz_vatsim_events';
  const STATE_LAST_SYNC = 'ecz_vatsim.events_last_sync';
  const SYNC_INTERVAL = 86400;
  const DEFAULT_EVENTS_URL = 'https://my.vatsim.net/api/v2/events/view/division/CAR';

  protected ClientInterface $httpClient;
  protected ConfigFactoryInterface $configFactory;
  protected CacheBackendInterface $cache;
  protected StateInterface $state;
  protected $logger;
  protected TimeInterface $time;
  protected DateFormatterInterface $dateFormatter;

  public function __construct(
    ClientInterface $http_client,
    ConfigFactoryInterface $config_factory,
    CacheBackendInterface $cache,
    StateInterface $state,
    LoggerChannelFactoryInterface $logger_factory,
    TimeInterface $time,
    DateFormatterInterface $date_formatter
  ) {
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
    $this->cache = $cache;
    $this->state = $state;
    $this->logger = $logger_factory->get('ecz_vatsim');
    $this->time = $time;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Returns the cached, filtered event list. Syncs on demand if empty.
   */
  public function getEvents(): array {
    $cache = $this->cache->get(self::CACHE_ID);
    if ($cache && isset($cache->data)) {
      return $cache->data;
    }
    // No cache yet (fresh install / cache cleared) — sync once so the block isn't empty, rather than waiting for the next cron run.
    return $this->sync();
  }

  /**
   * Whether more than SYNC_INTERVAL has elapsed since the last sync.
   */
  public function needsSync(): bool {
    $last_sync = (int) $this->state->get(self::STATE_LAST_SYNC, 0);
    return ($this->time->getRequestTime() - $last_sync) >= self::SYNC_INTERVAL;
  }

  /**
   * Fetches events from VATSIM, filters by configured prefixes, caches them.
   *
   * @return array
   *   The filtered event list (also what gets cached).
   */
  public function sync(): array {
    $config = $this->configFactory->get('ecz_vatsim.settings');
    $events_url = $config->get('events_url') ?: self::DEFAULT_EVENTS_URL;
    $prefixes = $this->getPrefixes($config);

    try {
      $response = $this->httpClient->get($events_url, ['timeout' => 10]);
      $raw = json_decode($response->getBody()->getContents(), TRUE);
      $events = (!empty($raw['data']) && is_array($raw['data'])) ? $raw['data'] : [];

      $filtered = [];
      foreach ($events as $event) {
        if (empty($event['airports']) || !is_array($event['airports'])) {
          continue;
        }

        $matched_airports = [];
        foreach ($event['airports'] as $airport) {
          $icao = strtoupper($airport['icao'] ?? '');
          if ($icao === '') {
            continue;
          }
          foreach ($prefixes as $prefix) {
            if ($prefix !== '' && strpos($icao, $prefix) === 0) {
              $matched_airports[$icao] = $icao;
              break;
            }
          }
        }

        if (empty($matched_airports)) {
          continue;
        }

        $filtered[] = [
          'id' => $event['id'] ?? NULL,
          'name' => $event['name'] ?? '',
          'link' => $event['link'] ?? '',
          'banner' => $event['banner'] ?? '',
          'short_description' => $event['short_description'] ?? '',
          'start_time' => $event['start_time'] ?? '',
          'end_time' => $event['end_time'] ?? '',
          'start_time_formatted' => $this->formatEventTime($event['start_time'] ?? ''),
          'end_time_formatted' => $this->formatEventTime($event['end_time'] ?? ''),
          'airports' => array_values($matched_airports),
        ];
      }

      usort($filtered, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));

      $this->cache->set(self::CACHE_ID, $filtered, Cache::PERMANENT, [self::CACHE_TAG]);
      $this->state->set(self::STATE_LAST_SYNC, $this->time->getRequestTime());

      return $filtered;
    }
    catch (RequestException $e) {
      $this->logger->error('Failed to fetch VATSIM events: @error', ['@error' => $e->getMessage()]);
      // Serve whatever is already cached (even if stale) rather than an empty block, so a transient API outage doesn't blank the homepage.
      $cache = $this->cache->get(self::CACHE_ID);
      return ($cache && isset($cache->data)) ? $cache->data : [];
    }
  }

  protected function formatEventTime(string $iso_time): string {
    if ($iso_time === '') {
      return '';
    }
    $timestamp = strtotime($iso_time);
    if ($timestamp === FALSE) {
      return '';
    }
    return $this->dateFormatter->format($timestamp, 'custom', 'D, d M Y H:i', NULL) . ' UTC';
  }

  protected function getPrefixes(ImmutableConfig $config): array {
    $raw = $config->get('prefixes') ?: '';
    return array_filter(array_map(fn($p) => strtoupper(trim($p)), explode(',', $raw)));
  }

}