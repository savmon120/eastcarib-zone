<?php

namespace Drupal\ecz_maptool\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ExtensionPathResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The map tool page: airspace sidebar on the left, MapLibre map on the right.
 */
class MaptoolController extends ControllerBase {

  protected ExtensionPathResolver $pathResolver;

  public function __construct(ExtensionPathResolver $path_resolver) {
    $this->pathResolver = $path_resolver;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('extension.path.resolver'));
  }

  public function page(): array {
    return [
      '#theme' => 'ecz_maptool',
      '#attached' => [
        'library' => ['ecz_maptool/maptool'],
        // app.js loads its GeoJSON from here.
        'drupalSettings' => [
          'eczMaptool' => [
            'dataPath' => base_path() . $this->pathResolver->getPath('module', 'ecz_maptool') . '/data/',
          ],
        ],
      ],
    ];
  }

}
