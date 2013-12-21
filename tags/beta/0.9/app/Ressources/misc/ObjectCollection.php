<?php

/**
 * NOTE: no cache here, cache is handled in objects instances
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-15 - Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class core_ObjectCollection extends core_BaseCollection
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
     * Constructor
     * @param array $aIds IDs of the elements to instanciate
     */
    public function __construct(array $aIds = array())
    {
        $this->sChildClass = preg_replace('/^(db_.*)Collection$/', '$1', get_called_class());

        if (!empty($aIds)) {
            $this->loadByIds($aIds);
        }
    }

    /**
     * Load collection regarding given IDs
     * @param array $aIds List of IDs
     */
    public function loadByIds($aIds)
    {
        assert('!empty($aIds)');

        if (is_null(constant($this->sChildClass . '::TABLE_NAME'))) {
            throw new CoreObjectException('CoreObject class table name not defined for class ' . $this->sChildClass);
        }

        if (is_null(constant($this->sChildClass . '::PRIMARY_KEY'))) {
            throw new CoreObjectException('CoreObject class primary key not defined for class ' . $this->sChildClass);
        }

        $this->aOriginIds = $aIds;
        $aCachedObjects = $this->getCachedObjects($aIds);
        $aUncachedObjects = array_values(array_diff($aIds, $aCachedObjects));

        foreach ($aCachedObjects as $iObjectId) {
            $oObject = new $this->sChildClass($iObjectId);
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

        uksort($this->aElements, array($this, 'sortElements'));
    }

    /**
     * Load collection regarding values and ordering parameters
     * @param array $aParameters List of parameters name/value
     * @param array $aOrderFields List of order fields/direction
     * @param integer $iLimit Maximum number of rows
     * @throws CoreObjectException
     */
    public function loadByParameters(array $aParameters, array $aOrderFields = array(), $iLimit = null)
    {
        assert('is_null($iLimit) || validator::integer($iLimit, 1) === validator::STATUS_OK');

        if (empty($aParameters)) {
            throw new CoreObjectException('No parameter provided for loading collection of type ' . $this->sChildClass);
        }

        $sQuery = '
            SELECT *
            FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
            WHERE `' . implode('` = ? AND `', array_keys($aParameters)) . '` = ?
            ORDER BY ';

        if (empty($aOrderFields)) {
            $sQuery .= '`' . constant($this->sChildClass . '::PRIMARY_KEY') . '` DESC';
        } else {
            foreach ($aOrderFields as $sFieldName => $sOrder) {
                $sQuery .= '`' . $sFieldName . '` ' . $sOrder . ', ';
            }
            $sQuery = trim($sQuery, ', ');
        }

        if (!is_null($iLimit)) {
            $sQuery .= ' LIMIT ' . $iLimit;
        }

        $this->loadByQuery($sQuery, array_values($aParameters));
    }

    /**
     * Load collection regarding given SQL query and values
     * @param string $sQuery SQL query
     * @param array $aValues Values of paramters
     * @throws CoreObjectException
     */
    public function loadByQuery($sQuery, array $aValues = array())
    {
        try {
            $oStatement = sql::query($sQuery, $aValues);
        } catch (PDOException $oException) {
            throw new CoreObjectException('Unable to load collection of ' . $this->sChildClass . ' with query "' . $sQuery . '" and values ' . print_r($aValues, true));
        }

        if ($oStatement !== false) {
            foreach ($oStatement->fetchAll(PDO::FETCH_ASSOC) as $aObjectData) {
                $oObject = new $this->sChildClass();
                $oObject->loadByData($aObjectData);
                $this->add($oObject->getId(), $oObject);
            }
        }
    }

    /**
     * Delete all instances of collection
     * @throws CoreObjectException
     * @return boolean TRUE if instances were successfully deleted, otherwise FALSE
     */
    public function delete()
    {
        if ($this->count() > 0) {
            $bIsDeleteable = false;
            $oReflection = new ReflectionClass($this->sChildClass);

            foreach ($oReflection->getDefaultProperties() as $sPropertyName => $mDefaultValue) {
                if ($sPropertyName === 'bIsDeletable' && $mDefaultValue === true) {
                    $bIsDeleteable = true;
                    break;
                }
            }

            if (!$bIsDeleteable) {
                throw new CoreObjectException('Cannot delete collection of objects of type "' . $this->sChildClass . '", this type of object is not deletable');
            }

            try {
                $oStatement = sql::query('
                    DELETE FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
                    WHERE `' . constant($this->sChildClass . '::PRIMARY_KEY') . '` IN(?' . str_repeat(', ?', $this->count() - 1) . ')',
                    array_keys($this->aElements)
                );
                $this->reset();
            } catch (PDOException $oException) {
                return false;
            }
        }

        return true;
    }

    /**
     * Reset instance to its original state
     * @see core_BaseCollection::reset()
     */
    public function reset()
    {
        parent::reset();
        $this->aOriginIds = array();
    }

    /**
     * Retrieve the cached instances of objects
     * @param   array   $aIds IDs of cached objects to get
     * @return  array   Instances of cached objects
     */
    protected function getCachedObjects($aIds)
    {
        $aCachedObjects = array();
        foreach ($aIds as $iId)
        {
            if (call_user_func(array($this->sChildClass, 'isInCache'), $iId)) {
                $aCachedObjects[] = $iId;
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
    protected function sortElements($iFirstKey, $iSecondKey)
    {
        $aKeys = array_flip($this->aOriginIds);
        return ($aKeys[$iFirstKey] > $aKeys[$iSecondKey]) ? 1 : -1;
    }
}
