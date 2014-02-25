<?php
  function db_connect(){
    @$db_conn= new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno()){
	  return false;
	}$db_conn->query('SET NAMES utf8');
	return $db_conn;
  }
?>