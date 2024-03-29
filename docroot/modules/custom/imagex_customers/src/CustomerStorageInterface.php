<?php

namespace Drupal\imagex_customers;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\imagex_customers\Entity\CustomerInterface;

/**
 * Defines the storage handler class for Customer entities.
 *
 * This extends the base storage class, adding required special handling for
 * Customer entities.
 *
 * @ingroup imagex_customers
 */
interface CustomerStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Customer revision IDs for a specific Customer.
   *
   * @param \Drupal\imagex_customers\Entity\CustomerInterface $entity
   *   The Customer entity.
   *
   * @return int[]
   *   Customer revision IDs (in ascending order).
   */
  public function revisionIds(CustomerInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Customer author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Customer revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\imagex_customers\Entity\CustomerInterface $entity
   *   The Customer entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(CustomerInterface $entity);

  /**
   * Unsets the language for all Customer with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
