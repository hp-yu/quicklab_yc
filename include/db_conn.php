<?php
  function db_connect(){
    @$db_conn= new mysqli('localhost', 'root', '', 'quicklab');
	if (mysqli_connect_errno()){
	  return false;
	}$db_conn->query('SET NAMES utf8');
	return $db_conn;
  }
?>