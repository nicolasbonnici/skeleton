<?php

/**
 * Common utilities
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-08-30 - Antoine <antoine.preveaux@bazarchic.com>
 */
class core_Utils
{
    /**
     * Retrieve client browser information
     * @return array Browser information (name, version, system, ...)
     */
    static public function getBrowser()
    {
        $sUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $sBrowserName = 'Unknown';
        $sPlatform = 'Unknown';
        $sBrowserVersion = '';

        //First get the platform?
        if (preg_match('/linux/i', $sUserAgent)) {
            $sPlatform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $sUserAgent)) {
            $sPlatform = 'mac';
        } elseif (preg_match('/windows|win32/i', $sUserAgent)) {
            $sPlatform = 'windows';
        }

        $sRequestUserAgent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/Win/', $sRequestUserAgent)) { //http://msdn.microsoft.com/workshop/author/dhtml/overview/aboutuseragent.asp
            $sPlatformVersion = 'Windows';

            if (preg_match('/Windows NT 5.1/i', $sRequestUserAgent)) {
                $sPlatform = 'XP';
                $sPlatformVersion = 'WinXP';

                if (preg_match('/Media Center PC 2.8/i', $sRequestUserAgent)) {
                    $sPlatform = 'Media Center 2004';
                    $sPlatformVersion = 'Windows';
                } elseif (preg_match('/Media Center PC 3.0/i', $sRequestUserAgent)) {
                    $sPlatform = 'Media Center 2005';
                    $sPlatformVersion = 'Windows';
                } elseif (preg_match('/Media Center PC 3.1/i', $sRequestUserAgent)) {
                    $sPlatform = 'Media Center 2005 + Update 1';
                    $sPlatformVersion = 'Windows';
                } elseif (preg_match('/Media Center PC 4.0/i', $sRequestUserAgent)) {
                    $sPlatform = 'Media Center 2005 + Update 2';
                    $sPlatformVersion = 'Windows';
                } elseif (preg_match('/SV1/i', $sRequestUserAgent)) {
                    $sPlatform = 'XP (SP2)';
                    $sPlatformVersion = 'Windows';
                }
            } elseif (preg_match('/Win 9x 4.90/i', $sRequestUserAgent)) {
                $sPlatform = 'Me';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows ME/i', $sRequestUserAgent)) {
                $sPlatform = 'Me';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows 98/i', $sRequestUserAgent)) {
                $sPlatform = '98';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows 95/i', $sRequestUserAgent)) {
                $sPlatform = '95';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 5.01/i', $sRequestUserAgent)) {
                $sPlatform = '2000 (SP1)';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 5.0/i', $sRequestUserAgent)) {
                $sPlatform = '2000';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Win95/i', $sRequestUserAgent)) {
                $sPlatform = '95';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Win98/i', $sRequestUserAgent)) {
                $sPlatform = '98';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 4.0/i', $sRequestUserAgent)) {
                $sPlatform = 'NT 4.0';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/WinNT/i', $sRequestUserAgent)) {
                $sPlatform = 'NT';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 5.2/i', $sRequestUserAgent)) {
                $sPlatform = 'Server 2003';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 6.0/i', $sRequestUserAgent)) {
                $sPlatform = 'Vista';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT 6.1/i', $sRequestUserAgent)) {
                $sPlatform = '7';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows NT/i', $sRequestUserAgent)) {
                $sPlatform = 'NT';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows XP/i', $sRequestUserAgent)) {
                $sPlatform = 'XP';
                $sPlatformVersion = 'Windows';
            } elseif (preg_match('/Windows CE/i', $sRequestUserAgent)) {
                $sPlatform = 'CE';
                $sPlatformVersion = 'Windows';
            }
        } elseif (preg_match('/Linux/i', $sRequestUserAgent)) {
            $sPlatformVersion = 'Linux';

            if (preg_match('/Ubuntu/i', $sRequestUserAgent)) {
                $sPlatform = 'Ubuntu';
                $sPlatformVersion = 'Ubuntu';
            } elseif (preg_match('/Debian/i', $sRequestUserAgent)) {
                $sPlatform = 'Debian';
                $sPlatformVersion = 'Debian';
            } elseif (preg_match('/Fedora/i', $sRequestUserAgent)) {
                $sPlatform = 'Fedora';
                $sPlatformVersion = 'Fedora';
            } elseif (preg_match('/Red Hat/i', $sRequestUserAgent)) {
                $sPlatform = 'Red Hat';
                $sPlatformVersion = 'RedHat';
            } elseif (preg_match('/redhat/i', $sRequestUserAgent)) {
                $sPlatform = 'Red Hat';
                $sPlatformVersion = 'RedHat';
            } elseif (preg_match('/slackware/i', $sRequestUserAgent)) {
                $sPlatform = 'Slackware';
                $sPlatformVersion = 'Slackware';
            } elseif (preg_match('/Mandriva/i', $sRequestUserAgent)) {
                $sPlatform = 'Mandriva';
                $sPlatformVersion = 'Mandriva';
            } elseif (preg_match('/mdk/i', $sRequestUserAgent)) {
                $sPlatform = 'Mandrake';
                $sPlatformVersion = 'Mandriva';
            } elseif (preg_match('/SUSE/i', $sRequestUserAgent)) {
                $sPlatform = 'SUSE';
                $sPlatformVersion = 'SUSE';
            } elseif (preg_match('/Gentoo/i', $sRequestUserAgent)) {
                $sPlatform = 'Gentoo';
                $sPlatformVersion = 'Gentoo';
            } elseif (preg_match('/XandrOS/i', $sRequestUserAgent)) {
                $sPlatform = 'XandrOS';
                $sPlatformVersion = 'XandrOS';
            } elseif (preg_match('/gnu/i', $sRequestUserAgent)) {
                $sPlatform = 'GNU';
                $sPlatformVersion = 'GNU';
            }
        } elseif (preg_match('/Mac/', $sRequestUserAgent)) {
            $sPlatformVersion = 'MacOS9';
            if (preg_match('/Mac OS X/i', $sRequestUserAgent)) {
                $sPlatform = 'OS X';
                $sPlatformVersion = 'MacOSX';
            } elseif (preg_match('/PPC/i', $sRequestUserAgent)) {
                $sPlatform = 'OS 9';
                $sPlatformVersion = 'MacOS9';
            } elseif (preg_match('/Mac_PowerPC/i', $sRequestUserAgent)) {
                $sPlatform = 'OS 9';
                $sPlatformVersion = 'MacOS9';
            }
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $sUserAgent) && !preg_match('/Opera/i', $sUserAgent)) {
            $sBrowserName = 'Internet Explorer';
            $sUserBrowser = 'MSIE';
        } elseif (preg_match('/Firefox/i', $sUserAgent)) {
            $sBrowserName = 'Mozilla Firefox';
            $sUserBrowser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $sUserAgent)) {
            $sBrowserName = 'Google Chrome';
            $sUserBrowser = 'Chrome';
        } elseif (preg_match('/Safari/i', $sUserAgent)) {
            $sBrowserName = 'Apple Safari';
            $sUserBrowser = 'Safari';
        } elseif (preg_match('/Opera/i', $sUserAgent)) {
            $sBrowserName = 'Opera';
            $sUserBrowser = 'Opera';
        } elseif (preg_match('/Netscape/i', $sUserAgent)) {
            $sBrowserName = 'Netscape';
            $sUserBrowser = 'Netscape';
        }

        // finally get the correct version number
        $sPattern = '#(?<browser>Version|' . $sUserBrowser . '|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (preg_match_all($sPattern, $sUserAgent, $aMatches)) {
            // see how many we have
            if (count($aMatches['browser']) > 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($sUserAgent, 'Version') < strripos($sUserAgent, $sUserBrowser)) {
                    $sBrowserVersion = $aMatches['version'][0];
                } elseif (isset($aMatches['version'][1])) {
                    $sBrowserVersion = $aMatches['version'][1];
                }
            } else {
                $sBrowserVersion = $aMatches['version'][0];
            }
        }

        // check if we have a number
        if (empty($sBrowserVersion)) {
            $sBrowserVersion = '?';
        }

        return array(
            'userAgent' => $sUserAgent,
            'name' => $sBrowserName,
            'version' => $sBrowserVersion,
            'platform' => $sPlatform,
            'platform_v' => $sPlatformVersion,
            'pattern' => $sPattern
        );
    }
}
