<?php

namespace Library\Core;

/**
 * Toolbox
 */
class Tools {
			/**
	 * Retrieve gravatar url
	 * 
	 * @param string $sEmail
	 * @param string $iSize
	 */
	public static function getGravatar($sEmail, $iSize) 
	{
		// Définition des paramètres utiles
		$sDefault = urlencode('http://use.perl.org/images/pix.gif');
		$sEmail = md5($sEmail);
		// Création de l'url
		return sprintf(
			'http://www.gravatar.com/avatar.php?gravatar_id=%s&amp;size=%d&amp;default=%s',
			$sEmail,
			intval($iSize),
			$sDefault
		);

	}
	
	/**
	 * List all database tables
	 * 
	 * @return \Library\Core\Collection
	 */
    public static function getDatabaseEntities() 
    {
    	$aDatabaseEntities = array();
    	$aConfig = \Bootstrap::getConfig();
    	
    	$oStatement = Database::dbQuery(
    		'SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE `TABLE_SCHEMA` = ? ORDER BY `TABLES`.`TABLE_SCHEMA` DESC',
    		array($aConfig['database']['name'])
		);
    	if ($oStatement !== false && $oStatement->rowCount() > 0) {
    	     $aDatabaseEntities = $oStatement->fetchAll(\PDO::FETCH_ASSOC);
    	}        	
    	
    	return $aDatabaseEntities;
    }	
    

}

class CoreToolsException extends \Exception {}
