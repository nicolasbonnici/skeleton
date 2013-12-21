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
    
    public function portfolioAction() 
    {
        $this->render('home/portfolio.tpl');    	
    }
    
    public function contactAction() 
    {
        $this->render('home/contact.tpl');    	
    }
    
    public function listAction() {  
		$aLimit = array(0,25);
    	if (isset($this->_params['iStep'])) {
    		$aLimit = array($this->_params['iStep'], $this->_params['iStep'] + 50);
    	}
    	
    	$oItems = new \app\Entities\Collection\FeedItemCollection();
    	$oItems->load('created', 'DESC', $aLimit);	    	
    	foreach ($oItems as $oItem) {
    		$oItem->title = utf8_encode($oItem->title);
    	}
    	
    	$this->_view['oItems'] = $oItems;    	

    	$this->render('home/list.tpl');
    }
    
}

?>
