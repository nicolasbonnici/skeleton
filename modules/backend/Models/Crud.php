<?php

namespace modules\backend\Models;

use modules\backend\Controllers\CrudControllerException;
class Crud {

	/**
	 * @var string
	 */
	const ENTITIES_NAMESPACE = '\app\Entities\\';

	/**
	 * Exceptions error code
	 * @var integer
	 */
	const ERROR_ENTITY_EXISTS	  			= 400;
	const ERROR_ENTITY_NOT_LOADED			= 402;
	const ERROR_ENTITY_NOT_LOADABLE			= 404;
	const ERROR_USER_INVALID				= 401;
	const ERROR_ENTITY_NOT_OWNED_BY_USER 	= 403;
	const ERROR_ENTITY_NOT_MAPPED_TO_USERS 	= 405;
	const ERROR_FORBIDDEN_BY_ACL		 	= 406;

	/**
	 * Current user instance (optional if $oEntity has no foreign key attribute to \app\Entities\User)
	 *
	 * @var \app\Entities\User
	 */
	private $oUser;

	/**
	 * Current \app\Entities\
	 *
	 * @var \app\Entities\
	 */
	private $oEntity;

	/**
	 * Entities collection
	 *
	 * @var \app\Entities\Collection\
	 */
	private $oEntities;

	/**
	 * Instance constructor
	 */
	public function __construct($sEntityClassName, $iPrimaryKey = 0, $mUser = null)
	{
		assert('is_null($mUser) || $mUser instanceof \app\Entities\User && $mUser->isLoaded() || is_int($mUser) && intval($mUser) > 0');
		assert('!empty($sEntityClassName) || !class_exists(self::ENTITIES_NAMESPACE . $sEntityClassName)');
		assert('intval($iPrimaryKey) === 0 || intval($iPrimaryKey) > 0');

		if (empty($sEntityClassName) || !class_exists(self::ENTITIES_NAMESPACE . $sEntityClassName)) {
			throw new CrudModelException("Entity requested not found, you need to create manually or scaffold his \app\Entities class.", self::ERROR_ENTITY_EXISTS);
		} else {
			try {
				// Instanciate \app\Entities\User provided at instance constructor
				if ($mUser instanceof \app\Entities\User && $mUser->isLoaded()) {
					$this->oUser = $mUser;
				} elseif (is_int($mUser) && intval($mUser) > 0) {
					try {
						$this->oUser = new \app\Entities\User($mUser);
					} catch (\Library\Core\EntityException $oException) {
						$this->oUser = null;
					}
				} else {
					$this->oUser = null;
				}
			} catch (\Library\Core\EntityException $oException) {
				throw new CrudModelException('Invalid user instance provided', self::ERROR_USER_INVALID);
 			}

 			try {
				$sEntityClassName = self::ENTITIES_NAMESPACE . $sEntityClassName;
				$this->oEntity = new $sEntityClassName(((intval($iPrimaryKey) > 0) ? $iPrimaryKey : null));
			} catch (\Library\Core\EntityException $oException) {
				throw new CrudModelException('Invalid user instance provided', self::ERROR_ENTITY_NOT_LOADABLE);
			}

		}

	}

