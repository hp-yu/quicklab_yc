<?php
function check_auth_user()
  // see if somebody is logged in and notify them if not
  {
    global $_SESSION;
    if (isset($_COOKIE['wy_user']))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
function check_login_status () {
	if (!check_auth_user()) {
		header('Location: '.'login.php?ReturnUrl='.$_SERVER['REQUEST_URI'] );
	}
}
function loginx () {
	if (filled_out(array($_REQUEST["username"],$_REQUEST["password"]))) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$remember =$_POST['remember'];
		$db_conn = db_connect();
		$query = "SELECT * FROM `users` WHERE `username`=BINARY '$username' AND `password` =BINARY sha1('$password') AND `valid`='1'";
		echo $query;
		exit;
		$result = $db_conn->query($query);
		if ($result->num_rows >0 ) {
			if ($remember==false) {
				setcookie('wy_user',$username);
			} else {
				setcookie('wy_user',$username,(time()+60*60*24*7));//COOKIE save for 30 days
			}
		}
	}
	if (!check_auth_user()) {
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="CSS/general.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="5" topmargin="5">
<table id="header" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="left" valign="bottom">
<a href="index.php"><img src="./assets/image/quicklab.gif" width="200" height="40" border="0"></a>
</td>
<td align="right">
<table cellspacing="0" cellpadding="0">
<form method="POST" id="login" action="login.php" target="_self">
<tr><td>
<img src="./assets/image/general/user.gif" alt="Username" border="0" align="absmiddle"/>&nbsp;<input type="text" name="username" size="6" style="height:18">&nbsp;
<img src="./assets/image/general/key.gif" alt="Password" border="0"  align="absmiddle"/>&nbsp;<input type="password" name="password" size="6" style="height:18">&nbsp;<input type="submit" value="Login" style="height:18;font-size:7pt" align="absmiddle" >
</td></tr>
<tr><td valign="middle">
<input type="checkbox" name="remember" >Remember me&nbsp;&nbsp;</td></tr>
</form>
</table>
</td></tr>
</table>
<div class="chromestyle" id="chromemenu">
<ul><li></li></ul>
</div>
</body>
</html>
	<?php
	exit;
	}
}
function get_username($id)
  // get username from the people_id
  {
    $db_conn = db_connect();
    $query = "select * from users where people_id = '$id'";
    $result = $db_conn->query($query);
    return($result->fetch_assoc());
  }
function get_pid_from_username($username )
  // get username from the people_id
  {
    $db_conn = db_connect();
    $query = "select people_id from users where username  = '$username '";
    $result = $db_conn->query($query);
    $people=$result->fetch_assoc();
    return $people['people_id'];
  }

function check_user_authority($username)
  // check user has permission to act on this story
  {
    // connect to db
    $db_conn = db_connect();
	$query= "select authority from users
	            where username = '$username'";
	$result = $db_conn->query($query);
    $authority = $result->fetch_assoc();
    return $authority['authority'];
  }
function userPermission($authority,$pid='') {
	$userauth=check_user_authority($_COOKIE['wy_user']);
	$userpid=get_pid_from_username($_COOKIE['wy_user']);
	if($pid!='') {
		if($userauth<=$authority OR $userpid==$pid) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		if($userauth<=$authority) {
			return true;
		}
		else {
			return false;
		}
	}
}
function alert()
{
	echo '</table><table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
	do_rightbar();
    do_footer();
    do_html_footer();
  	exit;
}
function alert_map()
{
	echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
  	exit;
}
function isMask($module,$item_id)
{
	$userauth=check_user_authority($_COOKIE['wy_user']);
	if($userauth<=2)
	{
		return false;
	}
	else
	{
	  $userpid=get_pid_from_usernamE($_COOKIE['wy_user']);
	  $db_conn=db_connect();
	  $query="SELECT id FROM $module WHERE id='$item_id'
	  AND(mask=0 OR created_by='{$userpid}')";
	  $result = $db_conn->query($query);
	  if($result->num_rows==0)
	  {
	  	return true;
	  }
	  else
	  {
	  	return false;
	  }
	}
}
?>
