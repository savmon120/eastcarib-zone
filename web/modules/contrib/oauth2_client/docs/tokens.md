# Retrieving and Using Tokens

## Retrieving Access Tokens

To retrieve an access token, use the `Oauth2ClientService::getAccessToken()` it
the Plugin ID of the OAuth2 Client for which token should be
retrieved. This will return a `\League\OAuth2\Client\Token\AccessToken` object
on which `getToken()` can be called.

```php title="Retrieve a token"
<?
$access_token = $oauth2ClientService->getAccessToken($client_id);
$token = $access_token->getToken();
```

In the above example `$token` will contain the access token that can be used in
requests made to the remote server. The `getAccessToken()` method should be
called before making any requests, to ensure that the token is always valid.
This method will refresh the token in the background if necessary.

If you must use the `resource owner` grant type then ideally the service that
you are connecting to returns a refresh token along with the initial token and
the resource owner's username and password are no longer needed.  If this is not
the case then you will need to securely store and retrieve this additional
confidential data and pass it to `::getAccessToken`.

```php title="Using owner credentials"
<?
$access_token = $this->oauth2ClientService
  ->getAccessToken($client_id, new OwnerCredentials($user, $password));
```

## Using Tokens

[Guzzle](https://docs.guzzlephp.org/en/stable/) is the standard http client
library in Drupal and in PHP in general.
Once you have your token, you will add the token value to a header to the
HttpClient provided by Guzzle.  Drupal provides a
[factory service](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Http%21ClientFactory.php/class/ClientFactory/11.x)
that uses Guzzle to produce these HttpClients.

```php title="Adding tokens to a request"
<?
$options = [
  'headers' => [
    'Authorization' => 'Bearer ' . $token,
  ],
];

$client = $this->clientFactory->fromOptions($options);
```
