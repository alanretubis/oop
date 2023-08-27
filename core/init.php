<?php
define('SITE_URL', $_SERVER['DOCUMENT_ROOT'] . '/oop/');

session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'db' => 'blog_db'
						  ),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 86400
                          ),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
  					      )
);

spl_autoload_register(function($class){
	require_once SITE_URL .'classes/'. $class . '.php';
}
);

require_once SITE_URL .'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('tbl_user_session', array('hash', '=', $hash));

	if($hashCheck->count()){
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}



