<?php

class core_mongoManager
{
    private static $_instance;
    private static $_sId = "mongoDB";
    
    protected $aConnection = array();
    protected $aConfig = array();

    /**
     * core_mongoManager::__construct()
     * Constructor
     * @return void
     */
    protected function __construct($sId) {
        global $_CONFIG;

        if (is_array($_CONFIG['DATABASE'][SERVER_ENV][$sId])) {
            $this->aConfig[$sId] = $_CONFIG['DATABASE'][SERVER_ENV][$sId];
            $this->aConnection = array();
        }
        else throw new Exception('Initialization error');
    }

    /**
     * @return core_mongo
     */
    public static function getInstance($sId) {
    
        if (!self::$_instance instanceof self) {
            self::$_instance = new self($sId);
        }
        return self::$_instance;
    }
    
    /**
     * core_mongoManager::setAConnection()
     * Try to connect to DB with configuration data
     * @param string $sId
     * @param array $aParameters
     * @param bool $_bSlaveOnly set slave(s) only if exist(s)
     * @return boolean
     * @throws Exception
     **/
    public static function setAConnection($sId, $aParameters = array()) {
    
        $oManager = self::getInstance($sId); /* @var $oManager PDOManager */
    
        if (is_string($sId) && is_array($oManager->aConnection)) {
    
            if (isset($oManager->aConfig[$sId])) {
    
                $aAccess = $oManager->aConfig[$sId];
    
                // Connect master
                $oManager->aConnection[$sId] = $oManager->setConnection($aAccess, $aParameters);
            }
            else
                return false;
        }
        return true;
    }
    
    /**
     * Set a connection
     * @param array $aAccess connection array config
     * @param array $aParameters
     * @return MongoDB 
     * @throws Exception
     */
    public static function setConnection($aAccess, $aParameters = array()) {
    
        if (!is_array($aAccess))
            throw new PDOException("given database config is not valid");
        else {
            try {
                $aOptions =  array("connect" => TRUE);
                external\FirePHPCore\fb::warn("Set a DB connection to ".$aAccess['host']);
                // $sDSN = sprintf('%s://%s:%s/%s', strtolower($aAccess['driver']), $aAccess['host'], $aAccess['port'], $aAccess['database']);
                $sDSN = sprintf('%s://%s:%s@%s:%s/%s', strtolower($aAccess['driver']), $aAccess['login'], $aAccess['password'], $aAccess['host'], $aAccess['port'], $aAccess['database']);
                $oMongoClient = new MongoClient($sDSN, $aOptions);
                
                return $oMongoClient->selectDB($aAccess['database']);
            }
            catch (PDOException $e) {
                // If the first attempt to connect failed, a second one is attempted
                try {
                    sleep(1);
                    
                    return $oMongoClient->selectDB($aAccess['database']);
                }
                catch (Exception $e) { throw $e; }
            }
        }
    }
    
    /**
     * Get a mongo DB connection
     * @param string $sId
     * @return MongoDB $oMongoDB
     * @throws Exception
     */
    public static function getConnection ($sId) {
        
        if (is_string($sId)) {
            $oManager = self::getInstance($sId);
            
            if ($oManager->isConnectionExists($sId)) {
                return $oManager->aConnection[$sId];
            }
            else {
                if ($oManager->setAConnection($sId))
                    return $oManager->aConnection[$sId];
                else
                    throw new Exception("Unknow database access '".$sId."'");
            }
        }
        throw new Exception("Unknow database access '".$sId."'");
    }
    
    /**
     * core_mongoManager::isConnectionExists()
     * Verify if mongo Connection exists
     * @param string $sId PDO connection id
     * @return boolean true if exists
     */
    public static function isConnectionExists($sId) {
    
        $bReturn = false;
    
        if (is_string($sId) || is_int($sId)) {
            if (isset(self::getInstance($sId)->aConnection[$sId])) {
                $bReturn = true;
            }
        }
        return $bReturn;
    }
    
    public static function executeInsert ($sCollectionName, $mParams, $aOptions=array()) {
        
        $oMongoDB = self::getConnection(self::$_sId); /* @var $oMongoDB MongoDB */
        $oMongoCollection = $oMongoDB->selectCollection($sCollectionName);
        
        return $oMongoCollection->insert($mParams, $aOptions);
    }
    
    public static function executeSelect ($sCollectionName, $aParams, $sLimit='') {
    
        $mReturn = NULL;
    
        if (is_string(self::$_sId)) {
    
            // Retrieve a connection
            $oMongoDB = self::getConnection(self::$_sId); /* @var $oMongoDB MongoDB */
            $oMongoCollection = $oMongoDB->selectCollection($sCollectionName);

            $mReturn = $oMongoCollection->find($aParams);
    
            //             $aErrorInfo = $oPDO->errorInfo();
            //             if (self::hasError($aErrorInfo)) {
            //                 $sqlError = sprintf('%s - %s', $aErrorInfo[1], $aErrorInfo[2]);
            //                 throw new PDOException('QUERY ERROR ['.$sId.'] : ('.$sqlError.') '.$sRequest);
            //             }
        }
        else throw new UnexpectedValueException("Expected a valid database access (".self::$_sId.")");
    
        return $mReturn;
    }
    
    public static function findOneResult ($sCollectionName, $aParams, $sLimit='') {
    
        $mReturn = NULL;
    
        if (is_string(self::$_sId)) {
    
            // Retrieve a connection
            $oMongoDB = self::getConnection(self::$_sId); /* @var $oMongoDB MongoDB */
            $oMongoCollection = $oMongoDB->selectCollection($sCollectionName);
            $mReturn = $oMongoCollection->findOne($aParams);
        }
        else throw new UnexpectedValueException("Expected a valid database access (".self::$_sId.")");
    
        return $mReturn;
    }
}