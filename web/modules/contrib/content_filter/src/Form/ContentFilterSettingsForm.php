<?php

namespace Drupal\content_filter\Form;

use Drupal\content_filter\CFService;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form for Content Filter.
 */
class ContentFilterSettingsForm extends ConfigFormBase {

  public function __construct(
    protected ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typedConfigManager,
    protected RouteBuilderInterface $routerBuilder,
    protected CFService $contentFilterService,
  ) {
    parent::__construct($config_factory, $typedConfigManager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): ContentFilterSettingsForm|static {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('router.builder'),
      $container->get('content_filter.content_filter_service'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'content_filter_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('content_filter.settings');
    $form = parent::buildForm($form, $form_state);
    $form['bundles_section'] = [
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
    ];
    $options = $this->contentFilterService->getAllBundles();
    $form['bundles_section']['content_filter_bundles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select bundles'),
      '#options' => $options,
      '#default_value' => $config->get('content_filter_bundles'),
      '#description' => $this->t('Select bundles to get filtered exclusive page.'),
    ];
    $form['options_section'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Options'),
      '#collapsible' => FALSE,
    ];
    $form['options_section']['content_filter_menus'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create specific menus under Content menu'),
      '#default_value' => $config->get('content_filter_menus'),
      '#description' => $this->t('If checked, Content filter will create a new submenu under the Content menu.'),
    ];
    // @todo Enable when filters can be defined by bundle.
    if (FALSE) {
      $form['filters_section'] = [
        '#type' => 'fieldset',
        '#collapsible' => FALSE,
      ];
      $options = $this->contentFilterService->getViewFilters();
      // This will always be hidden by default.
      unset($options['status_extra']);
      unset($options['type']);
      $default_filters = $config->get('content_filter_filters');
      if (empty($default_filters)) {
        // First time.
        $default_filters = array_keys($options);
      }
      else {
        $default_filters = array_keys(array_filter($default_filters));
      }
      $form['filters_section']['content_filter_filters'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Enable or disable view filters'),
        '#options' => $options,
        '#default_value' => $default_filters,
        '#description' => $this->t('Select the filters that will be shown.'),
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    // @todo Enable when filters can be defined by bundle.
    $values['content_filter_filters'] = [];
    $this->config('content_filter.settings')
      ->set('content_filter_bundles', $values['content_filter_bundles'])
      ->set('content_filter_menus', $values['content_filter_menus'])
      ->set('content_filter_filters', $values['content_filter_filters'])
      ->save();
    parent::submitForm($form, $form_state);
    // Menus are cached, so on each change, we need to clear menus cache.
    $this->routerBuilder->rebuild();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['content_filter.settings'];
  }

}
