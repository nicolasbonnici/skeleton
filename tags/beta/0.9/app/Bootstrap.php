<?php
/**
 * Boostrap app  
 */
class Bootstrap {
    
    protected static $_instance;

    protected static $_env;

    protected static $_paths;
    
    protected static $_config;
    
    protected static $_reporting;
    
    protected static $_logs;
    
    protected static $_request;
    
    protected static $_view;
    
    protected static $_loadedClass = array();
    
   
    private function __construct() {
        
        self::initComponents();      
        
        return;
    }    
   
    private function __clone() {
        return;
    }    
    
    public static function getInstance() {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public static function initComponents() {
        
        /**
         *  @see init environment
         */
        self::initEnv($_SERVER['REMOTE_ADDR']); 
        
        /**
         * @see load config
         */
        self::initPaths();    
        self::initConfig();

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
        self::$_request = self::initRouter();
        
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
            self::$_loadedClass[] = $sFileName;
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
    private function initController() {
        $_controller = 'modules\\' . \Library\Core\Router::getModule() . '\Controllers\\' . ucfirst(\Library\Core\Router::getController()) . 'Controller';
        if (class_exists($_controller)) {
           
            new $_controller(); 
            
        } else {
            
            if (ENV === 'dev') {
                self::$_loadedClass[] = $_controller;
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
     * @param string $sIp
     */
    private function initEnv($sIp) {
        // @see env info
        define('ENV', ('127.0.0.1' === $sIp) ? 'dev' : 'prod'); // @see env dev|prod   
        return;
    }
    
    private function initConfig() {
        if (is_file(APP_PATH . 'config/config.ini')) {
            self::$_config = parse_ini_file(APP_PATH . 'config/config.ini', true);
        } else {
            throw new Exception('Unable to load locales...');            
        }

        return;
    }
    
    /**
     * Init cache based on memcached
     */
    private function initCache() {       
        define('CACHE_HOST', self::$_config['cache']['host']);
        define('CACHE_PORT', self::$_config['cache']['port']);
        
        return;
    }
    
    
    /**
     * Init errors and notices reporting 
     */
    private function initReporting() {
        // @ see init logs and errors reporting
        error_reporting( (ENV === 'dev') ? E_ALL : 0 );
        ini_set('display_errors', (ENV === 'dev') ? 'On' : 'Off');
        ini_set('log_errors',  (ENV === 'dev') ? 'On' : 'Off');        
        
        return;
    }
    
    /**
     * Init log file
     */
    private function initLogs() {
        $sLogFile = LOG_PATH . '/' . DEFAULT_MODULE . '/errors.log';
        if (!is_file($sLogFile)) {
            
            // Reconstruire le chemin aussi
            if (!is_dir(substr($sLogFile, 0, strlen($sLogFile) - strlen('errors.log')))) {
                mkdir(substr($sLogFile, 0, strlen($sLogFile) - strlen('errors.log')));
            }
            
            fopen($sLogFile, 'w+');        
        }
        ini_set('error_log', $sLogFile); 
        
        return;
    }
    
    /**
     * Build all paths
     */
    private function initPaths() {
        // @see paths info
        define('ROOT_PATH', __DIR__ . '/../');
        define('APP_PATH', __DIR__ . '/../app/');
        define('LIBRARY_PATH', __DIR__ . '/../Library/');
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
    private function initRouter() {
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
    private function initLocales() {
        
        /**
         * @see regenerer les locales
         * find -name *.tpl > totranslate.txt
         * xgettext -f totranslate.txt -o project.pot
         */
        
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            
            $sLocale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            
            if (strlen($sLocale) === 2) {
                $sLocale = strtoupper($sLocale) . '_' . $sLocale;
            }            

            $sFilename = DEFAULT_MODULE;
            putenv('LC_ALL='.$sLocale . '.' . strtolower(str_replace('-', '', DEFAULT_ENCODING)));
            setlocale(LC_ALL, $sLocale . '.' . strtolower(str_replace('-', '', DEFAULT_ENCODING)));
  
//          @see gettext init (on utilise juste des array pour le moment)            
//            bindtextdomain($sFilename, DEFAULT_MODULES_PATH . DEFAULT_MODULE . '/Translations/');
//
//            bind_textdomain_codeset($sFilename, DEFAULT_ENCODING);
//            textdomain(DEFAULT_MODULE);            
            
            return $sLocale;
            
        } else {
            throw new Exception('Unable to load locales...');
        }
    }
    
    public function getEnv() {
        return self::$_env;
    }

    public function setEnv($env) {
        self::$_env = $env;
    }

    public function getPaths() {
        return self::$_paths;
    }

    public function setPaths($paths) {
        self::$_paths = $paths;
    }

    public static function getConfig() {
        return self::$_config;
    }

    public function setConfig($config) {
        self::$_config = $config;
    }

    public function getReporting() {
        return self::$_reporting;
    }

    public function setReporting($reporting) {
        self::$_reporting = $reporting;
    }

    public function getLogs() {
        return self::$_logs;
    }

    public function setLogs($logs) {
        self::$_logs = $logs;
    }

    public function getRequest() {
        return self::$_request;
    }

    public function setRequest($request) {
        self::$_request = $request;
    }

    public function getView() {
        return self::$_view;
    }

    public function setView($view) {
        self::$_view = $view;
    }

    public function getLoadedClass() {
        return self::$_loadedClass;
    }
    
}

