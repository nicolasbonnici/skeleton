<?php

namespace modules\backend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class TodoController extends \Library\Core\Auth {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {
        $this->render('todo/index.tpl');
    }

    public function listAction() {
        $oTodos = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
        if ($oTodos->loadByUserId()) {
	        $this->_view['oTodos']  = $oTodos->getTodos();
        }

        $this->render('todo/list.tpl');
    }

}

?>
