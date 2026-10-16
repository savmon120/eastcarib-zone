<?php

declare(strict_types=1);

namespace Drupal\node_access_by_field_role\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\user\Entity\Role;

/**
 * Formats the 'role_reference' field as a comma-separated list of role labels.
 *
 * @FieldFormatter(
 *   id = "role_reference_list",
 *   label = @Translation("Role labels list"),
 *   field_types = {"role_reference"}
 * )
 */
final class RoleReferenceListFormatter extends FormatterBase {

  /** {@inheritdoc} */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $roles = Role::loadMultiple();
    $labels = [];
    foreach ($items as $item) {
      $rid = $item->value;
      if (isset($roles[$rid])) {
        $labels[] = $roles[$rid]->label();
      }
      else {
        $labels[] = $rid;
      }
    }
    return [['#markup' => implode(', ', $labels)]];
  }

}
