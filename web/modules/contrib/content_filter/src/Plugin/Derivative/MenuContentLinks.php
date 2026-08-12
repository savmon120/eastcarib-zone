<?php

namespace Drupal\content_filter\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\content_filter\CFService;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines dynamic menus.
 */
class MenuContentLinks extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected CFService $contentFilterService,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id): MenuContentLinks|static {
    return new static(
      $container->get('config.factory'),
      $container->get('content_filter.content_filter_service'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {
    $links = [];
    $settings = $this->configFactory->get('content_filter.settings');
    if ($settings->get('content_filter_menus')) {
      if ($filter_bundles = $settings->get('content_filter_bundles')) {
        $bundles = $this->contentFilterService->getAllBundles();
        $filter_bundles = array_filter($filter_bundles);
        foreach ($filter_bundles as $bundle) {
          $links['content_filter.' . $bundle] = [
            'id' => 'content_filter.' . $bundle,
            'title' => $bundles[$bundle],
            'route_name' => 'content_filter.filtered',
            'route_parameters' => [
              'node_type' => $bundle,
            ],
            'parent' => 'content_filter.main',
          ] + $base_plugin_definition;
        }
      }
    }
    return $links;
  }

}
