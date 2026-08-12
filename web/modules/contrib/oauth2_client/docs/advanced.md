# Advanced customizations

## Extending base methods

Many of the behaviors of your Oauth2Client plugin are provided by a base class,
`Oauth2ClientPluginBase`, and can be customized by overriding or extending
methods from that class.  For example, we include the *redirect uri* in our
standard implementation as it allows our base class to work across grant types.

```php
<?php

  /**
   * Creates a new provider object.
   *
   * @return \League\OAuth2\Client\Provider\GenericProvider
   *   The provider of the OAuth2 Server.
   */
  public function getProvider(): AbstractProvider {
    return new GenericProvider(
      [
        'clientId' => $this->getClientId(),
        'clientSecret' => $this->getClientSecret(),
        'redirectUri' => $this->getRedirectUri(),
        'urlAuthorize' => $this->getAuthorizationUri(),
        'urlAccessToken' => $this->getTokenUri(),
        'urlResourceOwnerDetails' => $this->getResourceUri(),
        'scopes' => $this->getScopes(),
        'scopeSeparator' => $this->getScopeSeparator(),
      ],
      $this->getCollaborators()
    );
  }
```
Some services are strict about extra data however, so one simple way to
customize behavior is to override this method in your plugin and only include
what is needed for your service and grant type. GenericProvider requires values
for `urlAuthorize`, `urlAccessToken`, and `urlResourceOwnerDetails`.  See
`League\OAuth2\Client\Provider\GenericProvider` for all possible properties.



In addition the [collaborators](creating-plugins.md)
attribute allows you to override helper classes used to build your
`GenericClient`.

## Extension points in `GenericClient`

The `league/oauth2-client` library uses the name _Provider_ for classes that
provide Oauth2 token management.  This naming is reflected in our
`::getProvider` method in `Oauth2ClientPluginInterface` The implementation in
`Oauth2ClientPluginInterface` creates a new instance
of `\League\OAuth2\Client\Provider\GenericProvider` which provides several
methods that can be used to customize your client:

* `::setHttpClient(client: ClientInterface)` allows you to customize the Guzzle
  `HttpClient`.
* `setOptionProvider(provider: OptionProviderInterface)` allows you to customize
   the options sent to the HttpClient created for you by `GenericProvider`.

## Using a dedicated provider

> All providers are different, and the GenericProvider may not work for all of
them. It works for many cases, but not every provider follows the OAuth 2 spec.
This is why we made league/oauth2-client extensible, so you can extend
AbstractProvider to modify things according to the needs of the provider you're
trying to use.
[`League\Oauth2\Client` maintainer on Github](https://github.com/thephpleague/oauth2-client/issues/774#issuecomment-718167035)

The only requirement on the `::getProvider` method in `Oauth2ClientPluginInterface`
is that you return an `AbstractProvider` instance.  This allows you to either
[create your own class](https://oauth2-client.thephpleague.com/providers/implementing/)
that implements `AbstractProvider` to to require one
of the many composer packages that implement this abstract class for various
services. You can then instantiate and return an instance of your custom or
third-party provider in your implementation of `::getProvider`.
The maintainers [support a small number](https://oauth2-client.thephpleague.com/providers/league/)
of these and provide an [index of other packages](https://oauth2-client.thephpleague.com/providers/thirdparty/).

Examples of such dedicated providers include:

* Amazon
* Azure
* Dropbox
* GitHub
* Google

## Altering Grant Type Behavior

Developers can also alter the implementation of a grant type by replacing the
grant type plugin with a custom implementation. Be careful when doing this,
as the grant type plugin retrieves and stores the access token, and altering
this behavior may have unintended consequences.

```php
function my_module_oauth2_grant_type_info_alter(array &$definitions) {
  $definitions['authorization_code']['class'] = '\Drupal\my_module\Plugin\Oauth2GrantType\MyAuthorizationCode';
}
```
