<?php

namespace Drupal\Tests\groups_group_subscribe_formatter\ExistingSite;

use Drupal\Core\Url;
use weitzman\DrupalTestTraits\ExistingSiteBase;

/**
 * A test case for checking group subscribe message.
 */
class GroupSubscribeLinkTest extends ExistingSiteBase {

  /**
   * An example test method; note that Drupal API's and Mink are available.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupSubscribe() {
    // Creates users for group creation and viewing group page.
    $group_user = $this->createUser([], 'groupUser', FALSE);
    $admin_user = $this->createUser([], 'administratorUser', TRUE);

    // Login with admin user to create a group node.
    $this->drupalLogin($admin_user);
    // Create a "Group1" group.
    $node = $this->createNode([
      'title' => 'Group1',
      'type' => 'group',
      'uid' => $admin_user->id(),
    ]);
    $node->setPublished()->save();
    $this->assertEquals($admin_user->id(), $node->getOwnerId());

    // Go to group node page.
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);

    // Login and browse group node page.
    $this->drupalLogin($group_user);
    $this->drupalGet($node->toUrl());

    $expected_message = sprintf('Hi %s, click here if you would like to subscribe to this group called %s',
      $group_user->getDisplayName(), $node->getTitle());
    $text_content = $this->getTextContent();

    // Check the expected string on the page.
    $this->assertStringContainsString($expected_message, $text_content);
  }

}
