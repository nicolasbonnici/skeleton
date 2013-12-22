<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tag
 *
 * @author infradmin
 */
class Tag {
    
    const ENTITY = 'Tag';
    const TABLE_NAME = 'tag';
    const PRIMARY_KEY = 'idtag';

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 50;    
    
    protected static $aLinkedEntities = array(

    );    
    
}


?>
