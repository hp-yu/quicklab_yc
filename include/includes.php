<?php
include_once('db_conn.php');
include_once('db_fns.php');
include_once('user_auth_fns.php');
include_once('output_fns.php');
//include_once('form_fns.php');
include_once('storage_fns.php');
include_once('data_valid_fns.php');
include_once('results_inc.php');
include_once('mailer.php');
ob_start();
if(function_exists(session_cache_limiter)) {
	session_cache_limiter("private, must-revalidate");
}
session_start();

if ($_SERVER['SERVER_ADDR']) {
	$ip_address=$_SERVER['SERVER_ADDR'];
}
define("IP_ADDRESS",$ip_address);
?>
