<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the database schema defined by toc_node_schema().
 */
class TocNodeSchemaTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node', 'toc_node'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installSchema('toc_node', 'toc_node');
  }

  /**
   * Tests that the schema definition has the expected structure.
   */
  public function testSchemaDefinition(): void {
    \Drupal::moduleHandler()->loadInclude('toc_node', 'install');
    $schema = toc_node_schema();

    $this->assertArrayHasKey('toc_node', $schema);

    $table = $schema['toc_node'];
    $this->assertSame('Store Table Of Contents settings for individual nodes.', $table['description']);

    $fields = $table['fields'];
    $this->assertArrayHasKey('nid', $fields);
    $this->assertEquals('int', $fields['nid']['type']);
    $this->assertTrue($fields['nid']['not null']);

    $this->assertArrayHasKey('style', $fields);
    $this->assertEquals('varchar', $fields['style']['type']);
    $this->assertEquals(10, $fields['style']['length']);

    $this->assertArrayHasKey('level', $fields);
    $this->assertEquals('int', $fields['level']['type']);

    $this->assertArrayHasKey('back_links', $fields);
    $this->assertEquals('int', $fields['back_links']['type']);

    $this->assertEquals(['nid'], $table['primary key']);
  }

  /**
   * Tests that the schema is actually installed in the database.
   */
  public function testSchemaInstalled(): void {
    $table_found = FALSE;
    $tables = \Drupal::database()->schema()->findTables('%toc_node%');
    foreach ($tables as $table) {
      if ($table === 'toc_node') {
        $table_found = TRUE;
        break;
      }
    }
    $this->assertTrue($table_found, 'toc_node table exists in the database.');

    // Verify the table is usable by inserting and reading a row.
    $database = \Drupal::database();
    $database->insert('toc_node')
      ->fields(['nid' => 42, 'style' => 'bullets', 'level' => 3, 'back_links' => 1])
      ->execute();
    $record = $database->select('toc_node', 't')
      ->fields('t')
      ->condition('nid', 42)
      ->execute()
      ->fetchAssoc();
    $this->assertNotFalse($record, 'Inserted record found.');
    $this->assertEquals(42, (int) $record['nid']);
    $this->assertEquals('bullets', $record['style']);
    $this->assertEquals(3, (int) $record['level']);
    $this->assertEquals(1, (int) $record['back_links']);
  }

}
