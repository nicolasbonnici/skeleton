<?php

/**
 * @todo: nécessite memcache >= 3 pour session_redundancy
 */
$sPath = '';
$aMemcacheConfig = include CONF_DIR . '/environment/' . SERVER_ENV . '/memcache.php';

foreach ($aMemcacheConfig as $aServer) {
    $sPath .= 'tcp://' . $aServer['host'] . ':' . $aServer['port'] . '?persistent=1&weight=1&timeout=1&retry_interval=15,';
}

ini_set('session.save_handler',             'memcache');
ini_set('session.cookie_domain',            COOKIEDOMAINE);
ini_set('session.save_path',                substr($sPath, 0, strlen($sPath) - 1));
ini_set('session.cache_expire',             BASKET_LIFETIME);
ini_set('session.gc_maxlifetime',           BASKET_LIFETIME * 60);
ini_set('session.cookie_httponly',          '1');
ini_set('session.hash_bits_per_character',  '4');
ini_set('memcache.hash_strategy',           'consistent');
ini_set('memcache.allow_failover',          '1');
ini_set('memcache.session_redundancy',      count($aMemcacheConfig) + 1); // http://serverfault.com/questions/164350/can-a-pool-of-memcache-daemons-be-used-to-share-sessions-more-efficiently#336592

session_set_cookie_params(BASKET_LIFETIME * 60, '/', COOKIEDOMAINE);
session_start();
