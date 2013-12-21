<?php 
namespace modules\backend\Controllers;

use Library\Core\CoreControllerException;

use Library\Core\CoreEntityException;

class CrudController extends \Library\Core\AuthController {
  
    public function __preDispatch() {

    }    
  
    public function __postDispatch() {
        
    }   

    public function indexAction() {   
    	
    }
    
    /**
     * 
     * @todo implementer hasUpdateAccess() du composant ACL
     */
    public function updateAction() 
    {    	
		if (
		   isset(
			  $this->_params['pk'], 
			  $this->_params['name'], 
			  $this->_params['value'],
			  $this->_params['entity']
		   )				  
		) {
			// load then update entity and send bool to the view
			$oEntity = null; 	
			$sEntity = '\App\Entities\\' . $this->_params['entity'];
			if(class_exists($sEntity))  {
				try{
					$oUser = new \App\Entities\User($this->_session['iduser']);
				} catch (CoreEntityException $oException) {  
					throw new CoreEntityException($oException);		  
				}			  	
				try{
					$oEntity = new $sEntity($this->_params['pk']);
				} catch (CoreEntityException $oException) {  
					throw new CoreEntityException($oException);		  
				}			  	
				if (!is_null($oEntity) && isset($oEntity->{$this->_params['name']}))  {
					// Check ACL and data integrity
					if (
						$this->hasUpdateAccess($this->_params['entity']) &&
						($sDataType = $oEntity->getDataType($this->_params['name'])) !== null &&
						\Library\Core\Validator::$sDataType($this->_params['value']) === \Library\Core\Validator::STATUS_OK 
					) {
					   $oEntity->{$this->_params['name']} = $this->_params['value'];
					   if (isset($oEntity->lastupdate)) {
						   $oEntity->lastupdate = time();					   
					   }

					   // Return flag to the view
					   $this->_view['oEntity'] = $oEntity;
					   $this->_view['update'] = $oEntity->update();			
					}		 
				}					
			}						
		}


        
        $this->render('crud/update.tpl');           
    }            
}