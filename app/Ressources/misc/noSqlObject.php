<?php

abstract class core_noSqlObject implements Iterator
{
	protected $memckey;
	protected $sClassName;
	protected $cache_method;
	protected $sCollectionName;
	protected $oMongoId;
	protected $sPrimaryKey;
	protected $historisation = false;

	protected $aMask = array('id' => '');
	protected $aCollection = array();
	protected $properties = array();
	protected $fields = array();
	protected $init = 0;


	public function __construct() {
		$aArgs = func_get_args();
		$iNbArgs = count($aArgs);
		$this->sClassName = get_class($this);
		$this->memckey = $this->sClassName;

		if (!$this->$sCollectionName) $this->$sCollectionName = $this->sClassName;

		switch ($iNbArgs) {
			case 0:
				$this->id = 0;
				$this->init = 1;
				$key = $this->sPrimaryKey;
				$this->$key = 0;
				
				foreach ($this->aCollection as $key => $sValue){
					$this->$key = new core_collection($sValue);
				}
				break;
				
			case 1:
			    
				if ($aArgs[0] !== 0) {
					$this->id = $aArgs[0];
					$this->oMongoId = new MongoId($this->id);
					$key = $this->sPrimaryKey;
					$this->$key = $aArgs[0];
				}
				else self::__construct();

				break;
			default:
				throw new Exception ("Nombre d'arguments invalide (maximum 1).");
		}
	}


	public function init() {

		$this->init = 1;
		
		// on construct l'objet depuis les infos de la DB
		
		$aParams = array('_id' => $this->oMongoId);
		$aResult = core_mongoManager::findOneResult($this->sCollectionName, $aParams);
		$iNbResult = count($aResult);
		
		if ($iNbResult > 0 ) {
		    
			foreach  ($aResult as $sFieldName => $mValue) {
				$fields[] = $sFieldName;

				// patch pour id au debut des objet liés
				if(strpos($sFieldName, "id")==0)
				    $field_obj = lcfirst(substr($sFieldName, 2, strlen($sFieldName)));

				if (array_key_exists($sFieldName, $this->masque) && empty($this->masque[$sFieldName])) {
					$this->$sFieldName = $data[$sFieldName];
				}
				else {

					if (isset($this->masque[$field_obj])) {
						$obj_class = $this->masque[$field_obj];
						$this->$field_obj = new $obj_class($data[$sFieldName]);

					} 
					elseif (isset($this->collections[$sFieldName])) {
						//echo "new core_collection ".$data[$sFieldName]." ".$this->collections[$sFieldName];
						$this->$sFieldName = new core_collection($data[$sFieldName], $this->collections[$sFieldName]);
					}
				}
			}
		} 
		else {
			try{
				throw new Exception ("L'objet $this->classname (" . $this->id . ") n'existe pas.", 101);
			}
			catch(Exception $e) {
				echo $e->getTraceAsString();
			}
		}
		core_memc::set($this->memckey . '-' . $this->id, serialize($this), 600);
	}

		public function __get($name)
		{

			if($name != "id" && $name!=$this->primary_key && $this->init != 1 && $this->id!==0) $this->init();
			if (!isset($this->properties[$name])) {

				$this->properties[$name] = $this->getDB($name);

			}
			//echo "<br />GET $name:".$this->properties[$name];
			return $this->properties[$name];
		}


		public function getDB($name)
		{
			if ($this->id) {
				$key = $this->primary_key;
				$query = "select $name from $this->tablename where $key = '" . $this->id."'";

				$result = core_sql::sql_query($query, DB_NAME, true);
				switch (mysql_errno()) {
					case 0:
						$data = mysql_fetch_array($result, MYSQL_ASSOC);
						return $data[$name];
						break;
					case 1054:
						$query = "select " . $name . "s_id from $this->tablename where id = " . $this->id;
						$result = core_sql::sql_query($query, DB_NAME, true);
						switch (mysql_errno()) {
							case 0:
								$data = mysql_fetch_array($result, MYSQL_ASSOC);
								if ($data[$name . 's_id']) return new $name($data[$name . 's_id']);
								else return null;
								break;
							case 1054:
								return null;
								break;
						}
					default:
						if($name!=""){
							print_r(debug_backtrace());
							throw new Exception ("$name n'est pas une propriété valide.");
						}

						break;
				}
			}elseif($this->init == 1){
				return null;
			}
			else{
			 print_r(debug_backtrace());
			 throw new Exception ("Objet non instancié".$this->classname." ".$this->id);
			}
		}


		public function __set($name, $value)
		{

			if($name != "id" && $name!=$this->primary_key && $this->init!=1 && $this->id!=0){
				$this->init();
				$this->$name = $value;
			}
			if (isset($value)) {
				$this->properties[$name] = $value;
			}
		}


