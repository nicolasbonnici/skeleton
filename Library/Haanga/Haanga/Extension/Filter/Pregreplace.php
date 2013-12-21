<?php

class Haanga_Extension_Filter_Pregreplace
{
	static function generator($compiler,$args)
	{	
		$replace = hexec('preg_replace',$args[1], '-', $args[0]);
		return $replace;
	}
}
