<?php

include_once dirname(__FILE__) . '/../../libs/configs/defined.php';

/**
 * Database connection management
 * NOTE: cache should not be implemented here but in upper layers (models and/or controllers)
 * @author  Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-08 - Antoine <antoine.preveaux@bazarchic.com>
 */
class sql
{
    /**
     * Slave and Master connections
     * @var PDO
     */
    private static $master;
    private static $slave;

    /**
     * Database configurations
     * @var array
     */
    private static $aConfig = array();

    /**
     * Total number of available slave servers
     * @var integer
     */
    private static $iSlavesCount = 1;

    /**
     * Last query's link (master, slave)
     * @var string
     */
    private static $sLastLink;

    /**
     * Last successful query statement
     * @var PDOStatement
     */
    private static $oLastStatement;

    /**
     * Errors list
     * @var array
     */
    public static $aErrors = array();

    /**
     * Benchmark information
     * @var array
     */
    public static $aBenchmark = array(
        'master' => array(
            'time'          => 0.0,
            'queries_count' => 0,
            'queries_list'  => array()
        ),
        'slave' => array(
            'time'          => 0.0,
            'queries_count' => 0,
            'queries_list'  => array()
        )
    );

    /**
     * Execute an SQL query
     * @param   string  $sQuery         SQL query to execute
     * @param   array   $aValues        Binded values
     * @param   boolean $bForceMaster   Whether query should be executed on master or not
     * @return  PDOStatement|boolean    Query's result PDO statement
     */
    public static function query($sQuery, array $aValues = array(), $bForceMaster = false)
    {
        assert('is_string($sQuery)');
        assert('is_bool($bForceMaster)');

        $sLink = ($bForceMaster || self::isMasterQuery($sQuery)) ? 'master' : 'slave';

        try {
            if (!isset(self::${$sLink})) {
                self::connect($sLink);
            }

            $fStart = microtime(true);
            $oStatement = self::${$sLink}->prepare($sQuery, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
            $oStatement->execute($aValues);

            // Must be after query successful execution
            self::$sLastLink = $sLink;
            self::$aBenchmark[$sLink]['time'] += microtime(true) - $fStart;
            self::$aBenchmark[$sLink]['queries_count']++;
            self::$aBenchmark[$sLink]['queries_list'][] = $sQuery;

            return $oStatement;
        } catch (Exception $oException) {
            self::$aErrors[] = array(
                'query'     => $sQuery,
                'server'    => $sLink,
                'error'     => $oException->getMessage()
            );

            if ((!defined('SERVER_ENV') || SERVER_ENV === 'prod') && self::$aBenchmark[$sLink]['queries_count'] < 50) {
                global $_TP;
                @mail(
                    'cyril@bazarchic.com',
                    'erreur SQL PROD',
                    'Errors: ' . print_r(self::$aErrors, true) .
                    (isset($_TP) ? '$_TP: ' . print_r($_TP, true) : '') .
                    '$_SERVER: ' . print_r($_SERVER, true) .
                    '$_GET: ' . print_r($_GET, true) .
                    '$_POST: ' . print_r($_POST, true) .
                    '$_FILES: ' . print_r($_FILES, true) .
                    '$_COOKIE: ' . print_r($_COOKIE, true)
                );
            } elseif (defined('SERVER_ENV') && SERVER_ENV === 'dev') {
                echo $oException->getMessage();
            }
            return false;
        }
    }

    /**
     * Execute an SQL query on master link
     * @see sql::query
     */
    public static function master($sQuery, array $aValues = array())
    {
        return self::query($sQuery, $aValues, true);
    }

    /**
     * Retrieve last inserted ID
     * @see PDO::lastInsertId
     */
    public static function lastInsertId()
    {
        if (!isset(self::$sLastLink)) {
            return '0';
        }
        return self::${self::$sLastLink}->lastInsertId();
    }

    /**
     * Check whether query execution must be forced on master or not
     * @param   string  $sQuery SQL query
     * @return  boolean         TRUE if query execution must be forced on master, otherwise FALSE
     */
    private static function isMasterQuery($sQuery)
    {
        return (
            preg_match('/insert|update|delete|stock|tmp|avoirs|paiements|remboursements|membres_sessions|membres|basket|prepa|users_session/i', $sQuery)
            || self::isBackOffice()
        );
    }

    /**
     * Check whether query is done from back office tool or not
     * TODO: move to another more generic class because this test may be useful in other cases
     * @return boolean TRUE if query is made from back office, otherwise FALSE
     */
    private static function isBackOffice()
    {
        global $_TP;

        return (isset($_TP['user']['iduser']) && $_TP['user']['iduser'] > 0);
    }

    /**
     * Connect given link database
     * @param string $sLink Database link (master or slave)
     */
    private static function connect($sLink)
    {
        assert('is_string($sLink)');

        $aConfig = self::getConfig();

        if (!isset($aConfig['hosts'][$sLink])) {
            throw new Exception('Unable to load server configuration');
        }

        self::${$sLink} = new PDO(
            $aConfig['driver'] . ':dbname=' . $aConfig['database'] . ';host=' . $aConfig['hosts'][$sLink][array_rand($aConfig['hosts'][$sLink])],
            $aConfig['username'],
            $aConfig['password']
        );
        self::${$sLink}->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Retrieve configuration for given server
     * @return array DB configuration
     */
    private static function getConfig()
    {
        if (empty(self::$aConfig)) {
            self::$aConfig = include_once CONF_DIR . '/environment/' . SERVER_ENV . '/sql.php';
        }

        return self::$aConfig;
    }
}

/**
 * @deprecated: replaced by sql class that uses PDO
 * Do not use this class anymore
 */
class core_sql
{
    private static $query; // dernière requête exécutée
    private static $arrConnections = array(); // connexions établies

    public static function sql_link($linkName)
    {
        global $FSvnServer, $FSvnUser, $FSvnPassword, $FSvnDatabase, $nb_connection, $time_connection;

        if ($linkName) {
            if (self::$arrConnections[$linkName]) {
                return self::$arrConnections[$linkName];
            } elseif ($FSvnServer[$linkName] && $FSvnUser[$linkName] && $FSvnPassword[$linkName] && $FSvnDatabase[$linkName]) {
                $t0 = microtime(true);
                if (self::$arrConnections[$linkName] = mysql_connect($FSvnServer[$linkName], $FSvnUser[$linkName], $FSvnPassword[$linkName])) {
                    if (mysql_select_db($FSvnDatabase[$linkName], self::$arrConnections[$linkName])){
                        // Bug fix
                        mysql_set_charset('utf-8');

                        $nb_connection++;
                        $time_connection += microtime(true) - $t0;
                        return self::$arrConnections[$linkName];
                    } else {
                        self::CheckSQLError();
                    }
                } else {
                    throw new Exception ("Erreur lors de la connexion à $linkName.");
                }
            } else {
                throw new Exception (
                    "$linkName n'est pas configuré dans le fichier access.php.<br>" .
                    "server : '" . $FSvnServer[$linkName] . "'," .
                    "user : '" . $FSvnUser[$linkName] . "'," .
                    "pwd : '" . $FSvnPassword[$linkName] . "'," .
                    "db : '" . $FSvnDatabase[$linkName]
                );
            }
        } else {
            throw new Exception ('Vous devez indiquer le nom de la connexion.');
        }
        return false;
    }

    private static function CheckSQLError($addText)
    {
        if (mysql_errno() > 0) {
            echo "<br><br><font style='color:red;'>Erreur lors de la derniÃ¨re requete SQL : <b>".(mysql_errno())."</b> ".(mysql_error()).".</font><br>$addText<br>";
            return true;
        } else {
            return false;
        }
    }

    public static function sql_query($query, $linkName = DB_NAME, $quiet = false, $debug = false, $slave = false)
    {
        if (!preg_match("/insert|update|delete|membres_sessions|tmp_stock|tmp_basket|commandes|clients|prepa|users_session/i", $query) && strtolower(substr(trim($query), 0, 6)) === 'select') {
            $slave = true;
        }

        if ($slave === 'true') {
            return tp_query($query, 'slave', true);
        }  else {
            return tp_query($query);
        }
    }

    public static function sql_cached_query($query, $linkName, $cache_key, $cache_method = 'lmemc', $cache_expire = 3600, $slave = 'false')
    {
        $arr_result = lmemc::get('sql-cache-'.$cache_key);

        if ($arr_result) {
            return unserialize($arr_result);
        } else {
            $result = self::sql_query($query, $linkName,false,false,$slave);
            $arr_result = array();
            while ($data = mysql_fetch_assoc($result)) {
                $arr_result[] = $data;
            }
            lmemc::set('sql-cache-'.$cache_key, serialize($arr_result), false, $cache_expire);
            return $arr_result;
        }
    }

    public static function sql_insert_id($link)
    {
        return mysql_insert_id(sql::sql_link($link));
    }

    /**
     * Generate a WHERE clause with a given array of values
     * @param array $_aArrayParameters array of parameters
     * Can use variable prefix to define a variable type
     * i => int
     * f => float
     * d => date
     * s => string
     * @example
     *     array( 'iField' => 1, 'sName' => array('A', 'B'), 'dDate' => array(
     * @param array $aAmbigousField array of ambigous fields with prefix to add
     * @param bool $_addWhere add 'WHERE' if true
     * @param bool $_addDoubleQuotesToField add double quotes to field name
     * @param bool $_keepFieldType keep char type in field name
     */
    public static function generateWHERE($_aArrayParameters, $_aAmbigousField = array(), $_addWhere = true, $_addDoubleQuotesToField = false, $_keepFieldType = false)
    {
        $sReturn = '';

        if (count($_aArrayParameters) > 0) {
            $aWhere = array();

            // Creating where clause with given parameters and value
            foreach ($_aArrayParameters as $sField => $mValue) {
                if (!is_numeric($sField)) {
                    if (preg_match('/^([difms])([a-zA-Z0-9]\w*)(.?)$/', (string)$sField, $aMatches)) {
                        $bNegation = ($aMatches[3] == '!') ? true : false;
                        $sField = $aMatches[2];
                        if ($_keepFieldType) $sField = $aMatches[1].$sField;
                        if ($_addDoubleQuotesToField) $sField = '"'.$sField.'"';
                        switch ($aMatches[1]) {
                            case 'i':
                            case 'f':
                                $aWhere[] = $sField.((is_array($mValue)) ? (($bNegation) ? ' NOT ' : '' ).' IN ('.implode(',', $mValue).')' : (($bNegation) ? ' <> ' : ' = ' ).$mValue);
                                break;

                            case 'd':
                                if (is_array($mValue) && (count($mValue) > 1))
                                    $aWhere[] = $sField.(($bNegation) ? ' NOT ' : '' )." BETWEEN '".implode("' AND '", $mValue)."'";
                                else
                                    $aWhere[] = $sField.(($bNegation) ? " != " : " = " ).self::quote($mValue);
                                break;

                            case 'm':
                            case 's':
                            default:
                                if (is_array($mValue)) $mValue = array_map('addslashes', $mValue); // Add slashes if needed
                                $aWhere[] = $sField.((is_array($mValue)) ? (($bNegation) ? ' NOT ' : '' )." IN ('".implode("','", $mValue)."')" : (($bNegation) ? ' <> ' : ' = ' ).self::quote($mValue));
                                break;
                        }
                    } elseif (preg_match('/^([a-zA-Z0-9]\w*)(.?)$/', (string)$sField, $aMatches)) {
                        $bNegation = ($aMatches[2] == '!') ? true : false;
                        $sField = $aMatches[1];
                        if ($_addDoubleQuotesToField) $sField = '"'.$sField.'"';
                        $aWhere[] = $sField.((is_array($mValue)) ? (($bNegation) ? ' NOT ' : '' )." IN ('".implode("','", $mValue)."')" : " = ".self::quote($mValue));
                    } else {
                        if ($_addDoubleQuotesToField) $sField = '"'.$sField.'"';
                        $aWhere[] = $sField.((is_array($mValue)) ? " IN ('".implode("','", $mValue)."')" : " = ".self::quote($mValue));
                    }
                } else {
                    $aWhere[] = (string)$mValue;
                }
            }

            $sReturn = (($_addWhere) ? ' WHERE ' : '').implode(' AND ', $aWhere);

            // Correction of ambigous field
            foreach ($_aAmbigousField as $sField => $sPrefix) {
                $sReturn = preg_replace("/\b$sField\b/", $sPrefix.'.'.$sField, $sReturn);
            }
        }

        return $sReturn;
    }

    /**
     * Quotes a string for use in a query
     * NOTE: use addslashes() method
     * @param string $_sString string to quote
     * @return string
     */
    public static function quote($_sString)
    {
        return "'".addslashes($_sString)."'";
    }
}