		public function __isset($name)
		{
			if($name != "id" && $name!=$this->primary_key && $this->init!=1) $this->init();
			return isset($this->properties[$name]);
		}


		public function __unset($name)
		{
			unset($this->properties[$name]);
		}


		public function __toString(){
			$key = $this->primary_key;
			if($this->$key)
			return $this->$key."";
			else
			return "0";
		}


		public function __clone()
		{
			foreach ($this as $key => $value) {
				if (gettype($value) == 'object') $this->properties[$key] = clone $value;
			}
		}


		public function rewind()
		{
			reset($this->properties);
		}


		public function current()
		{
			return current($this->properties);
		}


		public function key()
		{
			return key($this->properties);
		}


		public function next()
		{
			return next($this->properties);
		}


		public function valid()
		{
			return ($this->current() !== false);
		}


		protected function getFields()
		{
			if (count($this->fields) == 0) {
				//                $query = "select * from $this->tablename limit 0, 1";
				//                $result = core_sql::sql_query($query, DB_NAME, false, true);
				//                $nb_fields = mysql_num_fields($result);
				//                for ($i = 0; $i < $nb_fields; $i++) {
				//                    $fields[] = mysql_field_name($result, $i);
				//                }
					$mask= array_keys($this->masque);
					$collect= array_keys($this->collections);
					$fields = array_merge($mask,$collect);
					unset($fields["id"]);
			}
			return $fields;
		}


		public function update()
		{
			global $user;
			if ($this->init!=1) {
				$this->init();
			}

			if($this->historisation == true){
				$hist = new core_myobjectHistorisation();
				$hist->classe = $this->classname;
				$hist->idobjet = $this->id;
				$before = new $this->classname ($this->id);
				$before->init();
				$hist->objetbefore = serialize($before);
				$hist->objetafter = serialize($this);
				$hist->timestamp = time();
				$hist->iduser = $user->idUser;
				$hist->add();
			}

			if ($this->id) {
				$set_clause = '';
				$fields = $this->getFields();

				//debug($this);

				$nb_fields = count($fields);
				for ($i = 0; $i < $nb_fields; $i++) {
					$field_name = $fields[$i];

					if (array_key_exists($field_name, $this->masque) && empty($this->masque[$field_name])) {


						if (isset($this->$field_name)) {
							if ($set_clause == '') $set_clause = " `" . $field_name . "` = '" . addslashes(stripslashes($this->$field_name)) . "'";
							else $set_clause .= ", `" . $field_name . "` = '" . addslashes(stripslashes($this->$field_name)) . "'";
						}

						//print $this->$field_name; print '<br />';

					}else {

						if (isset($this->masque[$field_name])) {
							if (isset($this->$field_name)) {
								if ($set_clause == '') $set_clause = " id`" . $field_name . "` = '" . $this->$field_name->id . "'";
								else $set_clause .= ", id`" . $field_name . "` = '" . $this->$field_name->id . "'";
							}
						}elseif (isset($this->collections[$field_name])) {

							if (isset($this->$field_name)) {
								if ($set_clause == '') $set_clause = " `" . $field_name . "` = '" . $this->$field_name . "'";
								else $set_clause .= ", `" . $field_name . "` = '" . $this->$field_name . "'";
							}
						}
					}
				}
				$query = "update $this->tablename set $set_clause where $this->primary_key = '" . $this->id."'";
				
                

				if (core_sql::sql_query($query, DB_NAME, true)) {
					core_memc::set($this->memckey . '-' . $this->id, serialize($this), 600);
					return true;
				} else {
					throw new Exception ("Update : Erreur lors de l'update");
					return false;
				}
			}else {
				throw new Exception ("Update : Id non d�fini.");
				return false;
			}
		}

		public function delete()
		{
			if ($this->id) {
				$query = "delete from $this->tablename where $this->primary_key = " . $this->id;
				if (!core_sql::sql_query($query, DB_NAME, true)) {
					throw new Exception ("Delete : Erreur lors de la suppression dans " . $this->tablename . ".");
					return false;
				}
				$this->flush();
				return true;
			} else {
				throw new Exception ("Delete : Id non d�fini.");
				return false;
			}
		}

