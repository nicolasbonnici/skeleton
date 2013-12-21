<?php

namespace  Library\Core;
/**
 * @package Core
 */
class Cookie
{
    /**
     * Tableau pour stocker les variables de cookie
     * @var array
     */
    private $aCookieVars = array();

    public function __construct()
    {
        $this->aCookieVars = $_COOKIE;
    }

    /**
     * Setter un ou plusieurs cookie(s)
     *
     * @param array $aCookieParams
     * @param int $iLifeTime
     * @param string $sPath
     * @return boolean
     */
    public function set($aCookieParams, $iLifeTime = 0, $sPath = '/')
    {
        $bWriteFlag = false;
        if (is_array($aCookieParams) && !empty($aCookieParams)) {
            foreach ($aCookieParams as $sKey=>$sValue) {
                if (! empty($sKey)) {
                    //var_dump($sKey, $sValue, $iLifeTime, $sPath);
                    setcookie($sKey, $sValue, $iLifeTime, $sPath);
                    $bWriteFlag = true;
                }
            }

        }

        return $bWriteFlag;
    }

    /**
     * Getter for a cookie var value
     *
     * @param type $sKeyName
     * @return mixed|null
     */
    public function get($sKeyName = NULL)
    {
    	if (is_null($sKeyName)) {
			return $this->aCookieVars;    		
    	}
    	
        if (!empty($sKeyName) && array_key_exists($sKeyName, $this->aCookieVars)) {
            return $this->aCookieVars[$sKeyName];
        }

        return false;
    }
}
