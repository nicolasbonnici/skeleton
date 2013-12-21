<?php

/**
 * @package core
 */
class core_Acl
{
    /**
     * Verifier une permission sur une condition pour un user
     *
     * @param int $iIdCond
     * @param int $iIdUser
     * @return boolean
     */
    static public function checkUserPerm($iIdCond , $iIdUser)
    {
        if (!empty($iIdCond) && !empty($iIdUser)) {
            // Recupérer la liste des groupes de l'utilisateur
            $aGroups = self::getUserGroups($iIdUser);
            $oStatement = sql::master(
                'SELECT idperm FROM perm_cond_group WHERE idcond = ? AND idgroup IN(?' . str_repeat(',?', count($aGroups) -1) . ')',
                array(array_merge($aGroups, array($iIdCond)))
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifier une permission sur un accès fichier/controller|action pour un user
     *
     * @param int $iIdFile
     * @param string $sPath
     * @param int $iIdUser
     * @return boolean
     */
    static public function checkUserAccess($iIdUser, $sName  = '', $iIdFile = 0)
    {
        if (!empty($iIdUser) && !empty($sName) || !empty($iIdFile)) {
            if (!empty($sName)) {
                // @see on ne dispose que d'un path il faut recuperer l'id de la condition
                $iIdFile = self::getIdFileByCondName($sName);
            }
            if ($iIdFile !== false) {
                // Recupérer la liste des groupes de l'utilisateur
                $aGroups = self::getUserGroups($iIdUser);
                $oStatement = sql::master(
                    'SELECT idperm FROM perm_file_group WHERE `idgroup` IN(?' . str_repeat(',?', count($aGroups) -1) . ') AND `idfile` = ?',
                    array_merge($aGroups, array($iIdFile))
                );

                if ($oStatement !== false && $oStatement->rowCount() > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Verifier une permission sur un accès fichier/controller|action pour un group
     *
     * @param int $iIdFile
     * @param string $sPath
     * @param int $iIdGroup
     * @return boolean
     */
    static public function checkGroupAccess($iIdGroup, $sName  = '', $iIdFile = 0)
    {
        if (!empty($iIdGroup) && !empty($sName) || !empty($iIdFile)) {
            if (!empty($sName)) {
                // @see on ne dispose que d'un path il faut recuperer l'id de la condition
                $iIdFile = self::getIdFileByCondName($sName);
            }
            if ($iIdFile  !== false && $iIdFile !== 0) {
                $oStatement = sql::master(
                    'SELECT idperm FROM perm_file_group WHERE `idgroup` = ? AND `idfile` = ?',
                    array($iIdGroup, $iIdFile)
                );
                if ($oStatement !== false && $oStatement->rowCount() > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Recupérer un tableau de toutes les permissions dont dispose l'utilisateur
     *
     * @param int $iIdUser
     * @return array
     */
    static public function getUserPerms($iIdUser)
    {
        $aResult = array();
        $aGroups = self::getUserGroups($iIdUser);

        if (is_array($aGroups)) {
            $oStatement = sql::master(
                'SELECT GROUP_CONCAT(idcond) AS ids FROM perm_cond_group WHERE `idgroup` IN (?' . str_repeat(',?', count($aGroups) -1) . ')',
                $aGroups
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                $sResult = $oStatement->fetch();
                $aResult = explode(',', $sResult['ids']);
            }
        }

        return $aResult;
    }

    /**
     * Determiner si une permission d'accès concerne tout le controlleur ou juste une methode
     *
     * @param int $iIdFile
     * @return string|false
     */
    static public function getFileNameByCondId($iIdFile)
    {
        $sName = '';
        $oStatement = sql::master(
            'SELECT `name` FROM perm_file WHERE `idfile` = ?',
            array($iIdFile)
        );

        if ($oStatement !== false && $oStatement->rowCount() > 0) {
            $aPermFile = $oStatement->fetch();
            $sName = $aPermFile['name'];
        }
        return $sName;
    }

   /**
     * Ajouter une permission d'accès à un controller/methode ou fichier pour un group
     *
     * @param int $iIdCond
     * @param int $iIdGroup
     * @return boolean
     */
    static public function addGroupAccessPerm($iIdCond, $iIdGroup)
    {
        if ( !empty($iIdCond) && !empty($iIdGroup)) {
            $oStatement = sql::master(
                'INSERT INTO  perm_file_group (`idfile`, `idgroup`) VALUES(?, ?)',
                array($iIdCond, $iIdGroup)
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }

        }

        return false;
    }

    /**
     * Revoquer une permission d'accès à un controller/action/fichier pour un group
     *
     * @param int $iIdCond
     * @param int $iIdGroup
     * @return boolean
     */
    static public function revokeGroupAccessPerm($iIdCond, $iIdGroup)
    {
        if ( !empty($iIdCond) && !empty($iIdGroup)) {
            $oStatement = sql::master(
                'DELETE FROM perm_file_group WHERE `idfile` = ? AND `idgroup` = ?',
                array($iIdCond, $iIdGroup)
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }

        }

        return false;
    }

   /**
     * Ajouter une permission d'accès à une condition
     *
     * @param int $iIdCond
     * @param int $iIdGroup
     * @return boolean
     */
    static public function addGroupCondPerm($iIdCond, $iIdGroup)
    {
        if (!empty($iIdCond) && !empty($iIdGroup)) {
            $oStatement = sql::master(
                'INSERT INTO  perm_cond_group (`idcond`, `idgroup`) VALUES(?, ?)',
                array($iIdCond, $iIdGroup)
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Revoquer une permission à une condition
     *
     * @param int $iIdCond
     * @param int $iIdGroup
     * @return boolean
     */
    static public function revokeGroupCondPerm($iIdCond, $iIdGroup)
    {
        if ( !empty($iIdCond) && !empty($iIdGroup)) {
            $oStatement = sql::master(
               'DELETE FROM perm_cond_group WHERE idcond = ? AND idgroup = ?',
                array($iIdCond, $iIdGroup)
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Creer une nouvelle condition d'accès à un controlleur/methode
     *
     * @param array $aCond  Contiens soit les paramètres controller|action ou uniquement file ainsi que le titre et la desc
     * @return int|boolean
     */
    static public function addAccessCond($aCond = array())
    {
        if (count($aCond) > 0 && isset($aCond['controller']) || (isset($aCond['file']))) {
            // @see V4
            if (isset($aCond['controller']) && isset($aCond['action']) && method_exists($aCond['controller'], $aCond['action'])) {
                $sTarget = $aCond['controller'] . '/' . $aCond['action'];
                $sPath = ROOT . '/application/' . MODULE . '/controllers/' . $aCond['controller'] . '.php';
            } else if (isset($aCond['controller'])) {
                $sTarget = $aCond['controller'];
                $sPath = ROOT . '/application/' . MODULE . '/controllers/' . $aCond['controller'] . '.php';
            } else if (isset($aCond['file']) ) {
                $sPath = $aCond['file'];
            }

            if (!empty($sPath)) {
                $oStatement = sql::master(
                    'INSERT INTO perm_file (`filekey`, `name`, `title`, `path`, `desc`) VALUES(?, ?, ?, ?, ?)',
                    array(md5($sPath), (!strlen($aCond['file']) ? $sTarget : $aCond['file']), $aCond['title'], $sPath, $aCond['desc'])
                );

                if ($oStatement !== false && $oStatement->rowCount() > 0) {
                    return sql::lastInsertId();
                }
            }
        }

        return false;
    }

    /**
     * Creer une nouvelle permission
     *
     * @param array $aCond  Contiens les paramètres description|path
     * @return boolean
     */
    static public function addPerm($aCond = array())
    {
        if (count($aCond) > 0 && ( isset($aCond['description']) && isset($aCond['path']))) {
            $iIdFile = self::getIdFileByCondName($aCond['path']);

            if ($iIdFile === false) {
                $iIdFile = 0;
            }

            $oStatement = sql::master(
                'INSERT INTO perm_cond (`idfile`, `description`, `type_cond`, `cond`, `status`) VALUES(?, ?, ?, ?, ?)',
                array($iIdFile, $aCond['description'], 'perm', '', 'on')
            );

            if ($oStatement !== false && $oStatement->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renvoyer l'idfile en fonction du champs name
     *
     * @param string $sCondName
     * @return int|false
     */
    static public function getIdFileByCondName($sCondName)
    {
        $iId = false;
        $oStatement = sql::master(
            'SELECT idfile FROM perm_file WHERE `name` = ?',
            array($sCondName)
        );

        if ($oStatement !== false && $oStatement->rowCount() > 0) {
            $iId = $oStatement->fetch();
        }

        return isset($iId['idfile']) ? $iId['idfile'] : false;
    }

    /**
     * Recupérer les groupes auquel l'utilisateur appartient
     *
     * @param int $iIdUser
     * @return array    Liste des groupes auquel l'utilisateur appartiens
     */
    static private function getUserGroups($iIdUser)
    {
        $aGroups = array();
        $oStatement = sql::master(
            'SELECT idgroup FROM users_groups WHERE iduser = ?',
            array($iIdUser)
        );

        if ($oStatement !== false && $oStatement->rowCount() > 0) {
            while ($aData = $oStatement->fetch(PDO::FETCH_ASSOC)) {
                $aGroups[] = $aData['idgroup'];
            }
        }

        return $aGroups;
    }
}
