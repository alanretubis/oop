<?php
require_once 'core/init.php';

try{

$user = new User();
$user->logout();

Redirect::to('index.php');

}catch(Exception $ex){
	echo $ex->getMessage();
}