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

$DEVELOPER_KEY = 'AIzaSyBDTxs1WETa-rCXNcUyTJaw4ReR0c8l-T8';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  $htmlBody = '';
  

    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $value,
      'maxResults' => 20,
    ));

    $videos = '';
    $channels = '';
    $playlists = '';

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['videoId']);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['channelId']);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
          break;
      }
    }

   print_r($videos);die('lklk');


//    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
//    db_insert('rsvplist')
//      ->fields(array(
//        'mail' => $form_state->getValue('email'),
//        'nid' => $form_state->getValue('nid'),
//        'uid' => $user->id(),
//        'created' => time(),
//       ))
//      ->execute();
//    drupal_set_message(t('Thank you for your RSVP, you are on the list for the event.'));
  }
}
