<?php
require_once 'core/init.php';

try{

$user = new User();
$salt = Hash::salt(32);

try{

    $user->create(array(
        'username' => 'johndoe',
        'password' => Hash::make('password', $salt),
        'salt' => $salt,
        'first_name' => 'John',
        'surname' => 'Doe',
        'active' => 1
    ));

}catch(Exception $e){
    die($e->getMessage());
}

Redirect::to('index.php');

}catch(Exception $ex){
	echo $ex->getMessage();
}