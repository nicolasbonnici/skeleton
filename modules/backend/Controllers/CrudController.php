<?php
namespace modules\backend\Controllers;

use Library\Core\CoreControllerException;
use Library\Core\CoreEntityException;

/**
 * Crud actions controller
 *
 *	@todo Tout est réunis dans le Core pour faire un scaffold des forms
 *
 * Check user's permissions with the \Libray\Core\ACL component layer first then the CRUD model methods and finaly
 * the \Libray\Core\Entity component to ensure data integrity on write access and perform the database request
 *
 * @author niko
 *
 */
class CrudController extends \Library\Core\Auth {

	/**
	 * One dimensional array to restrict the CrudController entities scope (Before even check the ACL)
	 * @var array
	 */
	private $aEntitiesScope = array();

	public function __preDispatch()
	{
		if (
			count($this->aEntitiesScope) > 0
			&& !in_array($this->_params['entity'], $this->aEntitiesScope)
		) {
			throw new CrudControllerException('Entity restricted in CrudController scope', \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL);
		}

		if ($this->oUser->getId() !== intval($this->_session['iduser'])) {
			throw new CrudControllerException('User session is invalid', \modules\backend\Models\Crud::ERROR_USER_INVALID);
		}

		// @todo en fonction des ACL et de l'action demandé lever ou non des exceptions avant même d'appeler la methode demandé

	}

	public function __postDispatch() {}

	public function indexAction()
	{
		$oCrudModel = new \modules\backend\Models\Crud('Todo', 5, $this->oUser);
	}

	/**
	 * Create an \app\Entities entity object then pass it to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function createAction($sViewTpl = 'crud/read.tpl')
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

					if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
						$sViewTpl = $this->_params['view'];
					}


					if (($this->_view['bCreateNewEntity'] = $oCrudModel->create($aParameters)) === true) {
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->_view['bUpdateEntity'] = false; // clean exception
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->_view['bCreateNewEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}

			} else {
				$this->_view['bCreateNewEntity'] = false;
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/create.tpl', $this->_view['iStatus']);
	}

	/**
	 * Read an entity then pass it to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function readAction($sViewTpl = 'crud/read.tpl') {
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

					if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
						$sViewTpl = $this->_params['view'];
					}

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
	 * Update an \app\Entities entity object then pass them to a given view
	 */
	public function updateAction($sViewTpl = 'crud/read.tpl')
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
			// Only check ACL, no need to check data integrity \Core\Entity check it before updating object
			if (
				$this->hasUpdateAccess(strtolower($sEntityName))
			) {
				// Explode ou json_decode les parametres serialisés en un post parametre
				$aParameters = array();

				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));

					if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
						$sViewTpl = $this->_params['view'];
					}

					if (($this->_view['bUpdateEntity'] = $oCrudModel->update($aParameters)) === true) {
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->_view['bUpdateEntity'] = false; // clean exception
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->_view['bUpdateEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->_view['bUpdateEntity'] = false;
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

					if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
						$sViewTpl = $this->_params['view'];
					}

					if ($oCrudModel->delete($aParameters, 0, $this->oUser)) {
						$this->_view['bDeleteEntity'] = true;
						$this->_view['iStatus'] = self::XHR_STATUS_OK;
						$this->_view['oEntity'] = $oCrudModel->getEntity();
					} else {
						$this->_view['bDeleteEntity'] = false; // delete exception
					}
				} catch (\modules\backend\Models\CrudModelException $oException) {
					$this->_view['bDeleteEntity'] = false;
					$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
					$this->_view['error_message'] = $oException->getMessage();
					$this->_view['error_code'] = $oException->getCode();
				}
			} else {
				$this->_view['bDeleteEntity'] = false;
				$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
				$this->_view['error_message'] = 'Unauthorized by the ACL layer';
				$this->_view['error_code'] = \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL;
			}
		}
		$this->render('crud/delete.tpl', $this->_view['iStatus']);
	}

	/**
	 * List \app\Entities entity then pass them to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function listAction($sViewTpl = 'crud/list.tpl')
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
			$this->hasReadAccess(strtolower($sEntityName))
			) {
				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));

					if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
						$sViewTpl = $this->_params['view'];
					}

					$this->_view['iStatus'] = self::XHR_STATUS_OK;
					$this->_view['oEntity'] = $oCrudModel->loadEntities(); // @todo passer les params

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
		$this->render($sViewTpl, $this->_view['iStatus']);
	}

	/**
	 * Load latest entities restricted to the curently instantiate \app\Entities\User session scope
	 *
	 * @param string $sViewTpl
	 */
	public function listByUserAction($sViewTpl = 'crud/list.tpl')
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
			$this->hasReadAccess(strtolower($sEntityName))
			) {
				try {
					$oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityClassName, 0, $this->oUser));

					$this->_view['iStatus'] = self::XHR_STATUS_OK;
					$this->_view['oEntity'] = $oCrudModel->loadUserEntities(); // @todo passer les params

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
		$this->render('crud/list.tpl', $this->_view['iStatus']);
	}
}

class CrudControllerException extends \Exception {}