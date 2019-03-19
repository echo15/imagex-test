<?php

namespace Drupal\imagex_customers\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Customer entities.
 */
class CustomerViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
