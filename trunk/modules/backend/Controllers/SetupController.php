<?php

namespace modules\backend\Controllers;

/**
 * Manage Core setup
 *
 * @author info
 */
class SetupController extends \Library\Core\AuthController {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {           
        $this->render('setup/index.tpl');
    }    

    public function usersAction() 
    {
    	$oUsers = new \app\Entities\Collection\UserCollection();
    	$oUsers->loadLast();
    	$this->_view['oUsers'] = $oUsers;
    	
        $this->render('setup/users.tpl');
    }

    public function entitiesAction() {         
        
    	$aDatabaseEntitiesClass = array();
    	$aDatabaseEntities = \Library\Core\Tools::getDatabaseEntities();
    	foreach ($aDatabaseEntities as $aEntity) {
    		$sDatabaseEntityName = $aEntity['TABLE_NAME'];
    		$aDatabaseEntitiesClass[] = \Library\Core\Scaffold::generateEntity($sDatabaseEntityName);
    	}   		
    	
        $this->_view['aDatabaseEntitiesClass'] = $aDatabaseEntitiesClass;
    	$this->render('setup/entities.tpl');
    }
    
    public function aclAction() {   


    	$this->render('setup/acl.tpl');
    }
}

?>
