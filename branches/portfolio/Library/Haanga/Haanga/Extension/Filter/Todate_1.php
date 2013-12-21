<?php

class Haanga_Extension_Filter_Todate
{
	static function main($date, $format)
	{
		$val = date($format,$date);
		return $val;
	}
}


