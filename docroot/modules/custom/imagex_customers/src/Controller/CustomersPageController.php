<?php

namespace Drupal\imagex_customers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Renderer;

/**
 * Class CustomersPageController.
 */
class CustomersPageController extends ControllerBase {

  /**
   * @var Renderer
   */
  protected $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * Customerspage.
   *
   * @return string
   *   Return Hello string.
   */
  public function customersPage($customer_id) {
    if (!isset($customer_id) || empty($customer_id)) {
      $arg = 'all';
    }
    else {
      $arg = $customer_id;
    }

    $content = [];
    // get renderable array for view
    $view = views_embed_view('customers_view', 'default', $arg);
    // render view
    $content['#markup'] = $this->renderer->render($view);

    return $content;
  }

  /**
   * All Customers page.
   *
   * @return string
   *   Return Hello string.
   */
  public function allCustomersPage() {
    $arg = 'all';
    $content = [];
    // get renderable array for view
    $view = views_embed_view('customers_view', 'default', $arg);
    // render view
    $content['#markup'] = $this->renderer->render($view);

    return $content;
  }

}
