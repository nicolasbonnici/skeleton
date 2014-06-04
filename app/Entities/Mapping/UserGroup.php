<?php

namespace app\Entities\Mapping;

/**
 * \app\Entity\UserGroup Entity
 *
 * @see \app\Entities\User
 * @author infradmin
 */
class UserGroup extends \Library\Core\Entity {

    const ENTITY = 'UserGroup';
    const TABLE_NAME = 'userGroups';
    const PRIMARY_KEY = 'idusergroup';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120;

    protected $aLinkedEntities;

}
