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
	 *
	 *
	 * @var \app\Entities\Todo
	 */
	private $oTodo;

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
    public function loadByUserId()
    {
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
     * Get a todo by his primary key (restricted to current user)
     *
     * @param integer $iTodoId
     */
    public function loadByTodoId($iTodoId)
    {
    	try {
    		$this->oTodo = new \app\Entities\Todo();
    		$this->oTodo->loadByParameters(
    				array(
    						'idtodo' 		=> $iTodoId,
    						'user_iduser' 	=> $this->oUser->getId()
    				)
    		);
    		if ($this->oTodo->isLoaded()) {
    			return $this->oTodo;
    		}
    	} catch (\Library\Core\EntityException $oException) {}
		return null;
    }

    /**
     *
     * @return \app\Entities\Collection\TodoCollection
     */
    public function getTodos()
    {
    	assert('$this->oUser->isLoaded()');
    	return $this->oTodos;
    }

    /**
     *
     * @return \app\Entities\Collection\TodoCollection
     */
    public function getTodo()
    {
    	assert('$this->oUser->isLoaded()');
    	return $this->oTodo;
    }
}

class TodoModelException extends \Exception {}