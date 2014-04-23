<?php
function do_html_header($title)
{
		// print an HTML header
?>
  <html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title;?></title>
	<link href="css/general.css" rel="stylesheet" type="text/css" />
	<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
	<BASE target='_self'>
  </head>
  <body leftmargin="5" topmargin="5">
<?php
}
function do_html_header_begin($title)
{
	// print an HTML header
?>
  <html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title;?></title>
	<link href="css/general.css" rel="stylesheet" type="text/css" />		
	<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
	<BASE target='_self'>
<?php
}
function do_html_header_end()
{
	// print an HTML header
?>
  </head>
  <body leftmargin="5" topmargin="5">
<?php
}
function do_html_footer() {
	// print an HTML footer
?>
  </body>
  </html>
<?php
}

function do_header() {
	check_login_status();
	?>
<table id="header" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="left" valign="bottom">
<a href="index.php"><img src="./assets/image/quicklab.gif" width="200" height="40" border="0"></a>
</td>
<td align="right"  >
<table cellspacing="0" cellpadding="0" >
	<?php
	$db_conn = db_connect();
	$query = "SELECT p.id,p.name FROM users u, people p
			   WHERE p.id = u.people_id and u.username='{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	echo "<tr><td >".$match['name'].", ".$_COOKIE['wy_user'];
	$userauth=check_user_authority($_COOKIE['wy_user']);
	$authority_name = array( array( '1', 'super administrator'),
	array( '2', 'administrator'),
	array( '3', 'staff'),
	array( '4', 'user'));
	for ($i=0; $i < 4; $i++) {
		if ($authority_name[$i][0] == $userauth) {
			echo " [{$authority_name[$i][1]}]</td></tr>";
		}
	}
	echo "<tr><td align='right'><a href='./logout.php'>Log out</a> | <a href='change_password.php?username=".$_COOKIE['wy_user']."'>Change password</a></td></tr>";
 	?>
</table>
</td>
</tr>
</table>
<script src="include/chromejs/chrome.js" type="text/javascript" ></script>
<script src="include/jquery/jquery.watermark.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
		$("#code").watermark("Enter Quick ID");
	});
</script>
<table id="menu" width="100%" cellpadding="0" cellspacing="0" class="menu">
<tr>
<td>
<div class="chromestyle" id="chromemenu">
<ul>
<li><a href="./index.php">Home</a></li>
<li><a href="#" rel="dropmenu1">Materials</a></li>
<li><a href="#" rel="dropmenu2">Methods</a></li>
<li><a href="#" rel="dropmenu3">Tools</a></li>
<!--<li><a href="#" rel="dropmenu6">Schedule</a></li>-->
<li><a href="#" rel="dropmenu7">Manage</a></li>
<li><a href="#" rel="dropmenu4">Admin</a></li>
<!--<li><a href="#" rel="dropmenu5">Help</a></li>-->
</ul>
</div>
</td>
<td style="background:#006633" align="right" valign="middle">
<form name="quickIdFrom" action="code.php" method="get" target="_blank">
<input class="code" type="search" name="code" id="code" style="height:18px;WIDTH:120px;" placeholder="Enter Quick ID">
<!--<input type="submit" value="Go" style="font-size:10;height:20px;">-->
</form>
</td>
</tr>
</table>
<!--1st drop down menu -->
<div id="dropmenu1" class="dropmenudiv" style="width: 150px;">
	<?php
	$query="SELECT * FROM modules WHERE material=1 ORDER BY name";
	$result=$db_conn->query($query);
	while ($match=$result->fetch_assoc()) {
		echo "<a href='./".$match['table'].".php'>".ucfirst($match['name'])."</a>";
	}
	?>
</div>
<div id="dropmenu2" class="dropmenudiv" style="width: 150px;">
<a href="./kbase.php"  target="_blank">Knowledge base</a>
</div>
<div id="dropmenu3" class="dropmenudiv" style="width: 200px;">
<a href="./storages.php">Storage</a>
<a href="./plasmid_mapping.php">Plasmid mapping</a>
<!--<a href="./label.php">Label printer</a>-->
<!--<a href="./solution_calc.php">Solution calculator</a>-->
<a href="./jme.php">Chemical structure editor</a>
<a href="./sendmail.php">Send mail</a>
<!--<a href="./posts.php">Posts</a>-->
</div>

