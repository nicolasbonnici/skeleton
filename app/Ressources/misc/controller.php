<?php

/**
 * @package core
 */
abstract class core_controller
{
    public $name; // string
    public $country; // string
    public $lang; // string
    public $action; // string
    public $classname; // string
    protected $_view; // array
    protected $_params; // array
    protected $_session;
    protected $_cookie; // array

    public function __call($name, $args)
    {
        header('Location: /');
    }

    public function __construct($args)
    {
        if (!empty($args)) {
            $this->action = $args[0];
            $this->_params = $args[1];
        }

        if (!empty($_POST)) {
            foreach ($_POST as $post_name => $post_value) {
                $this->_params[$post_name] = $post_value;
            }
        }

        $this->_cookie = new core_Cookie();

        $this->classname = get_class($this);
        $this->name = substr($this->classname, 0, -(strlen('_controller')));

        $this->_view['controller'] = $this;

        if (in_array(MODULE, array('site', 'world'))) {
            $this->country = $this->getLocationCountry();
            $this->lang = $this->getLanguage();
        }
    }

    public function userAgent()
    {
        $OSList = array
            (
            // Match user agent string with operating systems
            'Windows 3.11' => 'Win16',
            'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98' => '(Windows 98)|(Win98)',
            'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
            'Windows Server 2003' => '(Windows NT 5.2)',
            'Windows Vista' => '(Windows NT 6.0)',
            'Windows 7' => '(Windows NT 6.1)',
            'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
            'Open BSD' => 'OpenBSD',
            'Sun OS' => 'SunOS',
            'Linux' => '(Linux)|(X11)',
            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS/2' => 'OS/2',
            'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );

        foreach ($OSList as $CurrOS => $Match) {
            if (preg_match('/' . $Match . '/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $os = true;
                break;
            }
        }

        return ($os) ? "/www/" : "/mobile/";
    }

    // @TODO: utilisé ?
    public function getLocationCountry()
    {
        return isset($_SERVER['GEOIP_COUNTRY_CODE']) ? $_SERVER['GEOIP_COUNTRY_CODE'] : 'FR';
    }

    public function getLanguage()
    {
    	/** @TODO: 'en' ne fait pas partie des valeurs possibles de $_SERVER['GEOIP_COUNTRY_CODE']
         * http://spip-plugins.quesaco.org/phpdoc/_plugins_/geoip_country_code/geoip_country_code/GeoIP.html
    	**/
        if (strtolower($this->getLocationCountry()) == "en") {
            return "en";
        } else {
            return "fr";
        }

    }

    public function renderAjax($tpl, $iStatus, $bToString = false)
    {

        $aResponse = json_encode(array(
            'status'    => $iStatus,
            'content'   => str_replace(array("\r", "\r\n", "\n", "\t"), '', $this->render($tpl, true))
        ));

        if ($bToString === true) {
            return $aResponse;
        }

        header('Content-Type: application/json');
        echo $aResponse;
    }

    public function render($tpl, $bToString = false)
    {
        foreach ($this->_view as $key => $val) {
            if (is_object($val) && get_class($val) == 'core_collection') {
                $this->_view[$key] = $val->get_elements();
            }
        }

        $this->_view['CDN_CSS']                 = CDN_CSS;
        $this->_view['CDN_IMG']                 = CDN_IMG;
        $this->_view['CDN_IMAGES']              = CDN_IMAGES;
        $this->_view['CDN_JS']                  = CDN_JS;
        $this->_view['STATIC_FILES_VERSION']    = STATIC_FILES_VERSION;

        if (MODULE == "site")
        {
            $file = str_replace(".tpl", ".php", $tpl);

            require_once ROOT . "/application/" . MODULE . "/translations/" . $this->lang . "/global.php";
            require_once ROOT . "/application/" . MODULE . "/translations/" . $this->lang . "/" . $file;

            $this->_view["txt"] = $txt;
            $this->_view["now"] = time();

            $sReturn = Haanga::Load($tpl, ($this->_view), $bToString);
        } elseif (MODULE == "world") {
            $file = str_replace(".tpl", ".php", $tpl);

            require_once ROOT . "/application/" . MODULE . "/translations/" . $this->lang . "/global.php";
            if (file_exists(ROOT . "/application/" . MODULE . "/translations/" . $this->lang . "/" . $file)) {
                require_once ROOT . "/application/" . MODULE . "/translations/" . $this->lang . "/" . $file;
            }

            $this->_view["now"] = time();
            $this->_view["txt"] = $txt;

            $sReturn = Haanga::Load($tpl, ($this->_view), $bToString);
        } else
            $sReturn = Haanga::Load($tpl, ($this->_view), $bToString);

        return $sReturn;
    }

    public function render2($tpl)
    {
        foreach ($this->_view as $key => $val) {
            if (is_object($val) && get_class($val) == 'core_collection') {
                $this->_view[$key] = $val->get_elements();
            }
        }

        Haanga::Load($tpl, ($this->_view));
    }

    public function debug()
    {
        error_reporting(E_ERROR | E_USER_ERROR);
        ini_set('display_errors', true);
        error_reporting(E_ALL);
        $phpDebugOptions = array(
            'render_type' => 'HTML', // Renderer type
            'render_mode' => 'Div', // Renderer mode
            'restrict_access' => false, // Restrict access of debug
            'allow_url_access' => true, // Allow url access
            'url_key' => 'key', // Url key
            'url_pass' => 'nounou', // Url pass
            'enable_watch' => true, // Enable wath of vars
            'replace_errorhandler' => true, // Replace the php error handler
            'lang' => 'FR', // Lang
            // Renderer specific
            'HTML_DIV_view_source_script_name' => ROOT . '/htdocs/world/PHP_Debug_ShowSource.php',
            'HTML_DIV_remove_templates_pattern' => true,
            'HTML_DIV_templates_pattern' =>
            array(
                '/home/phpdebug/www/' => '/projectroot/'
            ),
            'HTML_DIV_images_path' => '/images',
            'HTML_DIV_css_path' => ROOT . '/htdocs/world/css',
            'HTML_DIV_js_path' => ROOT . '/htdocs/world/js',
        );
        require_once(ROOT . '/htdocs/world/PHP/Debug.php');
        $Dbg = new PHP_Debug($phpDebugOptions);
        $renderer = 'HTML_Div';
        $intro = 'This is the <b>' . $renderer . '_Renderer</b>, client IP is ' .
                $_SERVER['REMOTE_ADDR'];
        $Dbg->add($intro);

        // Database related info
        $Dbg->queryRel('Connecting to DATABASE [<b>phpdebug</b>]');
        $Dbg->stopTimer();

        $y = 0;
        for ($index = 0; $index < 10000; $index++) {
            $y = $y + $index;
        }
        $Dbg->stopTimer();
        return $Dbg->display();
    }
}
