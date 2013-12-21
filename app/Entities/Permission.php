<?php

namespace app\Entities;

/**
 * Block entity
 * 
 * Wrapper for any entity that have a title/content/created field [CMS]
 * 
 * @author infradmin
 */
class Permission extends \Library\Core\Entity {

    const ENTITY = 'Permission';
    const TABLE_NAME = 'permissions';
    const PRIMARY_KEY = 'idpermission';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 60;    
    
    protected $aLinkedEntities = array(
        'role' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'Role',
    		'mappedByField' => 'roles_idrole'
        ),
        'ressource' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'Ressource',
    		'mappedByField' => 'ressources_idressource'
        )
    );
    
}

?>