<div id="dropmenu4" class="dropmenudiv" style="width: 150px;">
<a href="./people.php">People</a>
<a href="./users.php">Users</a>
<!--<a href="./roles.php">Roles</a>-->
<a href="./projects.php">Projects</a>
<a href="./accounts.php">Accounts</a>
<a href="./sellers.php">Sellers</a>
<!--<a href="./abook.php">Address book</a>-->
<a href="./ordering_rules.php">Ordering rules</a>
<a href="./rebase.php">Restriction enzyme</a>
<a href="./species.php">Species</a>
<a href="./ani_strains.php">Animal strains</a>
<a href="./reagent_cat.php">Reagent categories</a>
<a href="./supply_cat.php">Supply categories</a>
<a href="./device_cat.php">Device categories</a>
<a href="./mail_setting.php">Mail setting</a>
<!--<a href="./custom_fields.php">Custom fields</a>-->
</div>

<div id="dropmenu5" class="dropmenudiv" style="width: 150px;">
<a href="./tutorial.php">Tutorial</a>
<a href="./about.php">About Quicklab</a>
</div>
<!--<div id="dropmenu6" class="dropmenudiv" style="width: 150px;">
<a href="./planner.php">Planner</a>
</div>-->
</div>
<div id="dropmenu7" class="dropmenudiv" style="width: 150px;">
<a href="./orders.php">Orders</a>
<a href="./labmap.php" target="_blank">Lab map</a>
<!--<a href="./plasmid_request.php">Plasmid request</a>
<a href="./plasmid_bank.php">Plasmid bank</a>-->
</div>
<script type="text/javascript">
cssdropdown.startchrome("chromemenu")
</script>
	<?php
}

function do_header_3() {
?>
<table id="header" width="100%" cellpadding="0" cellspacing="0">
  <tr>
  	<td align="left" valign="bottom">
  		<a href="index.php"><img src="./assets/image/quicklab.gif" width="200" height="40" border="0"></a>
  	</td>
  </tr>
</table>
<table id="menu" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
  	<div class="chromestyle" id="chromemenu">
  	<ul><li></li></ul>
  	</div>
  	</td>
  </tr>
</table>
<?php
}

