<?php

namespace Library\Core;

/**
 * On the fly ORM CRUD managment
 * @author niko nicolasbonnici@gmail.com
 *
 * @todo optimiser la gestion du cache dans le composant Cache
 * @dependancy \Library\Core\Validator
 * @important Entity need a primary auto incremeted index
 */

abstract class Entity extends Database  {

    /**
     * List of associated table's fields
     * @var array
     */
    protected $aFields = array();

    /**
     * Whether object may be put in cache or not
     * @var boolean
     */
    protected $bIsCacheable = true;

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = 10;

    /**
     * Whether object has been successfully loaded or not
     * @var boolean
     */
    protected $bIsLoaded = false;

    /**
     * If loaded with an array of primary keys identifier
     * @var object collection
     */
    protected $aCollection = array();

    /**
     * Enhance perf
     * @var string
     */
    protected $sChildClass;

    /**
     * Cache identifiers params at instance
     *
     * Collection Idientifiers list at instance (optionnal)
     */
    protected $aOriginIds = array();

    /**
     * List of class members that may be used to chain
     * This array is composed of arrays with
     *     keys being class member name
     *     values being 'field' and 'class'
     *     'field' refers to current class member name which value will be used as the ID of chained object
     *     'class' refers to chained object class name
     * Example:
	    protected $aLinkedEntities = array(
	        'membre' => array(
	            'field' => 'idmembre',
	            'class' => 'db_Membres'
	        )
	    );
     * @var array
     */
    protected $aLinkedEntities = array();

    /**
     * Constructor
     * @param mixed $mPrimaryKey Primary key. If left empty, blank object will be instanciated
     * @throws CoreEntityException
     */
    public function __construct($mPrimaryKey = null)
    {
        // If we just want to instanciate a blank object, do not pass any parameter to constructor
		$this->loadFields();
        if (!is_null($mPrimaryKey) && is_string($mPrimaryKey) || is_int($mPrimaryKey)) {

            // @see Build only one object
            $this->{static::PRIMARY_KEY} = $mPrimaryKey;
            $this->loadByPrimaryKey();

        } elseif (is_array($mPrimaryKey)) {
        	// @see Sinon si c'est un array je load l'objet via different paramètres
        	$this->loadByParameters($mPrimaryKey);
        }

        $this->sChildClass = get_called_class();

        return;
    }

