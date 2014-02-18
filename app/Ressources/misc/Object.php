<?php

namespace core;

/**
 * Core class for database based objects
 * NOTE: Table must have an auto_increment as a primary key
 * NOTE: Child class must have class constants TABLE_NAME and PRIMARY_KEY defined
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-12 - Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class Object
{
    /**
     * List of associated table's fields
     * @var array
     */
    protected static $aFields = array();

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
     *             'class' => 'Membres'
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
     * Whether historical must be saved in DB on update/delete
     * @var boolean
     */
    protected $bIsHistorized = false;

    /**
     * Whether we should use MySQL `IGNORE` command (usefull when there are `UNIQUE` indexes other than primary key)
     * @var boolean
     */
    protected $bIgnoreOnInsert = false;

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
     * Child class name
     * @var string
     */
    protected $sClassName;

    /**
     * Constructor
     * @param mixed $mPrimaryKey Primary key. If left empty, blank object will be instanciated
     */
    public function __construct($mPrimaryKey = null, $bUseCache = true)
    {
        $this->sClassName = get_called_class();
        // If we just want to instanciate a blank object, do not pass any parameter to constructor
        if (is_null($mPrimaryKey) && !isset(self::$aFields[$this->sClassName])) {
            $this->loadFields();
        } elseif (!is_null($mPrimaryKey)) {
            $this->{static::PRIMARY_KEY} = $mPrimaryKey;
            $this->loadByPrimaryKey($bUseCache);
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
            $sClass = '\db\\' . $this->aChaining[$sName]['class'];
            $this->$sName = new $sClass($this->{$this->aChaining[$sName]['field']});
        }

        if (!isset($this->$sName)) {
            throw new ObjectException('Trying to get undefined member "' . $sName . '" of class "' . $this->sClassName . '"');
        }

        return $this->$sName;
    }

    /**
     * @see PHP::__set
     * Magic setter is deactivated because of poor performances
     */
//     public function __set($sName, $mValue)
//     {
//         if ($sName !== static::PRIMARY_KEY && !in_array($sName, self::$aFields[$this->sClassName]) && !array_key_exists($sName, $this->aChaining)) {
//             throw new ObjectException('Trying to set not existing class member "' . $sName . '" of class "' . $this->sClassName . '"');
//         }

