<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\trending_youtube\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a RSVP Email form.
 */
class VideoImportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'trending_youtube_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['keyword'] = array(
      '#title' => t('Enter Keyword to Search for'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("This Keyword will fetch 20 videos from youtube"),
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Import Videos'),
    );
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      $term = $form_state->getValue('keyword');
      $vocab = 'tags';
      $select = Database::getConnection()->select('taxonomy_term_field_data', 't');
    $select->fields('t', array('tid'));
    $select->condition('name', $term);
    $select->condition('vid', $vocab);
    $results = $select->execute();
    if (!empty($results->fetchCol())) {
          $form_state->setErrorByName('keyword', t('This keyword already exists, try new keywords.'));
      }
  }
//
//  /**
//   * {@inheritdoc}
//   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $value = $form_state->getValue('keyword');
      $term = \Drupal\taxonomy\Entity\Term::create([
          'vid' => 'tags',
          'name' => $value,
    ]);
$term->save();
$search = \Drupal::service('trending_youtube.search');
$search->searchKeyword($value);



//   print_r($videos);die('lklk');

  }
}
