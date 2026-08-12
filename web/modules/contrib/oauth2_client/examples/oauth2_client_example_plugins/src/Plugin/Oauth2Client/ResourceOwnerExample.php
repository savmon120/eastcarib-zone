<?php

declare(strict_types=1);

namespace Drupal\oauth2_client_example_plugins\Plugin\Oauth2Client;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\oauth2_client\Attribute\Oauth2Client;
use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;
use Drupal\oauth2_client\Plugin\Oauth2Client\StateTokenStorage;

/**
 * Resource Owner example plugin.
 */
#[Oauth2Client(
  id: 'resource_owner_example',
  name: new TranslatableMarkup('Resource Owner Example'),
  grant_type: 'resource_owner',
  authorization_uri: 'http://example.com/oauth/token',
  token_uri: 'http://example.com/oauth/token'
)]
class ResourceOwnerExample extends Oauth2ClientPluginBase {

  /*
   * This example assumes that the Drupal site is using a shared resource
   * from a third-party service that provides a service to all uses of the site.
   *
   * Storing a single AccessToken in state for the plugin shares access to the
   * external resource for ALL users of this plugin.
   */

  use StateTokenStorage;

}
