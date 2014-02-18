<?php

namespace core;

/**
 * NOTE: no cache here, cache is handled in objects instances
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-15 - Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class ObjectCollection extends \core\BaseCollection
{
    /**
     * Collection objects class name
     * @var string
     */
    protected $sChildClass;

    /**
     * IDs of elements to add to collection
     * @var array
     */
    protected $aOriginIds = array();

    /**
     * Object key used for sorting elements (@see \core\ObjectCollection::sort)
     * @var string
     */
    protected $sSortKey;

    /**
     * Type of values used for sorting elements
     * @var string
     */
    protected $sSortType;

    /**
     * Constructor
     * @param array $aIds IDs of the elements to instanciate
     */
    public function __construct(array $aIds = array())
    {
        $this->sChildClass = str_replace('\\collections\\', '\\', get_called_class());

        if (!empty($aIds)) {
            $this->loadByIds($aIds);
        }
    }

    /**
     * Load every available instances of the CoreObject class
     * @param string $sOrderBy Ordering field
     * @param string $sOrderDirection Ordering direction
     */
    public function loadAll($sOrderBy = null, $sOrderDirection = 'ASC')
    {
        assert('is_null($sOrderBy) || \\core\\Validator::string($sOrderBy, 1) === \\core\\Validator::STATUS_OK');
        assert('in_array($sOrderDirection, array("ASC", "DESC"))');

        if (is_null($sOrderBy)) {
            $sOrderBy = constant($this->sChildClass . '::PRIMARY_KEY');
        }

        $this->loadByQuery('
            SELECT *
            FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
            ORDER BY `' . $sOrderBy . '`  ' . $sOrderDirection
        );
    }

    /**
     * Load collection regarding given IDs
     * @param array $aIds List of IDs
     */
    public function loadByIds($aIds)
    {
        assert('!empty($aIds)');

        if (is_null(constant($this->sChildClass . '::TABLE_NAME'))) {
            throw new ObjectException('CoreObject class table name not defined for class ' . $this->sChildClass);
        }

        if (is_null(constant($this->sChildClass . '::PRIMARY_KEY'))) {
            throw new ObjectException('CoreObject class primary key not defined for class ' . $this->sChildClass);
        }

        $this->aOriginIds = $aIds;
        $aCachedObjects = $this->getCachedObjects($aIds);
        $aUncachedObjects = array_values(array_diff($aIds, array_keys($aCachedObjects)));

        foreach ($aCachedObjects as $iObjectId => $aCachedObject) {
            $oObject = new $this->sChildClass();
            $oObject->loadByData($aCachedObject, false);
            $this->add($oObject->getId(), $oObject);
        }

        if (!empty($aUncachedObjects)) {
            $this->loadByQuery('
                SELECT *
                FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
                WHERE `' . constant($this->sChildClass . '::PRIMARY_KEY') . '` IN(?' . str_repeat(', ?', count($aUncachedObjects) - 1) . ')',
                $aUncachedObjects
            );
        }

        uksort($this->aElements, array($this, 'sortElementsById'));
    }

    /**
     * Load collection regarding values and ordering parameters
     * @param array $aParameters List of parameters name/value
     * @param array $aOrderFields List of order fields/direction
     * @param string $sLimit Maximum number of rows
     * @throws \core\ObjectException
     */
    public function loadByParameters(array $aParameters, array $aOrderFields = array(), $sLimit = null)
    {
        assert('is_null($sLimit) || \\core\\Validator::integer($sLimit, 1) === \\core\\Validator::STATUS_OK || \\core\\Validator::string($sLimit, 1) === \\core\\Validator::STATUS_OK');

        if (empty($aParameters)) {
            throw new ObjectException('No parameter provided for loading collection of type ' . $this->sChildClass);
        }

        $sWhere = '';
        $aBindedValues = array();

        foreach ($aParameters as $sParameterName => $mParameterValue) {
            if (!empty($sWhere)) {
                $sWhere .= ' AND ';
            }
            // Enable using LOWER(), UPPER(), ...
            if (strpos($sParameterName, '(') === false) {
                $sWhere .= '`' . $sParameterName . '`';
            } else {
                $sWhere .= $sParameterName;
            }

            if (is_array($mParameterValue)) {
                $sWhere .= ' IN(?' . str_repeat(', ?', count($mParameterValue) - 1) . ')';
                $aBindedValues = array_merge($aBindedValues, $mParameterValue);
            } else {
                $sWhere .= ' = ?';
                $aBindedValues[] = $mParameterValue;
            }
        }

        $sQuery = '
            SELECT *
            FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
            WHERE ' . $sWhere . '
            ORDER BY ';

        if (empty($aOrderFields)) {
            $sQuery .= '`' . constant($this->sChildClass . '::PRIMARY_KEY') . '` DESC';
        } else {
            foreach ($aOrderFields as $sFieldName => $sOrder) {
                if (strpos($sFieldName, '(') === false) {
                    $sQuery .= '`' . $sFieldName . '` ' . $sOrder . ', ';
                } else {
                    $sQuery .= $sFieldName . ' ' . $sOrder . ', ';
                }
            }
            $sQuery = trim($sQuery, ', ');
        }

        if (!is_null($sLimit)) {
            $sQuery .= ' LIMIT ' . $sLimit;
        }

        $this->loadByQuery($sQuery, $aBindedValues);
    }

    /**
     * Load collection regarding given SQL query and values
     * @param string $sQuery SQL query
     * @param array $aValues Values of paramters
     * @throws \core\ObjectException
     */
    public function loadByQuery($sQuery, array $aValues = array())
    {
        try {
            $oStatement = \core\DB::query($sQuery, $aValues);
        } catch (\PDOException $oException) {
            throw new ObjectException('Unable to load collection of ' . $this->sChildClass . ' with query "' . $sQuery . '" and values ' . print_r($aValues, true));
        }

        if ($oStatement !== false) {
            foreach ($oStatement->fetchAll(\PDO::FETCH_ASSOC) as $aObjectData) {
                $oObject = new $this->sChildClass();
                $oObject->loadByData($aObjectData, false); // Second parameter may be set to true if we want cache to be refreshed on collection loading. It may lead to a performance issue with too many calls to \core\Cache::set
                $this->add($oObject->getId(), $oObject);
            }
        }
    }

    /**
     * Delete all instances of collection
     * @throws \core\ObjectException
     * @return boolean TRUE if instances were successfully deleted, otherwise FALSE
     */
    public function delete()
    {
        if ($this->count() > 0) {
            $bIsDeleteable = false;
            $oReflection = new \ReflectionClass($this->sChildClass);

            foreach ($oReflection->getDefaultProperties() as $sPropertyName => $mDefaultValue) {
                if ($sPropertyName === 'bIsDeletable' && $mDefaultValue === true) {
                    $bIsDeleteable = true;
                    break;
                }
            }

            if (!$bIsDeleteable) {
                throw new ObjectException('Cannot delete collection of objects of type "' . $this->sChildClass . '", this type of object is not deletable');
            }

            try {
                $oStatement = \core\DB::query('
                    DELETE FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
                    WHERE `' . constant($this->sChildClass . '::PRIMARY_KEY') . '` IN(?' . str_repeat(', ?', $this->count() - 1) . ')',
                    array_keys($this->aElements)
                );
                $this->reset();
            } catch (\PDOException $oException) {
                return false;
            }
        }

        return true;
    }

    /**
     * Reset instance to its original state
     * @see \core\BaseCollection::reset()
     */
    public function reset()
    {
        parent::reset();
        $this->aOriginIds = array();
    }

    /**
     * Extended in order to prevent sorting objects which does not makes sense
     * @see \core\BaseCollection::sort()
     * @throws ObjectException
     */
    public function sort()
    {
        throw new ObjectException('\core\ObjectCollection could not be sorted using this method, use "sortByKey"');
    }

    /**
     * Sort collection elements according to values of given key
     * Type of value must be specified (@see \core\ObjectCollection::sortCallback)
     * @param string $sSortKey Key of values to use
     * @param string $sSortType Type of values
     * @return boolean TRUE on success, otherwise FALSE
     */
    public function sortByKey($sSortKey, $sSortType)
    {
        $this->sSortKey = $sSortKey;
        $this->sSortType = $sSortType;

        return uasort($this->aElements, array($this, 'sortCallback'));
    }

    /**
     * Retrieve the cached instances of objects
     * @param   array   $aIds IDs of cached objects to get
     * @return  array   Instances of cached objects
     */
    protected function getCachedObjects($aIds)
    {
        $aCachedObjects = array();
        foreach ($aIds as $iId) {
            if (($aCachedObject = call_user_func(array($this->sChildClass, 'getCached'), $iId)) !== false) {
                $aCachedObjects[$iId] = $aCachedObject;
            }
        }
        return $aCachedObjects;
    }

    /**
     * Sort collection elements according to collection call order
     * @param   integer $iFirstKey  First element key
     * @param   integer $iSecondKey Second element key
     * @return  integer 1 if first element is after second element, otherwise -1
     */
    protected function sortElementsById($iFirstKey, $iSecondKey)
    {
        $aKeys = array_flip($this->aOriginIds);
        return ($aKeys[$iFirstKey] > $aKeys[$iSecondKey]) ? 1 : -1;
    }

    /**
     * Callback method used to sort elements by key values
     * @see \core\ObjectCollection::sortByKey
     * @param object $oFirstElement First element to compare
     * @param object $oSecondElement Second element to compare
     * @return boolean|number Result of comparaison
     * @throws ObjectException
     */
    protected function sortCallback($oFirstElement, $oSecondElement)
    {
        switch ($this->sSortType) {
            case 'integer':
                return (int) $oFirstElement->{$this->sSortKey} > (int) $oSecondElement->{$this->sSortKey};
            case 'boolean':
                return (bool) $oFirstElement->{$this->sSortKey} > (bool) $oSecondElement->{$this->sSortKey};
            case 'float':
                return (float) $oFirstElement->{$this->sSortKey} > (float) $oSecondElement->{$this->sSortKey};
            case 'string':
                return strcasecmp((string) $oFirstElement->{$this->sSortKey}, (string) $oSecondElement->{$this->sSortKey});
            default:
                throw new ObjectException('Type of values not supported for sorting collection elements');
        }
    }
}
