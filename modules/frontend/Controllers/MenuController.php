<?php

namespace modules\frontend\Controllers;

/**
 * Description of MenuController
 *
 * @author info
 */
class MenuController extends \Library\Core\Controller {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {   
               
    	$this->_view['aModule'] = $this->_module;
    	$this->_view['aControllers'] = $this->buildControllers($this->_module);
        $this->render('menu/index.tpl');
    }
    
}

?>
