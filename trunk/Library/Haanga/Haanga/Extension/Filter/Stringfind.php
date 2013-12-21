<?php

class Haanga_Extension_Filter_Stringfind
{
    /*
     * pour le template livraison en front
     * rajouter par yaniv
     */
	static function main($string, $tofind)
	{
            $result = strpos($string,$tofind);
            if ($result==false || $result===false)
                return false;
            else return $result;
	}
}


