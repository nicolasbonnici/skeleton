<?php

namespace modules\frontend\Controllers;

/**
 * Login controller
 *
 * @author info
 */
class AuthController extends \Library\Core\Controller {
  
    public function __preDispatch() {
		    	
    }    
  
    public function __postDispatch() {
        
    }   

    // @todo virer si session loggué
    public function indexAction() {  

        if (isset($this->_params['email']) && isset($this->_params['password'])) {
                    
            if($this->login()) {
                
                \Library\Core\Router::redirect('/backend/'); // @todo dynamiser ce param 
                
            }// @todo gestion erreur de login
            
        }
        
        $this->render('auth/index.tpl');
    }
    
    public function logoutAction() {
        
        $oCookie = new \Library\Core\Cookie();
        //die(var_dump($oCookie->getCookieVar('PHPSESSID')));
        
        session_destroy();
        \Library\Core\Router::redirect('/');
    }    
    
    protected function login() {
            $oUser = new \app\Entities\User();           

            if (
                $oUser->loadByParameters(array(
                    'mail' => $this->_params['email'],
                    'pass' => hash('SHA256', $this->_params['password'])
                )) === true
            ) {
				$oUser->pass = null;
                foreach ($oUser as $key=>$mValue) {
                    $_SESSION[$key] = $mValue;
                }
                return true;

            }

            return false;
    }
    
}

?>
