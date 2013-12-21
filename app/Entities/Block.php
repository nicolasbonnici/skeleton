<?php

namespace app\Entities;

/**
 * Block entity
 * 
 * Wrapper for any entity that have a title/content/created field [CMS]
 * 
 * @author infradmin
 */
class Block extends \Library\Core\Entity {

    const ENTITY = 'Block';
    const TABLE_NAME = 'blocks';
    const PRIMARY_KEY = 'idblock';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 60;    
    
    protected $aLinkedEntities = array(
        'blockType' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'BlockType',
    		'mappedByField' => 'blockTypes_idblockType'
        )
    );
    
}

?>
