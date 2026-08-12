<?php

/**
 * @file
 * Documents hooks provided by the OAuth2 Client module.
 */

/**
 * Alter OAuth2 Client plugin definitions.
 *
 * @param array $definitions
 *   An array of OAuth2 Client plugins registered on the system.
 *
 * @see \Drupal\Core\Plugin\DefaultPluginManager::alterDefinitions
 */
function hook_oauth2_client_info_alter(array &$definitions) {
  $oauth2_clients['some_id']['some_key'] = 'some_value';
}

/**
 * Alter OAuth2 Grant Type plugin definitions.
 *
 * Implement this hook to change any key of a registered grant type plugin's
 * definition, including its 'class'. Overriding 'class' with a different,
 * compatible plugin class allows a module to replace the implementation used
 * for an existing plugin ID (e.g. to customize the "authorization_code"
 * grant type) without needing other code to reference a new plugin ID.
 *
 * Developers should exercise caution when overriding the 'class'
 * of an existing plugin as it may have unintended consequences
 * if the new class does not fully implement the expected behavior
 * of the original plugin.
 *
 * @param array $definitions
 *   An array of OAuth2 Grant Type plugins registered on the system, keyed by
 *   plugin ID.
 *
 * @see \Drupal\Core\Plugin\DefaultPluginManager::alterDefinitions
 */
function hook_oauth2_grant_type_info_alter(array &$definitions) {
  // Replace the implementation used for the "authorization_code" grant type.
  $definitions['authorization_code']['class'] = '\Drupal\my_module\Plugin\Oauth2GrantType\MyAuthorizationCode';
}
