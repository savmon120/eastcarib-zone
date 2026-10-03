<?php

declare(strict_types=1);

namespace Drupal\fir_sso\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;
use Psr\Log\LoggerInterface;

/**
 * Creates and updates Drupal user accounts from VATSIM Connect user data.
 *
 * Called by VatsimLoginSubscriber. All user provisioning logic lives here so
 * the subscriber stays thin and this class is independently testable.
 */
class VatsimUserManager {

  /**
   * Constructs a VatsimUserManager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Psr\Log\LoggerInterface $logger
   *   The fir_sso logger channel.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly LoggerInterface $logger,
  ) {}

  /**
   * Creates or loads a Drupal user and syncs VATSIM profile fields.
   *
   * New accounts are given the 'controller' role. All accounts have their
   * VATSIM profile fields updated on every login so data stays current.
   *
   * @param array $vatsimData
   *   The decoded JSON response from VATSIM's /api/user endpoint.
   *
   * @return \Drupal\user\UserInterface
   *   The saved Drupal user account.
   *
   * @throws \RuntimeException
   *   If the VATSIM payload does not contain a CID.
   */
  public function provisionUser(array $vatsimData): UserInterface {
    $data = $this->normalizePayload($vatsimData);
    $cid = (string) ($this->readValue($data, ['cid']) ?? '');

    if ($cid === '') {
      throw new \RuntimeException('VATSIM authentication payload is missing the required CID field.');
    }

    $email = (string) ($this->readValue($data, ['personal.email', 'email']) ?? '');
    $firstName = (string) ($this->readValue($data, ['personal.name_first', 'first_name']) ?? '');
    $lastName = (string) ($this->readValue($data, ['personal.name_last', 'last_name']) ?? '');
    $rating = (string) ($this->readValue($data, ['vatsim.rating.short', 'rating.short', 'rating']) ?? '');
    $region = (string) ($this->readValue($data, ['vatsim.region.id', 'region.id']) ?? '');
    $division = (string) ($this->readValue($data, ['vatsim.division.id', 'division.id']) ?? '');
    $subdivision = (string) ($this->readValue($data, ['vatsim.subdivision.id', 'subdivision.id']) ?? '');

    $storage = $this->entityTypeManager->getStorage('user');
    $existing = $storage->loadByProperties(['field_vatsim_cid' => $cid]);

    /** @var \Drupal\user\UserInterface $account */
    if (empty($existing)) {
      $account = $storage->create([
        'name' => $cid,
        'mail' => $email,
        'status' => 1,
      ]);
      $this->logger->info('Created new Drupal account for VATSIM CID @cid.', ['@cid' => $cid]);
    }
    else {
      $account = reset($existing);
      $this->logger->info('Loaded existing Drupal account for VATSIM CID @cid.', ['@cid' => $cid]);
    }

    $account->set('field_vatsim_cid', $cid);
    $account->set('field_vatsim_first_name', $firstName);
    $account->set('field_vatsim_last_name', $lastName);
    $account->set('field_vatsim_rating', $rating);
    $account->set('field_vatsim_region', $region);
    $account->set('field_vatsim_division', $division);
    $account->set('field_vatsim_subdivision', $subdivision);

    $roles = ['authenticated'];
    $ratingMap = [
      'OBS' => 1,
      'S1' => 2,
      'S2' => 3,
      'S3' => 4,
      'C1' => 5,
      'C3' => 6,
      'I1' => 7,
      'I3' => 8,
      'SUP' => 9,
      'ADM' => 10,
    ];

    $ratingLevel = $ratingMap[$rating] ?? 0;
    $isSandbox = ((int) $cid >= 10000000 && (int) $cid <= 10000010);
    $allowedSubdivisions = ['CUR', 'PIA'];
    $isInZone = $division === 'CAR' && in_array($subdivision, $allowedSubdivisions, TRUE);

    if ($isSandbox) {
      $sandboxOverrideCIDs = ['10000000', '10000001', '10000002', '10000003', '10000004'];
      $isInZone = in_array($cid, $sandboxOverrideCIDs, TRUE);
    }

    $isLearningController = ($rating === 'OBS') && $isInZone;
    $isRatedController = ($ratingLevel >= 2) && $isInZone;
    $isController = $isLearningController || $isRatedController;

    if ($account->hasRole('visiting_controller')) {
      $isController = TRUE;
    }

    if ($isController) {
      $roles[] = 'controller';
    }
    else {
      $roles[] = 'pilot';
    }

    $account->set('roles', $roles);
    $account->save();

    return $account;
  }

  /**
   * Normalizes the payload shape returned by VATSIM Connect.
   *
   * Some responses are wrapped in a top-level "data" object, while others are
   * already flattened at the root level.
   */
  private function normalizePayload(array $vatsimData): array {
    if (isset($vatsimData['data']) && is_array($vatsimData['data'])) {
      return $vatsimData['data'];
    }

    return $vatsimData;
  }

  /**
   * Reads a nested value from the payload using dot-delimited paths.
   */
  private function readValue(array $data, array $paths): mixed {
    foreach ($paths as $path) {
      $value = $data;
      $segments = explode('.', $path);
      $found = TRUE;

      foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
          $found = FALSE;
          break;
        }
        $value = $value[$segment];
      }

      if ($found) {
        return $value;
      }
    }

    return NULL;
  }

}
