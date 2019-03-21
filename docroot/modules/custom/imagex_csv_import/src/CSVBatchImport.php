<?php
/**
 * Created by PhpStorm.
 * User: Dee
 * Date: 3/21/19
 * Time: 11:59 AM
 */

namespace Drupal\imagex_csv_import;

use Drupal\file\Entity\File;
use Drupal\imagex_customers\Entity\Customer;

/**
 * Class CSVBatchImport.
 *
 * @package Drupal\imagex_csv_import
 */
class CSVBatchImport {

  private $batch;

  private $fid;

  private $file;

  private $skip_first_line;

  private $delimiter;

  private $enclosure;

  /**
   * {@inheritdoc}
   */
  public function __construct($fid, $skip_first_line = FALSE, $delimiter = ';', $enclosure = ',', $batch_name = 'Custom CSV import') {
    $this->fid = $fid;
    $this->file = File::load($fid);
    $this->skip_first_line = $skip_first_line;
    $this->delimiter = $delimiter;
    $this->enclosure = $enclosure;
    $this->batch = [
      'title' => $batch_name,
      'finished' => [$this, 'finished'],
      'file' => drupal_get_path('module', 'imagex_csv_import') . '/src/CSVBatchImport.php',
    ];
    $this->parseCSV();
  }

  /**
   * {@inheritdoc}
   */
  public function parseCSV() {
    if (($handle = fopen($this->file->getFileUri(), 'r')) !== FALSE) {
      if ($this->skip_first_line) {
        fgetcsv($handle, 0, ';');
      }
      while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
        $this->setOperation($data);
      }
      fclose($handle);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setOperation($data) {
    $this->batch['operations'][] = [[$this, 'processItem'], $data];
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($id, $name, $balance, &$context) {
    if (!empty($id)) {
      $customer = Customer::load($id);
    }

    if (!isset($customer) || $customer == NULL) {
      $customer = Customer::create([
        'type' => 'article',
        'langcode' => 'ru',
        'uid' => 1,
        'status' => 1,
      ]);
    }

    $customer->name = $name;
    $customer->balance = $balance;
    $customer->save();
    $context['results'][] = $customer->id() . ' : ' . $customer->getName()  . ' Imported';
    $context['message'] = $customer->getName() . ' Imported';
  }

  /**
   * {@inheritdoc}
   */
  public function setBatch() {
    batch_set($this->batch);
  }

  /**
   * {@inheritdoc}
   */
  public function processBatch() {
    batch_process();
  }

  /**
   * {@inheritdoc}
   */
  public function finished($success, $results, $operations) {
    if ($success) {
      $message = \Drupal::translation()
        ->formatPlural(count($results), 'One customer imported.', '@count customers imported.');
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::logger('imagex_csv_import')->notice($message);
  }

}