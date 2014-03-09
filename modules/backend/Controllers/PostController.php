<?php

namespace modules\backend\Controllers;

/**
 * Description of PostController
 *
 * @author info
 */
use app\Entities\PostCollection;

class PostController extends \Library\Core\Auth {

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {
        $this->render('post/index.tpl');
    }

    public function createAction()
    {
        $this->render('post/create.tpl');
    }

    public function readAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Post(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->read();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('post/read.tpl');
    }

    public function updateAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Post(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->getEntity();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('post/update.tpl');
    }

    public function deleteAction()
    {
        if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
            $this->_view['pk'] = $this->_params['pk'];
        }
        $this->render('post/delete.tpl');
    }

}

?>
