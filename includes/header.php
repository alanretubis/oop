<?php
$init_configs = parse_ini_file("APPS.ini");
$page_title = $init_configs['SITE_NAME'];
$business = $init_configs['SITE_SUBSTITLE'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
                              
$user_no = $_SESSION['user'];
$user_name = $_SESSION['name'];
$user_access = $_SESSION['permission'];