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
		$oEntity = $this->oCrudModel->getEntity();
		$oEntity->getAttributeType('idtodo');
		$aAttrs = $oEntity->getAttributes();
		$bHasAttr = $oEntity->hasAttribute('idtodo');
		die(var_dump($aAttrs, $bHasAttr));

        $this->render('test/index.tpl');
    }
}

?>
