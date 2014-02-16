<?php

namespace modules\frontend\Controllers;

/**
 * Perform research on entities
 *
 * @author Nicolas Bonnici
 */
class SearchController extends \Library\Core\Controller {

    public function __preDispatch() {

    }

    public function __postDispatch() {

    }

    public function indexAction() {
        $this->render('search/index.tpl');
    }
}

?>
