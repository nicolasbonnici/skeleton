<?php

/*
 *  for Bazarchic Use only
 */

namespace core;

/**
 * Description of Intl : this class will retrieve internationalization process
 *
 * @todo A faire evoluer lors du redev du front
 * @author Yaniv <yaniv@bazarchic.com>
 */
class Intl extends \core\Singleton
{
    protected $locale;
    protected $sCurrency;
    protected $sCountry;
    protected $sLanguage;
    protected $sTimezone;
    protected $aConfig;
    protected $bIsMobile;

    public function __construct()
    {
        $this->aConfig = \core\Config::get('hosts');
        if (MODULE != 'site' || (isset($_GET['noredir']) && \core\Utils::isInternal())) {
            $this->setLocale('fr_FR');
            $this->setTimezone('Europe/Paris');
        } else {
            $this->routingFromBzcRules();
        }

        $aLocale = \explode("_", $this->locale);
        $this->sLanguage = $aLocale[0];
        $this->sCountry = $aLocale[1];

        if (!defined('COUNTRY')) {
            define('COUNTRY', $this->sCountry);
        }
        if (!defined('LANGUAGE')) {
            define('LANGUAGE', $this->sLanguage);
        }
        setlocale(LC_ALL, $this->locale);
    }

    /**
     * Test avant login
     * @return boolean
     */
    private function routingFromBzcRules()
    {
        $this->bIsMobile = \core\Utils::isMobile();
        $sHost = ($this->bIsMobile) ? 'hosts_mobile' : 'hosts';
        $sResult = $this->getHostsByServerName(\core\Utils::getServerName());
        $sHttpAcceptLanguage = \Locale::acceptFromHttp(\core\Utils::getBrowserLanguage());
        if (\strlen($sResult) == 5) {
            $aLocale = \explode("_", $sResult);
            $sLanguage = $aLocale[0];
            $sCountry = $aLocale[1];
            if ($this->aConfig[$sCountry][$sHost][$sLanguage][0] == \core\Utils::getServerName()) {
                $this->setLocale($sResult);
                $this->setTimezone($this->aConfig[$sCountry]['timezone']);
                $this->setCookie();
                return true;
            } else {
                \core\HTTPHeader::redirect('http://' . $this->aConfig[$sCountry][$sHost][$sLanguage][0]);
            }
        } elseif (\strlen($sResult) == 2 && isset($_COOKIE['locale'])) {
            $aLocale = \explode("_", $_COOKIE['locale']);
            $sLanguage = $aLocale[0];
            if (isset($this->aConfig[$sResult][$sHost][$sLanguage][0])) {
                \core\HTTPHeader::redirect('http://' . $this->aConfig[$sResult][$sHost][$sLanguage][0]);
            } else {
                $aHosts = current($this->aConfig[$sResult][$sHost]);
                $sUrl = $aHosts[0];
                \core\HTTPHeader::redirect('http://' . $sUrl);
            }
        } elseif (\strlen($sResult) == 2 && $sHttpAcceptLanguage) {
            $aLocale = \explode("_", $sHttpAcceptLanguage);
            $sLanguage = $aLocale[0];
            \core\HTTPHeader::redirect('http://' . $this->aConfig[$sResult][$sHost][$sLanguage][0]);
        } elseif (isset($_COOKIE['locale'])) {
            $aLocale = \explode("_", $_COOKIE['locale']);
            $sLanguage = $aLocale[0];
            $sCountry = $aLocale[1];
            \core\HTTPHeader::redirect('http://' . $this->aConfig[$sCountry][$sHost][$sLanguage][0]);
        } elseif ($sHttpAcceptLanguage) {
            $aLocale = \explode("_", $sHttpAcceptLanguage);
            $sLanguage = $aLocale[0];
            $sCountry = $aLocale[1];
            \core\HTTPHeader::redirect('http://' . $this->aConfig[$sCountry][$sHost][$sLanguage][0]);
        } else {
            $this->setLocale('fr_FR');
            $this->setTimezone($this->aConfig['FR']['timezone']);
            $this->setCookie();
            return true;
        }
    }

    private function setLocale($sLocale)
    {
        $this->locale = $sLocale;
    }

    private function setCookie()
    {
        \core\Cookie::getInstance()->set('locale', $this->locale, time() + 60 * 60 * 24 * 365, '/');
    }

    private function setTimezone($sTimezone)
    {
        \date_default_timezone_set($sTimezone);
        $this->sTimezone = $sTimezone;
    }

    private function getExtension($sValue)
    {
        return \str_replace('.bazarchic.com', '', $sValue);
    }

    private function getHostsByServerName($sServerName)
    {
        foreach ($this->aConfig as $sCountry => $value) {
            foreach ($value as $key => $aValue) {
                if (\is_array($aValue)) {
                    foreach ($aValue as $sLanguage => $sItem) {
                        if ($key === 'hosts' || $key === 'hosts_mobile') {
                            foreach ($sItem as $sValue) {
                                //array_push($aAllHostsNoDefault, $sValue);
                                if ($sValue == $sServerName) {
                                    return $sLanguage . '_' . $sCountry;
                                }
                            }
                        } elseif ($key === 'default_host') {
                            if ($sItem == $sServerName) {
                                return $sCountry;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getLanguage()
    {
        return $this->sLanguage;
    }

    public function getCountry()
    {
        return $this->sCountry;
    }

    private function getCurrency()
    {
        if (!isset($this->sCurrency) || empty($this->sCurrency)) {
            $iCurrencyId = \kernel\CountryLanguages::getCurrencyId($this->sCountry);
            $oDbCurrency = new \db\Devises($iCurrencyId);
            $this->sCurrency = $oDbCurrency->code_iso;
        }
        return $this->sCurrency;
    }

    public function formatNumber($mValue, $formatType = 'DECIMAL')
    {
        if (\is_null($formatType)) {
            $formatType = 'DECIMAL';
        }
        $fmt = new \NumberFormatter($this->locale, constant('\NumberFormatter::' . $formatType));
        if ($formatType == 'CURRENCY') {
            $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, '');
        }
        return $fmt->format($mValue);
    }

    public function formatCurrency($mValue, $formatType = 'CURRENCY')
    {
        $fmt = new \NumberFormatter($this->locale, constant('\NumberFormatter::' . $formatType));
        return $fmt->formatCurrency($mValue, $this->getCurrency());
    }

    /**
     * format des pattern de dates http://userguide.icu-project.org/formatparse/datetime
     * @param type $sDateValue
     * @param type $sPattern
     * @return type
     */
    public function formatDate($sDateValue, $sType = 'SHORT')
    {
        if (\is_null($sType)) {
            $sType = 'SHORT';
        }

        if (\is_string($sDateValue)) {
            $sDateValue = \strtotime($sDateValue);
        }
        if ($sType == 'VERBOSE' or $sType == 'DAYMONTH' or $sType == 'VERBOSEFULL') {
            $fmt = new \IntlDateFormatter($this->locale, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, $this->sTimezone, \IntlDateFormatter::GREGORIAN, $this->aConfig[$this->sCountry]['datepattern'][$sType]);
        } else {
            $fmt = new \IntlDateFormatter($this->locale, constant('\IntlDateFormatter::' . $sType), \IntlDateFormatter::NONE, $this->sTimezone);
        }
        return $fmt->format($sDateValue);
    }

    /**
     * @todo
     */
    function sortByLocale()
    {

    }

}