		public function add()
		{
			// ADD

			if (!$this->id) {
				$fields_clause = array();
				$values_clause = array();
				$fields = $this->getFields();
				unset($fields[$this->primary_key]);
				$nb_fields = count($fields);


				for ($i = 0; $i < $nb_fields; $i++) {

					$field_name =$fields[$i]; 

					if ($field_name !== $this->primary_key) {


						if (empty($this->masque[$field_name]) && !isset($this->collections[$field_name])) {

							if (isset($this->$field_name)) {

								$fields_clause[] = "`".$field_name."`";
								if ($this->$field_name === '') $values_clauses[] = 'NULL';
								else $values_clauses[] = "'" . addslashes(stripslashes($this->$field_name)) . "'";

							}
						} else {

							if (isset($this->masque[$field_name])) {

								if (isset($this->$field_name)) {

									$fields_clause[] = "id" .ucfirst( "`".$field_name."`");
									try{
										$values_clauses[] =  " '" . $this->$field_name . "'";
									}
									catch(exception $e){
										print_r($e);
									}

									//echo $this->$field_name ."\n\n";

								}

							} elseif (isset($this->collections[$field_name])) {

								//echo $this->collections[$field_name];

								if (isset($this->$field_name)) {
									$fields_clause[] =  "`".$field_name."`";
									$values_clauses[] = " '" . $this->$field_name . "'";
								}else {
									echo "not expected 2";
								}

							}
						}
					}
				}
				//print_r($values_clauses);
				$query = "insert into $this->tablename (" . implode(", ", $fields_clause) . ") values (" . implode(", ", $values_clauses) . ")";
                
				//print $query;print "\n";



				if (core_sql::sql_query($query, DB_NAME, true) or die(mysql_error())) {
					$this->id = mysql_insert_id(core_sql::sql_link(DB_NAME));
					$key = $this->primary_key;
					$this->$key = $this->id;


					core_memc::set($this->memckey . '-' . $this->id, serialize($this), 600);
					return true;
				} else {
				
				
					debug(mysql_error());
					if (mysql_errno() == 1062) throw new Exception ("Insert : Duplicate key.");
					else throw new Exception ("Insert : Erreur lors de l'enregistrement ------ mysql error : " . mysql_errno() . " - " . mysql_error() . "<br>" . $query);
					return false;
				}
			} else {
				return $this->update();
			}
		}

		public function flush()
		{
			core_memc::delete($this->memckey . '-' . $this->id);
		}

		public function get_elements() {
			$that = array();
			foreach($this->arrElements as $key => $val){
				if (is_object($val)) {

					$that[$key] = $val->get_elements();

				}
				else{
					$that[$key] = $val;
				}
			}
			//
			return $that;
		}

		public function insert_with_id()
		{
			$this->init = 1;
			$fields_clause = '';
			$values_clause = '';
			$fields = $this->getFields();

			$nb_fields = count($fields);
			for ($i = 0; $i < $nb_fields; $i++) {
				$field_name = $fields[$i];
				if ($field_name) {
					if (isset($this->masque[$field_name]) && !empty($this->masque[$field_name])) {
						if ($fields_clause == '') $fields_clause = "id".$field_name;
						else $fields_clause .= ", id" . $field_name;
					}
					else{
						if ($fields_clause == '') $fields_clause = $field_name;
						else $fields_clause .= ", " . $field_name;
					}

					$property_name = $field_name;
					if (isset($this->$property_name)) {
						if ($values_clause == '') $values_clause = "'" . addslashes(stripslashes($this->$property_name)) . "'";
						else $values_clause .= ", '" . addslashes(stripslashes($this->$property_name)) . "'";
					} else {
						if ($values_clause == '') $values_clause = "NULL";
						else $values_clause .= ", NULL";
					}
				}
			}
			$query = "insert into $this->tablename ($fields_clause) values ($values_clause)";

			if (core_sql::sql_query($query)) {
				$this->id = mysql_insert_id();
				core_memc::set($this->memckey . '-' . $this->id, serialize($this), 600);
				return true;
			} else {
				if (mysql_errno() == 1062) throw new Exception ("Insert : Duplicate key.");
				else throw new Exception ("Insert : Erreur lors de l'enregistrement ------ mysql error : " . mysql_errno() . " - " . mysql_error() . "<br>" . $query);
				return false;
			}
		}

		public function getCollection($table, $class, $key, $foreign_key, $val){
			$sql="SELECT GROUP_CONCAT($key) as ids FROM $table WHERE $foreign_key='".$val."'";
			$res=core_sql::sql_cached_query($sql, DB_NAME, "clients-membres-".$this->membre->idmembre);
			return new core_collection($res[0]["ids"], $class);
		}

		public function objectToArray($lvl=0){
			$tmp = array();
			$fields = $this->getFields();
			$nb_fields = count($fields);
			$lvl++;
			if($lvl>3){
				return $tmp;
			}
			else{
				for ($i = 0; $i < $nb_fields; $i++) {
					$field_name = $fields[$i];
					if(is_object($this->$field_name)){
						if($this->$field_name->id)
						$tmp[$field_name] = $this->$field_name->objectToArray($lvl);
					}
					else
					$tmp[$field_name] = $this->$field_name;
				}

				return $tmp;
			}

		}


}

?>