    /**
     * Restrict scope to database schema and manage dependances
     *
     * @todo performance loose
     *
     * @param type $sName
     * @return object
     * @throws CoreException

    public function __get($sName)
    {
        if (
            array_key_exists($sName, $this->aLinkedEntities)
            && !empty($this->aLinkedEntities[$sName]['entity'])
            && !empty($this->aLinkedEntities[$sName]['mappedByField'])
            && isset($this->{$this->aLinkedEntities[$sName]['mappedByField']})
        ) {
            $sClass = '\app\Entities\\' . $this->aLinkedEntities[$sName]['entity'];
            $this->$sName = new $sClass($this->{$this->aLinkedEntities[$sName]['mappedByField']});
        }

        if (!isset($this->$sName)) {
            throw new CoreEntityException('Trying to get undefined member "' . $sName . '" of entity "' . get_called_class() . '"');
        }

        return $this->$sName;
    }
     */

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
                $this->aFields[$sName]['value'] = $mValue;
            }

		    $this->{$sName} = $mValue;
        }

        if ($this->bIsCacheable && $bRefreshCache && isset($aData[static::PRIMARY_KEY]) && !empty($this->iCacheDuration)) {
            $sCacheKey = Cache::getKey(get_called_class(), $aData[static::PRIMARY_KEY]); // Do not change this key, it must match isInCache() method
            Cache::set($sCacheKey, $aData, false, $this->iCacheDuration);
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Load object depending on given parameters values
     * Parameters is a key/value array, key being table fields names
     * @param   array   $aParameters Parameters to check
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  CoreEntityException
     */
    public function loadByParameters(array $aParameters)
    {
        if (empty($aParameters)) {
            throw new CoreEntityException('No parameter provided for loading object of type ' . get_called_class());
        }

        return $this->loadByQuery(
            'SELECT * FROM ' . static::TABLE_NAME . ' WHERE `' . implode('` = ? AND `', array_keys($aParameters)) . '` = ?',
            array_values($aParameters),
            true,
            Cache::getKey(__METHOD__, $aParameters)
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
     * @throws  CoreEntityException
     */
    protected function loadByQuery($sQuery, array $aBindedValues = array(), $bUseCache = true, $sCacheKey = null)
    {
        $bRefreshCache = false;
        if ($bUseCache && $this->bIsCacheable && !empty($this->iCacheDuration)) {
            if (is_null($sCacheKey)) {
                $sCacheKey = Cache::getKey(get_called_class(), $sQuery, $aBindedValues);
            }
            $aObject = Cache::get($sCacheKey);

        }

        if (!isset($aObject) || $aObject === false) {
            $bRefreshCache = true;

            if (($oStatement = \Library\Core\Database::dbQuery($sQuery, $aBindedValues)) === false) {
                throw new CoreEntityException('Unable to construct object of class ' . get_called_class() . ' with query ' . $sQuery);
            }

            if ($oStatement->rowCount() === 0) {
                return NULL;
            }

            if ($oStatement->rowCount() > 1) {
                throw new CoreEntityException('More than one occurence of object try to build a entityCollection?...' . get_called_class() . ' found for query ' . $sQuery);
            }

            $aObject = $oStatement->fetchAll(\PDO::FETCH_ASSOC);
            $aObject = $aObject[0];
        }

        return $this->loadByData($aObject, $bRefreshCache);
    }

    /**
     * Retrieve the cached instances of objects
     * @param   integer $iId Instance ID (primary key of table)
     * @return  boolean TRUE if instance is in cache, otherwise false
     */
    protected function getCached($iId)
    {
        return \Library\Core\Cache::get(self::getCacheKey($iId));
    }

    /**
     * Retrieve cache key for single instance of class for given ID
     * @param unknown $iId
     * @return string
     */
    public static function getCacheKey($iId)
    {
    	return \Library\Core\Cache::getKey(get_called_class(), $iId);
    }

    /**
     * Add record corresponding to object to database
     * @return boolean TRUE if record was successfully inserted, otherwise FALSE
     * @throws CoreEntityException
     */
    public function add()
    {
        $aInsertedFields = array();
        $aInsertedValues = array();
        foreach ($this->aFields as $sFieldName=>$aFieldInfos) {
        	if (
        		isset($this->{$sFieldName}) &&
        		!is_null($this->{$sFieldName}) &&
        		$this->validateDataIntegrity($sFieldName, $this->{$sFieldName})
        	) {
        		$aInsertedFields[] = $sFieldName;
                $aInsertedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aInsertedFields) === 0) {
            throw new CoreEntityException('Cannot create empty object of class ' . get_called_class());
        }

        try {
            $oStatement = \Library\Core\Database::dbQuery('INSERT INTO ' . static::TABLE_NAME . '(`' . implode('`,`', $aInsertedFields) . '`) VALUES (?' . str_repeat(',?', count($aInsertedValues) -1) . ')', $aInsertedValues);
            $this->{static::PRIMARY_KEY} = \Library\Core\Database::lastInsertId();
            $this->refresh();
        } catch (PDOException $oException) {
            return false;
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Update record corresponding to object in database
     * @return boolean TRUE if record was successfully updated, otherwise FALSE
     * @throws CoreEntityException
     */
    public function update()
    {
        $aUpdatedFields = array();
        $aUpdatedValues = array();
        foreach ($this->aFields as $sFieldName=>$aFieldInfos) {
        	if (
        		isset($this->{$sFieldName}) &&
        		!is_null($this->{$sFieldName}) &&
        		$this->validateDataIntegrity($sFieldName, $this->{$sFieldName})
        	) {
                $aUpdatedFields[] = $sFieldName;
                $aUpdatedValues[] = $this->{$sFieldName};
            }
        }

        if (count($aUpdatedFields) === 0) {
            throw new CoreEntityException('Cannot update empty object of class ' . get_called_class());
        }

        if (empty($this->{static::PRIMARY_KEY})) {
            throw new CoreEntityException('Cannot update object of class ' . get_called_class() . ' with no primary key value');
        }

        try {
            $aUpdatedValues[] = $this->{static::PRIMARY_KEY};
            $oStatement = \Library\Core\Database::dbQuery('UPDATE ' . static::TABLE_NAME . ' SET `' . implode('` = ?, `', $aUpdatedFields) . '` = ? WHERE `' . static::PRIMARY_KEY . '` = ?', $aUpdatedValues);
            $this->refresh();
        } catch (\PDOException $oException) {
            return false;
        }

        return ($this->bIsLoaded = true);
    }

    /**
     * Refresh object data from database
     * @return boolean TRUE if object was successfully refreshed, otherwise FALSE
     */
    public function refresh()
    {
        return $this->loadByPrimaryKey(false);
    }

    /**
     * Retrieve instance ID (primary key)
     * @return mixed Instance ID
     * @throws CoreEntityException
     */
    public function getId()
    {
        if (!$this->bIsLoaded) {
            throw new CoreEntityException('Cannot get ID of object not loaded');
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
    public static function isInCache($iId)
    {
        return (Cache::get(Cache::getKey(get_called_class(), $iId)) !== false);
    }

    /**
     * Load object using its primary key
     * @param   boolean $bUseCache Whether object caching must be used to retrieve data or not
     * @return  boolean TRUE if object was successfully loaded, otherwise FALSE
     * @throws  CoreEntityException
     */
    protected function loadByPrimaryKey($bUseCache = true)
    {
        if (!isset($this->{static::PRIMARY_KEY})) {
            throw new CoreEntityException('Cannot load object of class ' . get_called_class() . ' by primary key, no value provided for key ' . static::PRIMARY_KEY);
        }

        return $this->loadByQuery(
            'SELECT * FROM ' . static::TABLE_NAME . ' WHERE `' . static::PRIMARY_KEY . '` = ?',
            array($this->{static::PRIMARY_KEY}),
            $bUseCache,
            Cache::getKey(get_called_class(), $this->{static::PRIMARY_KEY})
        );
    }

    /**
     * Load the list of fields of the associated database table
     * @throws CoreEntityException
     */
    protected function loadFields()
    {
        $sCacheKey = Cache::getKey(__METHOD__, get_called_class());
        if (($this->aFields = Cache::get($sCacheKey)) === false) {
            if (($oStatement = \Library\Core\Database::dbQuery('SHOW COLUMNS FROM ' . static::TABLE_NAME)) === false) {
                throw new CoreEntityException('Unable to list fields for table ' . static::TABLE_NAME);
            }

            foreach ($oStatement->fetchAll(\PDO::FETCH_ASSOC) as $aColumn) {
                $this->aFields[$aColumn['Field']] = $aColumn;
            }

            Cache::set($sCacheKey, $this->aFields, false, Cache::CACHE_TIME_MINUTE);
        }
    }

    /**
     * Return type of data in function of database field type
     *
     * @param string $sName
     * @return string|null
     * @throws CoreEntityException
     */
    public function getDataType($sName = null) {

    	assert('$this->getFieldType($sName) !== null');

    	$sDataType = null;
    	if (!is_null($sName)) {

    		$sDataType = $this->getFieldType($sName);

			if (preg_match('#(^int|^integer|^tinyint|^smallmint|^mediumint|^tinyint|^bigint)#', $sDataType)) {
				$sDataType = 'integer';
			} elseif (preg_match('#(^float|^decimal|^numeric)#', $this->aFields[$sName]['Type'])) {
				$sDataType = 'float';
			} elseif (preg_match('#(^varchar|^text|^blob|^tinyblob|^tinytext|^mediumblob|^mediumtext|^longblob|^longtext|^date|^datetime|^timestamp)#', $this->aFields[$sName]['Type'])) {
				$sDataType = 'string';
			} elseif (preg_match('#^enum#', $this->aFields[$sName]['Type'])) {
				$sDataType = 'enum'; // @todo ajouter un type enum dans validator puis un inArray pour valider
			} else {
				throw new CoreEntityException(__CLASS__ . ' Unsuported database field type: ' . $this->aFields[$sName]['Type']);
			}
    	}
		return $sDataType;
    }

	/**
	 * Return database field type
	 *
	 * @param string $sName
	 * @return string
	 */
    protected function getFieldType($sName = '') {
		return (!empty($sName) && isset($this->aFields[$sName]['Type'])) ? $this->aFields[$sName]['Type'] : null;
    }

    /**
     * Validate data integrity for the database field
     *
     * @todo remettre la gestion des exceptions
     *
     * @param string $sFieldName
     * @param mixed string|int|float $mValue
     * @throws CoreEntityException
     * @return bool
     */
    protected function validateDataIntegrity($sFieldName, $mValue)
    {
    	assert('isset($this->aFields[$sFieldName]["Type"])');

    	$iValidatorStatus = 0;
    	$sDataType = '';

    	// @todo prendre en charge les variables nullables à ce niveau en fonctions des infos sur le champs mysql
    	// @todo Dépend d'une feature experimentale de PDO attendre la version stable
    	if (is_null($mValue) && $this->aFields[$sFieldName]['Null'] === 'YES') {
    		return true;
    	}

    	if (!empty($sFieldName) && !empty($mValue)) {
			if (
	            ($sDataType = $this->getDataType($sFieldName)) !== NULL &&
	            method_exists(__NAMESPACE__ . '\\Validator', $sDataType) &&
	            ($iValidatorStatus = Validator::$sDataType($mValue)) === Validator::STATUS_OK
			) {
				return true;
			}
    	}
		return false;
    }

	/**
	 * List all database tables
	 *
	 * @return \Library\Core\Collection
	 */
    protected function getDatabaseEntities()
    {
    	$aDatabaseEntities = array();
    	$aConfig = \Bootstrap::getConfig();

    	$oStatement = Database::dbQuery(
    		'SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE `TABLE_SCHEMA` = ? ORDER BY `TABLES`.`TABLE_SCHEMA` DESC',
    		array($aConfig['database']['name'])
		);
    	if ($oStatement !== false && $oStatement->rowCount() > 0) {
    	     $aDatabaseEntities = $oStatement->fetchAll(\PDO::FETCH_ASSOC);
    	}

    	return $aDatabaseEntities;
    }

}

class CoreEntityException extends \Exception {}

?>
