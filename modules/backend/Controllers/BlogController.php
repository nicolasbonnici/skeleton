<?php

namespace modules\backend\Controllers;

/**
 * Backoffice blogging app
 *
 * @author niko
 */

class BlogController extends \Library\Core\Auth {

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {
        $this->render('blog/index.tpl');
    }

    public function dashboardAction()
    {
        $this->render('blog/dashboard.tpl');
    }

    public function createPostAction()
    {
        $this->render('blog/createPost.tpl');
    }

    public function readPostAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Blog(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->read();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('blog/readPost.tpl');
    }

    public function updatePostAction()
    {
        if (isset($this->_params['idpost']) && intval($this->_params['idpost']) > 0) {
            $oTodoModel = new \modules\backend\Models\Blog(intval($this->_params['idpost']), $this->oUser);
            $oTodo = $oTodoModel->getEntity();
            if (! is_null($oTodo) && $oTodo->isLoaded()) {
                $this->_view['oTodo'] = $oTodo;
            }

        }
        $this->render('blog/updatePost.tpl');
    }

    public function deletePostAction()
    {
        if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
            $this->_view['pk'] = $this->_params['pk'];
        }
        $this->render('blog/deletePost.tpl');
    }

    public function postsAction()
    {
        $this->render('blog/posts.tpl');
    }

}

?>
