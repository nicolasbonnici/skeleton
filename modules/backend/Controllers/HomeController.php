<?php

namespace modules\backend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class HomeController extends \Library\Core\Auth {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {

//        $oBlocks = new \app\Entities\Block(array(1,2));

        //$oUser = new \app\Entities\User(1);
        //die(var_dump($oDb));
        $this->_view['foo'] = 'bar';

        $this->render('home/index.tpl');
    }

}

?>
