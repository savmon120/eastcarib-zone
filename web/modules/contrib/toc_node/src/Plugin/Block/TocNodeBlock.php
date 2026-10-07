<?php

namespace Drupal\toc_node\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Markup;
use Drupal\node\NodeInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Table Of Contents' block.
 *
 * @Block(
 *   id = "toc_node",
 *   admin_label = @Translation("Table Of Contents"),
 *   category = @Translation("Content")
 * )
 */
class TocNodeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route match.
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * The entity type manager.
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The renderer.
   */
  protected RendererInterface $renderer;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');

    if (!$node instanceof NodeInterface) {
      return [];
    }

    $type_settings = _toc_node_type_settings($node->bundle());
    if (empty($type_settings['enabled'])) {
      return [];
    }

    // Block settings take priority; fall back to node/content type.
    $style = (string) ($this->configuration['style'] ?? '');
    if ($style === '') {
      $style = (string) ($node->toc_node_style ?? $type_settings['style_default'] ?? 'bullets');
    }
    if ($style === '' || $style === 'none') {
      $style = 'bullets';
    }
    $style = _toc_node_valid_style($style);

    $level = $this->configuration['level'] ?: $node->toc_node_level ?? $type_settings['level'];
    $level = _toc_node_clamp_level($level);

    $view_builder = $this->entityTypeManager->getViewBuilder('node');
    $build = $view_builder->view($node, 'full');
    $node_content = $this->renderer->render($build);

    $toc = _toc_node_generate($node_content, $style, $level, 0, 'toc_list');

    if (empty($toc)) {
      return [];
    }

    $result = [
      '#markup' => Markup::create($toc),
      '#attached' => [
        'library' => ['toc_node/toc_node'],
      ],
      '#cache' => [
        'contexts' => ['url.path', 'user.permissions'],
        'tags' => ['node:' . $node->id()],
      ],
    ];

    $back_to_top = (int) ($this->configuration['back_to_top'] ?? 0);
    if ($back_to_top && strpos($node_content, 'toc-back-to-top') === FALSE) {
      $result['#markup'] = Markup::create($toc . _toc_node_back_to_top_button());
      $result['#attached']['library'][] = 'toc_node/back_to_top';
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['toc_node'] = [
      '#type' => 'details',
      '#title' => $this->t('Table of Contents options'),
      '#open' => TRUE,
    ];

    $form['toc_node']['style'] = [
      '#type' => 'select',
      '#title' => $this->t('Display style'),
      '#description' => $this->t('Override the node/content type TOC style. Leave empty to inherit.'),
      '#default_value' => $this->configuration['style'] ?? '',
      '#empty_option' => $this->t('- Inherit from node -'),
      '#options' => [
        'bullets' => $this->t('Bullets'),
        'numbers' => $this->t('Numbered'),
      ],
    ];

    $form['toc_node']['level'] = [
      '#type' => 'select',
      '#title' => $this->t('Heading level'),
      '#description' => $this->t('Override the node/content type heading level. Leave empty to inherit.'),
      '#default_value' => $this->configuration['level'] ?? '',
      '#empty_option' => $this->t('- Inherit from node -'),
      '#options' => [2 => 'h2', 3 => 'h3', 4 => 'h4', 5 => 'h5', 6 => 'h6'],
    ];

    $form['toc_node']['back_to_top'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Back to top links'),
      '#description' => $this->t('Show a floating back-to-top button in the block content.'),
      '#default_value' => $this->configuration['back_to_top'] ?? 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValue('toc_node');
    $this->configuration['style'] = $values['style'] ?: '';
    $this->configuration['level'] = $values['level'] ?: '';
    $this->configuration['back_to_top'] = (int) ($values['back_to_top'] ?? 0);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'style' => '',
      'level' => '',
      'back_to_top' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['url.path', 'user.permissions'];
  }

}
