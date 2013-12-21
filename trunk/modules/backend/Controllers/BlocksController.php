<?php

namespace modules\backend\Controllers;

/**
 * Cette entité permet de wrapper d'autres entité (blockTypes) via leur cle primaire (feedItems, posts...) 
 *
 * @author Niko
 */

class BlocksController extends \Library\Core\AuthController {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {   
    	$oBlocks = new \app\Entities\BlockCollection();
    	$oBlocks->loadByParameters(array('blockTypes_idblockType' => 2));
    	
    	foreach ($oBlocks as $oBlock) {
    		$oType = $oBlock->blockType;
    		
    		$sEntity = '\app\Entities\\' . $oType->entity;
    		$oEntity = new $sEntity($oBlock->pkEntity);
    		
    		if ($oEntity->isLoaded()) {
    			$oBlock->title = $oEntity->title;
    			$oBlock->content = $oEntity->content;
    			$oBlock->created = $oEntity->created;
    		}
    		
    	}
    	
    	$this->_view['oBlocks'] = $oBlocks;
    	
        $this->render('blocks/index.tpl');
    }    
    
    public function deleteAction() {
    	
    }
}

?>
