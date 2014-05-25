<?php

namespace app\Entities;

/**
 * Description of Tag
 *
 * @author infradmin
 */
class Tag  extends \Library\Core\Entity {

    const ENTITY = 'Tag';
    const TABLE_NAME = 'tag';
    const PRIMARY_KEY = 'idtag';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;
    protected $bIsSearchable = true;

    protected $aLinkedEntities = array();

}
