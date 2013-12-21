<?php

class Haanga_Extension_Filter_Strftime
{
	static function main($date, $format)
	{
		$val = strftime($format,strtotime($date));
		return $val;
	}
}


