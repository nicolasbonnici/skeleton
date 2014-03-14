<?php

namespace modules\frontend\Controllers;

/**
 * LoginController
 *
 * @author info
 */
class AuthController extends \Library\Core\Controller {

    public function __preDispatch() {}

    public function __postDispatch() {}

    /**
     * Login form
     */
    public function indexAction()
    {
        // @todo rediriger si session existante
        if (isset($this->_params['email']) && isset($this->_params['password'])) {

            if($this->login()) {
                $sRedirectUrl = '/backend/';
                if (isset($this->_params['redirect']) && !empty($this->_params['redirect'])) {
                    $sRedirectUrl = str_replace('*', '/', urldecode($this->_params['redirect']));
                }
                \Library\Core\Router::redirect($sRedirectUrl);
            }// @todo gestion erreur de login

        }

        $this->render('auth/index.tpl');
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        // @todo Cookie::delete() et setter une constante pour le nom de session via la config
        $oCookie = new \Library\Core\Cookie();
        //die(var_dump($oCookie->getCookieVar('PHPSESSID')));

        session_destroy();
        \Library\Core\Router::redirect('/');
    }

    /**
     * Open a user session
     * @return boolean
     */
    protected function login()
    {
        $oUser = new \app\Entities\User();
        try {
            $oUser->loadByParameters(array(
                'mail' => $this->_params['email'],
                'pass' => hash('SHA256', $this->_params['password'])
            ));
            $oUser->pass = null;
            foreach ($oUser as $key=>$mValue) {
                $_SESSION[$key] = $mValue;
            }
            return true;
        } catch(\Library\Core\EntityException $oException) {
            return false;
        }
    }

}

?>
