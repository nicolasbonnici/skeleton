<?php

namespace app\Entities;

/**
 * \app\Entity\User Group Entity
 *
 * @see \app\Entities\User
 * @author infradmin
 */
class Config extends \Library\Core\Entity {

    const ENTITY = 'Config';
    const TABLE_NAME = 'config';
    const PRIMARY_KEY = 'idconfig';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120;

    protected $aLinkedEntities;

}
