<?php
/**
 * @file
 * Contains \Drupal\rsvplist\EnablerService.
 */

namespace Drupal\trending_youtube;

use \Drupal\node\Entity\Node;
use Drupal\taxonomy\TermStorage;
use Google_Client;
use Google_Service_YouTube;
/**
 * Defines a service for managing RSVP list enabled for nodes.
 */
class search {
  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * Sets a individual node to be RSVP enabled.
   *
   * @param \Drupal\node\Entity\Node $node
   */
  
  public function searchKeyword($keyword) {
//    require_once DRUPAL_ROOT.'/l'vendor/autoload.php';
//  require_once ($_SERVER["DRUPAL_ROOT"].'/librarires/google-api-php-client/src/Google_Client.php');
//  require_once ($_SERVER["DRUPAL_ROOT"].'/libraries/google-api-php-client/src/contrib/Google_YouTubeService.php');
    $DEVELOPER_KEY = 'AIzaSyBDTxs1WETa-rCXNcUyTJaw4ReR0c8l-T8';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  $htmlBody = '';
  

    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $keyword,
      'maxResults' => 100,
    ));
//echo "Keyword=";print_r($keyword);echo "\n search response =";print_r($searchResponse);die('mam');
    $videos = '';

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    $videos = array();
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos['url'][] = $searchResult['id']['videoId'];
          $videos['title'][] = $searchResult['snippet']['title'];
        break;
      }
    }
//    print_r($videos);die('hey');
    
    $terms = \Drupal::service('entity_type.manager')
->getStorage("taxonomy_term")
->loadTree('tags');
    
    foreach($terms as $term){
        if($term->name == $keyword){
            $tid = $term->tid;
            break;
        }
    }
//    kint($tid);die('haha');
    $batch = array(
  'title' => t('Importing Videos from youtube'),
  'operations' => array(
    array(
      'my_function_1',
      array(
        $videos,
        $tid,
      ),
    ),
  ),
  'finished' => 'my_finished_callback',
//  'file' => drupal_get_path('module', 'trending_youtube') . '/src/search.php',

);
batch_set($batch);
//batch_process('admin/content');
    
  }


}
