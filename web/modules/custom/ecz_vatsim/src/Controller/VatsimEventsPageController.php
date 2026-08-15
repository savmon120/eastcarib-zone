<?php

namespace Drupal\ecz_vatsim\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ecz_vatsim\EventsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VatsimEventsPageController extends ControllerBase {

  protected EventsManager $eventsManager;

  public function __construct(EventsManager $events_manager) {
    $this->eventsManager = $events_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('ecz_vatsim.events_manager'));
  }

  public function viewAll(): array {
    $events = $this->eventsManager->getEvents();

    return [
      '#theme' => 'ecz_vatsim_events',
      '#events' => $events,
      '#show_view_all' => FALSE,
      '#cache' => [
        'tags' => [EventsManager::CACHE_TAG],
        'max-age' => 3600,
      ],
    ];
  }

}