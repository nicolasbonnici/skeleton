<?php

namespace modules\tag\Controllers;

/**
 * Description of Tag HomeController
 *
 * @author info
 */
class TagController extends \Library\Core\Auth {

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {
        $this->render('tag/index.tpl');
    }

    public function createAction()
    {
        $this->render('tag/create.tpl');
    }

    public function readAction()
    {
        if (isset($this->_params['idtag']) && intval($this->_params['idtag']) > 0) {
               $oTagModel = new \modules\backend\Models\Tag(intval($this->_params['idtag']), $this->oUser);
               $oTag = $oTagModel->read();
            if (! is_null($oTag) && $oTag->isLoaded()) {
                $this->_view['oTag'] = $oTag;
            }

        }
        $this->render('tag/read.tpl');
    }

    public function updateAction()
    {
        if (isset($this->_params['idtag']) && intval($this->_params['idtag']) > 0) {
               $oTagModel = new \modules\backend\Models\Tag(intval($this->_params['idtag']), $this->oUser);
            $oTag = $oTagModel->getEntity();
            if (! is_null($oTag) && $oTag->isLoaded()) {
                $this->_view['oTag'] = $oTag;
            }

        }
        $this->render('tag/update.tpl');
    }

    public function deleteAction()
    {
        if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
            $this->_view['pk'] = $this->_params['pk'];
        }
        $this->render('tag/delete.tpl');
    }

}

class TagControllerException extends \Exception {}
?>
