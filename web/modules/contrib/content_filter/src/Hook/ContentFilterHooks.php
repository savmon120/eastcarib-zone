<?php

namespace Drupal\content_filter\Hook;

use Drupal\content_filter\CFService;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\views\ViewExecutable;

/**
 * Hook implementations for Content Filter.
 */
class ContentFilterHooks {

  use stringTranslationTrait;

  public function __construct(
    protected RouteMatchInterface $routeMatch,
    protected CurrentPathStack $currentPathStack,
    protected ConfigFactoryInterface $configFactory,
    protected CFService $contentFilterService,
  ) {
  }

  /**
   * Implements hook_form_alter().
   *
   * @phpstan-ignore-next-line
   */
  #[Hook('form_alter')]
  public function formAlter(&$form, FormStateInterface $form_state, $form_id): void {
    if ($form_id === 'views_exposed_form' && $form['#id'] == 'views-exposed-form-content-default') {
      $form_action = Url::fromRoute('<current>')->toString();
      $form['#action'] = $form_action;
    }
  }

  /**
   * Implements hook_views_pre_view().
   *
   * @phpstan-ignore-next-line
   */
  #[Hook('views_pre_view')]
  public function viewsPreView(ViewExecutable $view, $display_id, array $args): void {
    $route_name = $this->routeMatch->getRouteName();
    if ($route_name == 'content_filter.filtered') {
      if ($view->id() == 'content') {
        $node_type = $this->routeMatch->getParameter('node_type');
        // Set the add button into the view header.
        if ($header_options = $this->contentFilterService->getHeaderButton($node_type)) {
          $view->setHandler($display_id, 'header', 'area_text_custom', $header_options);
        }
        // Alter current view filters.
        $filters = $view->display_handler->getOption('filters');
        if ($altered_filters = $this->contentFilterService->alterViewFilters($filters, $node_type)) {
          $view->display_handler->overrideOption('filters', $altered_filters);
        }
      }
    }
  }

  /**
   * Implements hook_menu_links_discovered_alter().
   *
   * @phpstan-ignore-next-line
   */
  #[Hook('menu_links_discovered_alter')]
  public function menuLinksDiscoveredAlter(&$links): void {
    $settings = $this->configFactory->get('content_filter.settings');
    // Remove Filtered main menu if disabled on settings.
    if (!$settings->get('content_filter_menus')) {
      $links['content_filter.main']['enabled'] = 0;
    }
  }

}
