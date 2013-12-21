<?php

class Haanga_Extension_Filter_Datefrslash
{
    static function generator($compiler, $args)
    {
    	$date=hexec('strtotime',$args[0]);
    	return hexec('date',"d/m/Y",$date);
    }
}
