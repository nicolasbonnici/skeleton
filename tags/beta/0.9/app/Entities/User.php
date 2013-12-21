<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class User extends \Library\Core\Entity {

    const ENTITY = 'User';
    const TABLE_NAME = 'users';
    const PRIMARY_KEY = 'iduser';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120; 
    
    protected $aLinkedEntities = array(
        'role' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'Role',
    		'mappedByField' => 'roles_idrole'
        )
    );
    
}

?>
