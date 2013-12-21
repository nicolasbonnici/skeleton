<?

/* ------------------------------------------------------------- */
/* URL Router class */
/* ------------------------------------------------------------- */

class core_router {

	static protected $instance;
	static protected $controller;
	static protected $action;
	static protected $params;
	static protected $rules;

	public static function getInstance() {
		if (isset(self::$instance) and (self::$instance instanceof self)) {
			return self::$instance;
		} else {
			self::$instance = new self();
			return self::$instance;
		}
	}

	private static function arrayClean(&$array) {
		foreach($array as $key => $value) {
			if (strlen($value) == 0) unset($array[$key]);
		}
	}

	private static function ruleMatch($rule, $data) {
		$ruleItems = explode('/',$rule); self::arrayClean($ruleItems);
		$dataItems = explode('/',$data); self::arrayClean($dataItems);

		//		print '<br>';
		//		print_r($ruleItems);
		//		print '<br>';
		//		print_r($dataItems);
		//		print '<br>';

		if (count($ruleItems) == count($dataItems)) {
			$result = array();

			foreach($ruleItems as $ruleKey => $ruleValue) {
				if (preg_match('/^:[\w]{1,}$/',$ruleValue)) {
					$ruleValue = substr($ruleValue,1);

					//print $ruleValue;

					$result[$ruleValue] = $dataItems[$ruleKey];
				}
				else {
					//print $ruleValue;
					if (strcmp($ruleValue,$dataItems[$ruleKey]) != 0) {
						return false;
					}
				}
			}

			if (count($result) > 0) return $result;
			unset($result);
		} else {

			//print 'fdsfdff';

		}
		return false;
	}

	private static function defaultRoutes($url) {


		//print 'xvxkvnxcvnklxcvjklxcv';
		// process default routes
		$items = explode('/', $url);

		// remove empty blocks
		foreach($items as $key => $value) {

			if (strlen($value) == 0)
				unset($items[$key]);
			else {
				if(!preg_match('/\?_/', $value)){
					// patch a la con pour jqgrid ....

					$tab1 = explode("?", $key);
					$tab2 = explode("?", $value);
					// ON VIRE LES PARAMETRES EN GET NON DESIRES !
					unset($items[$key]);

					// on set le parametres voulus
					if (strlen($tab2[0]) != 0)
						$items[$tab1[0]] = $tab2[0];

					// si ? on set $this->_params avec les valeurs
					if (isset($tab1[1])||isset($tab2[1])) {
						parse_str(parse_url($url, PHP_URL_QUERY), $parametres);

						foreach ($parametres as $key => $val) {
							$items[] = $key;
							$items[] = $val;
						}
					}
				}
				else{
					unset($items[$key]);
					$items[$key] = $value;
				}
			}
		}



		// extract data
		if (count($items)) {
			self::$controller = array_shift($items);
			self::$action = array_shift($items);

			// TO OPTIMIZE
			self::$params = self::setParams($items);
			// self::$params = $items;
		}
	}

	protected function __construct() {
		self::$rules = array();
	}

	public static function init() {
		$url = $_SERVER['REQUEST_URI'];
		$isCustom = false;

		//print_r(self::$rules);

		if (count(self::$rules)) {
			foreach(self::$rules as $ruleKey => $ruleData) {
				$params = self::ruleMatch($ruleKey, $url);

				if ($params) {
					//print_r($params);
					self::$controller = $ruleData['controller'];
					self::$action = $ruleData['action'];
					self::$params = $params;
					$isCustom = true;
					break;
				}
			}
		}

		if (!$isCustom) self::defaultRoutes($url);

		if (!strlen(self::$controller)) self::$controller = 'home';
		if (!strlen(self::$action)) self::$action = 'index';
	}

	public static function addRule($rule, $target) {
		self::$rules[$rule] = $target;
	}


	// TO OPTIMIZE
	private static function setParams($items) {
		$params = array();
		if ( (!empty($items)) && (count($items)%2 == 0) ) {
			for ($i=0; $i<count($items); $i++) {
				if ($i%2 == 0) { $params[$items[$i]] = $items[$i+1]; }
			}
		}
		return $params;
	}





	public static function getController() { return self::$controller; }
	public static function getAction() { return self::$action; }
	public static function getParams() { return self::$params; }
	public static function getParam($id) { return self::$params[$id]; }
}
?>