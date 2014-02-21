<?php
include ("php/phprpc_server.php");
include("../includes.php");
function get_suggestions ($table,$field,$keyword) {
	$patterns = array('/\s+/', '/"+/', '/%+/');
	$replace = array('');
	$keyword = preg_replace($patterns, $replace, $keyword);
	$db_conn= db_connect();
	if($keyword != '') {
		$query = "SELECT DISTINCT `$field` FROM $table WHERE `$field` LIKE '%" . $keyword . "%' ORDER BY `$field`";
	} else {
		$query = "SELECT DISTINCT `$field` FROM $table WHERE `$field` = ''";
	}
	$rs = $db_conn -> query ($query);
	$output = array();
	while ($match = $rs->fetch_assoc()) {
		$output[] = $match[$field];
	}
	return  $output;
}
$server = new PHPRPC_Server();
$server->add("get_suggestions");
$server->start();
?>