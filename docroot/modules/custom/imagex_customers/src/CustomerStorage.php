<?php

namespace Drupal\imagex_customers;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class CustomerStorage extends SqlContentEntityStorage implements CustomerStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CustomerInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {customer_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {customer_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(CustomerInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {customer_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('customer_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
