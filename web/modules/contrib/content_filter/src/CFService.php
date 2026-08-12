<?php

namespace Drupal\content_filter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Routing\RedirectDestinationTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\node\Entity\NodeType;
use Drupal\views\Entity\View;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class for Content Filter service.
 */
class CFService {

  use RedirectDestinationTrait;
  use stringTranslationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected RequestStack $requestStack,
    protected EntityFieldManagerInterface $entityFieldManager,
    protected RendererInterface $renderer,
    protected ConfigFactoryInterface $configFactory,
  ) {
  }

  /**
   * Gets all bundles in Database.
   *
   * @return array
   *   All bundles.
   */
  public function getAllBundles(): array {
    $node_types = NodeType::loadMultiple();
    $options = [];
    foreach ($node_types as $node_type) {
      $options[$node_type->id()] = $node_type->label();
    }
    return $options;
  }

  /**
   * Get all available filters for the content view.
   *
   * @return array
   *   All the filters available.
   */
  public function getViewFilters(): array {
    $view = View::load('content');
    $view = $view->getExecutable();
    $view->setDisplay('default');
    $filters = $view->display_handler->getOption('filters');
    $options = [];
    foreach ($filters as $key => $filter) {
      $options[$key] = ucfirst(str_replace('_', ' ', $key));
    }
    return $options + $this->getExtraFilters();
  }

  /**
   * Returns extra filters proposed by the Content filter module.
   *
   * @return string[]
   *   Extra filters.
   */
  private function getExtraFilters(): array {
    return [
      'uid' => 'Uid',
      'promote' => 'Promoted',
      'sticky' => 'Sticky',
    ];
  }

  /**
   * Creates view header button.
   *
   * @param string $bundle
   *   Bundle filtered by.
   *
   * @return array
   *   View render area options.
   */
  public function getHeaderButton(string $bundle): array {
    $bundles = $this->getAllBundles();
    if (!isset($bundles[$bundle])) {
      return [];
    }
    $url = Url::fromRoute('entity.node.add_form', ['node_type' => $bundle]);
    $url->setOptions([
      'attributes' => [
        'class' => ['button', 'button--action', 'button--primary'],
      ],
      'query' => $this->getDestinationArray(),
    ]);
    $link = Link::fromTextAndUrl($this->t('Add @bundle', ['@bundle' => $bundles[$bundle]]), $url);
    $render_link = $link->toRenderable();
    $header = $this->renderer->renderRoot($render_link);
    return [
      'id' => 'content_filter_header',
      'table' => 'views',
      'field' => 'area_text_custom',
      'relationship' => 'none',
      'group_type' => 'none',
      'admin_label' => '',
      'empty' => FALSE,
      'tokenize' => TRUE,
      'content' => $header,
      'plugin_id' => 'views_add_button_area',
    ];
  }

  /**
   * Alters view filtering.
   *
   * @param array $filters
   *   Current view filters.
   * @param string $bundle
   *   Bundle filtered by.
   *
   * @return array
   *   Altered filters.
   */
  public function alterViewFilters(array $filters, string $bundle): array {
    $config = $this->configFactory->get('content_filter.settings');
    $modified = FALSE;
    if (!empty($filters['type'])) {
      // Set bundle as default Content Type filter & hide it.
      $filters['type']['value'] = [$bundle];
      $filters['type']['exposed'] = FALSE;
      $filters['type']['is_grouped'] = FALSE;
      $modified = TRUE;
    }
    // @todo Enable when filters can be defined by bundle.
    // Check for filters in settings.
    $default_filters = $config->get('content_filter_filters');
    if (FALSE && !empty($default_filters)) {
      foreach ($default_filters as $filter => $enabled) {
        // Hides disabled by configuration filters.
        if (!$enabled && isset($filters[$filter])) {
          $this->hideFilter($filters, $filter);
          $modified = TRUE;
        }
        // Shows enabled by configuration filters.
        if ($enabled && !isset($filters[$filter])) {
          $this->showFilters($filters, $filter);
          $modified = TRUE;
        }
      }
    }
    if ($modified) {
      return $filters;
    }
    return [];
  }

  /**
   * Hides view exposed filter.
   *
   * @param array $filters
   *   Current view filters.
   * @param string $key
   *   Filter to hide.
   */
  private function hideFilter(array &$filters, string $key): void {
    $filters[$key]['exposed'] = FALSE;
    $filters[$key]['is_grouped'] = FALSE;
  }

  /**
   * Shows view exposed filter.
   *
   * @param array $filters
   *   Current view filters.
   * @param string $key
   *   Filter to hide.
   */
  private function showFilters(array &$filters, string $key): void {
    $config = [
      'id' => $key,
      'table' => 'node_field_data',
      'field' => $key,
      'relationship' => 'none',
      'group_type' => 'group',
      'admin_label' => '',
      'entity_type' => 'node',
      'entity_field' => $key,
      // Overwritten.
      'plugin_id' => 'boolean',
      // Overwritten.
      'operator' => '=',
      // Overwritten.
      'value' => 'All',
      'group' => 1,
      'exposed' => TRUE,
      'expose' => [
        // Overwritten.
        'operator_id' => '',
        // Overwritten.
        'label' => 'Promoted to front page status',
        'description' => '',
        'use_operator' => FALSE,
        // Overwritten.
        'operator' => 'promote_op',
        'operator_limit_selection' => FALSE,
        'operator_list' => [],
        'identifier' => $key,
        'required' => FALSE,
        'remember' => FALSE,
        'multiple' => FALSE,
        'remember_roles' => [
          'authenticated' => 'authenticated',
        ],
      ],
      'is_grouped' => FALSE,
      'group_info' => [
        'label' => '',
        'description' => '',
        'identifier' => '',
        'optional' => TRUE,
        'widget' => 'select',
        'multiple' => FALSE,
        'remember' => FALSE,
        'default_group' => 'All',
        'default_group_multiple' => [],
        'group_items' => [],
      ],
    ];
    $this->getExclusiveFilterOptions($config, $key);
    $filters[$key] = $config;
  }

  /**
   * Overwrite shared filter configuration with exclusive options by key.
   *
   * @param array $config
   *   Shared filter configuration.
   * @param string $key
   *   Filter key.
   */
  private function getExclusiveFilterOptions(array &$config, string $key): void {
    switch ($key) {
      case 'uid':
        $config['plugin_id'] = 'user_name';
        $config['operator'] = 'in';
        $config['value'] = [];
        $config['expose']['operator_id'] = 'uid_op';
        $config['expose']['label'] = $this->t('Authored by');
        $config['expose']['operator'] = 'uid_op';
        $config['expose']['reduce'] = FALSE;
        break;

      case 'promote':
        $config['plugin_id'] = 'boolean';
        $config['operator'] = '=';
        $config['value'] = 'All';
        $config['expose']['operator_id'] = '';
        $config['expose']['label'] = $this->t('Promoted');
        $config['expose']['operator'] = 'promote_op';
        break;

      case 'sticky':
        $config['plugin_id'] = 'boolean';
        $config['operator'] = '=';
        $config['value'] = 'All';
        $config['expose']['operator_id'] = '';
        $config['expose']['label'] = $this->t('Sticky');
        $config['expose']['operator'] = 'sticky_op';
        break;
    }
  }

}
