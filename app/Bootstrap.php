<?php

/**
 * Boostrap components and initialise framework
 */
class Bootstrap {

	/**
	 * @var Bootstrap Instance
	 */
    private static $oInstance;

    /**
     * Config multi dimensional array parsed from .ini files
     * @var array
     */
    private static $aConfig;

    /**
     * Http request
     *
     * @var array
     */
    private static $aRequest;

    /**
     * Loaded classes for debug
     * @var array
     */
    private static $aLoadedClass = array();

    /**
     * An array of ip
     * @todo passer en config
     * @var array
     */
    private static $aDevelopmentEnvironments = array('core.local', 'dev.nbonnici.info');


    public function __construct() {
    	return self::initComponents();
    }

    private final function __clone() {}

    public static function getInstance() {
    	if (! self::$oInstance instanceof self) {
    		self::$oInstance = new self();
    	}
    	return self::$oInstance;
    }

    public static function initComponents()
    {
    	/**
    	 *  @see register class autoloader
    	 */
		spl_autoload_register('Bootstrap::classLoader');

		/**
		 * @see paths
		 */
		self::initPaths();

		/**
		 * Init config
		 */
        self::initConfig();

        /**
         *  @see init environment
         */
        self::initEnv($_SERVER['SERVER_NAME']);


        /**
         * @see Errors and reporting
         */
        self::initReporting();
        self::initLogs();

        /**
         * Init cache
         */
        self::initCache();

        /**
         *  @see register class autoloader
         */
        spl_autoload_register('Bootstrap::classLoader');

        /**
         * @see Parse request
         */
        self::$aRequest = self::initRouter();

        /**
         * @see bootstrap application controller
         */
        self::initController();

        return;
    }

    /**
     * Autoload any class that use namespaces
     *
     * @param string $sClassName
     */
    public static function classLoader($sClassName) {
        $sClassName = ltrim($sClassName, '\\');
        $sFileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($sClassName, '\\')) {
            $namespace = substr($sClassName, 0, $lastNsPos);
            $sClassName = substr($sClassName, $lastNsPos + 1);

            $sFileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $sFileName .= str_replace('_', DIRECTORY_SEPARATOR, $sClassName) . '.php';

        if (ENV === 'dev') {
            self::$aLoadedClass[] = $sFileName;
        }

        if (is_file(ROOT_PATH . $sFileName)) {
            require_once ROOT_PATH . $sFileName;
        }
    }


    /**
     * Boostrap app controller
     *
     * @throws CoreException
     */
    private static function initController() {
        $_controller = 'modules\\' . \Library\Core\Router::getModule() . '\Controllers\\' . ucfirst(\Library\Core\Router::getController()) . 'Controller';
        if (class_exists($_controller)) {

            new $_controller();

        } else {

            if (ENV === 'dev') {
                self::$aLoadedClass[] = $_controller;
            }

            \Library\Core\Router::redirect('/'); //@todo handle 404 errors here

        }

        return;
    }

    /**
     * Init template engine
     *
     * @param string $sTpl
     * @param array $aViewParams
     * @param bool $bToString
     */
    public static function initView($sTpl, $aViewParams, $bToString) {

        $sHaangaPath = LIBRARY_PATH . 'Haanga/';
        $sViewsPath = MODULES_PATH . \Library\Core\Router::getModule() . '/Views/';
        $sCachePath = CACHE_PATH . \Library\Core\Router::getModule() . '/Views';

        require_once $sHaangaPath . 'Haanga.php';

        \Haanga::configure(array(
            'template_dir' => $sViewsPath,
            'cache_dir' => $sCachePath
        ));

        return \Haanga::load($sTpl, $aViewParams, $bToString);
    }

    /**
     * Init environement
     *
     * @todo
     *
     * @param string $sIp
     */
    private static function initEnv($sIp)
    {
        define(
        	'ENV',
        	(
        		(in_array($sIp, self::$aDevelopmentEnvironments) &&
        		self::$aConfig['env']['prod'] !== $sIp)
        		? 'dev' : 'prod'
        	)
        ); // set environment dev|prod
	}

    /**
     *
     * @throws Exception
     */
    private static function initConfig() {
        if (is_file(APP_PATH . 'config/config.ini')) {
            self::$aConfig = parse_ini_file(APP_PATH . 'config/config.ini', true);
        } else {
            throw new Exception('Unable to load locales...');
        }

        return;
    }

