<?php

namespace modules\backend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class TodoController extends \Library\Core\AuthController {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {
        $this->render('todo/index.tpl');
    }

    public function updateAction() {

        if (isset($this->_params['id']) && ! empty($this->_params['id'])) {

            // load then update item and send bool to the view
            try{
            	$oTodo = new \app\Entities\Todo(array('idtodo' => $this->_params['id'], 'users_iduser' => $this->_session['iduser']));
            } catch (\Library\Core\EntityException $oException) {

                throw new \Exception($oException->getMessage());
                $oTodo = null;
            }

            if (isset($this->_params['label'], $this->_params['content'])) {

                $oTodo->label = $this->_params['label'];
                $oTodo->content = $this->_params['content'];
                $oTodo->lastupdate = time();

                // Return bool to the view
                $this->_view['update'] = $oTodo->update();

            }

            $this->_view['oTodo'] = $oTodo;

        }

        $this->render('todo/update.tpl');

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