function do_header_2()
{
?>
<table id="header" width="100%" cellpadding="0" cellspacing="0">
  <tr>
  	<td align="left" valign="bottom">
  		<a href="index.php"><img src="./assets/image/quicklab.gif" width="200" height="40" border="0"></a>
  	</td>
  <?php
  if (check_auth_user()) {
  ?>
  <td align="right"  >
      <table cellspacing="0" cellpadding="0" >
    	<?php
    	$db_conn = db_connect();
    	$query = "SELECT p.id,p.name FROM users u, people p
			   WHERE p.id = u.people_id and u.username='{$_COOKIE['wy_user']}'";
    	$result = $db_conn->query($query);
    	$match=$result->fetch_assoc();
    	echo "<tr><td >".$match['name'].", ".$_COOKIE['wy_user'];
    	$userauth=check_user_authority($_COOKIE['wy_user']);
    	$authority_name = array( array( '1', 'super administrator'),
    	array( '2', 'administrator'),
    	array( '3', 'staff'),
    	array( '4', 'user'));
    	for ($i=0; $i < 4; $i++) {
    		if ($authority_name[$i][0] == $userauth) {
    			echo " [{$authority_name[$i][1]}]</td></tr>";
    		}
    	}
    	echo "<tr><td align='right'><a href='./logout.php'>Log out</a> | <a href='change_password.php?username=".$_COOKIE['wy_user']."'>Change password</a></td></tr>";
  }
  else {
    ?>
      <td align="right">
      <table cellspacing="0" cellpadding="0">
  		<form method="POST" id="login" action="login.php" >
  		<tr><td><img
	    src="./assets/image/general/user.gif" alt="Username" title="Username" border="0" align="absmiddle"/>&nbsp;<input type="text" name="username" size="6" style="height:18">&nbsp;<img
	    src="./assets/image/general/key.gif" alt="Password" title="Password" border="0"  align="absmiddle"/>&nbsp;<input type="password" name="password" size="6" style="height:18">&nbsp;<input type="submit" value="Login" style="height:18;font-size:7pt" align="absmiddle" ></td></tr>
  		<tr><td valign="middle">
  		<input type="checkbox" name="remember" >Remember me&nbsp;&nbsp;
  		<a href='register.php'>Register</a></td></tr>
  		<input type="hidden" name="destinatioin" value="<?php echo $_SERVER['HTTP_REFERER'];?>"/>
  		</form>
    <?php
  }
 	?>
  </table>
  </td>
  </tr>
</table>
<?php
  if(check_auth_user()) {
?>
<script type="text/javascript" src="include/chromejs/chrome.js">
</script>
<table id="menu" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="chromestyle" id="chromemenu">
<ul>
<li><a href="./index.php">Home</a></li>
<li><a href="#" rel="dropmenu1">Materials</a></li>
<li><a href="#" rel="dropmenu2">Methods</a></li>
<li><a href="#" rel="dropmenu3">Tools</a></li>
<li><a href="#" rel="dropmenu4">Admin</a></li>
<li><a href="#" rel="dropmenu5">Help</a></li>
</ul>
</div>
</td>
<td style="background:#006633">
<div id="code">
<script>
function quickId() {
	if (document.quickIdFrom.code.value=="Quick ID") {
		document.quickIdFrom.code.value="";
	}
}
</script>
<form name="quickIdFrom" action="code.php" method="get" target="_blank">
<input class="code" type="text" size="15" name="code" style="font-size:10;height:15;text-align: middle;" value="Quick ID" onclick="javascript:quickId()" align="middle">
<input type="submit" value="Go" style="font-size:8;height:15">
</form>
</div>
</td>
</tr>
</table>
<!--1st drop down menu -->
<div id="dropmenu1" class="dropmenudiv" style="width: 150px;">
<?php
$query="SELECT * FROM modules ORDER BY name";
$result=$db_conn->query($query);
while ($match=$result->fetch_assoc()) {
	echo "<a href='./".$match['name'].".php'>".ucfirst($match['name'])."</a>";
}
?>
</div>

<div id="dropmenu2" class="dropmenudiv" style="width: 150px;">
<a href="./protocols.php"  target="_blank">Protocols</a>
</div>

<div id="dropmenu3" class="dropmenudiv" style="width: 150px;">
<a href="./orders.php">Oders manager</a>
<a href="./storages.php">Storage manager</a>
<a href="./labmap.php" target="_blank">LabMap manager</a>
<a href="./label.php">Label printer</a>
<a href="./solution_calc.php">Solution calculator</a>
<a href="./jme.php">Chemical structure editor</a>
</div>

<div id="dropmenu4" class="dropmenudiv" style="width: 150px;">
<a href="./people.php">People</a>
<a href="./users.php">Users</a>
<a href="./projects.php">Projects</a>
<a href="./accounts.php">Accounts</a>
<a href="./sellers.php">Sellers</a>
<a href="./ordering_rules.php">Ordering rules</a>
<a href="./species.php">Species</a>
<a href="./ani_strains.php">Animal strains</a>
<a href="./reagent_cat.php">Reagent categories</a>
<a href="./supply_cat.php">Supply categories</a>
<a href="./mail_setting.php">Mail setting</a>
<a href="./custom_fields.php">Custom fields</a>
</div>

<div id="dropmenu5" class="dropmenudiv" style="width: 150px;">
<a href="./tutorial.php">Tutorial</a>
<a href="./about.php">About Quicklab</a>
</div>
<script type="text/javascript">
cssdropdown.startchrome("chromemenu")
</script>

<?php
  }
  else {
?>
  <div class="chromestyle" id="chromemenu">
  <ul><li></li></ul>
  </div>
<?php
  }
}
function do_footer()
{
?>
</td></tr></table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="footer">
	<tr>
		<Td>&nbsp;</Td>
	</tr>
<!--  <tr>
    <td width="61%" class="footer">&copy;2007 Quicklab. Copyright.</td>
    <td width="39%" align="right" class="footer">Version 1.00 Beta</td>
  </tr>-->
</table>
<?php
}
function do_leftnav()
{
?>
<script type="text/javascript" src="./include/accordionjs/ddaccordion.js"></script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "submenuheader", //Shared CSS class name of headers group that are expandable
	contentclass: "submenu", //Shared CSS class name of contents group
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
	animatedefault: true, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", "openheader"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "normal" //speed of animation: "fast", "normal", or "slow"
})
</script>
<script>
function moveOption(e1, e2) {
	for(var i=0;i<e1.options.length;i++) {
		if(e1.options[i].selected){
			for (var n=0;n<e2.options.length;n++)
			{
				if(e2.options[n].text==e1.options[i].text)
				{return}
			}
			var e = e1.options[i];
			e2.options.add(new Option(e.text, e.value));
		}
	}
}
function removeOption(e1) {
	for(var i=0;i<e1.options.length;i++) {
		if(e1.options[i].selected) {
			e1.remove(i);
			i=i-1
		}
	}
}
function allselected(e1) {
	for(var i=0;i<e1.options.length;i++){
		e1.options[i].selected=true;
	}
}
</script>
  <table cellpadding="0" height="450" cellspacing="3" width="100%">
  <tr>
  <td align="left" valign="top">
  <table width="100%" class="standard" >
  <tr>
  	<td><span class="mylabtitle">Posts:</span></td>
  	<td align="right"><a href="posts_operate.php?type=add_form" /><img src="./assets/image/general/add-s.gif" border="0"></a>&nbsp;&nbsp;</td>
  </tr>
  <?php
  $db_conn=db_connect();
  $query="SELECT * FROM `posts` ORDER BY id DESC LIMIT 0 , 5";
  $rs=$db_conn->query($query);
  while ($match=$rs->fetch_assoc()) {
  	echo "<tr>";
  	echo "<td class=\"posts\" width = '200px'>";
  	$created_by = get_name_from_id('people',$match['created_by']);
  	$date=date('Y-m-d',strtotime($match['date_create']));
  	echo $created_by['name']." on ".$date." wrote:<br>";
  	echo "<a href='posts_operate.php?type=detail&id=".$match['id']."' target='_blank'>".$match['name']."</a>";
  	echo "</td>";
  	echo "</tr>";
  }
  echo "<tr><td align=\"right\"><a href=\"posts.php\" target=\"_blank\"/>more...</a></td></tr>";
  //birthday remaider
  $query="SELECT id,name, birthday FROM people WHERE birthday<>'0000-00-00' AND DATE_FORMAT(birthday,'%m-%d') >= DATE_FORMAT(now(),'%m-%d')
