<?php
/*
 * developpé par yaniv pour le fichier cata du front
 */
class Haanga_Extension_Filter_Fori0
{
	static function main($args)
	{	
            $replace = array();
            for ( $i=0;$i<$args;$i++)
            $replace[]=$i;
            return $replace;
	}
}
