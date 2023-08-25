<?php
class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()){
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {

				$value  = trim($source[$item]);
				$item = escape($item);

				$tag_name;
				$filter = isset($source['filter']) ? $source['filter'] : '';
				if ($filter !== ''){
					$filter_val = $source[$filter]; # this also means update
				}

				if($rule === 'tag_name'){
					$tag_name = $rule_value;
				}

				if($rule === 'required' && empty($value)){
					$this->addError("{$tag_name} is required");
				}else if(!empty($value)){
					switch($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this->addError("{$tag_name} must  be a minimun of {$rule_value} characters.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$tag_name} must  be a maximum of {$rule_value} characters.");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$tag_name} must match {$rule_value}");
							}
						break;
						case 'unique':
							$field = "";
							$fieldVal = "";
							if ($filter !== "" && $filter_val !== "") {
								$field = "{$filter}";
								$fieldVal = "{$filter_val}";

								$sql = "SELECT * FROM {$rule_value} WHERE {$item} = ? AND  {$field} != ?";
								$params = array($value, $fieldVal);
								$check = $this->_db->query($sql, $params);
								
							}else{

								$check = $this->_db->get($rule_value, array($item, '=', $value));

							}
							if($check){
								if($check->count()){
									$this->addError("{$tag_name} already exist.");
								}
							}
						break;
						case 'alphanumeric':
							if (!preg_match("/^[a-z0-9]+([\\s]{1}[a-z0-9]|[a-z0-9])+$/i", $value)) {
							   $this->addError("{$tag_name} must be combination of letters and numbers.");
							}
						break;
						case 'numeric':
							// if (!preg_match('/^\d+$/', $value)) {
							if (!is_numeric($value)) {
							   $this->addError("{$tag_name} must be numeric.");
							}
						break;
						case 'email';
							if(filter_var($value, FILTER_VALIDATE_EMAIL) === false){
								$this->addError("Email is not valid");
							}
						break;
					}

				}
			}
		}

		if(empty($this->_errors)){
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return $this->_passed;
	}
}