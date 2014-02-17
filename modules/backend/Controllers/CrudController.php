<?php
namespace modules\backend\Controllers;

use Library\Core\CoreControllerException;
use Library\Core\CoreEntityException;

class CrudController extends \Library\Core\Auth {

	public function __preDispatch() {
		if (
			! isset(
				$this->_session['iduser'],
				$this->_session['token'],
				$this->_session['mail']
			)
		) {
			throw new CrudControllerException('No valid session found. ', CrudControllerException::ERROR_UNAUTHORIZED_ACCESS);
		}
	}

	public function __postDispatch() {

	}

	public function indexAction() {

	}

	/**
	 * Update an entity

	 */
	public function updateAction()
	{
		$this->_view['iStatus'] = self::XHR_STATUS_OK;
		if (
				isset(
						$this->_params['pk'],
						$this->_params['name'],
						$this->_params['value'],
						$this->_params['entity']
				)
		) {
			// load then update entity and send bool to the view
			$sEntity = '\app\Entities\\' . $this->_params['entity'];
			if(class_exists($sEntity))  {
				try{
					$oUser = new \App\Entities\User($this->_session['iduser']);
				} catch (\Library\Core\EntityException $oException) {
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				}
				try{
					$oEntity = new $sEntity($this->_params['pk']);
					// @todo virer ca et faire un singleton de gestion de session avec un appel directement dans les acl
					// If we got a foreign key related to a BO user entity
					if (isset($oEntity->user_userid)) {
						if ($this->_session['iduser']!= $oEntity->user_userid) {
							die('foreign key exception');
						}
					}
				} catch (\Library\Core\EntityException $oException) {
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				}
				if (isset($oEntity, $oEntity->{$this->_params['name']}))  {
					// Only check ACL, no need to check data type integrity \Core\Entity check it before updating object
					if (
							$this->hasUpdateAccess($this->_params['entity'])
					) {
						$oEntity->{$this->_params['name']} = $this->_params['value'];

						if (isset($oEntity->lastupdate)) {
							$oEntity->lastupdate = time();
						}

						// Return flag to the view
						$this->_view['oEntity'] = $oEntity;
						if ($oEntity->update()) {
							$this->_view['bUpdateFlag'] = true;
						}
					} else {
						$this->_view['iStatus'] = self::XHR_STATUS_ACCESS_DENIED;
						$this->_view['update'] = false;
					}
				}
			}
		}

		$this->render('crud/update.tpl', $this->_view['iStatus']);
	}
}

class CrudControllerException extends \Exception {
	const ERROR_UNAUTHORIZED_ACCESS = 403;
	const ERROR_ENTITY_NOT_LOADABLE = 404;
}