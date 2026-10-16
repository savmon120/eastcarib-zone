<?php

declare(strict_types=1);

namespace Drupal\node_access_by_field_role\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\Role;

/**
 * Admin settings for Node Access by Field.
 *
 * - Global: full access roles and deny roles.
 * - Per bundle: full/deny roles and Role → User reference field mapping.
 * - Per-node role visibility: automatic if the node has any 'role_reference' field with values.
 *
 * Settings page lives under: /admin/config/people/node-access-by-field
 */
final class SettingsForm extends ConfigFormBase {

  /** {@inheritdoc} */
  protected function getEditableConfigNames(): array {
    return ['node_access_by_field_role.settings'];
  }

  /** {@inheritdoc} */
  public function getFormId(): string {
    return 'node_access_by_field_role_settings';
  }

  /** {@inheritdoc} */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('node_access_by_field_role.settings');

    $roles = Role::loadMultiple();
    $role_options = [];
    foreach ($roles as $rid => $role) {
      $role_options[$rid] = $role->label() . ' (' . $rid . ')';
    }

    $form['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debug logging'),
      '#default_value' => (bool) $config->get('debug'),
    ];

    // Global defaults.
    $form['global'] = [
      '#type' => 'details',
      '#title' => $this->t('Global defaults'),
      '#open' => TRUE,
    ];
    $form['global']['global_full_access_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Global roles with full access (see every node)'),
      '#options' => $role_options,
      '#default_value' => $config->get('global_full_access_roles') ?? [],
    ];
    $form['global']['global_deny_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Global roles denied (see no nodes)'),
      '#options' => $role_options,
      '#default_value' => $config->get('global_deny_roles') ?? [],
    ];

    // Per-bundle configuration.
    $form['bundles'] = [
      '#type' => 'details',
      '#title' => $this->t('Per content type'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    $bundles = NodeType::loadMultiple();
    foreach ($bundles as $bundle_id => $bundle) {
      $fieldset = [
        '#type' => 'details',
        '#title' => $bundle->label() . ' (' . $bundle_id . ')',
        '#open' => FALSE,
        '#tree' => TRUE,
      ];

      $fieldset['full_access_roles'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Full access roles (this type)'),
        '#options' => $role_options,
        '#default_value' => $config->get('bundles.' . $bundle_id . '.full_access_roles') ?? [],
      ];
      $fieldset['deny_roles'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Deny roles (this type)'),
        '#options' => $role_options,
        '#default_value' => $config->get('bundles.' . $bundle_id . '.deny_roles') ?? [],
      ];

      // Role → user field mapping for this bundle.
      $field_options = $this->getUserReferenceFieldsForBundle($bundle_id);
      $fieldset['role_field_map'] = [
        '#type' => 'details',
        '#title' => $this->t('Restricted roles: choose the user field that must reference the current user'),
        '#open' => TRUE,
        '#tree' => TRUE,
      ];
      foreach ($roles as $rid => $role) {
        $fieldset['role_field_map']['role_' . $rid] = [
          '#type' => 'select',
          '#title' => $this->t('@role field', ['@role' => $role->label() . ' (' . $rid . ')']),
          '#options' => ['' => $this->t('- None -')] + $field_options,
          '#default_value' => $config->get('bundles.' . $bundle_id . '.role_field_map.' . $rid) ?? '',
          '#description' => $this->t('If selected, users with this role will only see nodes where this field references them.'),
        ];
      }

      $form['bundles'][$bundle_id] = $fieldset;
    }

    // Note for per-node role visibility feature.
    $form['note'] = [
      '#type' => 'item',
      '#markup' => $this->t('<strong>Per-node role visibility</strong>: if a node has a <code>role_reference</code> field with values, only users with any of those roles can view the node. To use it, add the provided “Role reference” field to your content type.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /** {@inheritdoc} */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);
    $v = $form_state->getValues();
    $cfg = $this->configFactory->getEditable('node_access_by_field_role.settings');

    $bundles = $v['bundles'] ?? [];
    foreach ($bundles as $bundle_id => &$bundle_conf) {
      if (!empty($bundle_conf['role_field_map'])) {
        $normalized = [];
        foreach ($bundle_conf['role_field_map'] as $key => $val) {
          if (strpos((string) $key, 'role_') === 0 && $val !== '') {
            $rid = substr((string) $key, 5);
            $normalized[$rid] = $val;
          }
        }
        $bundle_conf['role_field_map'] = $normalized;
      }
      $bundle_conf['full_access_roles'] = array_filter($bundle_conf['full_access_roles'] ?? []);
      $bundle_conf['deny_roles'] = array_filter($bundle_conf['deny_roles'] ?? []);
    }

    $cfg->set('debug', !empty($v['debug']))
      ->set('global_full_access_roles', array_filter($v['global']['global_full_access_roles'] ?? []))
      ->set('global_deny_roles', array_filter($v['global']['global_deny_roles'] ?? []))
      ->set('bundles', $bundles)
      ->save();
  }

  /**
   * List user reference fields for a node bundle.
   */
  protected function getUserReferenceFieldsForBundle(string $bundle): array {
    $options = [];
    $fields = \Drupal::entityTypeManager()->getStorage('field_config')->loadByProperties([
      'entity_type' => 'node',
      'bundle' => $bundle,
    ]);
    foreach ($fields as $field) {
      $def = $field->getFieldStorageDefinition();
      if ($def->getType() === 'entity_reference' && $def->getSetting('target_type') === 'user') {
        $options[$field->getName()] = $field->label() . ' (' . $field->getName() . ')';
      }
    }
    return $options;
  }

}
