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

    public function dashboardAction()
    {
        $this->render('post/dashboard.tpl');
    }

    public function createPostAction()
    {
        $this->render('post/createPost.tpl');
    }

    public function readPostAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Post(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->read();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('post/readPost.tpl');
    }

    public function updatePostAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Post(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->getEntity();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('post/updatePost.tpl');
    }

    public function deletePostAction()
    {
        if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
            $this->_view['pk'] = $this->_params['pk'];
        }
        $this->render('post/deletePost.tpl');
    }

    public function postsAction()
    {
        $this->render('post/posts.tpl');
    }

}

?>
