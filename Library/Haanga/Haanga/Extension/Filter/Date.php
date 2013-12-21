<?php

class Haanga_Extension_Filter_Date
{
    /*
     * condition dans le cas de produitCategorie
     * rajouter par yaniv
     */
	static function main($date, $format)
	{
            if ($date!="0000-00-00")
            $val = date($format,strtotime($date));
            else $val = $date;
            return $val;
	}
}


