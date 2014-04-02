<?php
include('./include/includes.php');
include("fckeditor/fckeditor.php") ;
include('./tree/common_functions.php');
/*
<%@ Language=VBScript %>
<% option explicit %>

<!-- #INCLUDE file="_functions.asp" -->
<%
	'Globals in bookmarks_connection: Conn, userId
	Dim errorVal
	errorVal = authorizedLogin()
    'If cookies were lost or cleared, take the user back to registration
	if errorVal<> "" then
        response.write "<html><head><script>alert('Your session expired. Please login again.');top.location = 'default.asp';</script></head></html>"
        response.end
    end if
%>
*/
?>
<html>
<title>
Links in folder
</title>
<link href="./css/general.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
   TD {font-size: 8pt;
       font-family: verdana,helvetica;
	  }
   TD.separator {
       border-bottom-style: solid;
       border-bottom-width:1;
       border-bottom-color:'silver';
      }
   TD.separator-top {
       border-top-style: solid;
       border-top-width:1;
       border-top-color:'silver';
      }
</style>

<script>
function submitShowFormT(actionType) {
  document.forms[0].submit()
}
function submitSelected() {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.form.elements.length;i++){
		if (document.form.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any item.")
		document.form.actionRequest.value = ""
		return
	}

	var confirmVal
	if (document.form.actionRequest.value == "move") {
		submitShowForm("MOVELINKFORM")
	}
	document.form.submit()
}
function submitShowForm(actionType) {
	var i;
	var found="";
	var confirmVal
	var folderName

	if (actionType == "DELLINK") {
		confirmVal = confirm("Are you sure you want to delete the selected box(s)?")
		if (!confirmVal)
		return;
	}
	if (actionType == "DELFOLDER") {
		folderName = document.forms[0].elements[2].value
		if (folderName=="Knowledge base") {
			alert("Root folder Bookmarks cannot be removed.")
			return;
		}
		confirmVal = confirm("Are you sure you want to delete Location " + folderName + "?")
		if (!confirmVal)
		return;
	}
	if (actionType == "EDITFOLDERFORM") {
		folderName = document.forms[0].elements[2].value
		if (folderName=="Knowledge base") {
			alert("Root folder Bookmarks cannot be edited.")
			return;
		}
	}
	if (actionType == "MOVEFOLDERFORM") {
		folderName = document.forms[0].elements[2].value
		if (folderName=="Knowledge base") {
			alert("Root folder Bookmarks cannot be moved.")
			return;
		}
	}
	document.forms[0].elements[0].value=actionType
	document.forms[0].submit()
}
</script>
</head>
<body bgcolor=white link="#3A5AF4">
<font face=verdana size=-1>
<?php
//<%
//Outputs the hidden filds that most of the forms will need
//sub standardForm(principalAction, parent, parentName, user)
function standardForm($principalAction, $parent, $parentName, $user) {
	echo '<form name="form" action="kbase_content.php" method=post>' . "\n" . "\n";
	//Don't change order
	echo "<input type=hidden name=action value='$principalAction'>" . "\n" ;
	echo "<input type=hidden name=parent value='$parent'>" . "\n";
	echo "<input type=hidden name=parentName value='$parentName'>" . "\n";
	//echo "<input type=hidden name=user value='$user'>" . "\n";
}
//end sub

//Get a string with the path of the folder
function getPath($folderId) {
	//dim rsHits, queryString, auxStr, parentId
	$auxStr="";
	$parentId=999;
	while ($folderId != 0 ) {
		$db_conn = db_connect();
		$queryString = "SELECT pid, name FROM kbase_cat WHERE ((id=".$folderId."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if ($auxStr == "" ) {
			//$auxStr = FixUpItems($rsHits['name']);
			$auxStr = $rsHits['name'];
		}
		else {
			//$auxStr = FixUpItems($rsHits['name']) . " > " . $auxStr;
			$auxStr = $rsHits['name']. " > " . $auxStr;
		}
		$folderId = $rsHits['pid'];
	}
	return $auxStr;
}

//sub ShowLinks(user, parentId)
function ShowLinks($user, $parentId) {
	//dim rsHits, queryString, notesString, aux, parentName, notes
	$db_conn = db_connect();
	if ($parentId == -10 ) {
		//Means root folder in this script (we don;t know the real id, only that its parent has the -1
		$queryString = "SELECT id, name, note FROM kbase_cat WHERE ((pid=0))";// AND (user=".$user."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if (!$rsHits) {
			echo("Could not find data for user " . $user . ". Contact administrator");
		}
		$parentId = $rsHits['id'];
		$parentName = $rsHits['name'];
		$notes = $rsHits['note'];
	}
	else {
		$queryString = "SELECT * FROM kbase_cat WHERE ((id=" . $parentId . ") )";//AND (user=".$user."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		$parentName = $rsHits['name'];
		$notes = $rsHits['note'];
		$pid = $rsHits['pid'];
	}

  $db_conn = db_connect();
	$queryString = "SELECT * FROM kbase WHERE ((cat_id=" . $parentId . ")) ORDER BY name";
	$rs= $db_conn->query($queryString);
	$rs_num = $rs->num_rows;

	standardForm ("EDITLINKFORM", $parentId, $parentName, $user);
	echo '<table border=0 width=100% cellspacing=0 cellpadding=5>' .  "\n";
	echo "<tr><td colspan=5 class=separator><font size=+2 color='#3A5AF4'>".$parentName." (".$rs_num.")</font>&nbsp;&nbsp;";
	//echo "<a href='#' ><img src='./assets/image/general/add.gif' alt='Add New Article' border='0' onclick=\"submitShowForm('NEWLINKFORM')\"/></a>";
	if (userPermission(3)) {
		echo "<a href='kbase_content.php?action=NEWLINKFORM&p=$parentId' target='_self' ><img src='./assets/image/general/add.gif' alt='Add New Article' border='0'/></a>";
	} else {
		echo "<img src='./assets/image/general/add-grey.gif' alt='Add New Article' border='0'/>";
	}
	echo "<br>" .$notes;
	echo "<br>" .getPath($pid).  "</td></tr>";
	if ($rs_num>0) {
		js_selectall();
	?>
	<tr>
	  <td class=separator width="10"><input type="checkbox" name="clickall" onclick='selectall(this.checked,form)'></td>
	  <td class=separator>Name</td>
	  <td class=separator>Create</br>Update</td>
	  <td class=separator>Comments/Views</td>
	  <td class=separator>Operate</td>
	</tr>
	<?php
	for($i=0;$i< $rs_num;$i++) {
		$rsHits=$rs->fetch_assoc();
		echo "<tr>" ."\n";
		echo "<td class=separator><input type='checkbox' onclick=changechecked(this,form)  name='selectedItem[]' value={$rsHits['id']}></td>";
		//article name
		echo "<td class=separator><a href='kbase_content.php?action=LINKDETAILFORM&id=".$rsHits['id']."' target='_self'><b>".$rsHits['name']."</b></a></td>";
		//article create and update
		$created_by = get_name_from_id('people',$rsHits['created_by']);
		$updated_by = get_name_from_id('people',$rsHits['updated_by']);
		echo "<td class=separator><small>".$created_by['name'].", ".$rsHits['date_create']. "&nbsp;&nbsp;";
		if ($rsHits['updated_by']>0) {
			echo "</br>".$updated_by['name'].", ".$rsHits['date_update'];
		}
		echo "</small></td>";
		//view and comments
		$query="select count(*) as num_comments from kbase_comments where article_id='{$rsHits['id']}'";
		$rs_2=$db_conn->query($query);
		$match=$rs_2->fetch_assoc();
		$num_comments=$match['num_comments'];
		echo "<td class=separator><small>".$num_comments." / ".$rsHits['views']."</small></td>";
		//will need to encode and decode the name
		if (userPermission(3)) {
			echo "<td class=separator><a href='kbase_content.php?action=EDITLINKFORM&id={$rsHits['id']}' target='_self' ><img src='./assets/image/general/edit.gif' alt='Edit' border='0'/></a></td></tr>";
		}
		else {
			echo "<td class=separator><img src='./assets/image/general/edit-grey.gif' alt='Edit' border='0'/></td></tr>";
		}
	}
?>
<tr><td colspan="5">Selected:
<select name="actionRequest" onchange="javascipt:submitSelected()">
<option value="" selected> -Choose- </option>
<option value="move">Move article(s) to another forder</option>
</select>
<input type="hidden" name="actionType" value=""></td>
<tr>
<?php
	}
	echo "</table>";
	echo "</form>";
}

