<?php

/**
 * NOTE: no cache here, cache is handled in objects instances
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-15 - Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class core_objectCollection extends core_baseCollection
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
    protected $aOriginIds;

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
            try {
                $oStatement = sql::query('
                    SELECT *
                    FROM ' . constant($this->sChildClass . '::TABLE_NAME') . '
                    WHERE ' . constant($this->sChildClass . '::PRIMARY_KEY') . ' IN(?' . str_repeat(', ?', count($aUncachedObjects) - 1) . ')',
                    $aUncachedObjects
                );
            } catch (PDOException $oException) {
                trigger_error('Unable to load collection of ' . $this->sChildClass . ' with IDs ' . implode(', ', $aUncachedObjects), E_USER_WARNING);
            }

            if ($oStatement !== false) {
                foreach ($oStatement->fetchAll(PDO::FETCH_ASSOC) as $aObjectData) {
                    $oObject = new $this->sChildClass();
                    $oObject->loadByData($aObjectData);
                    $this->add($oObject->getId(), $oObject);
                }
            }
        }

        uksort($this->aElements, array($this, 'sortElements'));
    }

    /**
     * Load collection regarding values and ordering parameters
     * @param array $aParameters List of parameters name/value
     * @param array $aOrderFields List of order fields/direction
     * @throws CoreObjectException
     */
    public function loadByParameters(array $aParameters, array $aOrderFields = array())
    {
        if (empty($aParameters)) {
            throw new CoreObjectException('No parameter provided for loading collection of type ' . $this->sChildClass);
        }

        if (empty($aOrderFields)) {
            $sOrderBy = '`' . constant($this->sChildClass . '::PRIMARY_KEY') . '` DESC';
        } else {
            $sOrderBy = '';
            foreach ($aOrderFields as $sFieldName => $sOrder) {
                $sOrderBy .= '`' . $sFieldName . '` ' . $sOrder . ', ';
            }
            $sOrderBy = trim($sOrderBy, ', ');
        }

        $this->loadByQuery('
            SELECT *
            FROM `' . constant($this->sChildClass . '::TABLE_NAME') . '`
            WHERE `' . implode('` = ? AND `', array_keys($aParameters)) . '` = ?
            ORDER BY ' . $sOrderBy,
            array_values($aParameters)
        );
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
