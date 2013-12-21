<?php

namespace modules\backend\Controllers;

/**
 * Description of FeedController
 *
 * @author info
 */

class FeedsController extends \Library\Core\AuthController {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {   

    	$oFeeds = new \app\Entities\FeedCollection();
    	$oFeeds->loadByParameters(array('status' => 1));
    	
		$oFeedItems = new \app\Entities\FeedItemCollection();
    	$oFeedItems->loadByParameters(array('status' => 'publish'), array('idfeedItem'=>'DESC'), array(0,2000));
    	/* @todo passer ca en conf
    	$sUrl = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    	$sRequestMethod = 'GET';
    	$sGetfield = '?screen_name=nicolasbonnici';
    	
    	$aSettings = array(
		    'oauth_access_token' => "17471981-qdnKvIpNmOMgRYEX93uG7uS7rrtOOZCY8YRNd9NWE",
		    'oauth_access_token_secret' => "DF3lR7CypxHnZXaTAuoDlcoR6WVmNOcRR2LpHu2Q",
		    'consumer_key' => "ZWgTx1j7VvOv75b3ofg",
		    'consumer_secret' => "hSmfX9oOWBBYJyQmxSvyI0aUMqoac3xze4utWunyrE"
		);
		
		$twitter = new \Library\Twitter\TwitterAPI($aSettings);
		$oTimelineTweets = $twitter->setGetfield($sGetfield)
					->buildOauth($sUrl, $sRequestMethod)
		            ->performRequest();		
		foreach(json_decode($oTimelineTweets) as $oTweet) {
			
			var_dump($oTweet);			
		}*/
    	$this->_view['oFeeds'] = $oFeeds;    	    	
		//$this->_view['oFeedItems'] = $oFeedItems;   
    	
        $this->render('feeds/index.tpl');
    }
    
    public function createAction() {
    	if (isset(
    		$this->_params['title'],
    		$this->_params['url'],
    		$this->_params['domain'],
    		$this->_params['icon']
    	)) {
    		$oFeed = new \app\Entities\Feed();
    		
    		$oFeed->title = $this->_params['title'];
    		$oFeed->url = $this->_params['url'];
    		$oFeed->domain = $this->_params['domain'];
    		$oFeed->icon = $this->_params['icon'];
    		
    		$this->_view['create'] = $oFeed->add();
    	}
    	
    	$this->render('feeds/create.tpl');
    	
    }
    
    public function updateAction() {   
	
        if (isset($this->_params['id']) && !empty($this->_params['id'])) {
        	        	
            // load then update item and send bool to the view
            try{
            	$oFeed = new \app\Entities\Feed($this->_params['id']);
            } catch (Exception $oException) {            	                
                $oFeed = null;
            }        	
        	
            if (
            	!is_null($oFeed) &&
            	isset(
            		$this->_params['pk'], 
            		$this->_params['name'], 
            		$this->_params['value'], 
            		$oFeed->{$this->_params['name']}
            		)            		
            ) {
            	$oFeed->{$this->_params['name']} = $this->_params['value'];    
	            $oFeed->lastupdate = time();
	                
	            // Return flag to the view
	            $this->_view['update'] = $oFeed->update();                     
            }

            $this->_view['oFeed'] = $oFeed;
            
        }
        
        $this->render('feeds/update.tpl');           
    }        

}

?>
