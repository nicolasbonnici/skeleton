<?php

namespace app\Entities;

/**
 * \app\Entity\User Group Entity
 *
 * @see \app\Entities\User
 * @author infradmin
 */
class Group extends \Library\Core\Entity {

    const ENTITY = 'Group';
    const TABLE_NAME = 'group';
    const PRIMARY_KEY = 'idgroup';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120;

    protected $aLinkedEntities;

}
