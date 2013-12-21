<?php

class Haanga_Extension_Filter_Ecartdate
{
	static function main($date1,$date2)
	{
        if (!$date1)
            $date1 = date('Y-m-d');
        list($annee1, $mois1, $jour1) = explode('-', $date1);
        $timestamp1 = mktime(0, 0, 0, $mois1, $jour1, $annee1);

        list($annee2, $mois2, $jour2) = explode('-', $date2);
        $timestamp2 = mktime(0, 0, 0, $mois2, $jour2, $annee2);

        $ecart_secondes = $timestamp1 - $timestamp2;
        $ecart_jours = floor($ecart_secondes / (60 * 60 * 24));

        return $ecart_jours;
	}
}
