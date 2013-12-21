<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class Todo extends \Library\Core\Entity {

    const ENTITY = 'Todo';
    const TABLE_NAME = 'todos';
    const PRIMARY_KEY = 'idtodo';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 60;    
    
    protected $aLinkedEntities;
    
}

?>
