<?php

namespace Library\Core;

/**
 * Scaffolding class
 * 
 * 
 */
class Scaffold   {
    
	public static function generateEntity($sTableName, $iCacheDuration = 120) {
		if (!empty($sTableName)) {
			$sEntityName = self::formatEntityName($sTableName);
			return '
<?php

namespace app\Entities;

/**
 * ' .$sEntityName . ' entity statement
 *
 * @author generated by Core \Libray\Core\Scaffold
 */
 
class ' . $sEntityName . ' extends \Library\Core\Entity {

    const ENTITY = "' . $sEntityName . '";
    const TABLE_NAME = "' . $sTableName . '";
    const PRIMARY_KEY = "id' . strtolower($sEntityName) . '";

    /**
     * Object caching duration in seconds
     * @var integer
     */
    protected $iCacheDuration = ' . $iCacheDuration . '; 
    
    protected $aLinkedEntities = array();
    
}
			
			';
		}
		
		return false;
	}

	/**
	 * Convert table name to \app\Entities valid name
	 * 
	 * @param string $sTableName
	 */
	public static function formatEntityName($sTableName)
	{
		assert('strlen($sTableName) > 0');
		return ucfirst(substr($sTableName, 0, strlen($sTableName) - 1));
	}
	
}

class CoreScaffoldingException extends \Exception {}

?>
