<?php

namespace modules\frontend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class HomeController extends \Library\Core\Controller {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {  
   	
        $this->render('home/index.tpl');
    }
    
    public function lifestreamAction() 
    {
    	$oFeeds = new \app\Entities\Collection\FeedCollection();
    	$oFeeds->load();
    	$this->_view['oFeeds'] = $oFeeds;    	
        $this->render('home/lifestream.tpl');    	
    }
    
    public function portfolioAction() 
    {
        $this->render('home/portfolio.tpl');    	
    }
    
    public function contactAction() 
    {
        $this->render('home/contact.tpl');    	
    }
    
    public function listAction($iLoadStep = 64) {  
    	$oItems = new \app\Entities\Collection\FeedItemCollection();
		$aLimit = array(0, $iLoadStep);

    	if (isset($this->_params['istep']) && $this->_params['istep'] > 0) {
    		$aLimit = array($this->_params['istep'], $iLoadStep);
    	}
    	
    	$aParameters = array('status' => 'publish');
    	if (isset($this->_params['ifeedid']) && (int)$this->_params['ifeedid'] > 0) {
    		$aParameters['feed_idfeed'] = (int)$this->_params['ifeedid'];
    	}
    	$oItems->loadByParameters(// @todo ajouter le support des array en param et faire des IN ensuite
    		$aParameters,
    		array(
    			'created' => 'desc'		
    		),
    		$aLimit
    	);
    	foreach ($oItems as $oItem) {
    		$oItem->title = utf8_encode($oItem->title);
    	}
    	
    	$this->_view['oItems'] = $oItems;    	

    	$this->render('home/list.tpl');
    }
    
}

?>
