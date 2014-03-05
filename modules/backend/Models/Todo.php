<?php

namespace modules\backend\Models;

class Todo extends \Library\Core\Crud {


	/**
	 * Instance constructor overide
	 */
	public function __construct($iPrimaryKey = null, \app\Entities\User $oUser)
	{
		assert('is_null($iPrimaryKey) || intval($iPrimaryKey) > 0');
		if (! $oUser->isLoaded()) {
			throw new CrudModelException('Crud need a valid user instance, no user provided.');
		} elseif (!$oUser instanceof \app\Entities\User || !$oUser->isLoaded()) {
			throw new CrudModelException('Crud need a valid user instance, no user provided.');
		} else {
			parent::__construct('Todo', $iPrimaryKey, $oUser);
		}
	}

    /**
     * Get a todo by his primary key (restricted to current user)
     *
     * @param integer $iTodoId
     */
    public function loadById($iTodoId)
    {
    	assert("$this->oEntity === 'Todo'");
    	try {
    		$this->oEntity = new \app\Entities\Todo();
    		$this->oEntity->loadByParameters(
    				array(
    						'idtodo' 		=> $iTodoId,
    						'user_iduser' 	=> $this->oUser->getId()
    				)
    		);
    		if ($this->oEntity->isLoaded()) {
    			return $this->oEntity;
    		}
    	} catch (\Library\Core\EntityException $oException) {}
		return null;
    }

}

class TodoModelException extends \Exception {}