//         $this->$sName = $mValue;
//     }

    /**
     * @see PHP::__isset
     */
    public function __isset($sName)
    {
        return (in_array($sName, self::$aFields[$this->sClassName]) && isset($this->$sName));
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
     * @param   string  $sCacheKey      Cache key
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     */
    public function loadByData($aData, $bRefreshCache = true, $sCacheKey = null)
    {
        self::$aFields[$this->sClassName] = array_keys($aData);

        foreach ($aData as $sName => $mValue) {
            $this->{$sName} = $mValue;
        }

        if ($bRefreshCache && isset($aData[static::PRIMARY_KEY]) && !empty($this->iCacheDuration)) {
            $sObjectCacheKey = self::getCacheKey($aData[static::PRIMARY_KEY]);
            // If given cache key is not object main key, we save relation between given cache key and object
            if (!is_null($sCacheKey) && $sCacheKey !== $sObjectCacheKey) {
                \core\Cache::set($sCacheKey, $aData[static::PRIMARY_KEY], \core\Cache::CACHE_TIME_DAY);
            }
            \core\Cache::set($sObjectCacheKey, $aData, $this->iCacheDuration);
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Load object depending on given parameters values
     * Parameters is a key/value array, key being table fields names
     * @param   array   $aParameters Parameters to check
     * @param   boolean $bUseCache      Whether object caching must be used to retrieve data or not
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  \core\ObjectException
     */
    public function loadByParameters(array $aParameters, $bUseCache = true)
    {
        if (empty($aParameters)) {
            throw new ObjectException('No parameter provided for loading object of type ' . $this->sClassName);
        }

        $bLoaded = false;
        $sCacheKey = \core\Cache::getKey($this->sClassName, 'loadByParameters', $aParameters);
        $iPrimaryKey = \core\Cache::get($sCacheKey);

        if ($iPrimaryKey !== false) {
            $bLoaded = true;
            $this->{static::PRIMARY_KEY} = $iPrimaryKey;
            $this->loadByPrimaryKey($bUseCache);
            foreach ($aParameters as $sParameterName => $mParameterValue) {
                if ($this->$sParameterName != $mParameterValue) {
                    \core\Cache::delete($sCacheKey);
                    $bLoaded = false;
                    break;
                }
            }
            if ($bLoaded) {
                return true;
            }
        }

        return $this->loadByQuery(
            'SELECT * FROM `' . static::TABLE_NAME . '` WHERE `' . implode('` = ? AND `', array_keys($aParameters)) . '` = ?',
            array_values($aParameters),
            $bUseCache,
            $sCacheKey
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
     * @throws  \core\ObjectException
     */
    public function loadByQuery($sQuery, array $aBindedValues = array(), $bUseCache = true, $sCacheKey = null)
    {
        $bRefreshCache = false;

        if ($bUseCache && !empty($this->iCacheDuration)) {
            if (is_null($sCacheKey)) {
                $sCacheKey = \core\Cache::getKey($this->sClassName, $sQuery, $aBindedValues);
            }
            $aObject = \core\Cache::get($sCacheKey);
        }

        if (!isset($aObject) || $aObject === false || !is_array($aObject)) {
            $bRefreshCache = true;

            if (($oStatement = \core\DB::query($sQuery, $aBindedValues, ! $bUseCache)) === false) {
                throw new ObjectException('Unable to construct object of class ' . $this->sClassName . ' with query ' . $sQuery);
            }

            if ($oStatement->rowCount() === 0) {
                throw new ObjectException('No object of class ' . $this->sClassName . ' found for query "' . $sQuery . '" with values ' . print_r($aBindedValues, true));
            }

            if ($oStatement->rowCount() > 1) {
                trigger_error('More than one occurence of object ' . $this->sClassName . ' found for query ' . $sQuery . ' // ' . implode(' - ', $aBindedValues), E_USER_WARNING);
            }

            $aObject = $oStatement->fetchAll(\PDO::FETCH_ASSOC);
            $aObject = $aObject[0];
        }

        return $this->loadByData($aObject, $bRefreshCache, $sCacheKey);
    }

    /**
     * Add record corresponding to object to database
     * @return boolean TRUE if record was successfully inserted, otherwise FALSE
     * @throws \core\ObjectException
     */
    public function add()
    {
        $aInsertedFields = array();
        $aInsertedValues = array();
        foreach (self::$aFields[$this->sClassName] as $sFieldName) {
            if (isset($this->{$sFieldName})) {
                $aInsertedFields[] = $sFieldName;
                $aInsertedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aInsertedFields) === 0) {
            throw new ObjectException('Cannot create empty object of class ' . $this->sClassName);
        }

        try {
            $sQuery = 'INSERT ' . ($this->bIgnoreOnInsert ? 'IGNORE' : '') . ' INTO ' . static::TABLE_NAME . '(`' . implode('`,`', $aInsertedFields) . '`) VALUES (?' . str_repeat(',?', count($aInsertedValues) -1) . ')';
            $oStatement = \core\DB::query($sQuery, $aInsertedValues);
            if ($oStatement === false) {
                throw new \core\ObjectException('Object creation failed: ' . $sQuery . ' // "' . implode('" - "', $aInsertedValues) . '"');
            }
            if ($oStatement->rowCount() === 0) {
                trigger_error('Trying to insert existing element: ' . $sQuery . ' // "' . implode('" - "', $aInsertedValues) . '"', E_USER_WARNING);
                return false;
            }
            if (! isset($this->{static::PRIMARY_KEY})) {
                $this->{static::PRIMARY_KEY} = \core\DB::lastInsertId();
            }
            $this->refresh();
        } catch (\PDOException $oException) {
            return false;
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Update record corresponding to object in database
     * @return boolean TRUE if record was successfully updated, otherwise FALSE
     * @throws \core\ObjectException
     */
    public function update()
    {
        $aUpdatedFields = array();
        $aUpdatedValues = array();
        foreach (self::$aFields[$this->sClassName] as $sFieldName) {
            if (isset($this->{$sFieldName})) {
                $aUpdatedFields[] = $sFieldName;
                $aUpdatedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aUpdatedFields) === 0) {
            throw new ObjectException('Cannot update empty object of class' . $this->sClassName);
        }

        if (empty($this->{static::PRIMARY_KEY})) {
            throw new ObjectException('Cannot update object of class ' . $this->sClassName . ' with no primary key value');
        }

        $oDbOriginalObject = new $this->sClassName($this->{static::PRIMARY_KEY});

        if ($this != $oDbOriginalObject) {
            if ($this->bIsHistorized) {
                $this->saveHistory($oDbOriginalObject);
            }

            try {
                $aUpdatedValues[] = $this->{static::PRIMARY_KEY};
                $oStatement = \core\DB::query('UPDATE ' . static::TABLE_NAME . ' SET `' . implode('` = ?, `', $aUpdatedFields) . '` = ? WHERE `' . static::PRIMARY_KEY . '` = ?', $aUpdatedValues);
                $this->refresh();
            } catch (\PDOException $oException) {
                return false;
            }
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Delete row corresponding to current instance in database and reset instance
     * @throws \core\ObjectException
     * @return boolean TRUE if deletion was successful, otherwise FALSE
     */
    public function delete()
    {
        if (!$this->bIsDeletable) {
            throw new ObjectException('Cannot delete object of type "' . $this->sClassName . '", this type of object is not deletable');
        }

        if (!$this->bIsLoaded) {
            throw new ObjectException('Cannot delete entry, object not loaded properly');
        }

        try {
            $oStatement = \core\DB::query('DELETE FROM `' . static::TABLE_NAME . '` WHERE `' . static::PRIMARY_KEY . '` = ?', array($this->{static::PRIMARY_KEY}));
            \core\Cache::delete(self::getCacheKey($this->{static::PRIMARY_KEY}));
            $this->reset();
        } catch (\PDOException $oException) {
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
        $oReflection = new \ReflectionClass($this);

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
     * @throws \core\ObjectException
     */
    public function getId()
    {
        if (!$this->bIsLoaded) {
            throw new ObjectException('Cannot get ID of object not loaded');
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
     * Retrieve list of fields for current object table
     * @return array List of fields
     */
    public function getFields()
    {
        if (empty(self::$aFields[$this->sClassName])) {
            $this->loadFields();
        }
        return self::$aFields[$this->sClassName];
    }

    /**
     * Check whether instance is in cache or not
     * @param   integer $iId Instance ID (primary key of table)
     * @return  boolean TRUE if instance is in cache, otherwise false
     */
    public static function getCached($iId)
    {
        return \core\Cache::get(self::getCacheKey($iId));
    }

    /**
     * Retrieve cache key for single instance of class for given ID
     * @param unknown $iId
     * @return string
     */
    public static function getCacheKey($iId)
    {
        return \core\Cache::getKey(get_called_class(), $iId);
    }

    /**
     * Load object using its primary key
     * @param   boolean $bUseCache Whether object caching must be used to retrieve data or not
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  \core\ObjectException
     */
    protected function loadByPrimaryKey($bUseCache = true)
    {
        if (!isset($this->{static::PRIMARY_KEY})) {
            throw new ObjectException('Cannot load object of class ' . $this->sClassName . ' by primary key, no value provided for key ' . static::PRIMARY_KEY);
        }

        return $this->loadByQuery(
            'SELECT * FROM `' . static::TABLE_NAME . '` WHERE `' . static::PRIMARY_KEY . '` = ?',
            array($this->{static::PRIMARY_KEY}),
            $bUseCache,
            self::getCacheKey($this->{static::PRIMARY_KEY})
        );
    }

    /**
     * Load the list of fields of the associated database table
     * @throws \core\ObjectException
     */
    protected function loadFields()
    {
        $sCacheKey = \core\Cache::getKey(__METHOD__, $this->sClassName);

        if ((self::$aFields[$this->sClassName] = \core\Cache::get($sCacheKey)) === false) {
            if (($oStatement = \core\DB::query('SHOW COLUMNS FROM `' . static::TABLE_NAME . '`')) === false) {
                throw new ObjectException('Unable to list fields for table ' . static::TABLE_NAME);
            }

            self::$aFields[$this->sClassName] = array();

            foreach ($oStatement->fetchAll(\PDO::FETCH_ASSOC) as $aColumn) {
                self::$aFields[$this->sClassName][] = $aColumn['Field'];
            }

            \core\Cache::set($sCacheKey, self::$aFields[$this->sClassName], \core\Cache::CACHE_TIME_DAY);
        }
    }

    /**
     * Save history on update for historized objects
     * @param CoreObject $oDbOriginalObject Original object before update
     */
    protected function saveHistory($oDbOriginalObject)
    {
        $aBefore = array();
        $aAfter = array();

        foreach ($this as $sPropertyName => $mValue) {
            if ($mValue != $oDbOriginalObject->{$sPropertyName}) {
                $aBefore[$sPropertyName] = $oDbOriginalObject->{$sPropertyName};
                $aAfter[$sPropertyName] = $mValue;
            }
        }

        $oDbHistorisationObjet = new \db\HistorisationObjet();
        $oDbHistorisationObjet->classe = substr($this->sClassName, 3);
        $oDbHistorisationObjet->idobjet = $this->{static::PRIMARY_KEY};
        $oDbHistorisationObjet->avant = json_encode($aBefore);
        $oDbHistorisationObjet->apres = json_encode($aAfter);
        $oDbHistorisationObjet->date_modif = date('Y-m-d');
        $oDbHistorisationObjet->time_modif = date('H:i:s');
        $oDbHistorisationObjet->iduser = \model\UserSession::getInstance()->getUserId();
        $oDbHistorisationObjet->add();
    }
}

class ObjectException extends \Exception {}
