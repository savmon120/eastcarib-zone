<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\NodeType;

/**
 * Tests _toc_node_type_settings() and third-party settings persistence.
 */
class TocNodeSettingsTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'system',
    'user',
    'toc_node',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['system', 'node']);
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installSchema('toc_node', 'toc_node');
  }

  /**
   * Tests that an unknown bundle returns an empty array.
   */
  public function testUnknownBundleReturnsEmpty(): void {
    \Drupal::moduleHandler()->loadInclude('toc_node', 'module');
    $result = _toc_node_type_settings('non_existent_bundle');
    $this->assertSame([], $result);
  }

  /**
   * Tests that a fresh node type returns default TOC settings.
   */
  public function testNewTypeReturnsDefaults(): void {
    NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ])->save();

    \Drupal::moduleHandler()->loadInclude('toc_node', 'module');
    $settings = _toc_node_type_settings('page');

    $this->assertFalse($settings['enabled']);
    $this->assertEquals(0, $settings['back_to_top_links']);
    $this->assertEquals(2, $settings['level']);
    $this->assertEquals(['none', 'bullets', 'numbers'], $settings['styles']);
    $this->assertEquals('bullets', $settings['style_default']);
  }

  /**
   * Tests that custom third-party settings are retrieved correctly.
   */
  public function testCustomSettingsAreRetrieved(): void {
    $node_type = NodeType::create([
      'type' => 'article',
      'name' => 'Article',
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 1);
    $node_type->setThirdPartySetting('toc_node', 'level', 4);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['bullets', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'numbers');
    $node_type->save();

    \Drupal::moduleHandler()->loadInclude('toc_node', 'module');
    $settings = _toc_node_type_settings('article');

    $this->assertTrue($settings['enabled']);
    $this->assertEquals(1, $settings['back_to_top_links']);
    $this->assertEquals(4, $settings['level']);
    $this->assertEquals(['bullets', 'numbers'], $settings['styles']);
    $this->assertEquals('numbers', $settings['style_default']);
  }

  /**
   * Tests that settings persist after config save and reload.
   */
  public function testSettingsPersistenceAfterReload(): void {
    $node_type = NodeType::create([
      'type' => 'book',
      'name' => 'Book',
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 2);
    $node_type->setThirdPartySetting('toc_node', 'level', 6);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['none', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'none');
    $node_type->save();

    // Reload the config entity from storage.
    $this->container->get('config.factory')->reset('node.type.book');
    $reloaded = NodeType::load('book');

    $this->assertTrue($reloaded->getThirdPartySetting('toc_node', 'enabled'));
    $this->assertEquals(2, $reloaded->getThirdPartySetting('toc_node', 'back_to_top_links'));
    $this->assertEquals(6, $reloaded->getThirdPartySetting('toc_node', 'level'));
    $this->assertEquals(['none', 'numbers'], $reloaded->getThirdPartySetting('toc_node', 'styles'));
    $this->assertEquals('none', $reloaded->getThirdPartySetting('toc_node', 'style_default'));
  }

}
