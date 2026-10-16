<?php

declare(strict_types=1);

namespace Drupal\node_access_by_field_role\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'role_reference' field type.
 *
 * Stores role IDs (machine names) as simple string values.
 *
 * @FieldType(
 *   id = "role_reference",
 *   label = @Translation("Role reference"),
 *   description = @Translation("Stores user role IDs (machine names)."),
 *   category = "reference",
 *   default_widget = "role_reference_checkboxes",
 *   default_formatter = "role_reference_list"
 * )
 */
final class RoleReferenceItem extends FieldItemBase {

  /** {@inheritdoc} */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')->setLabel(t('Role ID'));
    return $properties;
  }

  /** {@inheritdoc} */
  public static function mainPropertyName() {
    return 'value';
  }

  /** {@inheritdoc} */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 64,
        ],
      ],
      'indexes' => [
        'value' => ['value'],
      ],
    ];
  }

  /** {@inheritdoc} */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '' || $value === [];
  }

}