and DATE_FORMAT(birthday,'%m-%d') <= DATE_FORMAT(date_add(now(), interval 14 day),'%m-%d')";
  $rs=$db_conn->query($query);
  if ($rs->num_rows>0) {
  	echo "<tr><td class=\"mylabtitle\">Birthday:</td></tr>";
  }
  while ($match=$rs->fetch_assoc()) {
  	echo "<tr>";
  	echo "<td class=\"posts\">";
  	echo $match['name']." (".date('m-d',strtotime($match['birthday'])).")";
  	echo "</td>";
  	echo "</tr>";
  }
  ?>
  </table>
  <table width="100%" class="standard" style="margin-top:3pt">
  <tr><td><span class="mylabtitle">My Lab:</span></td></tr>
  <tr>
  <td>
  <div class="glossymenu">
  <?php
  for($i=0;$i<count($_REQUEST['clipboard']);$i++ ) {
  	unset($_SESSION['clipboard'][$_REQUEST['clipboard'][$i]]);
  }
  ?>
  <a class="menuitem submenuheader" class="menuitem">Clipboard
  <?php
  if(count($_SESSION['clipboard'])!=0) echo " (".count($_SESSION['clipboard']).")"
  ?>
  </a>
  <div class="submenu">
  <ul>
  <li>
  <form name='clipboard' method='post'>
  </li>
  <select name="clipboard[]" style="width:200px;font-size:7pt;" size="5" multiple
  	ondblclick="moveOption(document.getElementById('clipboard[]'), document.getElementById('clipboardtarget[]'))">
  <?php

  foreach ($_SESSION['clipboard'] as $key=>$value ) {
  	$key_array=split("_",$key);
  	$module=get_name_from_id(modules,$key_array[0]);
  	$item=get_name_from_id($module['name'],$key_array[1]);
  	echo "<option value=$key >".$module['name'].": ".$item['name']."</option>";
  }
  echo "</select></li>";
  echo "<li><input type='Submit' name='Submit' value='Delete' />";
  echo "</li></form>";?>
	</ul>
	</div>
	<?php
	  $pid=get_pid_from_username($_COOKIE['wy_user']);
	  $query="SELECT * FROM modules WHERE material=1 ORDER BY name";
		$results=$db_conn->query($query);
		while($module=$results->fetch_array()) {
			$query_module =  "SELECT COUNT(id) AS num_records
      FROM ".$module['table']."
      WHERE created_by LIKE '$pid'";
			$results_module=$db_conn->query($query_module);
			$matches_module=$results_module->fetch_array();
			$total_records+=$matches_module['num_records'];
		}
	?>
	<a class="menuitem submenuheader" class="menuitem">Materials (<?php echo $total_records;?>)</a>
	<div class="submenu">
	<ul>
	<?php
	  $query="SELECT * FROM modules WHERE material=1 ORDER BY name";
		$results=$db_conn->query($query);
	  while($module=$results->fetch_array()) {
			$query_module =  "SELECT COUNT(id) AS num_records
      FROM ".$module['table']."
      WHERE created_by LIKE '$pid'";
			$results_module=$db_conn->query($query_module);
			$matches_module=$results_module->fetch_array();
			echo "<li><a href='".$module['name'].".php?created_by=".$pid."'>".$module['name'].": ".
			$matches_module['num_records']."</a></li>";
		}
	?>
	</ul>
	</div>
	<?php
	$query="SELECT COUNT(id) FROM orders WHERE created_by='$pid' AND cancel=0";
	$results=$db_conn->query($query);
	$match=$results->fetch_array();
	?>
	<a class="menuitem submenuheader" class="menuitem">Orders (<?php echo $match['COUNT(id)'];?>)</a>
	<div class="submenu">
	<ul>
	<?php
  $query="SELECT COUNT(id) FROM orders WHERE created_by='$pid' AND cancel=0 AND state=1";
  $results=$db_conn->query($query);
  $match=$results->fetch_array();
	?>
	<li><a href="orders.php?created_by=<?php echo $pid;?>&state=1">Request (<?php echo 		$match['COUNT(id)'];?>)</a></li>
	<?php
  $query="SELECT COUNT(id) FROM orders WHERE created_by='$pid' AND cancel=0 AND state=2";
  $results=$db_conn->query($query);
  $match=$results->fetch_array();
	?>
	<li><a href="orders.php?created_by=<?php echo $pid;?>&state=2">Approve (<?php echo $match['COUNT(id)'];?>)</a></li>
	<?php
  $query="SELECT COUNT(id) FROM orders WHERE created_by='$pid' AND cancel=0 AND state=4";
  $results=$db_conn->query($query);
  $match=$results->fetch_array();
	?>
	<li><a href="orders.php?created_by=<?php echo $pid;?>&state=4">Receive (<?php echo $match['COUNT(id)'];?>)</a></li>
	</ul>
	</div>
	<?php
  $query="SELECT COUNT(id) FROM storages WHERE state=1 AND keeper='$pid'";
  $result=$db_conn->query($query);
  $match=$result->fetch_assoc();
	?>
	<a href='storages.php?keeper=<?php echo $pid;?>' class="menuitem">Storages (<?php echo $match['COUNT(id)'];?>)</a>
	</div>
  </td></tr>
  </table>
  </td>
  <td width="100%" align="left" valign="top">
 <?php
}

