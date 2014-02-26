<?php

namespace modules\backend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
use app\Entities\PostCollection;

class PostController extends \Library\Core\Auth {

	public function __preDispatch() {

	}

	public function __postDispatch() {

	}

	public function indexAction()
	{
		$this->render('post/index.tpl');
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
		$this->render('post/create.tpl');
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
		$this->render('post/read.tpl');
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
		$this->render('post/update.tpl');
	}

	public function deleteAction()
	{
		if (
			isset(
				$this->_params['idtodo'],
				$this->_params['bconfirm']
			) &&
			intval($this->_params['idtodo']) > 0 &&
			intval($this->_params['bconfirm']) === 1
		) {
			$oTodoModel = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
			$oTodo = $oTodoModel->loadByTodoId((int)$this->_params['idtodo']);
			if (! is_null($oTodo) && $oTodo->isLoaded()) {
				$this->_view['bTodoDelete'] = $oTodo->delete();
			}

		}
		$this->render('post/delete.tpl');
	}

	public function listAction()
	{
		$oTodos = new \modules\backend\Models\Todo(new \app\Entities\User($this->_session['iduser']));
		if ($oTodos->loadByUserId()) {
			$this->_view['oTodos']  = $oTodos->getTodos();
		}

		$this->render('post/list.tpl');
	}
}

?>
