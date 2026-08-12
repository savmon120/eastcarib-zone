<?php

namespace Drupal\content_filter\Controller;

use Drupal\content_filter\CFService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Class for Main content filter controller.
 */
class ContentPageController extends ControllerBase {

  public function __construct(
    protected CFService $contentFilterService,
  ) {
  }

  /**
   * Loads the content view if $node_type exists.
   *
   * @param string $node_type
   *   The filtered bundle.
   *
   * @return array
   *   A render array.
   */
  public function getBlockContents(string $node_type): array {
    $bundles = $this->contentFilterService->getAllBundles();
    if (!isset($bundles[$node_type])) {
      $this->messenger()->addError($this->t('The node type %type does not exist.', ['%type' => $node_type]));
      return [];
    }
    $render['view'] = views_embed_view('content');
    return $render;
  }

  /**
   * Gets page title.
   *
   * @param string $node_type
   *   The filtered bundle.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|string
   *   The page title.
   */
  public function getTitle(string $node_type): string|TranslatableMarkup {
    $bundles = $this->contentFilterService->getAllBundles();
    if (!isset($bundles[$node_type])) {
      return $this->t('Nonexistent node type');
    }
    return $this->t('Content type @bundle', ['@bundle' => $bundles[$node_type]]);
  }

}
