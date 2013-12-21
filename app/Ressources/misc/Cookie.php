<?php

/**
 * @package core
 */
class core_Cookie
{
    /**
     * Tableau pour stocker les variable de cookie
     * @var array
     */
    private $aCookieVars = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aCookieVars = $_COOKIE;
    }

    /**
     * Retrieve cookies values
     *
     * @return array List of set cookies
     */
    public function getVars()
    {
        return $this->aCookieVars;
    }

    /**
     * Retrieve a cookie value
     *
     * @param string $sKeyName Cookie name
     * @return string Cookie value otherwise NULL
     */
    public function get($sKeyName)
    {
        if (!empty($sKeyName) && array_key_exists($sKeyName, $this->aCookieVars)) {
            return $this->aCookieVars[$sKeyName];
        }

        return null;
    }

    /**
     * Set a cookie
     *
     * @param string $sName Cookie name
     * @param string $sValue Cookie value
     * @param integer $iLifetime Cookie lifetime
     * @param string $sPath Registration path
     * @return boolean TRUE if cookie was successfully set, otherwise FALSE
     */
    public function set($sName, $sValue, $iLifetime = 0, $sPath = '/')
    {
        assert('validator::string($sName, 1) === validator::STATUS_OK && validator::string($sValue, 1) === validator::STATUS_OK');
        if (!empty($sName)) {
            return setcookie($sName, $sValue, $iLifetime, $sPath, COOKIEDOMAINE);
        }
        return false;
    }

    /**
     * Delete cookie
     *
     * @param string $sName Cookie name
     * @param string $sPath Cookie path
     * @return boolean TRUE if cookie was deleted, otherwise FALSE
     */
    public function delete($sName, $sPath = '/')
    {
        assert('validator::string($sName, 1) === validator::STATUS_OK');
        return setcookie($sName, '', time() - 3600, $sPath, COOKIEDOMAINE);
    }
}
