<?php

class Haanga_Extension_Filter_Formatdate
{
	static function generator($compiler, $args)
	{
		$date = explode('-',$args[0]);
		//return hexec('strftime',  hexec('mktime', 0,0,0,$date[2],$date[0],$date[1]), 'd/m/Y' );
		//return hexec('strftime', hexec('date'), 'd/m/Y' );
		return hexec('var_dump', $args[0] );
	}
}
