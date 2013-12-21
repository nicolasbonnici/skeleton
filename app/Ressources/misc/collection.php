<?php

/**
 * @package core
 */
class core_collection implements Iterator
{
    private $arrElements = array();
    private $classname;
    private $tri;

    public function __construct()
    {
        $this->arrElements = array();

        $arrArgs = func_get_args();
        $nbArgs = count($arrArgs);

        // new core_collection('db_user');
        if ($nbArgs == 1) {
            $this->classname = $arrArgs[0];
        }

        // new core_collection($r["ids"], 'db_user');
        elseif ($nbArgs == 2) {
            $listeIds = $arrArgs[0];
            $classe = $arrArgs[1];
            if($listeIds!=""){
                $arrids = explode(",", $listeIds);
                foreach($arrids as $id){
                    $this->arrElements[] = new $classe($id);
                }
            }
        } elseif ($nbArgs == 3 || $nbArgs == 4) {
            $aIdList    = $arrArgs[0];
            $sClass        = $arrArgs[1];
            $iCacheTime = $arrArgs[2];
            /// pour tapper sur le slace //
            $sCacheKey  = __METHOD__.implode('-', $aIdList);

            if (false === ($aSerializedElements = lmemc::get($sCacheKey))) {
                $oReflection    = new ReflectionClass($sClass);
                $sTableName     = $oReflection->getProperty('tablename')->getValue(new $sClass);
                $sKeyName       = $oReflection->getProperty('cle_primaire')->getValue(new $sClass);
                $aTmp           = array();
                $rResult        = core_sql::sql_query(
                    'SELECT * FROM ' . $sTableName . ' WHERE ' . $sKeyName . ' IN(' . implode(',', $aIdList) . ')',
                    DB_NAME,
                    false,
                    false,
                    (isset($arrArgs[3]) && $arrArgs[3] == true)
                );

                if ($rResult !== false && mysql_num_rows($rResult)) {
                    while ($aResult = mysql_fetch_assoc($rResult)) {
                        $aTmp[$aResult[$sKeyName]] = new $sClass($aResult[$sKeyName], $aResult);
                    }
                }

                foreach ($aIdList as $iId) {
                    if (isset($aTmp[$iId]) && $aTmp[$iId] instanceof $sClass) {
                        $this->add($aTmp[$iId]);
                    }
                }

                lmemc::set($sCacheKey, serialize($this->arrElements), false, $iCacheTime);
            } else {
                $this->arrElements = unserialize($aSerializedElements);
            }
        }
    }

    public function &get($mId)
    {
        if (isset($this->arrElements[$mId])) {
            return $this->arrElements[$mId];
        }

        return null;
    }

    public function get_elements()
    {
        $that = array();
        foreach($this->arrElements as $key => $val){
            if (is_object($val) && get_class($val) == 'core_collection') {

                $that[$key] = $val->get_elements();

            }
            else{
                $that[$key] = $val;
            }
        }
        //
        return $that;
    }

    public function add($oObject)
    {
        $this->arrElements[$oObject->id] = $oObject;
    }

    public function rewind()
    {
        reset($this->arrElements);
    }

    public function current()
    {
        return current($this->arrElements);
    }

    public function key()
    {
        return key($this->arrElements);
    }

    public function next()
    {
        return next($this->arrElements);
    }

    public function valid()
    {
        return ($this->current() !== false);
    }

    public function sort()
    {
        asort($this->arrElements);
    }

    public function rsort()
    {
        arsort($this->arrElements);
    }

    public function flush()
    {
        $this->arrElements = array();
    }

    public function removeElement($oElement)
    {
        $final = array();
        if(count($this->arrElements)){
            foreach ($this->arrElements as $key => $value) {
                if ($value->id != $oElement->id) {
                    $final[$key] = $value;
                }
            }
        }

        $this->arrElements = $final;
    }

    public function find_key($findthis)
    {
        if (in_array($findthis, $this->arrElements)) {
            foreach ($this->arrElements as $key => $value) {
                if ($value == $findthis) {
                    return $key;
                }
            }
        } else return false;
    }

    public function __toString()
    {
        if(count($this->arrElements)){
            foreach($this->arrElements as $obj){
                $keys[]=$obj->id;
            }
            return implode(",", $keys);
        }
        else return "";

    }

    public function objectToArray($lvl = 0)
    {
        $that = array();
        $lvl++;
        if($lvl>5){
            return $that;
        }
        else{
            foreach($this->arrElements as $key => $val){
                if (is_object($val)) {

                    $that[$key] = $val->objectToArray($lvl);

                }
                else{
                    $that[$key] = $val;
                }
            }
            //
            return $that;
        }

    }

    public function count()
    {
        return count($this->arrElements);
    }

    /**
     * Retrieve a bean collection representation of the query
     * @param string $sQuery
     * @param string $sBeanName the collection bean name
     */
    public static function getCollection ($sQuery, $sBeanName)
    {
        $oCollection = null;

        if (is_string($sQuery) && !empty($sQuery) && is_string($sBeanName) && !empty($sBeanName)) {
            $res = core_sql::sql_query($sQuery);
            if ($res !== false) {

                if (mysql_num_rows($res)) {
                    $oCollection = new core_collection($sBeanName);

                    while ($aRow = mysql_fetch_row($res)) {
                        $oCollection->add( new $sBeanName($aRow[0]) );
                    }
                }
            }
        }
        return $oCollection;
    }
}
