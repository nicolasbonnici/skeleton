<?php

$oSessionHandler = new DatabaseSessionHandler();
session_set_save_handler(
    array($oSessionHandler, 'open'),
    array($oSessionHandler, 'close'),
    array($oSessionHandler, 'read'),
    array($oSessionHandler, 'write'),
    array($oSessionHandler, 'destroy'),
    array($oSessionHandler, 'gc')
);

/**
 * Gestionnaire de sessions PHP basé sur la BDD
 * Permet de partager les sessions entre plusieurs serveurs
 *
 * @author  Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-06-21 - Antoine <antoine.preveaux@bazarchic.com>
 *
 * CREATE TABLE IF NOT EXISTS `sessions` (
 * `session_id` varchar(32) NOT NULL DEFAULT '',
 * `session_data` text NOT NULL,
 * `session_expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 * PRIMARY KEY (`session_id`)
 * );
*/
class DatabaseSessionHandler
{
    /**
     * Instance de connexion BDD
     * @var resource
     */
    protected $oDatabase;

    /**
     * Constructeur
     * @see SessionHandler::__construct()
     */
    public function __construct()
    {
        $this->oDatabase = mysql_connect('10.0.1.70', 'bazar', 'Tz#54!g');
        mysql_select_db('bazarshop_base', $this->oDatabase);
    }

    /**
     * @see SessionHandler::open()
     */
    public function open($sSavePath, $sName)
    {

    }

    /**
     * @see SessionHandler::close()
     */
    public function close()
    {

    }

    /**
     * @see SessionHandler::read()
     */
    public function read($sSessionId)
    {
        $sQuery = "
        SELECT session_data FROM sessions
        WHERE session_id = '$sSessionId'";
        //--AND UNIX_TIMESTAMP(session_expiration) > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL 1 hour))

        if (($rResult = $this->query($sQuery)) === false) {
            return false;
        } else {
            $aRow = mysql_fetch_row($rResult);
            mysql_free_result($rResult);
            return $aRow[0];
        }
    }

    /**
     * @see SessionHandler::write()
     */
    public function write($sSessionId, $sData)
    {
        $sData = addslashes($sData);
        $sQuery = "INSERT INTO sessions VALUES ('$sSessionId', '$sData', UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL 1 hour)))";

        if ($this->query($sQuery) === false) {
            $this->query("
                            UPDATE sessions
                            SET session_data = '$sData',
                            session_expiration = UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL 1 hour))
                            WHERE session_id = '$sSessionId'"
            );
        }
    }

    /**
     * @see SessionHandler::destroy()
     */
    public function destroy($sSessionId)
    {
        $this->query("DELETE FROM sessions WHERE session_id = '$sSessionId'");
    }

    /**
     * @see SessionHandler::gc()
     */
    public function gc($sMaxLifetime)
    {
        //$this->query("DELETE FROM sessions WHERE UNIX_TIMESTAMP(session_expiration) < UNIX_TIMESTAMP(NOW() + $sMaxLifetime)");
    }

    /**
     * Requête la base de données
     */
    protected function query($sQuery)
    {
        return mysql_query($sQuery, $this->oDatabase);
    }
}
