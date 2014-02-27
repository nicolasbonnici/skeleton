<?php
namespace modules\backend\Controllers;

use Library\Core\CoreControllerException;
use Library\Core\CoreEntityException;

/**
 * Crud actions controller
 *
 * Check user's permissions with the \Libray\Core\ACL component layer first then the CRUD model methods and finaly
 * the \Libray\Core\Entity component to ensure data integrity on write access and perform the database request
 *
 * @author niko
 *
 */
class CrudController extends \Library\Core\Auth {

	public function __preDispatch()
	{
		if ($this->oUser->getId() !== intval($this->_session['iduser'])) {
			throw new CrudControllerException('No valid user session found. ', \modules\backend\Models\Crud::ERROR_USER_INVALID);
		}

	}

	public function __postDispatch() {}

	public function indexAction()
	{
		$oCrudModel = new \modules\backend\Models\Crud('Todo', 5, $this->oUser);
	}

	/**
	 * Create an \app\Entities entity object
	 */
	public function createAction()
	{
		$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
		if (
			isset(
				$this->_params['entity'],
				$this->_params['parameters']
			) &&
			($sEntityName = $this->_params['entity']) &&
			strlen($sEntityName) > 0
		) {
			// Only check ACL, no need to check data type integrity \Core\Entity check it before updating object
			if (
				$this->hasCreateAccess(strtolower($sEntityName))
			) {
				// Explode ou json_decode les parametres serialisés en un post parametre
				$aParameters = array();

				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));
					if ($oCrudModel->createByUser($aParameters)) {
						$this->view['bCreateNewEntity'] = true;
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->view['bCreateNewEntity'] = false;
						$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->view['bCreateNewEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->view['bCreateNewEntity'] = false;
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/create.tpl', $this->_view['iStatus']);
	}

	/**
	 * Read an entity
	 */
	public function readAction() {
		$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
		if (
			isset(
				$this->_params['entity'],
				$this->_params['parameters']
			) &&
			($sEntityName = $this->_params['entity']) &&
			strlen($sEntityName) > 0
		) {
			// Only check ACL, no need to check data type integrity \Core\Entity check it before updating object
			if (
				$this->hasReadAccess(strtolower($sEntityName))
			) {
				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));

					$this->_view['iStatus'] = self::XHR_STATUS_OK;
					$this->_view['oEntity'] = $oCrudModel->read();

				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/read.tpl', $this->_view['iStatus']);
	}

	/**
	 * Update an \app\Entities entity object
	 */
	public function updateAction()
	{
		$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
		if (
			isset(
				$this->_params['entity'],
				$this->_params['parameters']
			) &&
			($sEntityName = $this->_params['entity']) &&
			strlen($sEntityName) > 0
		) {
			// Only check ACL, no need to check data type integrity \Core\Entity check it before updating object
			if (
				$this->hasUpdateAccess(strtolower($sEntityName))
			) {
				// Explode ou json_decode les parametres serialisés en un post parametre
				$aParameters = array();

				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));
					if ($oCrudModel->updateByUser($aParameters)) {
						$this->view['bUpdateEntity'] = true;
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->view['bUpdateEntity'] = false;
						$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->view['bUpdateEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->view['bUpdateEntity'] = false;
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/update.tpl', $this->_view['iStatus']);
	}


	/**
	 * Delete an \app\Entities entity object
	 */
	public function deleteAction()
	{
		$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
		if (
			isset(
				$this->_params['entity'],
				$this->_params['parameters']
			) &&
			($sEntityName = $this->_params['entity']) &&
			strlen($sEntityName) > 0
		) {
			// Only check ACL, no need to check data type integrity \Core\Entity check it before updating object
			if (
				$this->hasDeleteAccess(strtolower($sEntityName))
			) {
				// Explode ou json_decode les parametres serialisés en un post parametre
				$aParameters = array();

				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName));
					if ($oCrudModel->deleteByUser($aParameters, 0, $this->oUser)) {
						$this->view['bDeleteEntity'] = true;
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->view['bDeleteEntity'] = false;
						$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->view['bDeleteEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->view['bDeleteEntity'] = false;
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/delete.tpl', $this->_view['iStatus']);
	}


}

class CrudControllerException extends \Exception {}