function do_rightbar() {
	?>
	<!--
  </td><td width="200" valign="top">
  <table class="ads"><tr><td>a<td></tr></table>
  -->
  <?php
}
function reformat_datetime($datetime,$format)
{
	list($year, $month, $day, $hour, $min, $sec) = split( '[: -]', $datetime );
	return date($format,mktime($hour,$min,$sec,$month,$day,$year));
}
function selectSubmit($module_name)
{
	$module=get_id_from_name('modules',$module_name);
	$module_id=$module["id"];
	?>
<table>
<tr><td>Selected:
<select name="actionRequest" onchange="javascipt:submitResultsForm(<?php echo $module_id ?>)">
<option value="" selected> -Choose- </option>
<option value="store">Store</option>
<!--<option value="clipboard">Clipboard</option>-->
<option value="delete">Delete</option>
</select>
<input type="hidden" name="actionType" value=""></td>
<tr>
</table>
  <?php
}
function search_header($module_name) {
  ?>
<tr>
<td align='center' valign='middle'><h2><?php echo $module_name?>&nbsp;&nbsp;
	<?php
	if (userPermission("3")){
		echo "<a href='".$module_name."_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' title='Add new' border='0'/></a>";
//		echo "&nbsp;<a href='".$module_name."_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' title='Import from file' border='0'/></a></h2>";
	}
	else {
		echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" title="Add new" border="0"/>';
//		echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" title="Import from file" border="0"/></h2>';
	}
 	?>
</td></tr>
  <?php
}

function search_keywords_retired($module_name,$fields,$suggest_field = null,$start_length = null) {
	if ($suggest_field == null||$suggest_field == "") {
		$suggest_field = "name";
	}
	if ($start_length == null||$start_length == "") {
		$start_length = 2;
	}
	?>
<script type="text/javascript" src="include/jquery/lib/jquery.js"></script>
<script type="text/javascript" src="include/jquery/jquery.bgiframe.js"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
	$('#scroll').bgiframe();
});
</script>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/suggest.js"></script>
<script>
rpc_suggest.useService('include/phprpc/suggest.php');
table= "<?php echo $module_name ?>";
field= "<?php echo $suggest_field ?>";
start_length= "<?php echo $start_length ?>";
</script>
<tr onclick="hideSuggestions();">
<td colspan='2' height='21' valign='top'>
<input type="hidden" name="fields" value="<?php echo $fields ?>">
<div id="content" class="suggestcontent">Search:
<input type='text' name='keywords' id='keyword' size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
<input type='Submit' name='Submit' value='Go' />
<input type="reset" value="Clean"/>
<div id="scroll" class="suggestscroll">
<div id="suggest" class="suggest">
</div>
</div>
</div>
</td>
</tr>
  <?php
}

