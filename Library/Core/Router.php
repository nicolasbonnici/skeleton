<?php

namespace Library\Core;

/**
 * MVC basic router
 * 
 * module/controler/action/:param
 */
class Router {

    static protected $instance;
    static protected $lang;
    static protected $module;
    static protected $controller;
    static protected $action;
    static protected $params;
    static protected $sUrl;
    static protected $aRequest;
    // @todo move
    static protected $aRules = array(
            '/login/:module/:[module]/:controller/:[controller]/:action/:[action]/' => array(
                'module'    => 'frontend',
                'controller' => 'auth',
                'action'    => 'index'
            ),
            '/logout' => array(
                'module'    => 'frontend',
                'controller' => 'auth',
                'action'    => 'logout'
            ),
            '/profile' => array(
                'module'    => 'backend',
                'controller' => 'user',
                'action'    => 'profile'
            ),
            '/profile/update/:id/:[id]/' => array(
                'module'    => 'backend',
                'controller' => 'user',
                'action'    => 'update'
            ),
            '/blog/:param/' => array(
                'module'    => 'frontend',
                'controller' => 'blog',
                'action'    => 'index'
            ),
            '/portfolio/:param/' => array(
                'module'    => 'frontend',
                'controller' => 'home',
                'action'    => 'portfolio'
            ),
            '/contact/:param/' => array(
                'module'    => 'frontend',
                'controller' => 'home',
                'action'    => 'contact'
            )
        );    

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function init() {

        self::$sUrl = $_SERVER['REQUEST_URI'];

        self::$aRequest = self::cleanArray(explode('/', self::$sUrl));        // @todo move function cleanArray to toolbox
        
        self::$lang = DEFAULT_LANG;
        self::$module = DEFAULT_MODULE;
        self::$controller = DEFAULT_CONTROLLER;
        self::$action = DEFAULT_ACTION;
        
        if (is_array(self::$aRequest) && count(self::$aRequest) > 0) {
            
            // Test custom routing here
            self::matchRules();                        
            
        }
		
		foreach($_FILES as $key=>$value) {
            self::$params[$key] = $value;
        }

        foreach($_POST as $key=>$value) {
            self::$params[$key] = $value;
        }		

        foreach($_GET as $key=>$value) {
            self::$params[$key] = $value;
        }		

        return;
    }
    

    private static function matchRules() {
        
        assert('is_array(self::$aRequest) && count(self::$aRequest)>0');

        // @see flag cstom route found
        $bRouted = false;       

        foreach (self::$aRules as $sUrl=>$aRule) {   
            
            // @see custom routing rule match with request
            $aUrl = explode(':', $sUrl);
            if (preg_match('#^/' . self::$aRequest[0] . '#', $aUrl[0])) {
                
                assert('is_array($aRule)');
                
                $bRouted = false;  
                
                self::$module = self::$aRules[$sUrl]['module'];
                self::$controller = self::$aRules[$sUrl]['controller'];
                self::$action = self::$aRules[$sUrl]['action'];   
                if (
                	($aParams = array_slice(self::$aRequest, count(self::cleanArray(explode('/', $aUrl[0]))))) 
                	&& count($aParams) > 0
				) {
	                self::setParams($aParams); 
                }

                return;
                
            }
                  
        }
        if (!$bRouted) {
            // @see no custom route matched so we proceed with a basic routing treatment
            if (($iRequestCount = count(self::$aRequest)) > 0) {
                // @todo optimiser ce traitement 
                if (isset(self::$aRequest[0])) {
                    self::$module = self::$aRequest[0];                
                } 
                
                if (isset(self::$aRequest[1])) {
                    self::$controller = self::$aRequest[1];                    
                } 
                
                if(isset(self::$aRequest[2])) {
                    self::$action = self::$aRequest[2];
                }

                self::setParams(array_slice(self::$aRequest, 3));        
                
            }
        }

        return;
    }

        // @todo migrer une classe toolbox en methode static
    private static function cleanArray(array $aArray = array()) {
        if (count($aArray) > 0) {
            foreach ($aArray as $key=>$sValue) {
                if (!strlen($sValue)) {
                    unset($aArray[$key]);
                }
            }
        }
        return array_values($aArray);
    }

    /**
     * Parse parameters from request url
     * 
     * @param array $items
     * @return array
     */
    private static function setParams(array $items) {
        if ((!empty($items)) && (count($items) % 2 == 0)) {
            for ($i = 0; $i < count($items); $i++) {
                if ($i % 2 == 0) {
                    self::$params[$items[$i]] = $items[$i + 1];
                }
            }
        }
        return self::$params;
    }

    /**
     * Simple redirection abstraction layer
     * 
     * @param mixed array|string $mUrl
     * @todo handle router request 
     */
    public static function redirect($mUrl) {
        assert('is_string($mUrl) || is_array($mUrl)');
        
        if (is_string($mUrl)) {
            header('Location: ' . $mUrl );      
            exit();
        } elseif (is_array($mUrl)) {
             
            if (
                    array_key_exists('request', $mUrl) && 
                    isset(
                            $mUrl['request']['module'], 
                            $mUrl['request']['controller'],
                            $mUrl['request']['action']
                    )
             ) {
                self::$sUrl = '/' . $mUrl['request']['module'] . '/' .$mUrl['request']['controller'] . '/' . $mUrl['request']['action'];
            } else {
                throw new RouterException(__METHOD__ . ' malformed redirection request  ');                
            }
                        
            header('Location: ' .  self::$sUrl);   
        } else {
            
            throw new RouterException(__METHOD__ . ' wrong request data type (mixed string|array)  ');
        }
        
        return;
    }
    
    public static function getModule() {
        return self::$module;
    }

    public static function getController() {
        return self::$controller;
    }

    public static function getAction() {
        return self::$action;
    }

    public static function getParams() {
        return self::$params;
    }

    public static function getParam($id) {
        return self::$params[$id];
    }
    
    public static function getLang() {
        return self::$lang;
    }

}

class RouterException extends \Exception {}
