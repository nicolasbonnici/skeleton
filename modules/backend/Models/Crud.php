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
	 * Current user instance
	 *
	 * @var \app\Entities\User
	 */
	private $oUser;

	/**
	 * Entity
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
				// @todo move onto a UserSession singleton class
				// Instanciate \app\Entities\User provided at instance constructor
				if ($mUser instanceof \app\Entities\User && $mUser->isLoaded()) {
					$this->oUser = $mUser;
				} elseif (is_int($mUser) && intval($mUser) > 0) {
					try {
						$this->oUser = new \app\Entities\User($mUser);
					} catch (\Library\Core\EntitiesException $oException) {
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
	 * Create an entity
	 *
	 * @param array $aParameters
	 * @throws CrudModelException
	 * @return boolean
	 */
	public function create(array $aParameters = array()) {
		assert('$aParameters->count() > 0');
		assert('!is_null($this->oEntity)');

		try {
			$oEntity = clone $this->oEntity;

			foreach ($aParameters as $aParameter) {
				if (($sParameterName = $aParameter[0]) && isset($this->oEntity->{$aParameter[0]}) && !empty($aParameter[1])) {
					$oEntity->{$sParameterName} = $aParameter[1];
				}
			}

			return $oEntity->add();
		} catch (\Library\Core\EntityException $oException) {
			return false;
		}
	}

	/*
	 * Create new entity
	*
	* @param array $aParameters			A one dimensional array: attribute name => value
	* @throws CrudModelException		If the currently loaded user session is different than the ne entity one
	* @return boolean
	*/
	public function createByUser(array $aParameters = array())
	{
		assert('$aParameters->count() > 0');
		assert('!is_null($this->oEntity)');

		if (!$this->oUser->isLoaded()) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} else {

			// Check for user bypass attempt
			if (isset($aParameter['user_iduser']) && $this->oUser->getId() !== intval($aParameter['user_iduser'])) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			}

			return $this->create($aParameters);
		}
	}

	/**
	 * Update an entity
	 *
	 * @param array $aParameters
	 * @throws CrudModelException
	 * @return boolean
	 */
	public function update(array $aParameters = array())
	{
		assert('$aParameters->count() > 0');

		if (!$this->oEntities->isLoaded()) {
			throw new CrudModelException('Cannot update an unloaded entity.', self::ERROR_ENTITY_NOT_LOADED);
		} else {

			foreach ($aParameters as $aParameter) {
				if (($sParameterName = $aParameter[0]) && isset($this->oEntity->{$aParameter[0]}) && !empty($aParameter[1])) {
					$this->oEntity->{$sParameterName} = $aParameter[1];
				}
			}

			if (isset($this->oEntity->lastupdate)) {
				$this->oEntity->lastupdate = time();
			}

			return $this->oEntity->update();
		}
	}

	/**
	 * Update an entity restricted to instanciate user scope
	 *
	 * @throws CrudModelException
	 * @return boolean
	 */
	public function updateByUser() {
		assert('$aParameters->count() > 0');
		assert('!is_null($this->oEntity)');

		if (!$this->oUser->isLoaded()) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} else {

			// Check for user bypass attempt
			if (isset($aParameter['user_iduser']) && $this->oUser->getId() !== intval($aParameter['user_iduser'])) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			}

			return $this->update($aParameters);
		}
	}

	/**
	 * Delete an entity
	 *
	 * @throws CrudModelException
	 * @return boolean
	 */
	public function delete()
	{
		if (!$this->oEntity->isLoaded()) {
			throw new CrudModelException('Cannot delete an unloaded entity.', self::ERROR_ENTITY_NOT_LOADED);
		} else {
			return $this->oEntity->delete();
		}
	}

	/**
	 * Delete an entity restricted to user scope
	 *
	 * @throws CrudModelException
	 * @return boolean
	 */
	public function deleteByUser() {
		if (!$this->oUser->isLoaded()) {
			throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
		} else {
			// Check for user bypass attempt
			if (isset($this->oUser->user_iduser) && $this->oUser->getId() !== intval($this->oUser->user_iduser)) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			}

			return $this->delete();
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
    public function loadLatest(array $aParameters = array(), array $aOrderBy = array(), array $aLimit = array(0, 10))
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
     * @return \app\Entities\Collection\|NULL
     */
    public function loadUserEntities(array $aParameters = array(), array $aOrderBy = array(), array $aLimit = array(0, 10))
    {
    	if (!isset($this->oEntity->user_iduser)) {
    		throw new CrudModelException('No foreign key to User entity found!', self::ERROR_ENTITY_NOT_MAPPED_TO_USERS);
    	}

    	try {
    		$this->oEntities->loadByParameters($aParameters, $aOrderBy, $aLimit);
    		if ($this->oEntities->count() > 0) {
    			return $this->oEntities;
    		}
    	} catch (\Library\Core\EntityException $oException) {
			return null;
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
     *
     * @return \app\Entities\
     */
    public function getEntity()
    {
    	return $this->oEntity;
    }
}

class CrudModelException extends \Exception {}