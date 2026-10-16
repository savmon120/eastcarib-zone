<?php

declare(strict_types=1);

namespace Drupal\Tests\toc_node\Unit;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Tests hook_form_alter() routing so non-entity forms never crash.
 *
 * The generic /^node_\w+_form$/ pattern also matched the bulk-delete confirm
 * form ('node_delete_multiple_confirm_form'), whose form object has no
 * getEntity() method. See _toc_node_node_form_alter().
 *
 * @group toc_node
 */
class TocNodeFormAlterTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once __DIR__ . '/../../../toc_node.module';
  }

  /**
   * Non-node forms (e.g. the bulk-delete confirm) leave the form untouched.
   */
  public function testIgnoresBulkDeleteConfirmForm(): void {
    $form_state = $this->createMock(FormStateInterface::class);
    $form_state->expects($this->once())
      ->method('getBuildInfo')
      ->willReturn(['base_form_id' => 'node_delete_multiple_confirm_form']);
    $form_state->expects($this->never())
      ->method('getFormObject');

    $form = ['#form_id' => 'node_delete_multiple_confirm_form'];
    toc_node_form_alter($form, $form_state, 'node_delete_multiple_confirm_form');

    $this->assertSame(['#form_id' => 'node_delete_multiple_confirm_form'], $form);
  }

  /**
   * A node-routed form whose object is not an entity form is safely skipped.
   */
  public function testGuardsNonEntityFormObject(): void {
    $form_state = $this->createMock(FormStateInterface::class);
    $form_state->expects($this->once())
      ->method('getBuildInfo')
      ->willReturn(['base_form_id' => 'node_form']);
    $form_state->expects($this->once())
      ->method('getFormObject')
      ->willReturn(new \stdClass());

    $form = ['#form_id' => 'node_page_edit_form'];
    toc_node_form_alter($form, $form_state, 'node_page_edit_form');

    $this->assertArrayNotHasKey('toc_node', $form);
  }

}