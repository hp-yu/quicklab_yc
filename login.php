<?php
include('include/includes.php');

$username = $_POST['username'];
$password = $_POST['password'];
$remember =$_POST['remember'];

$db_conn = db_connect();
$query="SELECT * FROM users WHERE username=BINARY '$username' AND password =BINARY sha1('$password') AND valid='1'";
$result = $db_conn->query($query);
if ($result->num_rows >0 ) {
	if ($remember==false) {
		setcookie('wy_user',$username);
	} else {
		setcookie('wy_user',$username,(time()+60*60*24*7));//COOKIE save for 30 days
	}
}
header('Location: '.$_SERVER['HTTP_REFERER']);
?>
