<?php

namespace modules\backend\Models;

class Todo {
	
	/**
	 * Current user instance 
	 * 
	 * @var \app\Entities\User
	 */
	private $oUser;
	
	/**
	 * Todos collection
	 * 
	 * @var \app\Entities\Collection\TodoCollection
	 */
	private $oTodos;
	
	/**
	 * Instance constructor
	 */
	public function __construct(\app\Entities\User $oUser)
	{
		if (! $oUser->isLoaded()) {
			throw new TodoModelException('Todo need a valid user instance, not user provided.');
		}
		
		$this->oUser = $oUser;
	}
	
    /**
     * Load todos from a given user
     * 
     * @param int $iUserId
     * @return bool
     */
    public function loadByUserId() {
        
    	assert('$this->oUser->isLoaded()');
    	
        return $this->loadByParameters(
        	array(
            	'users_iduser' => $this->oUser->iduser
            ), 
            array(
            	'idtodo' => 'DESC'
            ),
            	array(0,10)
            );            
        return false;
    }
}

class TodoModelException extends \Exception {}