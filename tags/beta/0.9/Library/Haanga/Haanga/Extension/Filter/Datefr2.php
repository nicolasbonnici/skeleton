<?php

/* By Yaniv Afriat
 * ne pas modifier cette version est utilisé dans la version mobile et site
 */



class Haanga_Extension_Filter_Datefr2
{
	static function main($args)
	{
		setlocale (LC_TIME, 'fr_FR.utf-8','fra');
		$date=strtotime($args);
		$JourL = strftime("%A",$date);
		$Jour = strftime("%e",$date);
		$Mois = strftime("%B",$date);
		$Annee = strftime("%Y",$date);
		return $Jour.' '.$Mois;
	}
}

?>
