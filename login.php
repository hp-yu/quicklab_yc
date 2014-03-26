<?php
include('include/includes.php');
?>

<?php
do_html_header_begin('Login-Quicklab');
?>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.login_form.action.value = "login";
	document.login_form.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#login_form").validate({
		rules: {
			username: "required",
			password: "required"
		},
		messages: {
			username: {required: 'required'},
			password: {required: 'required'}
		}});
	$("#username").focus();
});
</script>
<?php
do_html_header_end();
if ($_REQUEST['action']=="login") {
	login();
}
?>
<form method="POST" id="login_form" name="login_form" target="">
<table cellspacing="5" cellpadding="5" class="login">
<tr>
<td colspan="2">
Username:</br>
<input type="text" name="username" id="username" class="login">&nbsp;
</td>
</tr>
<tr>
<td colspan="2">
Password:</br>
<input type="password" name="password" id="password" class="login" >&nbsp;
</td>
</tr>
<tr>
<td>
<input type="checkbox" name="remember" >Remember me&nbsp;&nbsp;
</td>
<td>
<input name="submit" type="submit" value="Login">
</td>
</tr>
<input type='hidden' name='action' value='login' >
</table>
</form>

<?php
function login() {
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
		if (isset($_REQUEST['ReturnUrl'])&&$_REQUEST['ReturnUrl']!='') {
			header('Location: '.$_REQUEST['ReturnUrl']);
		}
		else {
			header('Location: '.'index.php');
		}


	}
}


?>
