<?php

namespace Drupal\Tests\oauth2_client\Unit;

use Drupal\oauth2_client\Exception\NonrenewableTokenException;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the NonrenewableTokenException class.
 */
class NonrenewableTokenExceptionTest extends UnitTestCase {

  /**
   * Tests the NonrenewableTokenException constructor logic.
   */
  public function testNonrenewableTokenException() {
    // No message is set by default.
    $exception = new NonrenewableTokenException();
    $this->assertEquals('', $exception->getMessage());
    // Verify the calculated message is set when grant type is provided.
    $messageException = new NonrenewableTokenException('test_grant');
    $this->assertEquals('A token obtained using the test_grant grant has expired without a refresh, and cannot be renewed without user interaction', $messageException->getMessage());
    // Verify custom message overrides calculated default.
    $customMessageException = new NonrenewableTokenException('test_grant', 'Custom message');
    $this->assertEquals('Custom message', $customMessageException->getMessage());
  }

}
