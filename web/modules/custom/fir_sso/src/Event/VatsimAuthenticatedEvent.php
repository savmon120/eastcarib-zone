<?php

declare(strict_types=1);

namespace Drupal\fir_sso\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Dispatched by VatsimClient::storeAccessToken() after a successful VATSIM
 * token exchange. Carries the raw /api/user response so subscribers can
 * provision Drupal accounts without needing to know about the OAuth flow.
 *
 * The VATSIM Connect payload may arrive either as a flattened top-level array or
 * as a nested "data" wrapper depending on the endpoint and SDK response shape.
 * The subscriber should normalize the payload before reading fields.
 */
final class VatsimAuthenticatedEvent extends Event {

  /**
   * Constructs a VatsimAuthenticatedEvent.
   *
   * @param array $vatsimData
   *   The decoded JSON response from VATSIM's /api/user endpoint.
   */
  public function __construct(private readonly array $vatsimData) {}

  /**
   * Returns the raw VATSIM user data array.
   *
   * @return array
   *   The full response array from /api/user.
   */
  public function getVatsimData(): array {
    return $this->vatsimData;
  }

}
