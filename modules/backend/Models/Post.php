<?php

namespace modules\backend\Models;

class Posts {

    /**
     * Post available status
     */
    const STATUS_PUBLISHED        = 'published';
    const STATUS_UNPUBLISHED    = 'unpublished';
    const STATUS_NEED_AUTH        = 'needauth';

    /**
     * Current user instance
     *
     * @var \app\Entities\User
     */
    private $oUser;

    /**
     * Post attribute
     *
     * @var \app\Entities\Post
     */
    private $oPost;

    /**
     * Posts collection attribute
     *
     * @var \app\Entities\Collection\PostCollection
     */
    private $oPosts;

    /**
     * Instance constructor
     */
    public function __construct($mUser = null)
    {
        assert('is_null($mUser) || $mUser instanceof \app\Entities\User && $mUser->isLoaded() || is_int($mUser) && intval($mUser) > 0');

        if ($mUser instanceof \app\Entities\User && $mUser->isLoaded()) {
            $this->oUser = $mUser;
        } elseif (is_int($mUser) && intval($mUser) > 0) {
            try {
                $this->oUser = new \app\Entities\User($mUser);
            } catch (\Library\Core\EntitiesException $oException) {}
        } else {
            $this->oUser = null;
        }

        $this->oPosts = new \app\Entities\Collection\PostCollection();
        $this->oPost = new \app\Entities\Post();
    }

    /**
     * Load todos from a given user
     *
     * @return bool
     */
    public function loadLatest()
    {
        $this->oPosts->loadByParameters(
            array(
                'status' => self::STATUS_PUBLISH
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
    public function loadByPostId($iTodoId)
    {
        try {
            $this->oPost = new \app\Entities\Post();
            $this->oPost->loadByParameters(
                    array(
                            'idpost'         => $iTodoId
                    )
            );
            if ($this->oPost->isLoaded()) {
                return $this->oPost;
            }
        } catch (\Library\Core\EntityException $oException) {
            return null;
        }
    }

    /*
     * @todo
     */
    public function createByUser($sLabel, $sContent)
    {
        try {
            $oPost = new \app\Entities\Todo();
            $oPost->label         = $sLabel;
            $oPost->content     = $sContent;
            $oPost->deadline     = time();
            $oPost->lastupdate     = time();
            $oPost->created     = time();
            $oPost->user_iduser    = $this->oUser->getId();
            //$oPost->deadline     = $this->_params['deadline'];
            return $oPost->add();
        } catch (\Library\Core\EntityException $oException) {
            return false;
        }
    }

    /**
     *
     * @return \app\Entities\Collection\TodoCollection
     */
    public function getPosts()
    {
        return $this->oPosts;
    }

    /**
     *
     * @return \app\Entities\Collection\TodoCollection
     */
    public function getPost()
    {
        return $this->oPost;
    }
}

class TodoModelException extends \Exception {}
