<?php

namespace Drupal\imagex_customers\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\imagex_customers\Entity\CustomerInterface;

/**
 * Class CustomerController.
 *
 *  Returns responses for Customer routes.
 */
class CustomerController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Customer  revision.
   *
   * @param int $customer_revision
   *   The Customer  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($customer_revision) {
    $customer = $this->entityManager()->getStorage('customer')->loadRevision($customer_revision);
    $view_builder = $this->entityManager()->getViewBuilder('customer');

    return $view_builder->view($customer);
  }

  /**
   * Page title callback for a Customer  revision.
   *
   * @param int $customer_revision
   *   The Customer  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($customer_revision) {
    $customer = $this->entityManager()->getStorage('customer')->loadRevision($customer_revision);
    return $this->t('Revision of %title from %date', ['%title' => $customer->label(), '%date' => format_date($customer->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Customer .
   *
   * @param \Drupal\imagex_customers\Entity\CustomerInterface $customer
   *   A Customer  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CustomerInterface $customer) {
    $account = $this->currentUser();
    $langcode = $customer->language()->getId();
    $langname = $customer->language()->getName();
    $languages = $customer->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $customer_storage = $this->entityManager()->getStorage('customer');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $customer->label()]) : $this->t('Revisions for %title', ['%title' => $customer->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all customer revisions") || $account->hasPermission('administer customer entities')));
    $delete_permission = (($account->hasPermission("delete all customer revisions") || $account->hasPermission('administer customer entities')));

    $rows = [];

    $vids = $customer_storage->revisionIds($customer);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\imagex_customers\CustomerInterface $revision */
      $revision = $customer_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $customer->getRevisionId()) {
          $link = $this->l($date, new Url('entity.customer.revision', ['customer' => $customer->id(), 'customer_revision' => $vid]));
        }
        else {
          $link = $customer->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.customer.translation_revert', ['customer' => $customer->id(), 'customer_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.customer.revision_revert', ['customer' => $customer->id(), 'customer_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.customer.revision_delete', ['customer' => $customer->id(), 'customer_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['customer_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
