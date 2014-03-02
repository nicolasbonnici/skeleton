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
	 * Crud model couch instance
	 * @var \modules\backend\Models\Crud
	 */
	private $oCrudModel;

	/**
	 * One dimensional array to restrict the CrudController entities scope (Before even check the ACL)
	 * @var array
	 */
	private $aEntitiesScope = array();

	public function __preDispatch()
	{
		$this->_view['iStatus'] = self::XHR_STATUS_ERROR;

		if (
			count($this->aEntitiesScope) > 0
			&& !in_array($this->_params['entity'], $this->aEntitiesScope)
		) {
			throw new CrudControllerException('Entity restricted in CrudController scope', \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL);
		}

		if ($this->oUser->getId() !== intval($this->_session['iduser'])) {
			throw new CrudControllerException('User session is invalid', \modules\backend\Models\Crud::ERROR_USER_INVALID);
		}

		// Check user permissions on entity then entity itself
		if (
			isset($this->_params['entity']) &&
			($sEntityName = $this->_params['entity']) &&
			strlen($sEntityName) > 0 &&
			($sAction = substr($this->_action, 0, (strlen($this->_action) - strlen('action')))) &&
			in_array($sAction, array('create', 'read', 'update', 'delete', 'list', 'listByUser')) &&
			($sCheckMethodName = 'has' . $sAction . 'Access') &&
			$this->{$sCheckMethodName}(strtolower($sEntityName))
		) {

			try {
				$iPrimaryKey = 0;
				if (isset($this->_params['pk']) && intval($this->_params['pk']) > 0) {
					$iPrimaryKey = intval($this->_params['pk']);
				}
				// Check Entity instance with Crud model constructor
				$this->oCrudModel = new \modules\backend\Models\Crud(ucfirst($sEntityName), $iPrimaryKey, $this->oUser);
			} catch (\modules\backend\Models\CrudModelException $oException) {
				throw new CrudControllerException('Invalid Entity requested!', \modules\backend\Models\Crud::ERROR_ENTITY_NOT_LOADABLE);
			}

		} else {
			throw new CrudControllerException('Error forbidden by ACL or invalid unauthorized action.', \modules\backend\Models\Crud::ERROR_FORBIDDEN_BY_ACL);
		}
	}

	public function __postDispatch() {}

	/**
	 * Create an \app\Entities entity object then pass it to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function createAction($sViewTpl = 'crud/read.tpl')
	{
		try {

			// json_decode les parametres serialisés en un post parametre
			$aParameters = array();

			// Explode ou json_decode les parametres serialisés en un post parametre
			$aParameters = json_decode($this->_params['parameters']);

			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}


			if (($this->_view['bCreateNewEntity'] = $this->oCrudModel->create($aParameters)) === true) {
				$this->_view['iStatus'] = self::XHR_STATUS_OK;
				$this->_view['oEntity'] = $this->oCrudModel->getEntity();
			} else {
				$this->_view['bUpdateEntity'] = false; // clean exception
			}

		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['bCreateNewEntity'] = false;
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
		}

		$this->render('crud/create.tpl', $this->_view['iStatus']);
	}

	/**
	 * Read an entity then pass it to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function readAction($sViewTpl = 'crud/read.tpl') {

		try {
			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}

			$this->_view['iStatus'] = self::XHR_STATUS_OK;
			$this->_view['oEntity'] = $this->oCrudModel->read();

		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
		}
		$this->render($sViewTpl, $this->_view['iStatus']);
	}

	/**
	 * Update an \app\Entities entity object then pass them to a given view
	 */
	public function updateAction($sViewTpl = 'crud/read.tpl')
	{
		try {
			// Explode ou json_decode les parametres serialisés en un post parametre
			$aParameters = json_decode($this->_params['parameters']);

			// Json decode la vue
			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}

			if (($this->_view['bUpdateEntity'] = $this->oCrudModel->update($aParameters)) === true) {
				$this->_view['iStatus'] = self::XHR_STATUS_OK;
				$this->_view['oEntity'] = $this->oCrudModel->getEntity();
			} else {
				$this->_view['bUpdateEntity'] = false; // clean exception
			}
		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['bUpdateEntity'] = false;
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
		}

		$this->render($sViewTpl, $this->_view['iStatus']);
	}


	/**
	 * Delete an \app\Entities entity object
	 */
	public function deleteAction()
	{
		try {
			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}

			if ($this->oCrudModel->delete($aParameters, 0, $this->oUser)) {
				$this->_view['bDeleteEntity'] = true;
				$this->_view['iStatus'] = self::XHR_STATUS_OK;
				$this->_view['oEntity'] = $this->oCrudModel->getEntity();
			} else {
				$this->_view['bDeleteEntity'] = false; // delete exception
			}
		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['bDeleteEntity'] = false;
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
		}

		$this->render($sViewTpl, $this->_view['iStatus']);
	}

	/**
	 * List \app\Entities entity then pass them to a given view
	 *
	 * @param string $sViewTpl
	 */
	public function listAction($sViewTpl = 'crud/list.tpl')
	{
		try {
			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}

			if ($this->oCrudModel->loadEntities()) {
				$this->_view['iStatus'] = self::XHR_STATUS_OK;
				$oEntities = $this->oCrudModel->getEntities(); // @todo Bug si passé directement au moteur de template Haanga
				$this->_view['oEntities'] = $oEntities;
			}

		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
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
		assert($this->oCrudModel instanceof \modules\backend\Models\Crud);
		try {

			if (isset($this->_params['view']) && strlen(isset($this->_params['view'])) > 0) {
				$sViewTpl = $this->_params['view'];
			}

			if ($this->oCrudModel->loadUserEntities()) {
				$this->_view['iStatus'] = self::XHR_STATUS_OK;
				$oEntities = $this->oCrudModel->getEntities();// @todo Bug si passé directement au moteur de template Haanga
				$this->_view['oEntities'] = $oEntities;
			}

		} catch (\modules\backend\Models\CrudModelException $oException) {
			$this->_view['iStatus'] = self::XHR_STATUS_ERROR;
			$this->_view['error_message'] = $oException->getMessage();
			$this->_view['error_code'] = $oException->getCode();
		}

		$this->render($sViewTpl, $this->_view['iStatus']);
	}
}

class CrudControllerException extends \Exception {}