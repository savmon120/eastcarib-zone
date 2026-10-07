<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\filter\Entity\FilterFormat;
use Drupal\node\Entity\NodeType;

/**
 * Tests that the TOC renders on node pages.
 *
 * @group toc_node
 */
class TocNodeNodeViewTest extends BrowserTestBase {

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
    ]);
  }

  /**
   * Creates a content type with TOC enabled and a body field.
   */
  protected function createTocEnabledContentType(string $type, string $label): void {
    $node_type = NodeType::create([
      'type' => $type,
      'name' => $label,
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 0);
    $node_type->setThirdPartySetting('toc_node', 'level', 3);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['none', 'bullets', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'bullets');
    $node_type->save();

    node_add_body_field($node_type);

    \Drupal::service('entity_display.repository')
      ->getViewDisplay('node', $type, 'full')
      ->setComponent('body')
      ->save();
  }

  /**
   * Tests that the TOC is rendered on the canonical node page.
   */
  public function testTocRenderedOnNodePage(): void {
    $this->createTocEnabledContentType('page', 'Page');

    $this->drupalLogin($this->adminUser);

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'Test with TOC',
      'body' => [
        'value' => '<h2>Introduction</h2><p>Body text.</p><h2>Conclusion</h2>',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);

    // The TOC container div should be present.
    $this->assertSession()->elementExists('css', '.table-of-contents-links');
    // The TOC should contain the heading text.
    $this->assertSession()->pageTextContains('Introduction');
    $this->assertSession()->pageTextContains('Conclusion');
    // TOC links should link to anchors.
    $this->assertSession()->elementAttributeContains('css', '.table-of-contents-links a[href*="toc-1"]', 'href', 'toc-1');
  }

  /**
   * Tests that the TOC does not appear when the content type has it disabled.
   */
  public function testNoTocWhenDisabled(): void {
    // Create content type without enabling TOC.
    $node_type = NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ]);
    $node_type->save();
    node_add_body_field($node_type);

    \Drupal::service('entity_display.repository')
      ->getViewDisplay('node', 'page', 'full')
      ->setComponent('body')
      ->save();

    $this->drupalLogin($this->adminUser);

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'No TOC',
      'body' => [
        'value' => '<h2>Heading</h2>',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementNotExists('css', '.table-of-contents-links');
  }

  /**
   * Tests that the TOC does not appear on non-canonical routes like edit.
   */
  public function testTocNotRenderedOnEditPage(): void {
    $this->createTocEnabledContentType('page', 'Page');

    $this->drupalLogin($this->adminUser);

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'Edit test',
      'body' => [
        'value' => '<h2>Edit heading</h2>',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementNotExists('css', '.table-of-contents-links');
  }

  /**
   * Tests that the back-to-top button appears when enabled on the content type.
   */
  public function testBackToTopButton(): void {
    $node_type = NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ]);
    $node_type->setThirdPartySetting('toc_node', 'enabled', TRUE);
    $node_type->setThirdPartySetting('toc_node', 'back_to_top_links', 1);
    $node_type->setThirdPartySetting('toc_node', 'level', 3);
    $node_type->setThirdPartySetting('toc_node', 'styles', ['none', 'bullets', 'numbers']);
    $node_type->setThirdPartySetting('toc_node', 'style_default', 'bullets');
    $node_type->save();
    node_add_body_field($node_type);

    \Drupal::service('entity_display.repository')
      ->getViewDisplay('node', 'page', 'full')
      ->setComponent('body')
      ->save();

    $this->drupalLogin($this->adminUser);

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'Back to top test',
      'body' => [
        'value' => '<h2>Section</h2>',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->buttonExists('toc-back-to-top');
  }

  /**
   * Tests that headings get the toc-headings class in the rendered HTML.
   */
  public function testHeadingsHaveTocClass(): void {
    $this->createTocEnabledContentType('page', 'Page');

    $this->drupalLogin($this->adminUser);

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'Class test',
      'body' => [
        'value' => '<h2>Styled heading</h2>',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet($node->toUrl('canonical'));
    $this->assertSession()->elementExists('css', 'h2.toc-headings');
  }

}
