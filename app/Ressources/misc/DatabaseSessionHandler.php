<?php

namespace core;

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
     * @see SessionHandler::open()
     */
    public static function open($sSavePath, $sName)
    {
        return true;
    }

    /**
     * @see SessionHandler::close()
     */
    public static function close()
    {
        return true;
    }

    /**
     * @see SessionHandler::read()
     */
    public static function read($sSessionId)
    {
        $sQuery = '
            SELECT session_data FROM sessions
            WHERE session_id = ?
            AND session_expiration > NOW()';

        if (
            ($oStatement = \core\DB::query($sQuery, array($sSessionId))) === false
            || $oStatement->rowCount() === 0
        ) {
            return '';
        } else {
            return $oStatement->fetchColumn();
        }
    }

    /**
     * @see SessionHandler::write()
     */
    public static function write($sSessionId, $sData)
    {
        $oStatement = \core\DB::query(
            'REPLACE INTO sessions VALUES (?, ?, NOW() + INTERVAL 1 HOUR)',
            array($sSessionId, $sData)
        );
        return ($oStatement !== false);
    }

    /**
     * @see SessionHandler::destroy()
     */
    public static function destroy($sSessionId)
    {
        $oStatement = \core\DB::query('DELETE FROM sessions WHERE session_id = ?', array($sSessionId));
        return ($oStatement !== false);
    }

    /**
     * @see SessionHandler::gc()
     */
    public static function gc($sMaxLifetime)
    {
        $oStatement = \core\DB::query('DELETE FROM sessions WHERE session_expiration < NOW() + INTERVAL 1 HOUR');
        return ($oStatement !== false);
    }
}
