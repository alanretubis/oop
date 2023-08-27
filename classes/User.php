<?php
class User{
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$user_no = "user_id",
			$name = "name",
			$permission = "permission",
			$_user_id = "",
			$_user_permission = "",
			$_isLoggedIn;

	public function __construct($user = null){
		$this->_db = DB::getInstance(); 

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);

				if($this->find($user)){
					$this->_isLoggedIn = true;
				}else{
					//process logout
				}
			}
		}else{
			$this->find($user);
		}
	}

	public function update($fields = array(), $user_id = null){

		if(!$user_id && $this->isLoggedIn()){
			$user_id = $this->data()->user_id;

		}

		if(!$this->_db->update('tbl_users', 'user_id', $user_id, $fields)){
			throw new Exception('There was a problem updating.');
		}
	}

	public function create($fields = array()){
		try{
			if(!$this->_db->insert('tbl_users', $fields)){
				//throw new Exception('There was a problem creating an account.');
			}
		}catch(Exception $ex)
		{
			throw new Exception($ex->getMessage());	
		}
	}

	public function find($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('tbl_users', array($field, '=' , $user));
			if($data){
				if($data->count()){
				$this->_data = $data->first();
				Session::put($this->user_no, $this->data()->id);

				$name = $this->data()->first_name." ".$this->data()->surname;

				Session::put($this->name, $name);
				return true;
				}
			}

		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false){
		
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}else{

			
			$user = "";

			$user = $this->find($username);

			if($user){
				if($this->data()->password === Hash::make($password, $this->data()->salt)){
					Session::put($this->_sessionName, $this->data()->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('tbl_user_session', array('id', '=', $this->data()->id));

						if(!$hashCheck->count()){
							$this->_db->insert('tbl_user_session',array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

					}

					return true;
				}

			}

		}
		return false;
	}

	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}

	public function logout(){

		$this->_db->delete('tbl_user_session', array('user_id', '=', $this->data()->user_id));

		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data(){
		return $this->_data;
	}
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
}