function SearchForm() {
	$keywords=$_REQUEST['keywords'];
  $db_conn = db_connect();
	$queryString = "SELECT * FROM kbase WHERE ((CONCAT(name,content) LIKE '%" . $keywords . "%')) ORDER BY name";
	$rs= $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	//$parentName=Subst($parentName, "", "\");
	//standardForm ("EDITLINKFORM", $parentId, $parentName, $user);
	standardForm ("",1, "", "");
	echo '<table border=0 width=100% cellspacing=0 cellpadding=5>' .  "\n";
	echo "<tr><td colspan=5 class=separator><font size=+2 color='#3A5AF4'>Searching result (".$rs_num.")</font>&nbsp;&nbsp;" .  "\n";
	echo "</td></tr>";
	if ($rs_num>0) {
		js_selectall();
	?>
	<tr>
	  <td class=separator width="10"><input type="checkbox" name="clickall" onclick='selectall(this.checked,form)'></td>
	  <td class=separator>Name</td>
	  <td class=separator>Create</br>Update</td>
	  <td class=separator>Comments/Views</td>
	  <td class=separator>Operate</td>
	</tr>
	<?php
	for($i=0;$i< $rs_num;$i++) {
		$rsHits=$rs->fetch_assoc();
		echo "<tr>";
		//checkbox
		echo "<td class=separator><input type='checkbox' onclick=changechecked(this,form)  name='selectedItem[]' value={$rsHits['id']}></td>";
		//name and path
		echo "<td class=separator><a href='kbase_content.php?action=LINKDETAILFORM&id=".$rsHits['id']."' target='_self'><b>".$rsHits['name']."</b></a></br>";
		echo "<a href='kbase_content.php?action=FORDERDETAILFORM&p={$rsHits['cat_id']}'/>".getPath($rsHits['cat_id'])."</a></td>";
		//create and update
		$created_by = get_name_from_id('people',$rsHits['created_by']);
		$updated_by = get_name_from_id('people',$rsHits['updated_by']);
		echo "<td class=separator><small>".$created_by['name'].", ".$rsHits['date_create']. "</br>";
		if ($rsHits['updated_by']>0) {
			echo $updated_by['name'].", ".$rsHits['date_update'];
		}
		echo "</small></td>";
		//comments and views
		$query="select count(*) as num_comments from kbase_comments where article_id='{$rsHits['id']}'";
		$rs_2=$db_conn->query($query);
		$match=$rs_2->fetch_assoc();
		$num_comments=$match['num_comments'];
		echo "<td class=separator><small>".$num_comments." / ".$rsHits['views']."</small></td>";
		//will need to encode and decode the name
		if (userPermission(3)) {
			echo "<td class=separator><a href='kbase_content.php?action=EDITLINKFORM&id={$rsHits['id']}' target='_self' ><img src='./assets/image/general/edit.gif' alt='Edit' border='0'/></a></td></tr>";
		}
		else {
			echo "<td class=separator><img src='./assets/image/general/edit-grey.gif' alt='Edit' border='0'/></td></tr>";
		}
	}
	}
	echo "</table>";
	echo "</form>";
	echo  "\n" . "\n";
}

function causeTreeToReload()
{
	echo "<script>". "\n";
	echo "this.parent.parent.refreshLeftFrame();". "\n";
	echo "</script>". "\n";
}
//end sub

//-------------  start forms  -----------

