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
	    		// @todo migrer dans la couche model
				try {
					$oTodo = new \app\Entities\Todo();
					$oTodo->label 		= $this->_params['label'];
					$oTodo->content 	= $this->_params['content'];
					$oTodo->deadline 	= time();
					$oTodo->lastupdate 	= time();
					$oTodo->created 	= time();
					$oTodo->user_iduser	= $this->_session['iduser'];
					//$oTodo->deadline 	= $this->_params['deadline'];
					if ($oTodo->add()) {
						$this->view['bCreateNewTodo'] = true;
						$this->view['oTodo'] = $oTodo;
					}
				} catch (\Library\Core\EntityException $oException) {
					$this->view['bCreateNewTodo'] = false;
					$this->_view['label'] = $this->_params['label'];
					$this->_view['content'] = $this->_params['content'];
				}
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
    	if ($this->_params['idtodo'] && intval($this->_params['idtodo']) > 0) {
    		$oTodoModel = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
    		$oTodo = $oTodoModel->loadByTodoId((int)$this->_params['idtodo']);
    		if (! is_null($oTodo) && $oTodo->isLoaded()) {
    			$this->_view['bTodoDelete'] = $oTodo->delete();
    		}

    	}
    	$this->render('todo/delete.tpl');
    }

    public function listAction()
    {
    	$oTodos = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
    	if ($oTodos->loadByUserId()) {
    		$this->_view['oTodos']  = $oTodos->getTodos();
    	}

    	$this->render('todo/list.tpl');
    }
}

class TodoControllerException extends \Exception {}
?>
