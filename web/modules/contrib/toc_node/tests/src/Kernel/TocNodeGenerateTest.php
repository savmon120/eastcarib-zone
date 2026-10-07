<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Kernel;

use Drupal\Core\Render\RenderContext;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests _toc_node_generate() HTML transformations.
 *
 * @group toc_node
 */
class TocNodeGenerateTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'path_alias',
    'toc_node',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    \Drupal::moduleHandler()->loadInclude('toc_node', 'module');
  }

  /**
   * Calls _toc_node_generate() within a render context.
   */
  private function doGenerate(string $content, string $style, int $heading_levels, int $back_to_top_links, string $return = 'all', bool $anchor_all = FALSE): string {
    return (string) \Drupal::service('renderer')
      ->executeInRenderContext(new RenderContext(), function () use ($content, $style, $heading_levels, $back_to_top_links, $return, $anchor_all) {
        return _toc_node_generate($content, $style, $heading_levels, $back_to_top_links, $return, $anchor_all);
      });
  }

  /**
   * Tests that content with no headings is returned unchanged.
   */
  public function testNoHeadingsReturnsContent(): void {
    $content = '<p>No headings here.</p>';
    $result = $this->doGenerate($content, 'bullets', 2, 0);
    $this->assertSame($content, $result);
  }

  /**
   * Tests that anchor ids are added to each heading.
   */
  public function testAnchorsAdded(): void {
    $content = '<h2>First</h2><h2>Second</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 0);

    $this->assertStringContainsString('id="toc-1"', $result);
    $this->assertStringContainsString('id="toc-2"', $result);
  }

  /**
   * Tests that headings get the toc-headings CSS class.
   */
  public function testHeadingClassAdded(): void {
    $content = '<h2>Hello</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 0);

    $this->assertStringContainsString('class="toc-headings"', $result);
  }

  /**
   * Tests that bullet style produces a TOC list.
   */
  public function testBulletStyle(): void {
    $content = '<h2>A</h2><h2>B</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 0);

    $this->assertStringContainsString('toc-node-bullets', $result);
    $this->assertStringContainsString('>A<', $result);
    $this->assertStringContainsString('>B<', $result);
  }

  /**
   * Tests that numbered style produces numbered TOC links.
   */
  public function testNumberedStyle(): void {
    $content = '<h2>First</h2><h2>Second</h2>';
    $result = $this->doGenerate($content, 'numbers', 2, 0);

    $this->assertStringContainsString('toc-node-numbers', $result);
    $this->assertStringContainsString('toc-number">1<', $result);
    $this->assertStringContainsString('toc-number">2<', $result);
  }

  /**
   * Tests that style 'none' with anchors_only mode adds anchors but no TOC list.
   */
  public function testStyleNoneAnchorsOnly(): void {
    $content = '<h2>Quiet</h2>';
    $result = $this->doGenerate($content, 'none', 2, 0, 'anchors_only');

    $this->assertStringContainsString('id="toc-1"', $result);
    $this->assertStringNotContainsString('table-of-contents-links', $result);
    $this->assertStringNotContainsString('toc-back-to-top', $result);
  }

  /**
   * Tests that back-to-top button is appended when enabled.
   */
  public function testBackToTopButton(): void {
    $content = '<h2>Scroll</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 1);

    $this->assertStringContainsString('toc-back-to-top', $result);
  }

  /**
   * Tests that back-to-top button is not appended when disabled.
   */
  public function testNoBackToTopWhenDisabled(): void {
    $content = '<h2>Stay</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 0);

    $this->assertStringNotContainsString('toc-back-to-top', $result);
  }

  /**
   * Tests heading depth filtering.
   */
  public function testHeadingDepth(): void {
    $content = '<h2>L2</h2><h3>L3</h3><h4>L4</h4>';

    // Depth 2: only h2 should get a TOC anchor; L3/L4 are untouched.
    $result = $this->doGenerate($content, 'bullets', 2, 0);
    $this->assertStringContainsString('id="toc-1"', $result);
    // L3 and L4 headings are preserved in the content but get no anchors.
    $this->assertStringContainsString('<h3>L3</h3>', $result);
    $this->assertStringNotContainsString('id="toc-2', $result);
  }

  /**
   * Tests that headings are correctly included at multiple levels.
   */
  public function testMultiLevelNesting(): void {
    $content = '<h2>C1</h2><h3>S1.1</h3><h2>C2</h2>';
    $result = $this->doGenerate($content, 'numbers', 3, 0);

    $this->assertStringContainsString('C1', $result);
    $this->assertStringContainsString('S1.1', $result);
    $this->assertStringContainsString('C2', $result);
  }

  /**
   * Tests that with anchor_all, deeper headings still get anchors even when
   * the inline TOC list is limited to a shallower level.
   */
  public function testAnchorAllKeepsDeeperHeadingAnchors(): void {
    $content = '<h2>A</h2><h3>B</h3><h4>C</h4>';
    $result = $this->doGenerate($content, 'bullets', 2, 0, 'all', TRUE);

    // Anchors exist for every heading regardless of the list depth.
    $this->assertStringContainsString('id="toc-1"', $result);
    $this->assertStringContainsString('id="toc-2"', $result);
    $this->assertStringContainsString('id="toc-3"', $result);
    // Only the h2 is listed in the inline TOC.
    $this->assertSame(1, substr_count($result, '<li class="toc-node-level-'));
    // No ordering marker tags are left behind.
    $this->assertStringNotContainsString('<toc ', $result);
  }

  /**
   * Tests that anchors_only with anchor_all never emits a stray list or
   * marker tags while still anchoring every heading.
   */
  public function testAnchorAllAnchorsOnly(): void {
    $content = '<h2>A</h2><h3>B</h3>';
    $result = $this->doGenerate($content, 'none', 2, 0, 'anchors_only', TRUE);

    $this->assertStringContainsString('id="toc-1"', $result);
    $this->assertStringContainsString('id="toc-2"', $result);
    $this->assertStringNotContainsString('<toc ', $result);
  }

  /**
   * Tests that toc_list mode returns only the TOC list.
   */
  public function testTocListMode(): void {
    $content = '<h2>OnlyToc</h2>';
    $result = $this->doGenerate($content, 'bullets', 2, 0, 'toc_list');

    $this->assertStringContainsString('toc-node-bullets', $result);
    $this->assertStringNotContainsString('<h2>OnlyToc</h2>', $result);
  }

}
