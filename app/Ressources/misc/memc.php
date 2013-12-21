<?php

/**
 * @version 1.1.0 - 2013-07-09 - Antoine <antoine.preveaux@bazarchic.com>
 *              Add getKey() method
 */
class core_memc
{
    /**
     * Predifened constants for easier use/reading of cache time durations
     * @var integer
     */
    const CACHE_TIME_MINUTE = 60;
    const CACHE_TIME_HALF_HOUR = 180;
    const CACHE_TIME_HOUR = 360;
    const CACHE_TIME_HALF_DAY = 43200;
    const CACHE_TIME_DAY = 86400;

    /**
     * Cache keys prefix (used to differenciate cache keys when several sites are on the same server)
     * @var string
     */
    const PREFIX = 'Bazarchic';

    /**
     * Server configuration
     * @var array
     */
    static private $aConfig;

    /**
     * Memcache instance
     * @var Memcache
     */
    static private $oLocalMemcache;

    /**
     * Memcache instance
     * @var Memcache
     */
    static private $oRemoteMemcache;

    /**
     * Connect to available Memcache servers
     * @param string $sType Connector type (local or remote)
     */
    static private function connect($sType)
    {
        assert('in_array($sType, array("local", "remote"))');

        if ($sType === 'remote' && is_null(self::$oRemoteMemcache)) {
            self::loadConfig();
            self::$oRemoteMemcache = new Memcache;
            foreach (self::$aConfig as $aServer) {
                self::$oRemoteMemcache->addServer($aServer['host'], $aServer['port']);
            }
        }

        if ($sType === 'local' && is_null(self::$oLocalMemcache)) {
            self::$oLocalMemcache = new Memcache;
            self::$oLocalMemcache->addServer('localhost', 11211);
        }
    }

    /**
     * Loader servers configuration
     */
    static private function loadConfig()
    {
        if (!is_array(self::$aConfig)) {
            self::$aConfig = include CONF_DIR . '/environment/' . SERVER_ENV . '/memcache.php';
        }
    }

    /**
     * @see Memcache::get
     * @param string $sType Connector type (local or remote)
     */
    static public function get($sKey, $sType = 'remote')
    {
        assert('is_string($sKey) && strlen($sKey) < (250 - strlen(self::PREFIX) - 1)');

        if (isset($_GET['noCache']) && SERVER_ENV === 'dev') {
            return false;
        }

        if (isset($_GET['clearCache']) && SERVER_ENV === 'dev') {
            self::getConnector($sType)->delete(self::PREFIX.'-'.$sKey);
        }

        return self::getConnector($sType)->get(self::PREFIX . '-' . $sKey);
    }

    /**
     * @see Memcache::set
     * @param string $sType Connector type (local or remote)
     */
    static public function set($sKey, $mData, $mFlag = false, $iExpire = 120, $sType = 'remote')
    {
        return self::getConnector($sType)->set(self::PREFIX . '-' . $sKey, $mData, $mFlag, $iExpire);
    }

    /**
     * @see Memcache::delete
     * @param string $sType Connector type (local or remote)
    static public function delete($sKe, $sType = 'remote')
    {
        return self::getConnector($sType)->delete(self::PREFIX . '-' . $sKey);
    }

    /**
     * @see Memcache::flush
     * @param string $sType Connector type (local or remote)
     */
    static public function flush($sType = 'remote')
    {
        return self::getConnector($sType)->flush();
    }

    /**
     * Generate cache key depending on given parameters
     * Variable types "ressource" and "NULL" and "Unknow type" are not handled
     * If key string is more than 250 characters long, MD5 hash is retrieved (Memcache limitation)
     * @since 1.1.0
     * @return string Cache key
     */
    static public function getKey()
    {
        $sKey = '';
        foreach (func_get_args() as $mArgument) {
            switch (gettype($mArgument)) {
                case 'integer':
                case 'double':
                case 'string':
                    $sKey .= $mArgument . '-';
                    break;
                case 'boolean':
                    $sKey .= (string) $mArgument . '-';
                    break;
                case 'array':
                    $sKey .= call_user_func_array(array('self', 'getKey'), $mArgument) . '-';
                    break;
                case 'object':
                    $sKey .= serialize($mArgument) . '-';
                    break;
            }
        }

        if (strlen($sKey) > (250 - strlen(self::PREFIX) - 1)) {
            $sKey = md5($sKey);
        }

        return $sKey;
    }

    /**
     * Retrieve connector (local or remote)
     * @param string $sType Connector type (local or remote)
     * @return Memcache Connector instance
     */
    static private function getConnector($sType)
    {
        assert('in_array($sType, array("local", "remote"))');

        switch ($sType) {
            case 'remote':
                if (is_null(self::$oRemoteMemcache)) {
                    self::connect('remote');
                }
                return self::$oRemoteMemcache;

            case 'local':
            default:
                if (is_null(self::$oLocalMemcache)) {
                    self::connect('local');
                }
                return self::$oLocalMemcache;
        }
    }
}
