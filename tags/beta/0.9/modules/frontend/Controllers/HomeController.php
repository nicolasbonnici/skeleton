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
    
    public function listAction() {   		
    	$oItems = new \app\Entities\Collection\FeedItemCollection();
    	$oItems->loadByParameters(array('status' => 'publish' ), array('created'=>'DESC'), array(0,50));	
    	
    	foreach ($oItems as $oItem) {
    		$oItem->title = utf8_encode($oItem->title);
    	}
    	
    	$this->_view['oItems'] = $oItems;    	

    	$this->render('home/list.tpl');
    }
    
}

?>
