<?php

namespace Drupal\oauth2_client\Hook;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for oauth2_client.
 */
class Oauth2ClientHooks {

  /**
   * Implements hook_help() for oauth2_client module.
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): array {
    $build = [];
    if ($route_name === 'help.page.oauth2_client') {
      $readme_content = file_get_contents(__DIR__ . '/README.md');
      $build = [
        '#type' => 'html_tag',
        '#tag' => 'pre',
        '#value' => $readme_content,
      ];
    }
    return $build;
  }

}
