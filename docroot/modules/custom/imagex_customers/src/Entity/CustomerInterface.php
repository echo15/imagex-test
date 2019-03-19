<?php

namespace Drupal\imagex_customers\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Customer entities.
 *
 * @ingroup imagex_customers
 */
interface CustomerInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Customer name.
   *
   * @return string
   *   Name of the Customer.
   */
  public function getName();

  /**
   * Sets the Customer name.
   *
   * @param string $name
   *   The Customer name.
   *
   * @return \Drupal\imagex_customers\Entity\CustomerInterface
   *   The called Customer entity.
   */
  public function setName($name);

  /**
   * Gets the Customer creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Customer.
   */
  public function getCreatedTime();

  /**
   * Sets the Customer creation timestamp.
   *
   * @param int $timestamp
   *   The Customer creation timestamp.
   *
   * @return \Drupal\imagex_customers\Entity\CustomerInterface
   *   The called Customer entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Customer published status indicator.
   *
   * Unpublished Customer are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Customer is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Customer.
   *
   * @param bool $published
   *   TRUE to set this Customer to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\imagex_customers\Entity\CustomerInterface
   *   The called Customer entity.
   */
  public function setPublished($published);

  /**
   * Gets the Customer revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Customer revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\imagex_customers\Entity\CustomerInterface
   *   The called Customer entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Customer revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Customer revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\imagex_customers\Entity\CustomerInterface
   *   The called Customer entity.
   */
  public function setRevisionUserId($uid);

}
