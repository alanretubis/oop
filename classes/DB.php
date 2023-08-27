<?php
class DB{
	private static $_instance = null;
	private $_pdo,
			$_query, 
			$_error = false,
			$_results, 
			$_count = 0,
			$_lastId = 0,
			$_errorInfo;
	
	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname='. Config::get('mysql/db') , Config::get('mysql/username') , Config::get('mysql/password'));

		}catch(PDOException $e){
			die($e->getMessage());
		 }
	}
	
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function beginTransaction(){
		return $this->_pdo->beginTransaction();
	}

	public function commit(){
		return $this->_pdo->commit();
	}

	public function rollBack(){
		return $this->_pdo->rollBack();
	}

	public function errorInfo(){
		// $err = $this->_pdo->errorInfo();
		return $this->_errorInfo[2];
	}
	
	public function query($sql, $params = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
				$this->_lastId = $this->_pdo->lastInsertId();
			}
			else{
				$this->_error = true;
				$this->_errorInfo = $this->_query->errorInfo();
			}
		}
		return $this;
	}
	
	public function action($action, $table, $where = array(), $orderby = null){

		$operators = array('=','>','<', '>=', '<=', '!=', 'LIKE');
		
		if(count($where) === 3){
		
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
		
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				if($orderby != null){
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? {$orderby}";
				}

				if(!$this->query($sql, array($value))->error()){
					return $this;	
				}
			}
		}elseif(count($where) > 3 && count($where) % 3 == 0){

			$count = count($where);

			$arr = array_chunk($where, 3);
			$condition = "";
			$params = array();
			$i = 0;

			foreach ($arr as $key => $val) {

				$field 		= $val[0];
				$operator 	= $val[1];
				$value 		= $val[2];

				array_push($params, $value);

				if($i == 0){
					$condition .= "{$field} {$operator} ?";
				}else{
					$condition .= " AND {$field} {$operator} ?";
				}
				$i++;
			}
		
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$condition}";
				
				if($orderby != null){
					$sql = "{$action} FROM {$table} WHERE {$condition} {$orderby}";
				}

				if(!$this->query($sql, $params)->error()){
					return $this;	
				}
			}

		}

		return false;
	}
	
	public function get($table, $where, $orderby = null){
		return $this->action('SELECT *', $table, $where, $orderby);
	}
	
	public function delete($table, $where){
		return $this->action('DELETE ', $table, $where);
	}
	
	public function insert($table, $fields = array()){
			$keys = array_keys($fields);
			$values = '';
			$x = 1;
			
			foreach($fields as $field){
				$values .= "?";
				if($x < count ($fields)){
					$values .= ', ';
				}
				$x++;
			}			
			$sql = "INSERT INTO {$table}(`". implode('`,`', $keys) ."`) VALUES({$values})";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
			return false;
	}
	
	public function update($table, $id_field, $id, $fields){
		$set = '';
		$x = 1;
		
		foreach($fields as $name => $value){
			$set .= "{$name} = ?";
			if($x<count($fields)){
				$set .= ', ';
			}
			$x++;
		}
		
		$sql = "UPDATE {$table} SET {$set} WHERE {$id_field} = {$id}";
			
		if(!$this->query($sql, $fields)->error()){
			return true;
		}
		return false;
	}
	
	public function results(){
		return $this->_results;
	}
	
	public function first(){
		return $this->_results[0];
	}
	
	public function error(){
		return $this->_error;
	}
	
	public function count(){
		return $this->_count;
	}

	public function lastId(){
		return $this->_lastId;
	}
			
} //class DB