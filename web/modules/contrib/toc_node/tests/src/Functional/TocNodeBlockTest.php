<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\filter\Entity\FilterFormat;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * Tests the TOC Node block plugin.
 *
 * @group toc_node
 */
class TocNodeBlockTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'toc_node',
    'block',
    'field',
    'text',
    'filter',
    'user',
  ];

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

    FilterFormat::create([
      'format' => 'full_html',
      'name' => 'Full HTML',
      'filters' => [
        'filter_html' => [
          'status' => TRUE,
          'settings' => [
            'allowed_html' => '<p> <br> <strong> <em> <a href> <h2> <h3>',
          ],
        ],
      ],
    ])->save();

    $this->adminUser = $this->drupalCreateUser([
      'administer content types',
      'administer nodes',
      'bypass node access',
      'administer blocks',
    ]);

    $node_type = NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 0);
    $node_type->setThirdPartySetting('toc_node', 'level', 3);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['none', 'bullets', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'bullets');
    $node_type->save();

    node_add_body_field($node_type);

    \Drupal::service('entity_display.repository')
      ->getViewDisplay('node', 'page', 'full')
      ->setComponent('body')
      ->save();
  }

  /**
   * Tests that the TOC block can be placed.
   */
  public function testBlockCanBePlaced(): void {
    $this->drupalLogin($this->adminUser);
    $block = $this->drupalPlaceBlock('toc_node', ['region' => 'content']);
    $this->assertNotEmpty($block->id());
  }

  /**
   * Tests that the block renders the TOC when viewing a node with headings.
   */
  public function testBlockRendersToc(): void {
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('toc_node', ['region' => 'content']);

    $node = Node::create([
      'type' => 'page',
      'title' => 'Block test node',
      'body' => [
        'value' => '<h2>Section 1</h2><p>Text.</p><h2>Section 2</h2>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementExists('css', '.table-of-contents-links');
    $this->assertSession()->pageTextContains('Section 1');
    $this->assertSession()->pageTextContains('Section 2');
  }

  /**
   * Tests that the block returns empty on non-node routes.
   */
  public function testBlockEmptyOnNonNodeRoute(): void {
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('toc_node', ['region' => 'content']);

    $this->drupalGet('<front>');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementNotExists('css', '.table-of-contents-links');
  }

  /**
   * Tests that the block inherits the node/content type style by default.
   */
  public function testBlockInheritsStyle(): void {
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('toc_node', ['region' => 'content']);

    $node = Node::create([
      'type' => 'page',
      'title' => 'Inherit style',
      'body' => [
        'value' => '<h2>Section</h2>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    // Default style is 'bullets'.
    $this->assertSession()->elementExists('css', 'ul.toc-node-bullets');
  }

  /**
   * Tests that the block style override takes effect.
   */
  public function testBlockStyleOverride(): void {
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('toc_node', [
      'region' => 'content',
      'style' => 'numbers',
    ]);

    $node = Node::create([
      'type' => 'page',
      'title' => 'Override style',
      'body' => [
        'value' => '<h2>Section</h2>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->elementExists('css', 'ul.toc-node-numbers');
  }

  /**
   * Tests that a block with a deeper level than the node still links to
   * live anchors (all headings are anchored regardless of node depth).
   */
  public function testBlockLinksToDeeperHeadingsWhenNodeLevelShallower(): void {
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('toc_node', [
      'region' => 'content',
      'level' => '3',
    ]);

    // A content type whose node-level TOC is limited to h2.
    $node_type = NodeType::create([
      'type' => 'doc',
      'name' => 'Document',
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 0);
    $node_type->setThirdPartySetting('toc_node', 'level', 2);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['none', 'bullets', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'bullets');
    $node_type->save();
    node_add_body_field($node_type);

    \Drupal::service('entity_display.repository')
      ->getViewDisplay('node', 'doc', 'full')
      ->setComponent('body')
      ->save();

    $node = Node::create([
      'type' => 'doc',
      'title' => 'Depth test',
      'body' => [
        'value' => '<h2>Top level</h2><h3>Sub section</h3>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    // The deeper (h3) heading still carries an anchor on the page.
    $this->assertSession()->elementExists('css', 'h3[id^="toc-"]');
    // The block TOC links to that deeper anchor.
    $this->assertSession()->elementExists('css', '.table-of-contents-links a[href*="toc-2"]');
    // The inline (node-level) TOC is limited to h2 and must not reference
    // anchors beyond the configured depth.
    $this->assertSession()->elementNotExists('css', '.table-of-contents-links a[href*="toc-3"]');
  }

  /**
   * Tests that a single back-to-top button renders when both the node and
   * the block enable it (no duplicate id).
   */
  public function testSingleBackToTopButtonWhenNodeAndBlockEnabled(): void {
    $this->drupalLogin($this->adminUser);

    $node_type = NodeType::load('page');
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 1);
    $node_type->save();

    $this->drupalPlaceBlock('toc_node', [
      'region' => 'content',
      'back_to_top' => 1,
    ]);

    $node = Node::create([
      'type' => 'page',
      'title' => 'Single button',
      'body' => [
        'value' => '<h2>Section</h2><p>Text.</p>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementsCount('css', '#toc-back-to-top', 1);
  }

  /**
   * Tests that the block renders a back-to-top button when the node does
   * not provide one.
   */
  public function testBlockBackToTopButtonWhenNodeDisabled(): void {
    $this->drupalLogin($this->adminUser);

    // The 'page' content type has back_to_top_links disabled, so the node
    // view emits no button and the block placement must provide it.
    $this->drupalPlaceBlock('toc_node', [
      'region' => 'content',
      'back_to_top' => 1,
    ]);

    $node = Node::create([
      'type' => 'page',
      'title' => 'Block button',
      'body' => [
        'value' => '<h2>Section</h2><p>Text.</p>',
        'format' => 'full_html',
      ],
    ]);
    $node->save();

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementsCount('css', '#toc-back-to-top', 1);
  }

}
