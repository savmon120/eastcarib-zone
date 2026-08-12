<?php

namespace Drupal\content_filter\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\content_filter\CFService;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines dynamic local tasks.
 */
class ContentFilterLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected CFService $contentFilterService,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id): ContentFilterLocalTasks|static {
    return new static(
      $container->get('config.factory'),
      $container->get('content_filter.content_filter_service'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {
    $settings = $this->configFactory->get('content_filter.settings');
    if ($filter_bundles = $settings->get('content_filter_bundles')) {
      $bundles = $this->contentFilterService->getAllBundles();
      $filter_bundles = array_filter($filter_bundles);
      if (count($filter_bundles) < 2) {
        // We create a fake sub-tab as we need 2 sub-tabs minimum to show.
        $this->derivatives['content_filter.all'] = $base_plugin_definition;
        $this->derivatives['content_filter.all']['route_name'] = 'system.admin_content';
        $this->derivatives['content_filter.all']['parent_id'] = 'system.admin_content';
        $this->derivatives['content_filter.all']['title'] = $this->t('All');
      }
      foreach ($filter_bundles as $bundle) {
        $this->derivatives['content_filter.' . $bundle] = $base_plugin_definition;
        $this->derivatives['content_filter.' . $bundle]['route_name'] = 'content_filter.filtered';
        $this->derivatives['content_filter.' . $bundle]['route_parameters'] = ['node_type' => $bundle];
        $this->derivatives['content_filter.' . $bundle]['parent_id'] = 'system.admin_content';
        $this->derivatives['content_filter.' . $bundle]['title'] = ucfirst(str_replace('_', ' ', $bundles[$bundle]));
      }
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