function search_keywords($module_name,$fields,$suggest_field = null,$start_length = null) {
	if ($suggest_field == null||$suggest_field == "") {
		$suggest_field = "name";
	}
	if ($start_length == null||$start_length == "") {
		$start_length = 2;
	}
	?>
<tr >
<td colspan='2' height='21' valign='top'>
<input type="hidden" name="fields" value="<?php echo $fields ?>">
Search:
<input type='text' name='keywords' id='keyword' size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
<input type='Submit' name='Submit' value='Go' />
</td>
</tr>
  <?php
}

function search_project () {
	?>
<tr>
<td colspan='2' height='21' valign='top'>And project:
	<?php
	$query= "select * from projects ORDER BY name";
	echo query_select_all('project', $query,'id','name',$_REQUEST['project']);
  ?>
</td></tr>
	<?php
}
function search_create_mask () {
	?>
<tr><td>And created by:
	<?php
	$query= "select * from people ORDER BY CONVERT(name USING GBK)";
	echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
  ?>
AND masked:
	<?php
	$mask=array(array("0","no"),
	array("1","yes"));
	echo option_select_all('mask',$mask,2,$_REQUEST['mask']);
	?>
</td></tr>
	<?php
}
function standardSearch($module_name,$fields)
{
	search_header($module_name);
	search_keywords($module_name,$fields);
	search_project();
	search_create_mask();
}
function homeSearch()
{
	?>
  <script>
  function resetform(f)
  {
  	f.keywords.value="";
  	for (i=0; f.elements[i]; i++)
  	{
  		if(f.elements[i].type=="select-one")
  		{
  			f.elements[i].options[0].selected=true;
  		}
  	}
  }
  </script>
	<tr>
      <td align='center' valign='middle'><h2>All database&nbsp</h2></td>
    </tr>
    <tr>
      <td >Search name for:
        <input type='text' name='keywords' size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
        <input type='Submit' name='Submit' value='Go' />
    </td></tr>
    <tr><td>And project:<?php
    $query= "select * from projects";
    echo query_select_all('project', $query,'id','name',$_REQUEST['project']);
    echo "And created by:";
    $query= "SELECT * FROM people ORDER BY CONVERT(name USING GBK)";
		echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);?>
	  </td>
    </tr>

  <?php
}
function resultsDisplayControl($sort,$PageSizeDefault,$order=array(DESC=>DESC,ASC=>ASC))
{
	?>
  <tr>
      <td><?php
      if ( isset($_GET['PageSize']) )
      $PageSize=(int)$_GET['PageSize'];
      else{
      	if($PageSizeDefault==''){
      		$PageSize=10;
      	}
      	else{
      		$PageSize=$PageSizeDefault;
      	}
      }
      echo "Show <input type='text' name='PageSize' size='10' value=".$PageSize.
	    "> items per page, "?>
	    sort by:<?php
	    echo array_select('sort',$sort,$_REQUEST['sort']);
	    echo array_select('order',$order,$_REQUEST['order']);
        ?>
      </td>
    </tr>
    <?php
}

