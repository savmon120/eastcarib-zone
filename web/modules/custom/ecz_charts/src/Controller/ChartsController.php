<?php

namespace Drupal\ecz_charts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The charts page: airports along the top, chart columns and a PDF viewer.
 */
class ChartsController extends ControllerBase {

  /**
   * Chart columns, in display order (field_chart_category values).
   */
  const CATEGORIES = [
    'STAR' => 'STAR',
    'APP' => 'APP',
    'TAXI' => 'TAXI',
    'SID' => 'SID',
    'REF' => 'REF',
  ];

  protected FileUrlGeneratorInterface $fileUrlGenerator;

  public function __construct(FileUrlGeneratorInterface $file_url_generator) {
    $this->fileUrlGenerator = $file_url_generator;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('file_url_generator'));
  }

  public function page(string $icao): array {
    $airports = $this->getAirports();
    $icao = strtoupper($icao);
    // An unknown airport (or none) falls back to the first one.
    if (!isset($airports[$icao])) {
      $icao = array_key_first($airports);
    }

    return [
      '#theme' => 'ecz_charts',
      '#airports' => $airports,
      '#categories' => self::CATEGORIES,
      '#icao' => $icao,
      '#attached' => [
        'library' => ['east_caribbean/charts'],
      ],
      '#cache' => [
        'tags' => ['node_list:chart', 'node_list:featured_airport'],
        'contexts' => ['url.path', 'user.permissions'],
      ],
    ];
  }

  /**
   * Published charts grouped by airport, then by category.
   *
   * Only airports with at least one chart are included, sorted by ICAO code.
   *
   * @return array
   *   Keyed by ICAO code: ['icao', 'name', 'charts' => [category => [chart]]],
   *   where a chart is ['id', 'name', 'url'].
   */
  protected function getAirports(): array {
    $storage = $this->entityTypeManager()->getStorage('node');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'chart')
      ->condition('status', 1)
      ->exists('field_chart_file')
      ->sort('field_weight')
      ->sort('title')
      ->execute();

    $airports = [];
    foreach ($storage->loadMultiple($ids) as $chart) {
      $airport = $chart->get('field_chart_airport')->entity;
      $category = $chart->get('field_chart_category')->value;
      $file = $chart->get('field_chart_file')->entity;
      if (!$airport || !$file || !isset(self::CATEGORIES[$category]) || !$airport->access('view')) {
        continue;
      }

      $icao = strtoupper(trim($airport->label()));
      $airports[$icao] ??= [
        'icao' => $icao,
        'name' => $airport->hasField('field_airport_name') ? $airport->get('field_airport_name')->value : '',
        'charts' => array_fill_keys(array_keys(self::CATEGORIES), []),
      ];
      $airports[$icao]['charts'][$category][] = [
        'id' => $chart->id(),
        'name' => $chart->label(),
        'url' => $this->fileUrlGenerator->generateString($file->getFileUri()),
      ];
    }

    ksort($airports);
    return $airports;
  }

}
