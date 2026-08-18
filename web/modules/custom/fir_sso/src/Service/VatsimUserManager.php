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
    $data = $vatsimData['data'] ?? [];
    $cid = (string) ($data['cid'] ?? '');

    if (empty($cid)) {
      throw new \RuntimeException('VATSIM authentication payload is missing the required CID field.');
    }

    $email = $data['personal']['email'] ?? '';
    $firstName = $data['personal']['name_first'] ?? '';
    $lastName = $data['personal']['name_last'] ?? '';
    $rating = $data['vatsim']['rating']['short'] ?? '';
    $region = $data['vatsim']['region']['id'] ?? '';
    $division = $data['vatsim']['division']['id'] ?? '';
    $subdivision = $data['vatsim']['subdivision']['id'] ?? '';

    $storage = $this->entityTypeManager->getStorage('user');
    $existing = $storage->loadByProperties(['field_vatsim_cid' => $cid]);

    /** @var \Drupal\user\UserInterface $account */
    if (empty($existing)) {
      // First login — create a new Drupal account keyed on the VATSIM CID.
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

    // Sync VATSIM profile fields on every login so they stay current.
    $account->set('field_vatsim_cid', $cid);
    $account->set('field_vatsim_first_name', $firstName);
    $account->set('field_vatsim_last_name', $lastName);
    $account->set('field_vatsim_rating', $rating);
    $account->set('field_vatsim_region', $region);
    $account->set('field_vatsim_division', $division);
    $account->set('field_vatsim_subdivision', $subdivision);


  /**
   * -------------------------------
   * ROLE ASSIGNMENT LOGIC
   * -------------------------------
   */

  // Base roles.
  $roles = ['authenticated'];

  // Rating map.
  $ratingMap = [
    'OBS' => 1,
    'S1'  => 2,
    'S2'  => 3,
    'S3'  => 4,
    'C1'  => 5,
    'C3'  => 6,
    'I1'  => 7,
    'I3'  => 8,
    'SUP' => 9,
    'ADM' => 10,
  ];

  $ratingLevel = $ratingMap[$rating] ?? 0;

  // Dectect https://vatsim.dev/services/connect/sandbox/#test-accounts accounts
  $isSandbox = ((int)$cid >= 10000000 && (int)$cid <= 10000010);

  // East Caribbean Zone = Piarco FIR + Curaçao FIR.
  $allowedSubdivisions = ['CUR', 'PIA'];

  // Zone membership.
  $isInZone =
    $division === 'CAR' &&
    in_array($subdivision, $allowedSubdivisions, TRUE);

  // Sandbox override for half the sandbox accounts to allow testing of controller roles.
  if ($isSandbox) {
    $sandboxOverrideCIDs = ['10000000', '10000001', '10000002', '10000003', '10000004'];
    $isInZone = in_array($cid, $sandboxOverrideCIDs, TRUE);
  }
  // Learning controllers (OBS in zone).
  $isLearningController = ($rating === 'OBS') && $isInZone;

  // Rated controllers (S1+ in zone).
  $isRatedController = ($ratingLevel >= 2) && $isInZone;

  // Combined controller check.
  $isController = $isLearningController || $isRatedController;

  // Visitors override.
  if ($account->hasRole('visiting_controller')) {
    $isController = TRUE;
  }

  // Assign final role.
  if ($isController) {
    $roles[] = 'controller';
  }
  else {
    $roles[] = 'pilot';
  }

  $account->set('roles', $roles);

  /**
   * -------------------------------
   * END OF ROLE LOGIC
   * -------------------------------
   */

    $account->save();

    return $account;
}

}
