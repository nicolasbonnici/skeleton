<?php

namespace app\Entities\Collection;

/**
 * Block entity
 *
 * @author infradmin
 */
class TodoCollection extends \Library\Core\EntitiesCollection {

    const TABLE_NAME = 'todos';
    const PRIMARY_KEY = 'idtodo';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;    
    
//    protected static $aLinkedEntities = array(
//        'User' => array(
//            'loadByDefault' => false,
//            'mappedByEntity' => 'User',
//            'mappedByAttribute' => 'iduser',
//            'relationship' => 'oneToOne' // @see oneToOne|manyToOne|manyToMany
//        ),
//        'blocksTypes' => array(
//            'loadByDefault' => false,
//            //'mappedBy' => '', // @see mapping table for manyToOne|manyToMany
//            'mappedByAttribute' => 'blocksTypes_id',
//            'relationship' => 'oneToOne' // @see oneToOne|manyToOne|manyToMany
//        )
//    );
    
    /**
     * Load todos from a given user
     * 
     * @param int $iUserId
     * @return bool
     */
    public function loadByUserId($iUserId) {
        
        if (!empty($iUserId)) {
            return $this->loadByParameters(
                        array(
                            'users_iduser' => $iUserId
                        ), 
                        array(
                            'idtodo' => 'DESC'
                        ),
                        array(0,10)
                    );            
        }
        return false;
    }
    
}

?>