function js_selectall()
{
?>
<script>
<!-- select all -->
function selectall(v,f1) {
	if (f1==null) {
		var f = document.results;
	}
	else {
		var f = f1;
	}
	for (i=0;i<f.elements.length;i++)
	if (f.elements[i].name=="selectedItem[]") f.elements[i].checked = v;
	f.elements["clickall"].checked = v;
}
<!-- change select all -->
function changechecked(checkbut,f1) {
	if (f1==null) {
		var f = document.results;
	}
	else {
		var f = f1;
	}
	var v =checkbut.checked;
	if (v) {
		checkbut.checked = true;
		if(isallselected(f)){
			f.elements["clickall"].checked = true;
		}
	}
	else{
		f.elements["clickall"].checked = false;
		checkbut.checked = false;
	}
}
<!--all selected? -->
function isallselected(f) {
	var selectcount = 0;
	var allcount=0;
	for(i=0;i<f.elements.length;i++){
		if (f.elements[i].name=="selectedItem[]"&&f.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	for(i=0;i<f.elements.length;i++){
		if (f.elements[i].name=="selectedItem[]"&&(f.elements[i].name.indexOf("select")>-1)){
			allcount = allcount+1;
		}
	}
	if(selectcount==allcount)
	return true;
	else
	return false;
}
function countselecteditems(f1) {
	if (f1==null) {
		var f = document.results;
	}
	else {
		var f = f1;
	}
	var n=0;
	for (i=0;i<f.elements.length;i++) {
		if (f.elements[i].name=="selectedItem[]"&&f.elements[i].checked==true) {
			n=n+1;
		}
	}
	f.numselecteditems.value=n;

}
</script>
<?php
}
function js_selectedRequest_module()
{
?>
<script>
<!--all actionRequest -->
function submitResultsForm(module_id) {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++){
		if (document.results.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any item.")
		document.results.actionRequest.value = ""
		return
	}
	var confirmVal
	if (document.results.actionRequest.value == "clipboard") {
		confirmVal = confirm("Are you sure to past the selected item(s) to clipboard?")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
			document.results.actionType.value = "clipboard"
		}
	}
	if (document.results.actionRequest.value == "store") {
		confirmVal = confirm("Are you sure to store the selected item(s) at one location?")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		} else {
			document.results.actionRequest.value = ""
			n=0
			var selected_items = "";
			for(i=0;i<document.results.elements.length;i++){
				if (document.results.elements[i].checked&&document.results.elements[i].name == "selectedItem[]") {
					if (n!=0) selected_items += ",";
					selected_items += document.results.elements[i].value;
					n++;
				}
			}
			var   obj   =   new   Object();
			var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&selected_items="+selected_items,obj   ,"dialogWidth=500px;dialogHeight=500px");
			if   (a=="ok")
			window.location.href=window.location   .href;
			return
		}
	}
	if (document.results.actionRequest.value == "delete") {
		confirmVal = confirm("Are you sure to delete the selected item(s)?")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		} else {
			document.results.actionType.value = "delete"
		}
	}
	document.results.submit()
}
</script>
<?php
}

function query_module($module_name,$add_fields='')
{
	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
	if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
	if($_REQUEST['project']==null){$_REQUEST['project']='%';}
	if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
	if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}

	$id=$_REQUEST['id'];
	$fields=$_REQUEST['fields'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$project=$_REQUEST['project'];
	$created_by=$_REQUEST['created_by'];
	$mask=$_REQUEST['mask'];

	//seperate the keywords by space ("AND" "LIKE")
	$keywords = split(' ', $_REQUEST['keywords']);
	$num_keywords = count($keywords);
	for ($i=0; $i<$num_keywords; $i++) {
		if ($i) {
			$keywords_string .= "AND $fields LIKE '%".$keywords[$i]."%' ";
		}
		else {
			$keywords_string .= "$fields LIKE '%".$keywords[$i]."%' ";
		}
	}

	$userauth=check_user_authority($_COOKIE['wy_user']);
	$userpid=get_pid_from_username($_COOKIE['wy_user']);

	if($userauth>2) {
		$mask_str=" AND (mask=0 OR created_by='{$userpid}')";
	}
	else {
		$mask_str="";
	}

	$query =  "SELECT *,
	DATE_FORMAT(date_create,'%m/%d/%y')AS date_create
  FROM ".$module_name."
  WHERE ($keywords_string)
  AND id LIKE '$id'
  AND project LIKE '$project'
  AND created_by LIKE '$created_by'
  AND mask LIKE '$mask'
  $mask_str ORDER BY $sort $order";
	$_SESSION['query']=$query;//used for EXCEL export
	return $query;
}

function pagerForm($module_name,$query,$PageSize='10',$export=1)
{
	if ( isset($_REQUEST['page']) )
	{
	  $page = (int)$_REQUEST['page'];
	}
	else
	{
	  $page = 1;
	}
	if ( isset($_GET['PageSize']) )
    $PageSize=(int)$_GET['PageSize'];
	$pager_option = array(
	"sql" => $query,
	"PageSize" => $PageSize,
	"CurrentPageID" => $page
	);
	//if ( isset($_GET['numItems']) )
	//$pager_option['numItems'] = (int)$_GET['numItems'];
	$pager = new Pager($pager_option);
	global $results;
	$results = $pager->getDataLink();

	//$getURL=getURL(array(id,fields,keywords,sort,order,project,created_by,mask,PageSize));
	$getURL=$_SERVER['REQUEST_URI'];
	if (strpos($getURL,'?page=')) {
		//echo strpos($getURL,'&page=');
		$getURL=substr($getURL,0,strpos($getURL,'?page='));
	}
	if (strpos($getURL,'&page=')) {
		//echo strpos($getURL,'&page=');
		$getURL=substr($getURL,0,strpos($getURL,'&page='));
	}
	$turnover=turnover($pager->isFirstPage,$pager->isLastPage,
	$pager->numItems,$pager->numPages,$pager->PreviousPageID,$pager->NextPageID,$getURL);
	if ($results  && $results->num_rows) {
		echo "<table width='100%' class='pager'><tr><td align='left'>";
		echo "Totally ".$pager->numItems." items, page ".$page." / ".$pager->numPages.". ".
		$turnover."</td>";
		if ($export==1) {
			echo "<td align='right'>";
			if(!userPermission('3')) {
				echo "<img src='./assets/image/general/excel-grey.gif' alt='Export search results to EXCEL' title='Export search results to EXCEL' border='0'/></td>";
			} else {
				echo "<a href='".$module_name."_operate.php?type=export_excel' ><img
	  src='./assets/image/general/excel.gif' alt='Export search results to EXCEL' title='Export search results to EXCEL' border='0'/></a></td>";
			}
		}
		echo "</tr></table>";
	} else {
		echo "<h4>No matching records found!</h4>";
	}
}

