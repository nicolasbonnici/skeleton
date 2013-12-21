<?php

class Haanga_Extension_Filter_Formatfloat {

	static function main($args) {
		return number_format($args , 2 , ',' , ' ' );
	}
}