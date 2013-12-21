<?php

class db_Clients extends core_Object
{
    /**
     * core_Object related class constants
     * @var string
     */
    const TABLE_NAME = 'clients';
    const PRIMARY_KEY = 'idclient';

    /**
     * core_Object related class members
     * @var mixed
     */
    protected $aChaining = array(
        'membre' => array(
            'field' => 'idmembre',
            'class' => 'db_Membres'
        )
    );

    protected $iCacheDuration = 1200;
}
