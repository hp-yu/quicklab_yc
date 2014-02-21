<?php
include('include/includes.php');

if (isset($_REQUEST['code'])&&$_REQUEST['code']!='') {
	$code=$_REQUEST['code'];
	if (is_numeric($code)&&strlen($code)==8) {
		$module_id=substr($code,0,2);
		while (substr($module_id,0,1)==0) {
			$module_id=substr($module_id,-(strlen($module_id)-1));
		}
		$item_id=substr($code,-6);
		while (substr($item_id,0,1)==0) {
			$item_id=substr($item_id,-(strlen($item_id)-1));
		}
		$db_conn=db_connect();
		$query="SELECT name FROM modules WHERE id='$module_id'";
		$rs=$db_conn->query($query);
		$module=$rs->fetch_assoc();
		header('Location:'.$module['name'].'_operate.php?type=detail&id='.$item_id);
	}
}
?>
