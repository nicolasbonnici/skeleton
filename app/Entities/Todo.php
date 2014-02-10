<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class Todo extends \Library\Core\Entity {

    const ENTITY = 'Todo';
    const TABLE_NAME = 'todo';
    const PRIMARY_KEY = 'idtodo';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 60;

    protected $aLinkedEntities;
    protected $bIsDeletable = true;

}

?>
