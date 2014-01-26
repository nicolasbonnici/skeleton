<?php

namespace modules\frontend\Controllers;

/**
 * Description of FeedsController
 *
 * @author info
 */
class FeedsController extends \Library\Core\Controller {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {

    }   

    public function indexAction() {
    	
   		$oTwitterFeed = new \app\Entities\Feed();
    	$oTwitterFeed->loadByParameters(array('idfeed' => 1)); // @todo dynamiser    	
   		$oGoogleFeed = new \app\Entities\Feed();
    	$oGoogleFeed->loadByParameters(array('idfeed' => 3)); // @todo dynamiser
    	    	
		$oNewTwitterActivities = $this->parseTwitterActivity($oTwitterFeed, true);         	    	
		$oNewGoogleActivities = null;//$this->parseGoogleActivity($oGoogleFeed, true);         	    	
		
		$this->_view['oTwitterFeed'] = $oTwitterFeed;
		$this->_view['oNewTwitterActivities'] = $oNewTwitterActivities;
		$this->_view['oGoogleFeed'] = $oGoogleFeed;
		$this->_view['oNewGoogleActivities'] = $oNewGoogleActivities;
        $this->render('feeds/index.tpl');
    }

    private function parseTwitterActivity(\app\Entities\Feed $oTwitterFeed, $bSaveNewActivities = false, $iDelta = 100) {
    	
    	assert('$oTwitterFeed->isLoaded() && \Library\Core\Validator::integer($iDelta, 0, 500) === \Library\Core\Validator::STATUS_OK');   	
    	    	
    	$oItems = new \app\Entities\Collection\FeedItemCollection();
    	
    	// @see loader les derniers enregistrements de la db pour persister le diff des nouvelles activités
    	if ($bSaveNewActivities) {
    		$aDbElements = array();
	    	$oDatabaseLastFeedItems = new \app\Entities\Collection\FeedItemCollection();
	    	$oDatabaseLastFeedItems->loadByParameters(array('feed_idfeed' => 1, 'status' => 'publish'), array('created'=>'DESC'), array(0,50));
	    	// Indexer les items dans un array pour mapper plus vite
	    	foreach ($oDatabaseLastFeedItems as $oDbFeedItem) {
	    		if (isset($oDbFeedItem->created, $oDbFeedItem->title)) {
	    			$aDbElements[] = (int)$oDbFeedItem->created;
	    		}
	    	}
    	}
    	//die(var_dump($aDbElements));

    	
		// @todo move and use config dynamiser ...
		$sRequestMethod = 'GET';
		$sGetfield = '?screen_name=nicolasbonnici&count=' . $iDelta;
		
		$aSettings = array(
		    'oauth_access_token' => "17471981-qdnKvIpNmOMgRYEX93uG7uS7rrtOOZCY8YRNd9NWE",
		    'oauth_access_token_secret' => "DF3lR7CypxHnZXaTAuoDlcoR6WVmNOcRR2LpHu2Q",
		    'consumer_key' => "ZWgTx1j7VvOv75b3ofg",
		    'consumer_secret' => "hSmfX9oOWBBYJyQmxSvyI0aUMqoac3xze4utWunyrE"
	    );

	    $twitter = new \Library\Twitter\TwitterAPI($aSettings);
	    $json = $twitter->setGetfield($sGetfield)
		    ->buildOauth($oTwitterFeed->url, $sRequestMethod)
		    ->performRequest();
		    
	    foreach(json_decode($json) as $oItem) {
	    	$oFeedItem = new \app\Entities\FeedItem();

	    	$oCreated = new \DateTime($oItem->created_at);
	    	$oFeedItem->title = (string)$oItem->text;
	    	$oFeedItem->status = 'publish';
	    	$oFeedItem->created = (int)$oCreated->getTimestamp();
	    	$oPermalinks = $oItem->entities->urls;
	    		
	    	foreach($oPermalinks as $oPermalink) {
	    		// @see La structure en base ne permet pas de stocker plus d'un permalink par feedItem
	    		$sPermalink = (string)$oPermalink->expanded_url;
	    		break;
	    	}
	    	$oFeedItem->permalink = $sPermalink;
	    	$oFeedItem->feed_idfeed = 1;
    			    	
	    	if ($bSaveNewActivities) {
				$oAddedFeedActivities = new \Library\Core\Collection();			    	
	    		if (!in_array($oFeedItem->created, $aDbElements)) {	    
	    			$oFeedItem->title = htmlentities($oFeedItem->title, ENT_QUOTES, "UTF-8");
	    			// @see persist delta into database
	    			if ($oFeedItem->add()) {
	    				$oAddedFeedActivities->add($oAddedFeedActivities->count()+1, $oFeedItem);
	    			}
	    			
	    		}
	    			    		
	    	}

	    	$oItems->add($oItems->count()+1, $oFeedItem);
	    	
	    }
	    
	    if (isset($oAddedFeedActivities) && ($oAddedFeedActivities->count() > 0)) {
	    	$oItems->oAddedFeedActivities = $AddedFeedActivities; 
	    }
	    
		return $oItems;				    
    }    
        
    
	private function parseGoogleActivity(\app\Entities\Feed $oGoogleFeed, $bSaveNewActivities = false) {
//die(var_dump($oGoogleFeed->url));
// Declaring the variables required for authentication
	$client_key = '610732600908';
	$client_secret = 'tNm-Wm07_FKbU1w8apLPFVGh';
	$api_key = 'AIzaSyBM5m0GMud8n1zCQ-InRWr140GMfvlB1NY';
	$redirect_uri = 'http://dev.nbonnici.info/frontend/feeds/';

// Check if the authorization code is received or not !
// Also, if the access token is received or not
	if (!isset($_REQUEST['code']) && !isset($_SESSION['access_token'])) {
		// Print the below message, if the code is not received !
		echo "Please Authorize your account: <br />";
		echo '<a href = "https://accounts.google.com/o/oauth2/auth?client_id='. $client_key. '&redirect_uri='.$redirect_uri .'&scope=https://www.googleapis.com/auth/plus.me&response_type=code">Click Here to Authorize</a>';
	}
	else {
    if(!isset($_SESSION['access_token'])) {
		  // Initialize a cURL session
		  $ch = curl_init();

		  // Set the cURL URL
		  curl_setopt($ch, CURLOPT_URL, "https://accounts.google.com/o/oauth2/token");

		  // The HTTP METHOD is set to POST
		  curl_setopt($ch, CURLOPT_POST, TRUE);

		  // This option is set to TRUE so that the response
		  // doesnot get printed and is stored directly in 
		  // the variable
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		  // The POST variables which need to be sent along with the HTTP request
		  curl_setopt($ch, CURLOPT_POSTFIELDS, "code=" . $_REQUEST['code'] . "&client_id=" . $client_key . "&client_secret=" . $client_secret . "&redirect_uri=".$redirect_uri."&grant_type=authorization_code");

		  // Execute the cURL request		
		  $data = curl_exec($ch);

		  // Close the cURL connection
		  curl_close($ch);

		  // Decode the JSON request and remove the access token from it
		  $data = json_decode($data);

		  $access_token = $data->access_token;

		  // Set the session access token
		  $_SESSION['access_token'] = $data->access_token;
    }
    else {
      // If session access token is set
      $access_token = $_SESSION['access_token'];
    }
		// Initialize another cURL session
		$ch = curl_init();

		// Set all the options and execute the session
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/plus/v1/people/me?access_token=" . $access_token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		// Get the data from the JSON response
		$data = json_decode($data);

		// Show the output
		echo "<img src = \"". $data->image->url . "\" /> <br />";
		echo "<pre>";
		echo "========================================= <br/>";
		echo "  + Hello " . $data->displayName . "<br />";
		echo "  + I am shocked you are " . $data->relationshipStatus . " ! <br />";
		echo "  + I know something about you: <br />";
		echo $data->aboutMe . "<br />";
		echo "========================================= <br />";
		echo "Author: @dhruvbaldawa <br />";
		echo "</pre>"; 
		echo "You are successfully authorized.";
	}			
		
    	$oItems = new \app\Entities\Collection\FeedItemCollection();
    	$oDoc = new \DOMDocument();
		$oDoc->load($oGoogleFeed->url);
		
		foreach ($oDoc->getElementsByTagName('item') as $node) {
			var_dump($node);
		}


	}
        

}

?>
