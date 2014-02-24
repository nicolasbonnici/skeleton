<?php

namespace modules\frontend\Controllers;

/**
 * Description of HomeController
 *
 * @author info
 */
class BlogController extends \Library\Core\Controller {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {

        $this->render('blog/index.tpl');
    }

}

?>
