<?php

declare(strict_types=1);

namespace Drupal\node_access_by_field_role\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;

/**
 * Renders the 'role_reference' field as a set of checkboxes (roles list).
 *
 * @FieldWidget(
 *   id = "role_reference_checkboxes",
 *   label = @Translation("Role checkboxes"),
 *   field_types = {"role_reference"},
 *   multiple_values = TRUE
 * )
 */
final class RoleReferenceCheckboxesWidget extends WidgetBase {

  /** {@inheritdoc} */
  public static function supportsMultipleValues(): bool {
    return TRUE;
  }

  /** {@inheritdoc} */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $roles = Role::loadMultiple();
    $options = [];
    foreach ($roles as $rid => $role) {
      $options[$rid] = $role->label() . ' (' . $rid . ')';
    }

    // Pre-select current values.
    $selected = [];
    foreach ($items->getValue() as $item) {
      if (!empty($item['value'])) {
        $selected[(string) $item['value']] = (string) $item['value'];
      }
    }

    // Single root-level element controls all values.
    $element += [
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $selected,
    ];

    return $element;
  }

  /** {@inheritdoc} */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // $values is like [rid => rid|0,...]. Keep checked keys only.
    $out = [];
    if (is_array($values)) {
      foreach ($values as $rid => $checked) {
        if ($checked) {
          $out[] = ['value' => (string) $rid];
        }
      }
    }
    return $out;
  }

}
