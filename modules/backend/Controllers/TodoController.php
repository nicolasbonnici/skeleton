<?php

namespace modules\backend\Controllers;

/**
 * Description of TodoController
 *
 * @author info
 */
class TodoController extends \Library\Core\Auth {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction()
    {
        $this->render('todo/index.tpl');
    }

    public function createAction()
    {
    	if (
    		isset(
    			$this->_params['label'],
    			$this->_params['content']
    		)
    	) {

    		if (
    				\Library\Core\Validator::string($this->_params['label'], 3, 96) === \Library\Core\Validator::STATUS_OK &&
    				\Library\Core\Validator::string($this->_params['content'], 3) === \Library\Core\Validator::STATUS_OK
    				//! empty($this->_params['deadline'])
    		) {
				$oTodoModel = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
				$this->view['bCreateNewTodo'] = $oTodoModel->createByUser($this->_params['label'], $this->_params['content']);
				$this->_view['label'] = $this->_params['label'];
				$this->_view['content'] = $this->_params['content'];
    		}
    	}
    	$this->render('todo/create.tpl');
    }

    public function readAction()
    {
        if ($this->_params['idtodo'] && intval($this->_params['idtodo']) > 0) {
       		$oTodoModel = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
       		$oTodo = $oTodoModel->loadByTodoId((int)$this->_params['idtodo']);
    		if (! is_null($oTodo) && $oTodo->isLoaded()) {
    			$this->_view['oTodo'] = $oTodo;
    		}

    	}
		$this->render('todo/read.tpl');
    }

    public function updateAction()
    {
    	if ($this->_params['idtodo'] && intval($this->_params['idtodo']) > 0) {
       		$oTodoModel = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
       		$oTodo = $oTodoModel->loadByTodoId((int)$this->_params['idtodo']);
    		if (! is_null($oTodo) && $oTodo->isLoaded()) {
    			$this->_view['oTodo'] = $oTodo;
    		}

    	}
    	$this->render('todo/update.tpl');
    }

    public function deleteAction()
    {
    	if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
    		$this->_view['pk'] = $this->_params['pk'];
    	}
    	$this->render('todo/delete.tpl');
    }

}

class TodoControllerException extends \Exception {}
?>
