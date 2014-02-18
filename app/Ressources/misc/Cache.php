<?php

namespace core;

/**
 * Caching management class
 * @author Antoine <antoine.preveaux@bazarchic.com>
 */
abstract class Cache
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
    private static $aConfig;

    /**
     * Memcache instance
     * @var \Memcache
     */
    private static $oLocalMemcache;

    /**
     * Memcache instance
     * @var array
     */
    private static $aRemoteMemcaches = array();

    /**
     * Initialiaze cache instance
     */
    public static function init()
    {
        self::loadConfig();
        self::connectLocal();
        self::connectRemotes();
    }

    /**
     * Connect to local Memcache server
     */
    private static function connectLocal()
    {
        self::$oLocalMemcache = new \Memcache;
        self::$oLocalMemcache->addServer('localhost', 11211);
    }

    /**
     * Connect to available Memcache servers
     */
    private static function connectRemotes()
    {
        foreach (self::$aConfig as $aServer) {
            $oMemcache = new \Memcache;
            $oMemcache->addServer($aServer['host'], $aServer['port']);
            self::$aRemoteMemcaches[] = $oMemcache;
        }
    }

    /**
     * Loader servers configuration
     */
    private static function loadConfig()
    {
        if (! is_array(self::$aConfig)) {
            self::$aConfig = \core\Config::get('memcache');
        }
    }

    /**
     * @see Memcache::get
     */
    public static function get($sKey)
    {
        assert('is_string($sKey) && strlen($sKey) < (250 - strlen(self::PREFIX) - 1)');

        if (isset($_GET['noCache']) && \core\Utils::isInternal()) {
            return false;
        }

        if (isset($_GET['clearCache']) && \core\Utils::isInternal()) {
            self::delete($sKey);
        }

        return self::$oLocalMemcache->get(self::PREFIX . '-' . $sKey);
    }

    /**
     * @see Memcache::set
     * Returns false if there was an error with one of the servers
     */
    public static function set($sKey, $mData, $iExpire = 120)
    {
        $bReturn = self::$oLocalMemcache->set(self::PREFIX . '-' . $sKey, $mData, 0, $iExpire);
        if (self::isClusterRequest()) {
            foreach (self::$aRemoteMemcaches as $oMemcache) {
                $bReturn = ($bReturn && $oMemcache->set(self::PREFIX . '-' . $sKey, $mData, 0, $iExpire));
            }
        }
        return $bReturn;
    }

    /**
     * @see Memcache::delete
     */
    public static function delete($sKey)
    {
        $bReturn = self::$oLocalMemcache->delete(self::PREFIX . '-' . $sKey);
        foreach (self::$aRemoteMemcaches as $oMemcache) {
            $bReturn = ($bReturn && $oMemcache->delete(self::PREFIX . '-' . $sKey));
        }
        return $bReturn;
    }

    /**
     * @see Memcache::flush
     */
    public static function flush()
    {
        $bReturn = self::$oLocalMemcache->flush();
        foreach (self::$aRemoteMemcaches as $oMemcache) {
            $bReturn = ($bReturn && $oMemcache->flush());
        }
        return $bReturn;
    }

    /**
     * Generate cache key depending on given parameters
     * Variable types "ressource" and "NULL" and "Unknow type" are not handled
     * If key string is more than 250 characters long, MD5 hash is retrieved (Memcache limitation)
     * @since 1.1.0
     * @return string Cache key
     */
    public static function getKey()
    {
        $sKey = '';
        foreach (func_get_args() as $mArgument) {
            switch (gettype($mArgument)) {
                case 'integer':
                case 'double':
                case 'string':
                case 'boolean':
                    $sKey .= $mArgument . '-';
                    break;
                case 'array':
                    $sKey .= json_encode($mArgument) . '-';
                    break;
                case 'object':
                    $sKey .= serialize($mArgument) . '-';
                    break;
            }
        }

        if (strlen($sKey) > 240) { // = 250 - strlen(self::PREFIX) - 1
            $sKey = md5($sKey);
        }

        return $sKey;
    }

    /**
     * Check whether request must be done on every Memcache servers or only on local server (set, delete, flush)
     * @return boolean TRUE if request must be executed on all servers, otherwise FALSE
     */
    private static function isClusterRequest()
    {
        return (MODULE === 'world');
    }
}
