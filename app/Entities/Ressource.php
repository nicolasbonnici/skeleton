<?php

namespace app\Entities;

/**
 * Block entity
 *
 * @author infradmin
 */
class Ressource extends \Library\Core\Entity {

	const ENTITY = 'Ressource';
    const TABLE_NAME = 'ressources';
    const PRIMARY_KEY = 'idressource';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 120; 
    
    protected $aLinkedEntities;
    
}

?>
