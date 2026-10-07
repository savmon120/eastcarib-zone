<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\node\Entity\NodeType;

/**
 * Tests the TOC Node admin configuration UI.
 *
 * @group toc_node
 */
class TocNodeAdminTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node', 'toc_node'];

  /**
   * An admin user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser([
      'administer content types',
      'administer nodes',
      'bypass node access',
    ]);
    $this->drupalLogin($this->adminUser);

    NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ])->save();
  }

  /**
   * Tests that the TOC fieldset appears on the node type edit form.
   */
  public function testTocFieldsetPresent(): void {
    $this->drupalGet('/admin/structure/types/manage/page');

    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementTextContains('css', 'details#edit-toc-node', 'Table of contents');
    $this->assertSession()->fieldExists('toc_node[enabled]');
    $this->assertSession()->fieldExists('toc_node[back_to_top_links]');
    $this->assertSession()->fieldExists('toc_node[level]');
    $this->assertSession()->fieldExists('toc_node[styles][bullets]');
    $this->assertSession()->fieldExists('toc_node[styles][numbers]');
    $this->assertSession()->fieldExists('toc_node[style_default]');
  }

  /**
   * Tests that TOC settings are saved and persisted.
   */
  public function testTocSettingsSaved(): void {
    $this->drupalGet('/admin/structure/types/manage/page');

    $this->submitForm([
      'toc_node[enabled]' => '1',
      'toc_node[back_to_top_links]' => '1',
      'toc_node[level]' => '4',
      'toc_node[styles][none]' => 'none',
      'toc_node[styles][bullets]' => 'bullets',
      'toc_node[style_default]' => 'numbers',
    ], 'Save');

    $this->assertSession()->pageTextContains('The content type Page has been updated.');

    $node_type = NodeType::load('page');
    $this->assertTrue($node_type->getThirdPartySetting('toc_node', 'enabled'));
    $this->assertEquals(1, $node_type->getThirdPartySetting('toc_node', 'back_to_top_links'));
    $this->assertEquals(4, $node_type->getThirdPartySetting('toc_node', 'level'));
    $this->assertEquals(['none', 'bullets', 'numbers'], $node_type->getThirdPartySetting('toc_node', 'styles'));
    $this->assertEquals('numbers', $node_type->getThirdPartySetting('toc_node', 'style_default'));
  }

  /**
   * Tests default values on a fresh node type edit form.
   */
  public function testTocSettingsDefaults(): void {
    $this->drupalGet('/admin/structure/types/manage/page');

    $enabled = $this->assertSession()->fieldExists('toc_node[enabled]');
    $this->assertFalse($enabled->isChecked());

    $level = $this->assertSession()->fieldExists('toc_node[level]');
    $this->assertEquals('2', $level->getValue());
  }

  /**
   * Tests that the TOC fieldset does not appear for users without permission.
   */
  public function testTocFieldsetRequiresAdminPermission(): void {
    $user = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($user);

    $this->drupalGet('/admin/structure/types/manage/page');
    $this->assertSession()->statusCodeEquals(403);
  }

}
