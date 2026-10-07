<?php

namespace Drupal\toc_node\Render;

use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\node\NodeInterface;

/**
 * Trusted callbacks for TOC Node rendering.
 */
class TocNodeRenderCallbacks implements TrustedCallbackInterface {

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks(): array {
    return ['postRender'];
  }

  /**
   * Post render callback to generate TOC from rendered HTML.
   */
  public static function postRender($content, array $element): string {
    $node = $element['#node'] ?? NULL;
    if (!$node instanceof NodeInterface) {
      return $content;
    }
    $type_settings = _toc_node_type_settings($node->bundle());

    $style = $element['#toc_node_style'] ?? $node->toc_node_style ?? $type_settings['style_default'];
    $level = $node->toc_node_level ?? $type_settings['level'];
    $back_to_top = _toc_node_back_to_top($node, $type_settings);

    if (empty($style) || $style === 'none') {
      return _toc_node_generate($content, $style, $level, $back_to_top, 'anchors_only', TRUE);
    }

    return _toc_node_generate($content, $style, $level, $back_to_top, 'all', TRUE);
  }

}
