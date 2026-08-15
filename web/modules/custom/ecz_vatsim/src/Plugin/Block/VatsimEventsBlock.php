<?php

namespace Drupal\ecz_vatsim\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ecz_vatsim\EventsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a 'VATSIM Upcoming Events' block.
 *
 * @Block(
 *   id = "ecz_vatsim_events_block",
 *   admin_label = @Translation("VATSIM Upcoming Events"),
 *   category = @Translation("ECZ VATSIM"),
 * )
 */
class VatsimEventsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected EventsManager $eventsManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EventsManager $events_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->eventsManager = $events_manager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('ecz_vatsim.events_manager')
    );
  }

  public function defaultConfiguration(): array {
    return [
      'limit' => 5,
    ] + parent::defaultConfiguration();
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of events to show'),
      '#description' => $this->t('The full list is always available on the "view all" page regardless of this setting.'),
      '#default_value' => $this->configuration['limit'],
      '#min' => 1,
      '#max' => 50,
    ];
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $this->configuration['limit'] = (int) $form_state->getValue('limit');
  }

  public function build(): array {
    $events = $this->eventsManager->getEvents();
    $limit = $this->configuration['limit'] ?? 5;
    $total = count($events);

    return [
      '#theme' => 'ecz_vatsim_events',
      '#events' => array_slice($events, 0, $limit),
      '#show_view_all' => $total > $limit,
      '#view_all_url' => Url::fromRoute('ecz_vatsim.events_page')->toString(),
      '#cache' => [
        'tags' => Cache::mergeTags($this->getCacheTags(), [EventsManager::CACHE_TAG]),
        'contexts' => $this->getCacheContexts(),
        'max-age' => $this->getCacheMaxAge(),
      ],
    ];
  }

  public function getCacheMaxAge(): int {
    // Bound the render cache to an hour even though the data itself is
    // only refreshed daily by cron — keeps the block reasonably fresh
    // without hammering the cache/DB on every request.
    return 3600;
  }

}