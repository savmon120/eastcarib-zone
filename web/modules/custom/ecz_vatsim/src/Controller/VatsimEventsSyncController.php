<?php

namespace Drupal\ecz_vatsim\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\ecz_vatsim\EventsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VatsimEventsSyncController extends ControllerBase {

  protected EventsManager $eventsManager;

  public function __construct(EventsManager $events_manager) {
    $this->eventsManager = $events_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('ecz_vatsim.events_manager'));
  }

  public function sync(): RedirectResponse {
    $events = $this->eventsManager->sync();
    $this->messenger()->addStatus($this->t('VATSIM events synced. @count matching event(s) found.', [
      '@count' => count($events),
    ]));
    return new RedirectResponse(Url::fromRoute('ecz_vatsim.settings')->toString());
  }

}