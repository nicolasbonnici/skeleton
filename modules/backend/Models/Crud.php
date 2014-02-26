<?php

namespace modules\backend\Models;

use modules\backend\Controllers\CrudControllerException;
class Crud {

	/**
	 * @var string
	 */
	const ENTITIES_NAMESPACE = '\app\Entities\\';

	/**
	 * @var integer
	 */
	const ERROR_ENTITY_EXISTS	  			= 400;
	const ERROR_ENTITY_NOT_LOADED 			= 404;
	const ERROR_USER_INVALID			 	= 401;
	const ERROR_ENTITY_NOT_OWNED_BY_USER 	= 403;
	const ERROR_ENTITY_NOT_MAPPED_TO_USERS 	= 405;

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
	public function __construct($sEntityClassName, $iPrimaryKey, $mUser = null)
	{
		assert('is_null($mUser) || $mUser instanceof \app\Entities\User && $mUser->isLoaded() || is_int($mUser) && intval($mUser) > 0');
		assert('!empty($sEntityClassName) || !class_exists(self::ENTITIES_NAMESPACE . $sEntityClassName)');
		assert('intval($iPrimaryKey) > 0');

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

			$sEntityClassName = self::ENTITIES_NAMESPACE . $sEntityClassName;
			$this->oEntity = new $sEntityClassName($iPrimaryKey);

			if (isset($this->oEntity->user_iduser) && $this->oUser->getId() != $this->oEntity->user_iduser) {
				throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
			}
		}

	}

	public function create() {} // @todo check session integrité

	/*
	 * Create new entity
	*
	* @param array $aParameters		A one dimensional array: attribute name => value
	* @throws CrudModelException		If the currently loaded user session is different than the ne entity one
	* @return boolean
	*/
	public function createByUser(array $aParameters = array())
	{
		assert('$this->oUser->isLoaded()');
		assert('$aParameters->count() > 0');

		if ($this->oUser->isLoaded()) {
			try {
				$oEntity = clone $this->oEntity;

				if (isset($aParameter['user_iduser']) && $aParameter['user_iduser'] != $this->oUser->getId()) {
					throw new CrudModelException('Invalid user', self::ERROR_USER_INVALID);
				}

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
		return false;
	}

	public function read() {}
	public function update() {} // @todo check for no user_iduser attr
	public function updateByUser() {}
	public function delete() {} // @todo check for no user_iduser attr
	public function deleteByUser() {}

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
     * Get user's entities
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