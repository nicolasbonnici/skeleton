<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class User extends \Library\Core\Entity {

    const ENTITY = 'User';
    const TABLE_NAME = 'user';
    const PRIMARY_KEY = 'iduser';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120;

    protected $aLinkedEntities;

}
