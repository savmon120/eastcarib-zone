<?php

declare(strict_types=1);

namespace Drupal\node_access_by_field_role\Commands;

use Drush\Commands\DrushCommands;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

/**
 * Drush commands for Node Access by Field.
 */
final class NodeAccessByFieldCommands extends DrushCommands {

  /**
   * Add a 'role_reference' field to a node bundle (multi-valued).
   *
   * @command nabf:add-role-field
   * @aliases nabf-arf
   * @param string $bundle
   *   Node bundle machine name.
   * @param string $field
   *   Field machine name (e.g., field_visible_roles).
   */
  public function addRoleField(string $bundle, string $field): void {
    $etype = 'node';
    if (!FieldStorageConfig::loadByName($etype, $field)) {
      FieldStorageConfig::create([
        'field_name' => $field,
        'entity_type' => $etype,
        'type' => 'role_reference',
        'cardinality' => -1,
      ])->save();
      $this->logger()->success("Created field storage $field.");
    }
    if (!FieldConfig::loadByName($etype, $bundle, $field)) {
      FieldConfig::create([
        'field_name' => $field,
        'entity_type' => $etype,
        'bundle' => $bundle,
        'label' => 'Visible roles',
      ])->save();
      $this->logger()->success("Attached $field to bundle $bundle.");
    }
    else {
      $this->logger()->success("Field $field already attached to $bundle.");
    }
  }

}
