<?php

namespace modules\backend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
use app\Entities\PostCollection;

class PostsController extends \Library\Core\AuthController {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {   
        $this->_view['foo'] = 'bar';
        
        $this->render('posts/index.tpl');
    }
    
    public function listAction() {
    	$oPosts = new PostCollection(array(1,2,3,4));
    	$this->_view['oPosts'] = $oPosts;
    }
    
    public function createAction() {
        //$this->_view['oBlocks'] = ;
        
        $this->render('posts/create.tpl');    	
    }
    
}

?>
