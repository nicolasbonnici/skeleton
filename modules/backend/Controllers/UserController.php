<?php
namespace modules\backend\Controllers;


class UserController extends \Library\Core\Auth {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {

    }

    public function profileAction() {

        if (
            isset(
                $this->_params['password'],
                $this->_params['passwordNew1'],
                $this->_params['passwordNew2']
            ) &&
            !empty($this->_params['password']) &&
            !empty($this->_params['passwordNew1']) &&
            !empty($this->_params['passwordNew2']) &&
            $this->_params['passwordNew1'] === $this->_params['passwordNew2']
        ) {
            $oUser = new \app\Entities\User();
            try {
                $oUser->loadByParameters(
                    array(
                        'iduser'     => $this->_session['iduser'],
                        'mail'         => $this->_session['mail'],
                        'pass'         => $this->formatPassword($this->_params['password']),
                        'created'     => $this->_session['created']
                    )
                );
            } catch(CoreAuthControllerException $oException) {}
            if ($oUser->isLoaded()) {

                $oUser->pass = $this->formatPassword($this->_params['passwordNew1']);
                $this->_view['passwordUpdate'] = $oUser->update();
            } else {
                $this->_view['passwordUpdate'] = FALSE;
            }
        }


        $this->render('user/profile.tpl');
    }

    private function formatPassword($sPassword) {
        return hash('sha256', $sPassword);
    }
}
