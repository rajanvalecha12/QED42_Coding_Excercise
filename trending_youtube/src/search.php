<?php
/**
 * @file
 * Contains \Drupal\rsvplist\EnablerService.
 */

namespace Drupal\trending_youtube;

use Drupal\Core\Database\Database;

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

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    $videos = array();
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos['url'][] = $searchResult['snippet']['url'];
          $videos['tag'][] = $searchResult['snippet']['keyword'];
          break;
      }
    }
    foreach($videos['url'] as $key => $video){
        //create nodes of content type youtube video programmatically
        $data = array(
  'type' => 'youtube_videos', 
  'tag' => $videos['tag'][$key], 
   'field_training_video' => $video
);
$node = Drupal::entityManager()
  ->getStorage('node')
  ->create($data);
$node->save();
        
    }
  }


}
