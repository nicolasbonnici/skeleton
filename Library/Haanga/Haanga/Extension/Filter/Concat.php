<?php

class Haanga_Extension_Filter_Concat
{
	static function main($start, $end)
	{
		return $start.$end;
	}
}
