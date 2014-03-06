<?php

namespace modules\backend\Controllers;

/**
 *
 * @author info
 */
class TestController extends \Library\Core\Auth {

    public function __preDispatch() {}

    public function __postDispatch() {}

    public function indexAction()
    {

        $this->oCrudModel = new \modules\backend\Models\Crud('Todo', 2, $this->oUser);
        $oEntity         = $this->oCrudModel->getEntity();
        $aAttrs         = $oEntity->getAttributes();
        $sAttributeType = $oEntity->getAttributeType('idtodo');
        $bHasAttr         = $oEntity->hasAttribute('idtodo');
        $bIsNullable     = $oEntity->isNullable('idtodo');
        die(var_dump($aAttrs, $bHasAttr, $sAttributeType, $bIsNullable));

        $this->render('test/index.tpl');
    }
}

?>
