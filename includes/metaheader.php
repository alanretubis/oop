<?php
  // error_reporting(E_ALL);
  // ini_set("display_errors", 1);
  ob_start();
  
  $init_configs = parse_ini_file("APPS.ini");
  $page_title = $init_configs['SITE_NAME'];
  $business = $init_configs['SITE_SUBSTITLE'];

  date_default_timezone_set("Asia/Manila");
  
?>