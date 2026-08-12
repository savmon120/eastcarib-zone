<?php

namespace Drupal\content_filter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\system\SystemManager;

/**
 * Class for Main index controller.
 */
class MenuIndexController extends ControllerBase {

  public function __construct(
    protected SystemManager $systemManager,
  ) {
  }

  /**
   * Generates system manager default distributive page.
   *
   * As we can't have menu & submenu created dynamically from deriver,
   * to make it work, we replicate default behavior from own controller.
   *
   * @return array
   *   Render array.
   */
  public function getContent(): array {
    return $this->systemManager->getBlockContents();
  }

}
