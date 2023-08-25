<?php
$init_configs = parse_ini_file("APPS.ini");
 require_once $_SERVER['DOCUMENT_ROOT'] . '/' . $init_configs['SITE_FOLDER'] . '/core/init.php';

if(Session::exists('home')){
  echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();

if(!$user->isLoggedIn()){
  Redirect::to('login.php');
}

$db = DB::getInstance();
?>