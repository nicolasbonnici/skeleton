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
		$this->oTodos = new \app\Entities\Collection\TodoCollection();
	}

    /**
     * Load todos from a given user
     *
     * @return bool
     */
    public function loadByUserId() {

    	assert('$this->oUser->isLoaded()');
        $this->oTodos->loadByParameters(
        	array(
            	'user_iduser' => $this->oUser->getId()
			),
            array(
            	'lastupdate' => 'DESC'
            ),
            array(0,10)
        );
        return ($this->oTodos->count() > 0);
    }

    /**
     *
     * @return \app\Entities\Collection\TodoCollection
     */
    public function getTodos() {

    	assert('$this->oUser->isLoaded()');
    	return $this->oTodos;
    }
}

class TodoModelException extends \Exception {}