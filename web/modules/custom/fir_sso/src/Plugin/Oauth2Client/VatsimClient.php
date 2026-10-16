<?php

declare(strict_types=1);

namespace Drupal\fir_sso\Plugin\Oauth2Client;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\fir_sso\Event\VatsimAuthenticatedEvent;
use Drupal\oauth2_client\Annotation\Oauth2Client;
use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginAccessInterface;
use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginBase;
use Drupal\oauth2_client\Plugin\Oauth2Client\Oauth2ClientPluginRedirectInterface;
use Drupal\oauth2_client\Plugin\Oauth2Client\StateTokenStorage;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * VATSIM OAuth2 Client plugin.
 *
 * @Oauth2Client(
 *   id = "vatsim",
 *   name = @Translation("VATSIM OAuth2 Client"),
 *   grant_type = "authorization_code",
 *   scopes = {"full_name", "email", "vatsim_details"},
 *   scope_separator = " ",
 *   success_message = FALSE
 * )
 */
class VatsimClient extends Oauth2ClientPluginBase implements Oauth2ClientPluginRedirectInterface, Oauth2ClientPluginAccessInterface {

  use StateTokenStorage {
    storeAccessToken as storeTokenInState;
  }

  private EventDispatcherInterface $eventDispatcher;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->eventDispatcher = $container->get('event_dispatcher');
    return $instance;
  }

  private function getRuntimeSetting(string $name, string $default = ''): string {
    $settings = \Drupal::service('settings');
    return (string) $settings->get('fir_sso.vatsim.' . $name, $default);
  }

  public function getClientId(): string {
    return $this->getRuntimeSetting('client_id');
  }

  public function getClientSecret(): string {
    return $this->getRuntimeSetting('client_secret');
  }

  public function getAuthorizationUri(): string {
    return $this->getRuntimeSetting('authorization_uri');
  }

  public function getTokenUri(): string {
    return $this->getRuntimeSetting('token_uri');
  }

  public function getResourceUri(): string {
    return $this->getRuntimeSetting('resource_owner_uri');
  }

  public function storeAccessToken(AccessTokenInterface $accessToken): void {
    $this->storeTokenInState($accessToken);

    $resourceOwner = $this->getProvider()->getResourceOwner($accessToken);
    $this->eventDispatcher->dispatch(new VatsimAuthenticatedEvent($resourceOwner->toArray()));
  }

  public function getPostCaptureRedirect(): RedirectResponse {
    $url = Url::fromRoute('<front>', [], ['absolute' => TRUE]);
    return new RedirectResponse($url->toString());
  }

  public function codeRouteAccess(AccountInterface $account): AccessResultInterface {
    return AccessResult::allowed()->setCacheMaxAge(0);
  }

}