    /**
     * Init cache based on memcached
     */
    private static function initCache() {
        define('CACHE_HOST', self::$aConfig['cache']['host']);
        define('CACHE_PORT', self::$aConfig['cache']['port']);

        return;
    }


    /**
     * Init errors and notices reporting
     */
    private static function initReporting() {
        // @ see init logs and errors reporting
        error_reporting( (ENV === 'dev') ? E_ALL : 0 );
        ini_set('display_errors', (ENV === 'dev') ? 'On' : 'Off');
        ini_set('log_errors',  (ENV === 'dev') ? 'On' : 'Off');

        return;
    }

    /**
     * Init log file
     */
    private static function initLogs() {

        $sLogFile = LOG_PATH . DEFAULT_MODULE . '/errors.log';
        if (!is_file($sLogFile)) {

        	if (!is_dir(LOG_PATH)) {
        		mkdir(LOG_PATH);
        	}

            // Reconstruire le chemin aussi
            if (!is_dir(substr($sLogFile, 0, strlen($sLogFile) - strlen('/errors.log')))) {
                mkdir(substr($sLogFile, 0, strlen($sLogFile) - strlen('/errors.log')));
            }

            fopen($sLogFile, 'w+');
        }
        ini_set('error_log', $sLogFile);

        return;
    }

    /**
     * Build all paths
     */
    private static function initPaths() {
        // @see paths info
        define('ROOT_PATH', __DIR__ . '/../');
        define('APP_PATH', __DIR__ . '/../app/');
        define('LIBRARY_PATH', __DIR__ . '/../Library/');
        define('TMP_PATH', __DIR__ . '/../tmp/');
        define('CACHE_PATH', __DIR__ . '/../tmp/cache/');
        define('LOG_PATH', __DIR__ . '/../tmp/logs/');
        define('MODULES_PATH', __DIR__ . '/../modules/');

        // @see app defaults
        define('DEFAULT_ENCODING', 'UTF-8');
        define('DEFAULT_LANG', 'FR_fr');

        define('DEFAULT_MODULE', 'frontend');

        define('DEFAULT_CONTROLLER', 'home');
        define('DEFAULT_ACTION', 'index');

        return;
    }

    /**
     * Init router to parse current request
     *
     * @return array
     */
    private static function initRouter() {
        $oRouter = Library\Core\Router::getInstance();
        $oRouter->init();

        return array(
           'module' => $oRouter->getModule(),
           'controller' => $oRouter->getController(),
           'action' => $oRouter->getAction(),
           'params' => $oRouter->getParams(),
           'lang' => self::initLocales()
        );
    }

    /**
     * Load locales
     *
     * @return string   Current local on 2 caracters
     */
    private static function initLocales() {

        /**
         * @see regenerer les locales
         * find -name *.tpl > totranslate.txt
         * xgettext -f totranslate.txt -o project.pot
         */

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

        	// @todo intégrer intl à ce niveau
            $sLocale = 'FR_fr';

            if (strlen($sLocale) === 2) {
                $sLocale = strtoupper($sLocale) . '_' . $sLocale;
            }

            $sFilename = DEFAULT_MODULE;
            putenv('LC_ALL='.$sLocale . '.' . strtolower(str_replace('-', '', DEFAULT_ENCODING)));
            setlocale(LC_ALL, $sLocale . '.' . strtolower(str_replace('-', '', DEFAULT_ENCODING)));

//          @see gettext init (on utilise juste des array pour le moment c'est chiant de tout recompiler)
//            bindtextdomain($sFilename, DEFAULT_MODULES_PATH . DEFAULT_MODULE . '/Translations/');
//
//            bind_textdomain_codeset($sFilename, DEFAULT_ENCODING);
//            textdomain(DEFAULT_MODULE);

            return $sLocale;

        } else {
            throw new Exception('Unable to load locales...');
        }
    }

    public static function getConfig() {
        return self::$aConfig;
    }

    public static function setConfig($config) {
        self::$aConfig = $config;
    }

    public static function getRequest() {
        return self::$aRequest;
    }

    public static function setRequest($request) {
        self::$aRequest = $request;
    }

    public static function getLoadedClass() {
        return self::$aLoadedClass;
    }

}

