<?php

class Haanga_Extension_Filter_Trans
{
	static function main($aTranslation, $sKeyToTranslate)
	{
// 	    die(var_dump($sKeyToTranslate));
	    $sTranslation = '';
	    if (
	       is_array($aTranslation) &&
	       count($aTranslation) > 0 &&
	       array_key_exists($sKeyToTranslate, $aTranslation)
        ) {
            $sTranslation = $aTranslation[$sKeyToTranslate];
	    }
        return $sTranslation;
	}
}
