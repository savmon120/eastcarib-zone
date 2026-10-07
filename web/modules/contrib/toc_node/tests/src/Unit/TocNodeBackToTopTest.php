<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\node\NodeInterface;

/**
 * Tests _toc_node_back_to_top() resolution logic.
 */
class TocNodeBackToTopTest extends UnitTestCase {

  /**
   * The mocked node.
   */
  protected NodeInterface $node;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once __DIR__ . '/../../../toc_node.module';
    $this->node = $this->createMock(NodeInterface::class);
  }

  /**
   * Mode 0 always returns 0 regardless of per-node setting.
   */
  public function testMode0AlwaysDisabled(): void {
    $settings = ['back_to_top_links' => 0];

    $this->node->toc_node_back_to_top_links = 1;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);

    unset($this->node->toc_node_back_to_top_links);
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);
  }

  /**
   * Mode 1 always returns 1 regardless of per-node setting.
   */
  public function testMode1AlwaysEnabled(): void {
    $settings = ['back_to_top_links' => 1];

    $this->node->toc_node_back_to_top_links = 0;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(1, $result);

    unset($this->node->toc_node_back_to_top_links);
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(1, $result);
  }

  /**
   * Mode 2 returns per-node value when set.
   */
  public function testMode2EnabledPerNodeWithSetting(): void {
    $settings = ['back_to_top_links' => 2];

    $this->node->toc_node_back_to_top_links = 1;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(1, $result);

    $this->node->toc_node_back_to_top_links = 0;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);
  }

  /**
   * Mode 2 defaults to 1 when per-node is not set.
   */
  public function testMode2EnabledPerNodeDefault(): void {
    $settings = ['back_to_top_links' => 2];
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(1, $result);
  }

  /**
   * Mode 3 returns per-node value when set.
   */
  public function testMode3DisabledPerNodeWithSetting(): void {
    $settings = ['back_to_top_links' => 3];

    $this->node->toc_node_back_to_top_links = 1;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(1, $result);

    $this->node->toc_node_back_to_top_links = 0;
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);
  }

  /**
   * Mode 3 defaults to 0 when per-node is not set.
   */
  public function testMode3DisabledPerNodeDefault(): void {
    $settings = ['back_to_top_links' => 3];
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);
  }

  /**
   * Missing back_to_top_links key defaults to 0.
   */
  public function testMissingSettingKeyDefaultsToZero(): void {
    $settings = [];
    $result = _toc_node_back_to_top($this->node, $settings);
    $this->assertSame(0, $result);
  }

}
