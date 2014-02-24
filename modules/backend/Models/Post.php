<?php

namespace modules\backend\Models;

class Posts {

	/**
	 * Posts collection
	 *
	 * @var \app\Entities\Collection\PostCollection
	 */
	private $oTodos;

	/**
	 * Instance constructor
	 */
	public function __construct($mUser = null)
	{
		assert('is_null($mUser) || $mUser instanceof \app\Entities\User && $mUser->isLoaded() || is_int($mUser) && intval($mUser) > 0');

		if ($mUser instanceof \app\Entities\User && $mUser->isLoaded()) {
			$this->oUser = $oUser;
		}
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

    public function createByUser($sLabel, $sContent)
    {
    	try {
    		$oTodo = new \app\Entities\Todo();
    		$oTodo->label 		= $sLabel;
    		$oTodo->content 	= $sContent;
    		$oTodo->deadline 	= time();
    		$oTodo->lastupdate 	= time();
    		$oTodo->created 	= time();
    		$oTodo->user_iduser	= $this->oUser->getId();
    		//$oTodo->deadline 	= $this->_params['deadline'];
    		return $oTodo->add();
    	} catch (\Library\Core\EntityException $oException) {
    		return false;
    	}
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