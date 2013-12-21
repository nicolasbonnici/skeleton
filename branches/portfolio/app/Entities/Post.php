<?php

namespace app\Entities;

/**
 * Description of Pos
 *
 * @author infradmin
 */
class Post extends \Library\Core\Entity {

    const ENTITY = 'Post';
    const TABLE_NAME = 'posts';
    const PRIMARY_KEY = 'idpost';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;    

    protected $aLinkedEntities = array(
        'category' => array(
            'loadByDefault' => false,
            'relationship' => 'oneToOne', // @see oneToOne|manyToOne|manyToMany
    		'entity' => 'Category',
    		'mappedByField' => 'categories_idcategory'
        )
    );    
}

?>
