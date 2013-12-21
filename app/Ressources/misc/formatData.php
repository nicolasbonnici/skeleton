<?

class core_formatData {


	public static function mysql_date_to_fr($date, $sep="-")
	{
		$tmp=explode($sep, $date);

		return $tmp[2]." / ".$tmp[1]." / ".$tmp[0];

	}

	public static function fr_to_mysql_date($date, $sep="-")
	{
		$tmp=explode($sep, $date);

		return $tmp[2]."-".$tmp[1]."-".$tmp[0];

	}


	public static function remove_accents($str, $charset='utf-8')
	{
		//$str = htmlentities(trim($str), ENT_NOQUOTES, $charset);
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractère
		$str = preg_replace('#&#', 'et', $str);
		$str=str_replace("'"," ",$str);
		//$str=str_replace("ARRONDISSEMENT"," ",$str);
		return strtoupper($str);
	}

	//utilisé pour générer un id html (pour les collapse bootstrap par exemple)
	public static function clean_string($str, $charset='utf-8')
	{
		//$str = htmlentities(trim($str), ENT_NOQUOTES, $charset);
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractère
		$str = preg_replace('#&#', 'et', $str);
		$str = str_replace(' ', '_', $str);
		$str = str_replace('/', '-', $str);
		$str = str_replace('(', '_', $str);
		$str = str_replace(')', '_', $str);
		$str = str_replace('!', '', $str);
		$str = str_replace("'"," ",$str);
		return $str;
	}


	////////// SURTOUT UTILE POUR LE CONVOYEUR POUR COUPER A 30 char/////
	public static function couper_mot($mot,$longeur)
	{
		$mot_cool=format_donnee::remove_accents($mot);
		$mot_wrap = wordwrap($mot_cool, $longeur, "\n", true);
		$mot_split=split("\n",$mot_wrap);
		return $mot_split;
	}

	//alias de strstr sauf qu'il ne retourne pas le needle
	public static function strstr_after($haystack, $needle, $case_insensitive = false) 
	{
	    $strpos = ($case_insensitive) ? 'stripos' : 'strpos';
	    $pos = $strpos($haystack, $needle);
	    if (is_int($pos)) {
	        return substr($haystack, $pos + strlen($needle));
	    }
	    // Most likely false or null
	    return $pos;
	}



}

?>