	/**
	 * Create new entity
	 *
	 * @param array $aParameters			A one dimensional array: attribute name => value
	 * @throws CrudModelException		If the currently loaded user session is different than the ne entity one
	 * @return boolean|Library\Core\EntityException
	 */
	public function create(array $aParameters = array())
	{
		assert('$aParameters->count() > 0');
		assert('!is_null($this->oEntity)');

		// Check for user bypass attempt
		if (
			!is_null($this->oUser) ||
			isset($aParameter['user_iduser']) && $this->oUser->getId() !== intval($aParameter['user_iduser'])
		) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} else {
			$oEntity = clone $this->oEntity;

			foreach ($aParameters as $aParameter) {
				if (($sParameterName = $aParameter[0]) && isset($this->oEntity->{$aParameter[0]}) && !empty($aParameter[1])) {
					$oEntity->{$sParameterName} = $aParameter[1];
				}
			}

			if (
				isset($this->oEntity->created) &&
				\Library\Core\Validator::integer($this->oEntity->created) !== \Library\Core\Validator::STATUS_OK
			) {
				$this->oEntity->created = time();
			}
			try {
				return $oEntity->add();
			} catch (\Library\Core\EntityException $oException) {
				return $oException;
			}
		}

	}


	/**
	 * Read an entity restricted to user scope
	 *
	 * @throws CrudModelException
	 * @return mixed boolean|\Library\Core\EntityException TRUE is entity is correctly deleted otherwhise the \Library\Core\EntityException
	 */
	public function read() {
		if (!is_null($this->oUser)) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} else {
			// Check for user bypass attempt
			if (isset($this->oEntity->user_iduser) && $this->oUser->getId() !== intval($this->oEntity->user_iduser)) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			} elseif (!$this->oEntity->isLoaded()) {
				throw new CrudModelException('Cannot read an unloaded entity.', self::ERROR_ENTITY_NOT_LOADED);
			} else {
				try {
					return $this->getEntity();
				} catch (\Library\Core\EntityException $oException) {
					return $oException;
				}
			}
		}
	}

	/**
	 * Update an entity restricted to instanciate user scope if entity is mapped with \app\Entities\User
	 *
	 * @param array $aParameters
	 * @throws CrudModelException
	 * @return \Library\Core\EntityException
	 */
	public function update(array $aParameters = array()) {
		assert('$aParameters->count() > 0');

		if (!is_null($this->oUser)) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} elseif (!$this->oEntity->isLoaded()) {
			throw new CrudModelException('Cannot update an unloaded enitity.', self::ERROR_ENTITY_NOT_LOADED);
		} else {

			// Check for user bypass attempt
			if (isset($aParameter['user_iduser']) && $this->oUser->getId() !== intval($aParameter['user_iduser'])) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			}

			foreach ($aParameters as $aParameter) {
				if (($sParameterName = $aParameter[0]) && isset($this->oEntity->{$aParameter[0]}) && !empty($aParameter[1])) {
					$this->oEntity->{$sParameterName} = $aParameter[1];
				}
			}

			if (isset($this->oEntity->lastupdate)) {
				$this->oEntity->lastupdate = time();
			}
			try {
				return $this->oEntity->update();
			} catch (\Library\Core\EntityException $oException) {
				return $oException;
			}
		}
	}

	/**
	 * Delete an entity restricted to user scope
	 *
	 * @throws CrudModelException
	 * @return mixed boolean|\Library\Core\EntityException TRUE is entity is correctly deleted otherwhise the \Library\Core\EntityException
	 */
	public function delete() {

		// Check for user bypass attempt
		if (
			isset($this->oEntity->user_iduser) &&
			(!is_null($this->oUser)) ||
			($this->oUser->getId() !== intval($this->oEntity->user_iduser))
		) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} elseif (!$this->oEntity->isLoaded()) {
			throw new CrudModelException('Cannot delete an unloaded entity.', self::ERROR_ENTITY_NOT_LOADED);
		} else {
			try {
				return $this->oEntity->delete();
			} catch (\Library\Core\EntityException $oException) {
				return $oException;
			}
		}
	}

    /**
     * Load latest entities
     *
	 * @param array $aParameters
	 * @param array $aOrderBy
	 * @param array $aLimit
	 * @return boolean
	 */
    public function loadEntities(array $aParameters = array(), array $aOrderBy = array(), array $aLimit = array(0, 10))
    {
        $this->oEntities->loadByParameters($aParameters, $aOrderBy, $aLimit);
        return ($this->oEntities->count() > 0);
    }

    /**
     * Load user's entities
     *
     * @param array $aParameters
     * @param array $aOrderBy
     * @param array $aLimit
     * @throws CrudModelException
     * @return boolean|\Library\Core\EntityException
     */
    public function loadUserEntities(array $aParameters = array(), array $aOrderBy = array(), array $aLimit = array(0, 10))
    {
    	if (!isset($this->oEntity->user_iduser)) {
    		throw new CrudModelException('No foreign key to \app\Entities\User entity found!', self::ERROR_ENTITY_NOT_MAPPED_TO_USERS);
    	}

    	if (!isset($aParameters['user_iduser'])) {
    		$aParameters['user_iduser'] = $this->oUser->getId();
    	} elseif (intval($aParameters['user_iduser']) !== $this->oUser->getId()) {
    		throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
    	}

    	try {
    		return $this->loadEntities($aParameters, $aOrderBy, $aLimit);
    	} catch (\Library\Core\EntityException $oException) {
			return $oException;
    	}
    }

    /**
     * @return \app\Entities\Collection\
     */
    public function getEntities()
    {
    	return $this->oEntities;
    }

    /**
     * @return \app\Entities\
     */
    public function getEntity()
    {
    	return $this->oEntity;
    }
}

class CrudModelException extends \Exception {}