<?php

class Haanga_Extension_Filter_Timestamp
{
    /*
     * Retourne un timestamp formater en date
     * 
     * @param $iTimestamp int
     * @param $sFormat string
     * 
     * @return string formated date
     */
	static function main($iTimestamp, $sFormat = 'Y/m/d h:m s')
	{
    	assert('is_int($iTimestamp) && $iTimestamp != 0');    	
    	return date($sFormat, $iTimestamp);
	}
}


