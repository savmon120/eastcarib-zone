# Creating Oauth2Client Plugins

Decisions:

* Necessary data for plugin attributes such as grant type, urls, scopes.
* Storage method for tokens

## Data

Drupal uses PHP Attributes to store static data about plugins.  This data is
known as the plugin definition.  The possible values for `Oauth2Client`
attributes are:

`string id`

: The _machine name_ for the plugin

`TranslatableMarkup name`

: The _human readable_ name of the plugin.

`string grant_type`

: The grant type of the OAuth2 authorization. Possible values are
  'authorization_code', 'client_credentials', and 'resource_owner'.

`string authorization_uri`

: The authorization endpoint of the OAuth2 server.

`string token_uri`

: The token endpoint of the OAuth2 server.

`string resource_owner_uri`

: (optional) The Resource Owner Details endpoint.

`array|null scopes`

: (optional) The set of scopes for the provider to use by default.

`string scope_separator`

: (optional) The separator used to join the scopes when sent in the request.
  Defaults to a comma (`','`).

`array request_options`

: (optional) A set of additional parameters on the token request.
  The array key will be used as the request parameter:
  ```
  request_options: [
    'parameter' => 'value',
  ],
  ```

  `bool success_message`
: (optional) A flag that may be used by
  `Oauth2ClientPluginInterface::storeAccessToken`. Implementations may then
  conditionally display a message on successful storage.

  `array|null collaborators`

: (optional) An associative array of classes that are composed into the
  provider. Allowed keys are:
    - grantFactory
    - requestFactory
    - httpClient
    - optionProvider.

`class-string|null deriver`

: (optional) The [Drupal plugin deriver](https://www.drupal.org/docs/drupal-apis/plugin-api/plugin-derivatives) class.

## Storage

The base plugin class `Oauth2ClientPluginBase` is abstract because it does not
implement the storage method.  This is intentional as this varies by use case.
This module provides traits for the two most common scenarios.

* **The remote service is shared by all users:** Use the `StateTokenStorage` trait
  in your plugin.
* **Each user on your site needs to be individually authorized:** Use the
  `TempStoreTokenStorage` trait in your plugin.

If you need some other logic or storage system for the tokens, then you can
implement the three storage related methods`::storeAccessToken`,
`::retrieveAccessToken`, and`::clearAccessToken` directly in your plugin.

## Assemble the Plugin Class

Here is an example of a simple implementation for an _authorization_code_ flow:

```php title="A complete plugin class"
<?php

declare(strict_types=1);

namespace Drupal\oauth2_client_demo\Plugin\Oauth2Client;

use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;
use Drupal\oauth2_client\Plugin\Oauth2Client\StateTokenStorage;

/**
 * Auth code example.
 */
#[Oauth2Client(
  id: 'authcode_example',
  name: new TranslatableMarkup('Auth Code example plugin'),
  grant_type: 'authorization_code',
  authorization_uri: 'https://www.example.com/oauth/authorize',
  token_uri: 'https://www.example.com/oauth/token',
  resource_owner_uri: 'https://www.example.com/userinfo',
  success_message: true,
)]
class AuthCode extends Oauth2ClientPluginBase implements Oauth2ClientPluginAccessInterface {
  use StateTokenStorage;
}
```