function LinkForm ($nodeId, $name, $comments, $url, $action) {
	standardForm($action, $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
	echo "<input type=hidden name=nodeId value=" . $nodeId . ">" .  "\n";
	echo "<table width=100% border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>";
	if ($action == "NEWLINK" ) {
		echo "Add New Article";
	} else {
		echo "Edit Article";
	}
	echo "</font>" .  "\n";
	echo "<tr><td>Path:</td>";
	echo "<td><a href='kbase_content.php?action=FORDERDETAILFORM&p={$_REQUEST['parent']}'/>".getPath($_REQUEST['parent'])."</a></td></tr>";
	echo "<tr><td>Name:</td>";
	echo "<td><input name=name size=50 value='$name'></td></tr>";
	echo "<tr><td colspan='2'>";
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value = $_REQUEST['content'];
	$oFCKeditor->Config['EnterMode'] = 'br';
	$oFCKeditor->Height = '500';
	$oFCKeditor->Width = "100%";
	$oFCKeditor->ToolbarSet= "Quicklab";
	$oFCKeditor->Create() ;
	echo "</td></tr>";
	echo "<tr><td align=left colspan='2'>";
	echo "<input type=submit name=whichButton value='Save'>&nbsp;";
	echo "<input type=submit name=whichButton value='Cancel'></td></tr>";
	echo "</table>";
	echo "</form>";
}

function AddLinkForm () {
	if(!userPermission('3')){
		alert_map();
	}
	?>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	$("#add_link_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
	<?php
	echo "<form name='add_link_form' id='add_link_form' method=post target=''>";
	echo "<input type=hidden name=nodeId value=0>";
	echo "<input type=hidden name=action value=NEWLINK>";
	echo "<input type=hidden name=parent value='{$_REQUEST['p']}'>";
	echo "<table width=100% border=0 cellspacing=0 cellpadding=5>";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Add New Article</font></td></tr>";
	echo "<tr><td>Path:</td>";
	echo "<td><a href='kbase_content.php?action=FORDERDETAILFORM&p={$_REQUEST['p']}'/>".getPath($_REQUEST['p'])."</a></td></tr>";
	echo "<tr><td>Name:</td>";
	echo "<td><input name=name size=50 value='$name'></td></tr>";
	echo "<tr><td colspan='2'>";
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value = $_REQUEST['content'];
	$oFCKeditor->Config['EnterMode'] = 'br';
	$oFCKeditor->Height = '500';
	$oFCKeditor->Width = "100%";
	$oFCKeditor->ToolbarSet= "Tree";
	$oFCKeditor->Create() ;
	?>
	</td></tr>
	<tr><td align=left colspan='2'>
	<input type=submit name=whichButton id='Save' value='Save'>&nbsp;
	<input type="button" name=whichButton id='Cancel' value='Cancel' onclick="window.location.href='kbase_content.php?action=FORDERDETAILFORM&p=<?php echo $_REQUEST['p'];?>'"/></td></tr>
	<tr><td colspan="2">
	<table width="100%">
	<tr><td>Send mail?</td></tr>
	<tr><td>
	<input type="checkbox" name='clickall' onclick="selectall(this.checked,document.add_link_form)" />&nbsp;Select all
	</td></tr>
	<tr><td width="100%">
	<?php
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
		<input type="checkbox" onclick=changechecked(this,document.add_link_form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
		<?php
		echo $match['name'];
		if ($n%10==0) {
			echo "<br>";
		}
	}
	?>
	</td></tr>
	</table>
	</td></tr>
	</table>
	</form>
	<?php
}

function EditLinkForm () {
	$id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
  if(!userPermission('2',$match['created_by'])) {
  	alert_map();
  }
	?>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	$("#add_link_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
	<?php
	echo "<form name='add_link_form' id='add_link_form' method=post target=''>";
	//echo "<input type=hidden name=nodeId value=0>";
	echo "<input type=hidden name=action value=EDITLINK>";
	echo "<input type=hidden name=parent value='{$article['cat_id']}'>";
	echo "<table width=100% border=0 cellspacing=0 cellpadding=5>";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Edit article:</font></td></tr>";
	echo "<tr><td>Path:</td>";
	echo "<td><a href='kbase_content.php?action=FORDERDETAILFORM&p={$article['cat_id']}'/>".getPath($article['cat_id'])."</a></td></tr>";
	echo "<tr><td>Name:</td>";
	//be careful, use double quotes
	echo "<td><input name=name size=50 value=\"{$article['name']}\"></td></tr>";
	echo "<tr><td colspan='2'>";
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value = $article['content'];
	$oFCKeditor->Config['EnterMode'] = 'br';
	$oFCKeditor->Height = '500';
	$oFCKeditor->Width = "100%";
	$oFCKeditor->ToolbarSet= "Tree";
	$oFCKeditor->Create() ;
	?>
	</td></tr>
	<tr><td align=left colspan='2'>
	<input type=submit name=whichButton id='Save' value='Save'>&nbsp;
	<input type="button" name=whichButton id='Cancel' value='Cancel' onclick="window.location.href='kbase_content.php?action=FORDERDETAILFORM&p=<?php echo $article['cat_id'];?>'"/></td></tr>
	<tr><td colspan="2">
	<table width="100%">
	<tr><td>Send mail?</td></tr>
	<tr><td>
	<input type="checkbox" name='clickall' onclick="selectall(this.checked,document.add_link_form)" />&nbsp;Select all
	</td></tr>
	<tr><td width="100%">
	<?php
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
		<input type="checkbox" onclick=changechecked(this,document.add_link_form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
		<?php
		echo $match['name'];
		if ($n%10==0) {
			echo "<br>";
		}
	}
	?>
	</td></tr>
	</table>
	</td></tr>
	</table>
	</form>
	<?php
}

function AddCommentForm () {
	$id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
  if(!userPermission(3)) {
  	alert_map();
  }
	echo "<form name='add_comment_form' id='add_comment_form' method=post target=''>";
	//echo "<input type=hidden name=nodeId value=0>";
	echo "<input type=hidden name=action value=ADDCOMMENT>";
	//echo "<input type=hidden name=parent value='{$article['cat_id']}'>";
	echo "<table width=100% border=0 cellspacing=0 cellpadding=5>";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Add comment:</font></td></tr>";
	echo "<tr><td>Path:</td>";
	echo "<td>".getPath($article['cat_id'])."</td></tr>";
	echo "<tr><td>Name:</td>";
	echo "<td><a href='kbase_content.php?action=LINKDETAILFORM&id=$id' target='_self'/>{$article['name']}</a></td></tr>";
	echo "<tr><td colspan='2'>";
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value = $_REQUEST['content'];
	$oFCKeditor->Config['EnterMode'] = 'br';
	$oFCKeditor->Height = '500';
	$oFCKeditor->Width = "100%";
	$oFCKeditor->ToolbarSet= "Tree";
	$oFCKeditor->Create() ;
	?>
	</td></tr>
	<tr><td align=left colspan='2'>
	<input type=submit name=whichButton id='Save' value='Save'>&nbsp;
	<input type="button" name=whichButton id='Cancel' value='Cancel' onclick="window.location.href='kbase_content.php?action=LINKDETAILFORM&id=<?php echo $id;?>'"/></td></tr>
	<tr><td colspan="2">
	<table width="100%">
	<tr><td>Send mail?</td></tr>
	<tr><td>
	<input type="checkbox" name='clickall' onclick="selectall(this.checked,document.add_comment_form)" />&nbsp;Select all
	</td></tr>
	<tr><td width="100%">
	<?php
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
		<input type="checkbox" onclick=changechecked(this,document.add_comment_forms)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
		<?php
		echo $match['name'];
		if ($n%10==0) {
			echo "<br>";
		}
	}
	?>
	</td></tr>
	</table>
	</td></tr>
	</table>
	</form>
	<?php
}

function EditCommentForm () {
	$id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase_comments WHERE id=$id";
  $rs=$db_conn->query($query);
  $comment=$rs->fetch_assoc();
  if(!userPermission(0,$comment['created_by'])) {
  	alert_map();
  }
  $query="SELECT * FROM kbase WHERE id='{$comment['article_id']}'";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
	echo "<form name='edit_comment_form' id='add_comment_form' method=post target=''>";
	//echo "<input type=hidden name=nodeId value=0>";
	echo "<input type=hidden name=action value=EDITCOMMENT>";
	//echo "<input type=hidden name=parent value='{$article['cat_id']}'>";
	echo "<table width=100% border=0 cellspacing=0 cellpadding=5>";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Edit comment:</font></td></tr>";
	echo "<tr><td>Path:</td>";
	echo "<td>".getPath($article['cat_id'])."</td></tr>";
	echo "<tr><td>Name:</td>";
	echo "<td><a href='kbase_content.php?action=LINKDETAILFORM&id={$comment['article_id']}' target='_self'/>{$article['name']}</a></td></tr>";
	echo "<tr><td colspan='2'>";
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value = $comment['content'];
	$oFCKeditor->Config['EnterMode'] = 'br';
	$oFCKeditor->Height = '500';
	$oFCKeditor->Width = "100%";
	$oFCKeditor->ToolbarSet= "Tree";
	$oFCKeditor->Create() ;
	?>
	</td></tr>
	<tr><td align=left colspan='2'>
	<input type=submit name=whichButton id='Save' value='Save'>&nbsp;
	<input type="button" name=whichButton id='Cancel' value='Cancel' onclick="window.location.href='kbase_content.php?action=LINKDETAILFORM&id=<?php echo $comment['article_id'];?>'"/></td></tr>
	</table>
	</form>
	<?php
}

function LinkDetail () {
	if(!userPermission(4)) {
		alert();
	}
	$id=$_REQUEST['id'];
	$db_conn=db_connect();
	$query="SELECT * FROM kbase WHERE id=13";
	$rs=$db_conn->query($query);
	$article=$rs->fetch_assoc();
	echo "<p>".$article['content']."</p>";
}

function outputFolderSelection($id, $parentId, $indentation,$db_conn) {
	//dim rsHits, queryString, gFldStr
	//$db_conn = db_connect();
	if ($id!="") {
		$queryString = "SELECT id, name FROM kbase_cat WHERE ((pid=" . $parentId . ") AND id<>".$id.") ORDER BY name";
	}
	else {
		$queryString = "SELECT id, name FROM kbase_cat WHERE ((pid=" . $parentId . ")) ORDER BY name";
	}
	$rs= $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	for($i=0;$i<$rs_num;$i++) {
		$rsHits=$rs->fetch_assoc();
		echo "<option value=" . $rsHits['id'] . ">" .  "\n";
		echo $indentation . $rsHits['name'] .  "\n";
		outputFolderSelection ($id, $rsHits['id'], $indentation."&nbsp;&nbsp;",$db_conn) ;
	}
}

function MoveLinkForm () {
	if(!userPermission('3')) {
		alert_map();
	}
	$nodeIds=$nodeIds=implode(",",$_REQUEST['selectedItem']);
	standardForm ("MOVELINK", $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
	echo '<input type=hidden name=nodeIds value='.$nodeIds.">" .  "\n";
	echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Move Article(s)</font>" .  "\n";
	echo "<tr><td valign=top>Move selected<br>article(s) from:<td valign=bottom>";
	echo getPath($_REQUEST['parent']);
	echo "<tr><td valign=top>Move To:<td>" .  "\n";
	echo '<SELECT name=moveTo size=12>' .  "\n";
	$db_conn=db_connect();
	outputFolderSelection ("", 0, "",$db_conn);
	echo "</SELECT>" .  "\n";
	echo "<tr><td>&nbsp;<td align=left>" .  "\n";
	/*
	print_r($_REQUEST['selectedLink']) ;
	$selectedLink=$nodeIds;
	print_r($selectedLink) ;
	$num_selectedLink=count($selectedLink);
	echo $num_selectedLink."</br>";
	for($i=0;$i<$num_selectedLink;$i++)
	{
	echo $selectedLink[$i]."</br>";
	}
	*/
	echo '<input type=submit name=whichButton value="Save">';
	echo '&nbsp;<input type=submit name=whichButton value="Cancel">';
	echo "</table>";
	echo "</form>";
}

//In this case, for editing, nodeId is the folder id and should be the same thing as $_REQUEST("parent")
function FolderForm ($nodeId, $name, $comments, $action) {
	standardForm ($action, $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
	echo "<input type=hidden name=nodeId value=" . $nodeId . ">" .  "\n";
	echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>";
	if ($action == "NEWFOLDER") {
		echo "Add New Forder";
	}
	else {
		echo "Edit Forder";
	}
	echo "</font>" .  "\n";
	if ($action == "NEWFOLDER") {
		echo "<tr><td valign=top>Contained by:<td>";
		echo getPath($_REQUEST['parent']);
	}
	echo "<tr><td>Forder Name:<td>" .  "\n";
	//echo "<input name=name size=50 value=".FixUpItems($name).">" .  "\n";
	echo "<input name=name size=50 value='$name'>" .  "\n";
	echo "<tr><td>Comments:<td>" .  "\n";
	//echo '<input size=50 name=comments value='.FixUpItems($comments).">" .  "\n";
	echo "<input size=50 name=comments value='$comments'>" .  "\n";
	echo "<tr><td> <td align=left>" .  "\n";
	echo '<input type=submit name=whichButton value="Save">';
	if ($action == "NEWFOLDER" ) {
		echo ' <input type=submit name=whichButton value="Save & Add Another">';
	}
	echo ' <input type=submit name=whichButton value="Cancel">';
	echo "</table>";
	echo "</form>";
}

function AddFolderForm () {
	if(!userPermission('3')){
		alert_map();
	}
	FolderForm (0, "", "", "NEWFOLDER");
}

function EditFolderForm () {
	if(!userPermission('3')) {
		alert_map();
	}
	$db_conn=db_connect();
	$queryString = "SELECT name, note FROM kbase_cat WHERE ((id=" . $_REQUEST['parent'] . "))";
	$rs=$db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	if (!$rsHits) {
		echo "Could not find folder in database.";
	}
	else {
		$name = $rsHits['name'];
		$notes = $rsHits['note'];
		FolderForm ($_REQUEST['parent'], $name, $notes, "EDITFOLDER");
	}
}

function CopyFolderForm ()
{
	if(!userPermission('3'))
    {
  	  alert_map();
    }
	$nodeId = $_REQUEST['parent'];
	$db_conn=db_connect();
	$queryString = "SELECT name, pid, note FROM kbase_cat WHERE ((id=" . $nodeId . "))";
	$rs=$db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	if (!$rsHits)
	{
		echo "Could not find folder in database.";
	}
	else
	{
	  if( $rsHits['pid']==0)
	  {
        echo '<table class="alert"><tr><td><h3>Can not copy the root location!</h3></td></tr></table>';
        exit;
	  }
	  else
	  {
		$name = $rsHits['name'];
		$comments = $rsHits['note'];
		standardForm ("COPYFOLDER", $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
		echo "<input type=hidden name=nodeId value='$nodeId '>" .  "\n";
		echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
		echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Copy Location</font>" .  "\n";
		echo "<tr><td valign=top>Contained in:<td>";
		echo getPath($rsHits['pid']);
		echo "<tr><td valign=top>Location Name:<td>" .  "\n";
		//echo FixUpItems($name) .  "\n";
		echo $name.  "\n";
		echo "<tr><td valign=top>Comments:<td>" .  "\n";
		//echo FixUpItems($comments) .  "\n";
		//echo $comments.  "\n";
		echo "<tr><td valign=top>Paste To:<td>" .  "\n";
		echo "<SELECT name=moveTo size=24>" ."\n";
		$db_conn=db_connect();
		outputFolderSelection ($rsHits['id'], 0, "",$db_conn);
		echo "</SELECT>" .  "\n";
		echo "<tr><td> <td align=left>"."\n";
		echo '<input type=submit name=whichButton value="Save">';
		echo ' <input type=submit name=whichButton value="Cancel">';
		echo "</table>";
		echo "</form>";
	  }
	}
}
function MoveFolderForm () {
	if(!userPermission('3')) {
		alert_map();
	}
	$nodeId = $_REQUEST['parent'];
	$db_conn=db_connect();
	$queryString = "SELECT * FROM kbase_cat WHERE ((id=" . $nodeId . "))";
	$rs=$db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	if (!$rsHits) {
		echo "Could not find folder in database.";
	}
	else {
		if( $rsHits['pid']==0) {
			echo '<table class="alert"><tr><td><h3>The root Forder can not be moved!</h3></td></tr></table>';
			exit;
		}
		else {
			$name = $rsHits['name'];
			$comments = $rsHits['note'];
			standardForm ("MOVEFOLDER", $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
			echo "<input type=hidden name=nodeId value='$nodeId '>" .  "\n";
			echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
			echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Move Forder</font>" .  "\n";
			echo "<tr><td valign=top>Contained in:<td>";
			echo getPath($rsHits['pid']);
			echo "<tr><td valign=top>Forder Name:<td>" .  "\n";
			//echo FixUpItems($name) .  "\n";
			echo $name.  "\n";
			echo "<tr><td valign=top>Comments:<td>" .  "\n";
			//echo FixUpItems($comments) .  "\n";
			echo $comments.  "\n";
			echo "<tr><td valign=top>Move To:<td>" .  "\n";
			echo "<SELECT name=moveTo size=12>" ."\n";
			$db_conn=db_connect();
			outputFolderSelection ($rsHits['id'], 0, "",$db_conn);
			echo "</SELECT>" .  "\n";
			echo "<tr><td> <td align=left>"."\n";
			echo '<input type=submit name=whichButton value="Save">';
			echo ' <input type=submit name=whichButton value="Cancel">';
			echo "</table>";
			echo "</form>";
		}
	}
}

function AddLink () {
	if (!filled_out(array($_REQUEST['name']))) {
		exit;;
	}
	$p = $_REQUEST['parent'];
	$name = $_REQUEST['name'];
	$content = $_REQUEST['content'];
	$db_conn=db_connect();
	$date = date('Y-m-d G:i:s');
	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$created_by = $match['people_id'];
	$people=get_record_from_id('people',$created_by);
	//insert kbase
	$queryString = "INSERT INTO kbase
		(cat_id,name,content,date_create,created_by)
		values
		('$p','$name','$content','$date','$created_by')";
	$db_conn->query($queryString);
	$id=$db_conn->insert_id;
	//send mail
	if (count($_REQUEST['selectedItem'])>0) {
		$mail= new Mailer();
		$mail->basicSetting();
		$subject=$people['name']." wrote an article: ".$name.", Quicklab knowledge base";
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$message="
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?id=".$id."' target='_blank'>$name</a></p>
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?p=".getPath($p)."' target='_blank'>".getPath($p)."</a></p>
  <p>Created by: {$people['name']} $date</p>
  <p>Knowledge base, share your knowledge.</p>";
		$mail->Subject =$subject;
		$message=$css.$message;
		$mail->MsgHTML($message);
		$mail->AddReplyTo($people['email'],$people['name']);
		foreach ($_REQUEST['selectedItem'] as $key=>$value) {
			$people=get_record_from_id('people',$value);
			$email=trim($people['email']);
			if ($email!=''&&valid_email($email)) {
				$mail->AddAddress($email);
			}
			@$mail->Send();
			$mail -> ClearAddresses();
		}
	}
	if ($_REQUEST['whichButton'] == "Save" ) {
		ShowLinks ($_REQUEST['user'], $_REQUEST['parent']);
	} else {
		AddLinkForm();
	}
	causeTreeToReload();
}

function EditLink () {
	if (!filled_out(array($_REQUEST['name']))) {
		exit;
	}
	$db_conn=db_connect();
	$id = $_REQUEST['id'];
	$query="select * from kbase where id='$id'";
	$result = $db_conn->query($query);
	$article=$result->fetch_assoc();
	$name = $_REQUEST['name'];
	$content = $_REQUEST['content'];
	$date = date('Y-m-d G:i:s');
	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$updated_by = $match['people_id'];
	$people=get_record_from_id('people',$updated_by);
	//update kbase
	$query="update kbase set
	name='$name',
	content='$content',
	date_update='$date',
	updated_by='$updated_by'
	where id='$id'";
	$db_conn->query($query);
	//$id=$db_conn->insert_id;
	//send mail
	if (count($_REQUEST['selectedItem'])>0) {
		$mail= new Mailer();
		$mail->basicSetting();
		$subject=$people['name']." update the article: ".stripslashes($name).", Quicklab knowledge base";
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$message="
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?id=".$id."' target='_blank'>".stripslashes($name)."</a></p>
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?p=".$article['cat_id']."' target='_blank'>".getPath($article['cat_id'])."</a></p>
  <p>Updated by: {$people['name']} $date</p>
  <p>Knowledge base, share your knowledge.</p>";
		$message=$css.$message;
		$mail->Subject =$subject;
		$mail->MsgHTML($message);
		$mail->AddReplyTo($people['email'],$people['name']);
		foreach ($_REQUEST['selectedItem'] as $key=>$value) {
			$people=get_record_from_id('people',$value);
			$email=trim($people['email']);
			if ($email!=''&&valid_email($email)) {
				$mail->AddAddress($email);
			}
			@$mail->Send();
			$mail -> ClearAddresses();
		}
	}
	if ($_REQUEST['whichButton'] == "Save" ) {
		ShowLinks ($_REQUEST['user'], $article['cat_id']);
	} else {
		AddLinkForm();
	}
}

function AddComment () {
	$id=$_REQUEST['id'];
	$content=$_POST['content'];
	$db_conn = db_connect();
	$query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
	try {
		if (!filled_out(array($_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,  please try again.');
		}
		$date = date('Y-m-d G:i:s');
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by = $match['people_id'];
		$people=get_record_from_id('people',$created_by);
		//add comment
		$query="INSERT INTO kbase_comments (article_id,content,date_create,created_by) VALUES ('$id','$content','$date','$created_by')";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
	}
	catch (Exception $e) {
		echo '<table  width="100%" class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
	//send mail
	if (count($_REQUEST['selectedItem'])>0) {
		$mail= new Mailer();
		$mail->basicSetting();
		$subject=$people['name']." added comment to the article: ".$article['name'].", Quicklab knowledge base";
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$message="
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?id=".$article['id']."' target='_blank'>".$article['name']."</a></p>
  <p><a href='http://".IP_ADDRESS."/quicklab/kbase.php?p=".$article['cat_id']."' target='_blank'>".getPath($article['cat_id'])."</p>
  <p>Add comment by: {$people['name']} $date</p>
  <p>Knowledge base, share your knowledge.</p>";
		$message=$css.$message;
		$mail->Subject =$subject;
		$mail->MsgHTML($message);
		$mail->AddReplyTo($people['email'],$people['name']);
		foreach ($_REQUEST['selectedItem'] as $key=>$value) {
			$people=get_record_from_id('people',$value);
			$email=trim($people['email']);
			if ($email!=''&&valid_email($email)) {
				$mail->AddAddress($email);
			}
			@$mail->Send();
			$mail -> ClearAddresses();
		}
	}
	header("location:kbase_content.php?action=LINKDETAILFORM&id=$id");
}

function EditComment () {
	$id=$_REQUEST['id'];
	$content=$_POST['content'];
	$db_conn = db_connect();
	$query="SELECT * FROM kbase_comments WHERE id='$id'";
	$result = $db_conn->query($query);
	$comment=$result->fetch_assoc();
	try {
		if (!filled_out(array($_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,  please try again.');
		}
		$date = date('Y-m-d G:i:s');
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$updated_by = $match['people_id'];
		$people=get_name_from_id('people',$updated_by);
		//add comment
		$query = "UPDATE kbase_comments SET
		content='$content',
		date_update='$date',
		updated_by='$updated_by'
		WHERE id='$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
	}
	catch (Exception $e) {
		echo '<table  width="100%" class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
	header("location:kbase_content.php?action=LINKDETAILFORM&id={$comment['article_id']}");
}

function DeleteLink ()
{
	if(!userPermission('3'))
    {
  	  alert_map();
    }
	//dim queryString, i, selectedLink
	$db_conn=db_connect();
	$selectedLink=$_REQUEST['selectedLink'];
	$num_selectedLink=count($selectedLink);
	for($i=0;$i<$num_selectedLink;$i++)
	{
	  $queryString = "SELECT * FROM storages WHERE ((location_id=" . $selectedLink[$i] . ") AND state=1)";
	  $rs = $db_conn->query($queryString);
	  $rs_num = $rs->num_rows;
	  if($rs_num!=0)
	  {
	  	echo '<table class="alert"><tr><td><h3>The box(es) has storage(s) in stock,can not be deleted!</h3></td></tr></table>';
		exit;
	  }
	  else
	  {
	    $queryString = "DELETE FROM kbase_cat WHERE ((id=" . $selectedLink[$i] . "))";
	    $rs = $db_conn->query($queryString);
	  }
	}
	//Conn.Execute queryString, , adExecuteNoRecords
	//next
	ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
	causeTreeToReload();
}

function MoveLink () {
	$selectedLink=split(",",$_REQUEST['nodeIds']);
	$num_selectedLink=count($selectedLink);
	for($i=0;$i<$num_selectedLink;$i++) {
		$db_conn=db_connect();
		$queryString = "UPDATE kbase SET cat_id = ".$_REQUEST['moveTo']." WHERE (((id)=".$selectedLink[$i]."))";
		$rs = $db_conn->query($queryString);
	}
	ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
	causeTreeToReload();
}

function DBFolderUpdate () {
	// rsFolder, queryString, filteredLink, currentFolder
	//Set rsFolder = Server.CreateObject("ADODB.Recordset")
	$db_conn=db_connect();
	$user = $_REQUEST['user'];
	$parent = $_REQUEST['parent'];
	$isbox = 0;
	$nodeName = $_REQUEST['name'];
	$notes = $_REQUEST['comments'];

	if ($_REQUEST['action'] == "NEWFOLDER") {
		$queryString = "INSERT INTO kbase_cat
		(name,pid,note)
		values
		('$nodeName','$parent','$notes')";
	}
	else { // It must be EDITFOLDER
		$queryString = "UPDATE kbase_cat
		SET
		name='$nodeName',
		note='$notes'
		WHERE (id =" . $_REQUEST['nodeId'] . ")";
	}
	$rs = $db_conn->query($queryString);
	//rsFolder.Open queryString, Conn, adOpenKeyset, adLockOptimistic
	$currentFolder = $_REQUEST['parent'];
	//rsFolder.Close;
	if ($_REQUEST['whichButton'] == "Save")  {
		ShowLinks ($_REQUEST['user'], $currentFolder);
	}
	else {
		AddFolderForm();
	}
	causeTreeToReload();
}

function RecursiveDeleteOfFolders($folderId)
{
	//dim queryString, i, rsHits, subFolder
	$db_conn=db_connect();
	$queryString = "SELECT * FROM kbase_cat WHERE ((id=" . $folderId . "))";
	$rs = $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	try
	{
	  if($rsHits['pid']==0)
	  {
  		throw new Exception('The root forder can not be deleted!');
  	  }
	  $queryString = "SELECT * FROM kbase_cat WHERE ((pid=" . $folderId . "))";
	  $rs = $db_conn->query($queryString);
	  $rs_num = $rs->num_rows;
	  if($rs_num!=0)
	  {
	  	throw new Exception('This forder has subforder(s),can not be deleted!');
	  }
	  $queryString = "SELECT * FROM kbase WHERE ((cat_id=" . $folderId . "))";
	  $rs = $db_conn->query($queryString);
	  $rs_num = $rs->num_rows;
	  if($rs_num!=0)
	  {
	  	throw new Exception('This forder has article(s),can not be deleted!');
	  }
	  $queryString = "DELETE FROM kbase_cat WHERE ((id=" . $folderId . "))";
	  $rs = $db_conn->query($queryString);
	}
    catch (Exception $e)
  	{
  	  echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  	  exit;
    }
	/*
	//debugPrint queryString
	//Conn.Execute queryString, , adExecuteNoRecords

	//$queryString = "DELETE FROM location WHERE ((pid=" . $folderId . ") AND (isbox=1))";
	//$rs = $db_conn->query($queryString);
	//debugPrint queryString
	//Conn.Execute queryString, , adExecuteNoRecords

	//$queryString = "SELECT FROM location WHERE ((pid=" . $folderId . ") AND (isbox=0))";
	//Set rsHits = Server.CreateObject("ADODB.Recordset")
	//$rs = $db_conn->query($queryString);
	//$rsHits=$rs->fetch_assoc();
	//$subFolder=9999;
	//do while not subFolder=-1
	while($subFolder!=0)
	{
		//rsHits.Open queryString, Conn
		$subFolder=0;
		if($rsHits)
		{
			$subFolder = $rsHits['id'];
		}
		//if not rsHits.EOF then subFolder = rsHits("nodeId")
		//rsHits.close
		if($subFolder!=0)
		{
			RecursiveDeleteOfFolders ($subFolder);
		}
		//if not subFolder = -1 then RecursiveDeleteOfFolders ($subFolder)
	//loop
	}
	}
	*/
}

function CopyFolder ()
{
	$currentFolder = $_REQUEST['nodeId'];
	$movetoFolder = $_REQUEST['moveTo'];
	$db_conn=db_connect();

	$queryString = "SELECT * FROM kbase_cat WHERE (id=" . $currentFolder .")";
	$rs = $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();

	$queryString = "INSERT INTO kbase_cat
	(name,pid,note)
	VALUES
	('".$rsHits['name']."','".$movetoFolder."','".$rsHits['note']."')";
	$rs = $db_conn->query($queryString);
	$movetoFolder=$db_conn->insert_id;

	$db_conn=db_connect();
	CopyFolderRecursion($currentFolder,$movetoFolder,$db_conn);

	ShowLinks  ($_REQUEST['user'], $movetoFolder);
	causeTreeToReload();
}
function CopyFolderRecursion($currentFolder,$movetoFolder,$db_conn)
{
	//$db_conn=db_connect();
	$queryString = "SELECT * FROM kbase_cat WHERE (pid=" . $currentFolder ." AND isbox=0)";
	$rs = $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	if($rs_num)
	{
	  while ($rsHits=$rs->fetch_array())
	  {
	  	$queryString = "INSERT INTO kbase_cat
		(name,pid,note)
		VALUES
		('".$rsHits['name']."','".$movetoFolder."','".$rsHits['note']."')";
		$insert = $db_conn->query($queryString);
		$movetoFolder_new=$db_conn->insert_id;
		CopyFolderRecursion($rsHits['id'],$movetoFolder_new,$db_conn);
	  }
	}
}

function MoveFolder ()
{
	//dim queryString, i, currentFolder
	$currentFolder = $_REQUEST['nodeId'];
	$db_conn=db_connect();

	$queryString = "SELECT * FROM kbase_cat WHERE ((id=" . $currentFolder . "))";
	$rs = $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	$queryString = "UPDATE kbase_cat SET pid = ".$_REQUEST['moveTo']." WHERE (((id)=".$currentFolder."))";
	$rs = $db_conn->query($queryString);
	//debugPrint queryString
	//Conn.Execute queryString, , adExecuteNoRecords
	ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
	causeTreeToReload();
}

function DeleteFolder () {
	if(!userPermission('2')) {
		alert_map();
	}
	RecursiveDeleteOfFolders ($_REQUEST['parent']);
	ShowLinks  ($_REQUEST['user'], -10);
	//ShowLinks  $_REQUEST("user"), -10
	causeTreeToReload();
}

function LinkDetailForm() {
	$id=$_REQUEST['id'];
	$db_conn=db_connect();
	$query="SELECT * FROM kbase WHERE id='$id'";
	$rs=$db_conn->query($query);
	$article=$rs->fetch_assoc();
	$views=$article['views']+1;
  $query="update `kbase` set `views`='$views' where `id`='$id'";
  $db_conn->query($query);
	//name
	echo '<table border=0 width=100% cellspacing=0 cellpadding=5>';
	echo "<tr><td><font size=+2 color='#3A5AF4'>".$article['name']."</font>&nbsp;&nbsp;";
	//edit link
	if (userPermission(2,$article['created_by'])) {
  	echo "<a href='kbase_content.php?action=EDITLINKFORM&id=$id' target='_self' ><img src='./assets/image/general/edit.gif' title='Edit' border='0'/></a>&nbsp;&nbsp;";
  } else {
  	echo "<img src='./assets/image/general/edit-grey.gif' title='Edit' border='0'/>&nbsp;&nbsp;";
  }
  //comment link
  if (userPermission(3)) {
		echo "<a href='kbase_content.php?id=$id&action=ADDCOMMENTFORM' target='_self'><img src='./assets/image/general/comments.gif' title='Add comment' border='0'/></a>";
  } else {
		echo "<img src='./assets/image/general/comments-grey.gif' title='Add comment' border='0'/>";
  }
	echo "</td></tr>";
	//path
	echo "<tr><td><a href='kbase_content.php?action=FORDERDETAILFORM&p={$article['cat_id']}'/>".getPath($article['cat_id'])."</a></td></tr>";
	//link
	echo "<tr><td>Link: http://".IP_ADDRESS."/quicklab/kbase.php?id=$id</td></tr>";
	//create
	$query="select a.date_create,b.name from kbase a, people b
  where a.id=$id
  and a.created_by=b.id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  echo "<tr><td>Created by: ".$match['name']." ".$match['date_create']."</td></tr>";
  //update
  $query="select a.date_update,b.name from kbase a, people b
  where a.id=$id
  and a.updated_by=b.id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  echo "<tr><td class=separator>Last updated by: ".$match['name']." ".$match['date_update']."</td></tr>";
	echo "</table>";
	//content
	echo "<p>".$article['content']."</p>";
	//comment
	$query="select a.*, b.name from kbase_comments a,people b
	where a.article_id=$id
	and a.created_by=b.id
	order by a.id desc";
	$rs=$db_conn->query($query);
	if ($rs->num_rows>0) {
		echo "<table border=0 width=100% cellspacing=0 cellpadding=5>";
		echo "<tr><td><font size=+1 color='#3A5AF4'>Comment:</font></td></tr>";
		echo "</table>";
	}
	while ($match=$rs->fetch_assoc()) {
		echo "<table border=0 width=100% cellspacing=0 cellpadding=5>";
		echo "<tr><td class=separator-top>".$match['name']." ".$match['date_create']."&nbsp;&nbsp;";

		if (userPermission(0,$match['created_by'])) {
			echo "<a href='kbase_content.php?id={$match['id']}&action=EDITCOMMENTFORM' target=\"_self\"><img src='./assets/image/general/edit.gif' title='Edit comment' border='0'/></a>";
		} else {
			echo "<img src='./assets/image/general/edit-grey.gif' title='Edit comment' border='0'/>";
		}
		echo "</td></tr></table>";
		echo "<p>".$match['content']."</p>";
	}
}

function processRequest() {
	//There two types of requests: even triggered by the control buttons (these are all POST submissions of the form)
	//and simple GET queries either started on the left tree or simply for initialization of the frameset, the first time
	if ($_REQUEST['whichButton'] != "Cancel" ) {
		if (!isset($_REQUEST['action'])) {
			ShowLinks ($userId, -10) ;
		} else {
			$action = $_REQUEST['action'];
			switch ($action) {
				case "NEWFOLDERFORM": AddFolderForm(); break;
				case "EDITFOLDERFORM": EditFolderForm(); break;
				case "COPYFOLDERFORM": CopyFolderForm(); break;
				case "MOVEFOLDERFORM": MoveFolderForm(); break;
				case "NEWLINKFORM": AddLinkForm(); break;
				case "EDITLINKFORM": EditLinkForm(); break;
				case "NEWFOLDER": DBFolderUpdate(); break;
				case "EDITFOLDER": DBFolderUpdate(); break;
				case "MOVELINKFORM": MoveLinkForm(); break;
				case "LINKDETAILFORM": LinkDetailForm(); break; //?
				case "FORDERDETAILFORM": ShowLinks ($userId, $_REQUEST['p']); break; //?
				case "LINKDETAILFORM": LinkDetailForm (); break; //?
				case "SEARCHFORM": SearchForm (); break; //?
				case "ADDCOMMENTFORM": AddCommentForm(); break; //?
				case "EDITCOMMENTFORM": EditCommentForm(); break; //?

				case "MOVEFOLDER": MoveFolder(); break;
				case "COPYFOLDER": CopyFolder(); break;
				case "DELFOLDER": DeleteFolder(); break;
				case "NEWLINK": AddLink(); break;
				case "EDITLINK": EditLink(); break;
				case "MOVELINK": MoveLink(); break;
				case "DELLINK": DeleteLink(); break;
				case "ADDCOMMENT": AddComment(); break;
				case "EDITCOMMENT": EditComment(); break;
				default: break;
			}
		}
	} else {
		ShowLinks ($_REQUEST['user'], $_REQUEST['parent']);
	}
}
?>
<?php
	processRequest();
	/* used for debugging
	echo "parent=".$_REQUEST['parent']."</br>";
	echo "parentName=".$_REQUEST['parentName']."</br>";
	echo "name=".$_REQUEST['name']."</br>";
	echo "nodeId=".$_REQUEST['nodeId']."</br>";
	echo "nodeIds=".$_REQUEST['nodeIds']."</br>";
	echo "user=".$_REQUEST['user']."</br>";
	echo "selectedLink=".$_REQUEST['selectedLink']."</br>";
	echo "url=".$_REQUEST['url']."</br>";
	echo "comments=".$_REQUEST['comments']."</br>";
	echo "whichAction=".$_REQUEST['whichAction']."</br>";
	echo "whichButton=".$_REQUEST['whichButton']."</br>";
	echo "moveTo=".$_REQUEST['moveTo']."</br>";
	*/
?>
</html>