function searchResultsForm($module_name,$results) {
	?>
<script>
function   requestOrder (module_id, item_id)
{
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=request_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=600px;dialogHeight=400px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   addStorage (module_id, item_id)
{
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>
	<?php
	if ($results  && $results->num_rows) {
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
		echo "Quick ID</td><td class='results_header'>";
		echo "Name</td><td class='results_header'>";
		echo "Create</td><td class='results_header'>";
		echo "Operate</td><td class='results_header'>";
		echo "Storage</td><td class='results_header'>";
		echo "Order</td></tr>";
		while ($matches = $results->fetch_array()) {
			$module=get_record_from_name('modules',$module_name);
			echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
			echo get_quick_id($module[id],$matches[id])."</td><td width='200' class='results'>";
			echo "<a href='".$module_name."_operate.php?type=detail&id={$matches[id]}'>".wordwrap($matches[name],190,"<br>")."</a></td><td class='results'>";
			$people=get_record_from_id('people',$matches['created_by']);
			echo $people['name']." ".$matches['date_create']."</td><td class='results'>";			
			if (userPermission('2',$matches['created_by'])) {
//				echo "<a href='label.php?module_id=".$module['id']."&item_id=".$matches['id']."' target='_blank'><img src='./assets/image/general/label-s.gif' alt='Print label' title='Print label' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='".$module_name."_operate.php?type=edit&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;";
//				echo "<a href='".$module_name."_operate.php?type=relation&id=".$matches['id']."'><img src='./assets/image/general/attach-s.gif' alt='Related items' title='Related items' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='".$module_name."_operate.php?type=delete&id=".$matches['id']."'><img src='./assets/image/general/del-s.gif' alt='Delete' title='Delete' border='0'/></a></td><td class='results'>";
			} else {
//				echo '<img src="./assets/image/general/label-s.gif" alt="Print label" title="Print label" border="0"/>&nbsp;&nbsp;';
				echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" title="Edit" border="0"/>&nbsp;&nbsp;';
//				echo '<img src="./assets/image/general/attach-s-grey.gif" alt="Related items" title="Related items" border="0"/>&nbsp;&nbsp;';
				echo '<img src="./assets/image/general/del-s-grey.gif" alt="Delete" title="Delete" border="0"/></td><td class="results">';
			}
			//query the storages of this item where the state is in stock.
			$db_conn=db_connect();
			$query = "select id from storages WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND state=1";
			$storage = $db_conn->query($query);
			$storage_count=$storage->num_rows;
			if($storage_count>0) {
				echo '<a href="storages.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank">'.$storage_count.'</a>';
			} else {
				echo $storage_count;
			}
			echo "&nbsp;&nbsp;";
			if (userPermission('3')) {
				echo "<a onclick=\"addStorage({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img
	    src=\"./assets/image/general/add-s.gif\" alt=\"Store\" title=\"Store\" border=\"0\"/></a></td><td class=\"results\">";
			} else {
				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Store" title="Store" border="0"/></td><td class="results">';
			}
			//query the orders of this item where the state is not cancelled.
			$query = "select id from orders WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND cancel=0";
			$order = $db_conn->query($query);
			$order_count=$order->num_rows;
			if($order_count>0) {
				echo '<a href="orders.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank">'.$order_count.'</a>';
			} else {
				echo $order_count;
			}
			echo "&nbsp;&nbsp;";
			if (userPermission('3')) {
				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Request\" title=\"Request\" border=\"0\"/></a></td></tr>";
			} else {
				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Request" title="Request" border="0"/></td></tr>';
			}
		}
		echo '</table>';
		selectSubmit($module_name);
		echo '</form>';
	}
}
function datezero($date) {
	if ($date=='0000-00-00') {
		return '';
	}
	else {
		return $date;
	}
}
?>