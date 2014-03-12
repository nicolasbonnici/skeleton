<?php

namespace modules\frontend\Controllers;

/**
 * ErrorController
 *
 * Manage HTTP error redirection
 *
 * @author niko <nicolasbonnici@gmail.com>
 */
class ErrorController extends \Library\Core\Controller {

    /**
     * @todo defined class constant pour http error codes
     */

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {
        $this->render('error/index.tpl');
    }

    /**
     * Forbidden error 403
     */
    public function e403Action()
    {

        if (!empty($this->_params['redirect'])) {
            $this->_view['sRedirectUrl'] = urldecode($this->_params['redirect']);
        }

        $this->render('error/e403.tpl');
    }

    // @todo 404,301,302 errors methods....

}

?>
