<?php
/*
 * developpé par yaniv pour le fichier cata du front
 */
class Haanga_Extension_Filter_Fori
{
	static function main($args)
	{	
            $replace = array();
            for ( $i=1;$i<=$args;$i++)
            $replace[]=$i;
            return $replace;
	}
}
