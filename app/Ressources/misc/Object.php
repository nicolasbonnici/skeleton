<?php

/**
 * Core class for database based objects
 * NOTE: Table must have an auto_increment as a primary key
 * NOTE: Child class must have class constants TABLE_NAME and PRIMARY_KEY defined
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-12 - Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class core_Object
{
    /**
     * List of associated table's fields
     * @var array
     */
    protected $aFields = array();

    /**
     * List of class members that may be used to chain
     * This array is composed of arrays with
     *     keys being class member name
     *     values being 'field' and 'class'
     *     'field' refers to current class member name which value will be used as the ID of chained object
     *     'class' refers to chained object class name
     * Example:
     *      protected $aChaining = array(
     *         'parrain' => array(
     *             'field' => 'idparrain',
     *             'class' => 'db_Membres'
     *         )
     *     );
     * @var array
     */
    protected $aChaining = array();

    /**
     * Whether row in database may be deleted or not
     * @var boolean
     */
    protected $bIsDeletable = false;

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration;

    /**
     * Whether object has been successfully loaded or not
     * @var boolean
     */
    protected $bIsLoaded = false;

    /**
     * Constructor
     * @param mixed $mPrimaryKey Primary key. If left empty, blank object will be instanciated
     * @throws CoreObjectException
     */
    public function __construct($mPrimaryKey = null)
    {
        // If we just want to instanciate a blank object, do not pass any parameter to constructor
        if (is_null($mPrimaryKey)) {
            $this->loadFields();
        } else {
            $this->{static::PRIMARY_KEY} = $mPrimaryKey;
            $this->loadByPrimaryKey();
        }
    }

    /**
     * @see PHP::__get
     */
    public function __get($sName)
    {
        if (
            array_key_exists($sName, $this->aChaining)
            && !empty($this->aChaining[$sName]['class'])
            && !empty($this->aChaining[$sName]['field'])
            && isset($this->{$this->aChaining[$sName]['field']})
        ) {
            $sClass = $this->aChaining[$sName]['class'];
            $this->$sName = new $sClass($this->{$this->aChaining[$sName]['field']});
        }

        if (!isset($this->$sName)) {
            throw new CoreObjectException('Trying to get undefined member "' . $sName . '" of class "' . get_called_class() . '"');
        }

        return $this->$sName;
    }

    /**
     * @see PHP::__set
     */
    public function __set($sName, $mValue)
    {
        if ($sName !== static::PRIMARY_KEY && !in_array($sName, $this->aFields) && !array_key_exists($sName, $this->aChaining)) {
            throw new CoreObjectException('Trying to set not existing class member "' . $sName . '" of class "' . get_called_class() . '"');
        }

        $this->$sName = $mValue;
    }

    /**
     * @see PHP::__isset
     */
    public function __isset($sName)
    {
        return (in_array($sName, ($this->aFields)) && isset($this->$sName));
    }

    /**
     * @see PHP::__clone
     */
    final private function __clone() {}

    /**
     * Load object with given ID as a primary key value
     * @param mixed $mId Primary key value
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     */
    public function loadById($mId)
    {
        $this->{static::PRIMARY_KEY} = $mId;
        return $this->loadByPrimaryKey();
    }

    /**
     * Load object with provided data
     * Data must be an array of key/value, key being table fields names
     * @param   array   $aData          Object data
     * @param   boolean $bRefreshCache  Whether cache must be updated or not
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     */
    public function loadByData($aData, $bRefreshCache = true)
    {
        foreach ($aData as $sName => $mValue) {
            if (!in_array($sName, $this->aFields)) {
                $this->aFields[] = $sName;
            }

            $this->{$sName} = $mValue;
        }

        if ($bRefreshCache && isset($aData[static::PRIMARY_KEY]) && !empty($this->iCacheDuration)) {
            $sCacheKey = core_memc::getKey(get_called_class(), $aData[static::PRIMARY_KEY]); // Do not change this key, it must match isInCache() method
            core_memc::set($sCacheKey, $aData, false, $this->iCacheDuration);
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Load object depending on given parameters values
     * Parameters is a key/value array, key being table fields names
     * @param   array   $aParameters Parameters to check
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  CoreObjectException
     */
    public function loadByParameters(array $aParameters)
    {
        if (empty($aParameters)) {
            throw new CoreObjectException('No parameter provided for loading object of type ' . get_called_class());
        }

        return $this->loadByQuery(
            'SELECT * FROM `' . static::TABLE_NAME . '` WHERE `' . implode('` = ? AND `', array_keys($aParameters)) . '` = ?',
            array_values($aParameters),
            true,
            core_memc::getKey(__METHOD__, static::TABLE_NAME, $aParameters)
        );
    }

    /**
     * Load object by executing given SQL query
     * NOTE: method is protected because query must be generated within child class along with cache key definition
     * @param   string  $sQuery         SQL query to use for loading object
     * @param   array   $aBindedValues  Binded values for query
     * @param   boolean $bUseCache      Whether object caching must be used to retrieve data or not
     * @param   string  $sCacheKey      Cache key for given query
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  CoreObjectException
     */
    public function loadByQuery($sQuery, array $aBindedValues = array(), $bUseCache = true, $sCacheKey = null)
    {
        $bRefreshCache = false;

        if ($bUseCache && !empty($this->iCacheDuration)) {
            if (is_null($sCacheKey)) {
                $sCacheKey = core_memc::getKey(get_called_class(), $sQuery, $aBindedValues);
            }
            $aObject = core_memc::get($sCacheKey);
        }

        if (!isset($aObject) || $aObject === false) {
            $bRefreshCache = true;

            if (($oStatement = sql::query($sQuery, $aBindedValues)) === false) {
                throw new CoreObjectException('Unable to construct object of class ' . get_called_class() . ' with query ' . $sQuery);
            }

            if ($oStatement->rowCount() === 0) {
                throw new CoreObjectException('No object of class ' . get_called_class() . ' found for query "' . $sQuery . '" with values ' . print_r($aBindedValues, true));
            }

            if ($oStatement->rowCount() > 1) {
                throw new CoreObjectException('More than one occurence of object ' . get_called_class() . ' found for query ' . $sQuery);
            }

            $aObject = $oStatement->fetchAll(PDO::FETCH_ASSOC);
            $aObject = $aObject[0];
        }

        return $this->loadByData($aObject, $bRefreshCache);
    }

    /**
     * Add record corresponding to object to database
     * @return boolean TRUE if record was successfully inserted, otherwise FALSE
     * @throws CoreObjectException
     */
    public function add()
    {
        $aInsertedFields = array();
        $aInsertedValues = array();
        foreach ($this->aFields as $sFieldName) {
            if (isset($this->{$sFieldName})) {
                $aInsertedFields[] = $sFieldName;
                $aInsertedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aInsertedFields) === 0) {
            throw new CoreObjectException('Cannot create empty object of class ' . get_called_class());
        }

        try {
            $oStatement = sql::query('INSERT INTO ' . static::TABLE_NAME . '(`' . implode('`,`', $aInsertedFields) . '`) VALUES (?' . str_repeat(',?', count($aInsertedValues) -1) . ')', $aInsertedValues);
            if (!isset($this->{static::PRIMARY_KEY})) {
                $this->{static::PRIMARY_KEY} = sql::lastInsertId();
            }
            $this->refresh();
        } catch (PDOException $oException) {
            return false;
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Update record corresponding to object in database
     * @return boolean TRUE if record was successfully updated, otherwise FALSE
     * @throws CoreObjectException
     */
    public function update()
    {
        $aUpdatedFields = array();
        $aUpdatedValues = array();
        foreach ($this->aFields as $sFieldName) {
            if (isset($this->{$sFieldName})) {
                $aUpdatedFields[] = $sFieldName;
                $aUpdatedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aUpdatedFields) === 0) {
            throw new CoreObjectException('Cannot update empty object of class' . get_called_class());
        }

        if (empty($this->{static::PRIMARY_KEY})) {
            throw new CoreObjectException('Cannot update object of class ' . get_called_class() . ' with no primary key value');
        }

        try {
            $aUpdatedValues[] = $this->{static::PRIMARY_KEY};
            $oStatement = sql::query('UPDATE ' . static::TABLE_NAME . ' SET `' . implode('` = ?, `', $aUpdatedFields) . '` = ? WHERE `' . static::PRIMARY_KEY . '` = ?', $aUpdatedValues);
            $this->refresh();
        } catch (PDOException $oException) {
            return false;
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Delete row corresponding to current instance in database and reset instance
     * @throws CoreObjectException
     * @return boolean TRUE if deletion was successful, otherwise FALSE
     */
    public function delete()
    {
        if (!$this->bIsDeletable) {
            throw new CoreObjectException('Cannot delete object of type "' . get_called_class() . '", this type of object is not deletable');
        }

        if (!$this->bIsLoaded) {
            throw new CoreObjectException('Cannot delete entry, object not loaded properly');
        }

        try {
            $oStatement = sql::query('DELETE FROM `' . static::TABLE_NAME . '` WHERE `' . static::PRIMARY_KEY . '` = ?', array($this->{static::PRIMARY_KEY}));
            $this->reset();
        } catch (PDOException $oException) {
            return false;
        }

        return true;
    }

    /**
     * Refresh object data from database
     * @return boolean TRUE if object was successfully refreshed, otherwise FALSE
     */
    public function refresh()
    {
        $iPrimaryKey = $this->{static::PRIMARY_KEY};
        $this->reset();
        $this->{static::PRIMARY_KEY} = $iPrimaryKey;
        return $this->loadByPrimaryKey(false);
    }

    /**
     * Reset current instance to blank state
     */
    public function reset()
    {
        $aOriginProperties = array();
        $oReflection = new ReflectionClass($this);

        foreach ($oReflection->getProperties() as $oRelectionProperty) {
            $aOriginProperties[] = $oRelectionProperty->getName();
        }

        foreach ($this as $sKey => $mValue) {
            if (!in_array($sKey, $aOriginProperties)) {
                unset($this->$sKey);
            }
        }

        $this->bIsLoaded = false;
    }

    /**
     * Retrieve instance ID (primary key)
     * @return mixed Instance ID
     * @throws CoreObjectException
     */
    public function getId()
    {
        if (!$this->bIsLoaded) {
            throw new CoreObjectException('Cannot get ID of object not loaded');
        }
        return $this->{static::PRIMARY_KEY};
    }

    /**
     * Check whether object was successfully loaded
     * @return boolean TRUE if object was successfully loaded, otherwise FALSE
     */
    public function isLoaded()
    {
        return $this->bIsLoaded;
    }

    /**
     * Check whether instance is in cache or not
     * @param   integer $iId Instance ID (primary key of table)
     * @return  boolean TRUE if instance is in cache, otherwise false
     */
    static public function isInCache($iId)
    {
        return (core_memc::get(core_memc::getKey(get_called_class(), $iId)) !== false);
    }

    /**
     * Load object using its primary key
     * @param   boolean $bUseCache Whether object caching must be used to retrieve data or not
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  CoreObjectException
     */
    protected function loadByPrimaryKey($bUseCache = true)
    {
        if (!isset($this->{static::PRIMARY_KEY})) {
            throw new CoreObjectException('Cannot load object of class ' . get_called_class() . ' by primary key, no value provided for key ' . static::PRIMARY_KEY);
        }

        return $this->loadByQuery(
            'SELECT * FROM `' . static::TABLE_NAME . '` WHERE `' . static::PRIMARY_KEY . '` = ?',
            array($this->{static::PRIMARY_KEY}),
            $bUseCache,
            core_memc::getKey(get_called_class(), $this->{static::PRIMARY_KEY})
        );
    }

    /**
     * Load the list of fields of the associated database table
     * @throws CoreObjectException
     */
    protected function loadFields()
    {
        $sCacheKey = core_memc::getKey(__METHOD__, get_called_class());
        if (($this->aFields = core_memc::get($sCacheKey)) === false) {
            if (($oStatement = sql::query('SHOW COLUMNS FROM `' . static::TABLE_NAME . '`')) === false) {
                throw new CoreObjectException('Unable to list fields for table ' . static::TABLE_NAME);
            }

            foreach ($oStatement->fetchAll(PDO::FETCH_ASSOC) as $aColumn) {
                $this->aFields[] = $aColumn['Field'];
            }

            core_memc::set($sCacheKey, $this->aFields, false, core_memc::CACHE_TIME_DAY);
        }
    }
}

class CoreObjectException extends Exception {}
