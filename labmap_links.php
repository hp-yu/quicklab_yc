<?php

include('./include/includes.php');
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
<link href="./CSS/general.css" rel="stylesheet" type="text/css" />
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
	if (document.form.actionRequest.value == "changeUserForm") {
		<?php
		if (!userPermission('2')) {
		?>
			alert("You do not have the authority to do this.")
			document.form.actionRequest.value = ""
		  return
		<?php
		}
		?>
		submitShowForm("CHANGEUSERFORM")
	}
	document.form.submit()
}

function submitShowForm(actionType) {
	var i;
	var found="";
	var confirmVal
	var folderName
	if (actionType=='EDITLINKFORM' || actionType=='MOVELINKFORM' || actionType=='DELLINK') {
		for (i=0; document.forms[0].elements[i]; i++) {
			if (document.forms[0].elements[i].type == "checkbox" && document.forms[0].elements[i].name == "selectedLink[]" && document.forms[0].elements[i].checked)
				found=found + "\n" + document.forms[0].elements[i-1].value
		}
	}
	else
		found="link_sel_not_needed"

	if (found=="")
		alert("Please check at least one box")
	else {
		if (actionType == "DELLINK") {
			confirmVal = confirm("Are you sure you want to delete the selected box(s)?")
			if (!confirmVal)
				return;
		}
		if (actionType == "DELFOLDER") {
			folderName = document.forms[0].elements[2].value
			if (folderName=="Bookmarks") {
				alert("Root folder Bookmarks cannot be removed.")
				return;
			}
			confirmVal = confirm("Are you sure you want to delete Location " + folderName + "?")
			if (!confirmVal)
				return;
		}
		if (actionType == "EDITFOLDERFORM") {
			folderName = document.forms[0].elements[2].value
			if (folderName=="Bookmarks") {
				alert("Root folder Bookmarks cannot be edited.")
				return;
			}
		}
		if (actionType == "MOVEFOLDERFORM") {
			folderName = document.forms[0].elements[2].value
			if (folderName=="Bookmarks") {
				alert("Root folder Bookmarks cannot be moved.")
				return;
			}
		}
		document.forms[0].elements[0].value=actionType
		document.forms[0].submit()
	}
}
</script>


</head>


<body bgcolor=white link="#3A5AF4">


<font face=verdana size=-1>
<?php
//<%

//Outputs the hidden filds that most of the forms will need
//sub standardForm(principalAction, parent, parentName, user)
function standardForm($principalAction, $parent, $parentName, $user)
{
	echo '<form name="form" action="labmap_links.php" method=post>' . "\n" . "\n";
	//Don't change order
  echo "<input type=hidden name=whichAction value='$principalAction'>" . "\n" ;
  echo "<input type=hidden name=parent value='$parent'>" . "\n";
  echo "<input type=hidden name=parentName value='$parentName'>" . "\n";
  echo "<input type=hidden name=user value='$user'>" . "\n";
}
//end sub

function sqlQ($sqlArg)
{
	Subst($sqlArg, "'", "''");
}


//Get a string with the path of the folder
function getPath($folderId,$db_conn="")
{
	//dim rsHits, queryString, auxStr, parentId
	$auxStr="";
	$parentId=999;
	while ($folderId != 0 )
	{
		if ($db_conn=="") {
			$db_conn = db_connect();
		}
		$queryString = "SELECT pid, name FROM location WHERE ((id=".$folderId."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if ($auxStr == "" )
		{
			//$auxStr = FixUpItems($rsHits['name']);
			$auxStr = $rsHits['name'];
		}
		else
		{
			//$auxStr = FixUpItems($rsHits['name']) . " > " . $auxStr;
			$auxStr = $rsHits['name']. " > " . $auxStr;
		}
		$folderId = $rsHits['pid'];
	}
	return $auxStr;
}


//sub ShowLinks(user, parentId)
function ShowLinks($user, $parentId)
{
	//dim rsHits, queryString, notesString, aux, parentName, notes
  $db_conn = db_connect();
	if ($parentId == -10 )
	{
	//Means root folder in this script (we don;t know the real id, only that its parent has the -1
		$queryString = "SELECT id, user, pid, name, note FROM location WHERE ((pid=0))";// AND (user=".$user."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if (!$rsHits)
		{
			echo("Could not find data for user " . $user . ". Contact administrator");
		}
		$ppid=$rsHits['pid'];
		$parentId = $rsHits['id'];
		$user = $rsHits['user'];
		$parentName = $rsHits['name'];
		$notes = $rsHits['note'];
	}
	else
	{
		$queryString = "SELECT user, pid, name, note FROM location WHERE ((id=" . $parentId . ") )";//AND (user=".$user."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		$user = $rsHits['user'];
		$ppid=$rsHits['pid'];
		$parentName = $rsHits['name'];
		$notes = $rsHits['note'];
	}

	$queryString = "SELECT id, name, box_size, note FROM location WHERE ((pid=" . $parentId . ") AND (isbox=1) ) ORDER BY name";
	$rs= $db_conn->query($queryString);
	$rs_num = $rs->num_rows;

	//$parentName=Subst($parentName, "", "\");
	standardForm ("EDITLINKFORM", $parentId, $parentName, $user);
	echo '<table border=0 width=100% cellspacing=0 cellpadding=5>' .  "\n";
	//echo "<tr><td colspan=3 class=separator><font size=+2 color='#3A5AF4'>" . FixUpItems($parentName)  . "</font>" .  "\n";
	echo "<tr><td colspan=3 class=separator><font size=+2 color='#3A5AF4'>".$parentName."</font>" .  "\n";
	//echo "<br>" . FixUpItems($notes)  .  "\n";
	if ($ppid) {
		echo "<br>Contained by: ".getPath($ppid);
	}
	if ($notes!='') {
		echo "<br>Note: " .$notes.  "\n";
	}
	if ($user) {
		$people=get_name_from_id(people,$user);
		echo "<br>User: ".$people[name];
	}

	for($i=0;$i< $rs_num;$i++)
	{
		$rsHits=$rs->fetch_assoc();
		echo "<tr>" .  "\n";
		//echo "<td class=separator><a href='".$rsHits['box_size']."' target=_blank><b>".FixUpItems($rsHits['name'])."</a></b><br>" . "\n";
		echo "<td class=separator><a href='".$rsHits['box_size']."' target=_blank><b>".$rsHits['name']."</a></b><br>" . "\n";
		$notesString = "" . $rsHits['note'];
		//echo "<td class=separator>" . FixUpItems($notesString) . "&nbsp;</td>" . "\n";
		echo "<td class=separator>" .$notesString. "&nbsp;</td>" . "\n";
		//will need to encode and decode the name
		echo '<td class=separator><input type="checkbox" name="selectedLink[]" value=' . $rsHits['id'] . "></td></tr>";
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

function LinkForm ($nodeId, $name, $comments, $url, $action)
{
	standardForm($action, $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
    echo "<input type=hidden name=nodeId value=" . $nodeId . ">" .  "\n";
	echo "<table border=0 cellspacing=0 cellpadding=0>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>";
	if ($action == "NEWLINK" )
	{
		echo "Add New Box";
	}
	else
	{
		echo "Edit Box";
	}
	echo "</font>" .  "\n";
	echo "<tr><td>&nbsp;<td>&nbsp;";
	echo "<tr><td>Location:<td>";
	echo getPath($_REQUEST['parent']);
	echo "<tr><td>&nbsp;<td>&nbsp;";
	//echo "<tr><td>&nbsp;<td><font color=gray>(Include http://..., ftp://.., etc.)</font><br><br>";
	echo "<tr><td>Box&nbsp;Name:<td>" .  "\n";
	//echo '<input name=name size=50 value=' . FixUpItems($name) . ">" .  "\n";
	echo "<input name=name size=50 value='$name'>" .  "\n";
	echo "<tr><td>&nbsp;<td>&nbsp;";
	echo "<tr><td>Comments:&nbsp;&nbsp;<td>" .  "\n";
	//echo '<input size=50 name=comments value=""' . FixUpItems($comments) . ">" .  "\n";
	echo "<input size=50 name=comments value='$comments'>" .  "\n";
	echo "<tr><td>&nbsp;<td>&nbsp;";
	echo "<tr><td>Box&nbsp;Size:<td>" .  "\n";
	echo '<input size=50 name=url value=';
	echo $url;
	echo ">" .  "\n";
	echo "<tr><td>&nbsp;<td>&nbsp;";
	echo "<tr><td>&nbsp;<td align=left>" .  "\n";
	echo '<input type=submit name=whichButton value="Save">';
	if ($action == "NEWLINK" )
	{
		echo '&nbsp;<input type=submit name=whichButton value="Save & Add Another">';
	}
	echo '&nbsp;<input type=submit name=whichButton value="Cancel">';
	echo "</table>";
	echo "</form>";
}

function AddLinkForm ()
{
	if(!userPermission('3'))
    {
  	  alert_map();
    }
	LinkForm (0, "", "", "", "NEWLINK");
}

function EditLinkForm ()
{
	if(!userPermission('3'))
    {
  	  alert_map();
    }
	//dim rsHits, queryString, selectedName, url, notes, name, selectedLink
	$selectedLink = $_REQUEST[selectedLink][0];//'selectedLink'
	$db_conn = db_connect();
	$queryString = "SELECT name, pid, note, box_size FROM location WHERE ((id=" . $selectedLink . "))";
	$rs= $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	if (!$rsHits)
	{
		echo "Could not find link in database.";
	}
	else
	{
		$name = $rsHits['name'];
		$notes = $rsHits['note'];
		$url = $rsHits['box_size'];
		LinkForm ($selectedLink, $name, $notes, $url, "EDITLINK");
	}
}

function outputFolderSelection($id, $parentId, $indentation,$db_conn)
{
	//dim rsHits, queryString, gFldStr
	//$db_conn = db_connect();
	$queryString = "SELECT id, name, isbox, pid, box_size, note FROM location WHERE ((pid=" . $parentId . ") AND (isbox=0) ) ORDER BY name";
	$rs= $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	for($i=0;$i<$rs_num;$i++)
	{
		$rsHits=$rs->fetch_assoc();
		echo "<option value=" . $rsHits['id'] . ">" .  "\n";
		echo $indentation . $rsHits['name'] .  "\n";
		outputFolderSelection ($user, $rsHits['id'], $indentation."&nbsp;&nbsp;",$db_conn) ;
	}
}

function MoveLinkForm ()
{
	if(!userPermission('3'))
    {
  	  alert_map();
    }
        ?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
    <?php
	$nodeIds=implode(",",$_REQUEST['selectedLink']);
	standardForm ("MOVELINK", $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
	echo '<input type=hidden name=nodeIds value='.$nodeIds.">" .  "\n";
	echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Move Box(s)</font>" .  "\n";
	echo "<tr><td valign=top>Move selected<br>boxes from:<td valign=bottom>";
	echo getPath($_REQUEST['parent']);
	echo "<tr><td valign=top>Move To:<td>" .  "\n";
	echo "<div id=\"cascade_select\"><input type='hidden' id='br' value='1'/></div>";
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
function FolderForm ($nodeId, $user, $name, $comments, $action)
{
	standardForm ($action, $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
  echo "<input type=hidden name=nodeId value=" . $nodeId . ">" .  "\n";
	echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>";
	if ($action == "NEWFOLDER")
	{
		echo "Add New Location";
	}
	else
	{
		echo "Edit Location";
	}
	echo "</font>" .  "\n";
	if ($action == "NEWFOLDER")
	{
		echo "<tr><td valign=top>Contained by:<td>";
		echo getPath($_REQUEST['parent']);
	}
	echo "<tr><td>Location Name:<td>" .  "\n";
	//echo "<input name=name size=50 value=".FixUpItems($name).">" .  "\n";
	echo "<input name=name size=50 value='$name'>" .  "\n";
	echo "<tr><td>Comments:<td>" .  "\n";
	//echo '<input size=50 name=comments value='.FixUpItems($comments).">" .  "\n";
	echo "<input size=50 name=comments value='$comments'>" .  "\n";
	echo "<tr><td>User:<td>" .  "\n";
	$query="SELECT id,name FROM people WHERE `state`=0 ORDER BY CONVERT(name using GBK)";
	echo query_select_choose('user',$query,'id','name',$user);
	if ($action == "EDITFOLDER" ) {
		echo '&nbsp;&nbsp;<input type="checkbox" name="include_daughter" value="1"/>&nbsp;Include daughter locations';
	}
	echo "<tr><td> <td align=left>" .  "\n";
	echo '<input type=submit name=whichButton value="Save">';
	if ($action == "NEWFOLDER" ) {
		echo ' <input type=submit name=whichButton value="Save & Add Another">';
	}
	echo ' <input type=submit name=whichButton value="Cancel">';
	echo "</table>";
	echo "</form>";
}

function AddFolderForm ()
{
	if(!userPermission('2'))
    {
  	  alert_map();
    }
	FolderForm (0, "", "", "", "NEWFOLDER");
}

function EditFolderForm () {
	if(!userPermission('2')) {
		alert_map();
	}
	$db_conn=db_connect();
	$queryString = "SELECT user, name, note FROM location WHERE ((id=" . $_REQUEST['parent'] . "))";
	$rs=$db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	if (!$rsHits) {
		echo "Could not find folder in database.";
	}
	else {
		$user = $rsHits['user'];
		$name = $rsHits['name'];
		$notes = $rsHits['note'];
		FolderForm ($_REQUEST['parent'], $user, $name, $notes, "EDITFOLDER");
	}
}

function CopyFolderForm ()
{
	if(!userPermission('2'))
    {
  	  alert_map();
    }
        ?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
    <?php
	$nodeId = $_REQUEST['parent'];
	$db_conn=db_connect();
	$queryString = "SELECT name, pid, note FROM location WHERE ((id=" . $nodeId . "))";
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
		echo $comments.  "\n";
		echo "<tr><td valign=top>Paste To:<td>" .  "\n";
		echo "<div id=\"cascade_select\"><input type='hidden' id='br' value='1'/></div>";
		echo "<tr><td> <td align=left>"."\n";
		echo '<input type=submit name=whichButton value="Save">';
		echo ' <input type=submit name=whichButton value="Cancel">';
		echo "</table>";
		echo "</form>";
	  }
	}
}
function MoveFolderForm ()
{
	if(!userPermission('2'))
    {
  	  alert_map();
    }
    ?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
    <?php
	$nodeId = $_REQUEST['parent'];
	$db_conn=db_connect();
	$queryString = "SELECT name, pid, note FROM location WHERE ((id=" . $nodeId . "))";
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
        echo '<table class="alert"><tr><td><h3>The root location can not be moved!</h3></td></tr></table>';
        exit;
	  }
	  else
	  {
		$name = $rsHits['name'];
		$comments = $rsHits['note'];
		standardForm ("MOVEFOLDER", $_REQUEST['parent'], $_REQUEST['parentName'], $_REQUEST['user']);
		echo "<input type=hidden name=nodeId value='$nodeId '>" .  "\n";
		echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
		echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>Move Location</font>" .  "\n";
		echo "<tr><td valign=top>Contained in:<td>";
		echo getPath($rsHits['pid']);
		echo "<tr><td valign=top>Location Name:<td>" .  "\n";
		//echo FixUpItems($name) .  "\n";
		echo $name.  "\n";
		echo "<tr><td valign=top>Comments:<td>" .  "\n";
		//echo FixUpItems($comments) .  "\n";
		echo $comments.  "\n";
		echo "<tr><td valign=top>Move To:<td>" .  "\n";
		echo "<div id=\"cascade_select\"><input type='hidden' id='br' value='1'/></div>";
		echo "<tr><td> <td align=left>"."\n";
		echo '<input type=submit name=whichButton value="Save">';
		echo ' <input type=submit name=whichButton value="Cancel">';
		echo "</table>";
		echo "</form>";
	  }
	}
}


//------- end forms ---------

function DBLinkUpdate ()
{
	//Dim rsLink, queryString, filteredLink
	//Set rsLink = Server.CreateObject("ADODB.Recordset")
	$db_conn=db_connect();
	$user = $_REQUEST['user'];
	$parent = $_REQUEST['parent'];
	$isbox = 1;
	$nodeName = $_REQUEST['name'];
	$filteredLink = Subst($_REQUEST['url'], "", "");
	$filteredLink = Subst($filteredLink, "'", "");
	$filteredLink = Subst($filteredLink, "<", "");
	$filteredLink = Subst($filteredLink, ">", "");
	$box_size = $filteredLink;
	$notes = $_REQUEST['comments'];

	if ($_REQUEST['whichAction'] == "NEWLINK" )
	{
		$queryString = "INSERT INTO location
		(name,pid,isbox,box_size,note)
		values
		('$nodeName','$parent','$isbox','$box_size','$notes')";
	}
	else //It must be EDITLINK
	{
		$queryString = "UPDATE location
		SET
		user='$user',
		name='$nodeName',
		pid='$parent',
		isbox='$isbox',
		box_size='$box_size',
		note='$notes'
		WHERE ((id =" . $_REQUEST['nodeId'] . "))";
	}
	$rs = $db_conn->query($queryString);
	if ($_REQUEST['whichButton'] == "Save" )
	{
		ShowLinks ($_REQUEST['user'], $_REQUEST['parent']);
	}
	else
	{
		AddLinkForm();
	}
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
	    $queryString = "DELETE FROM location WHERE ((id=" . $selectedLink[$i] . "))";
	    $rs = $db_conn->query($queryString);
	  }
	}
	//Conn.Execute queryString, , adExecuteNoRecords
	//next
	ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
}

function MoveLink ()
{
	$selectedLink=split(",",$_REQUEST['nodeIds']);
	$num_selectedLink=count($selectedLink);
	
	$num_select = $_REQUEST['num_select'];
	for ($i=1;$i<=$num_select;$i++) {
		if ($_REQUEST['S'.$i]!="") {
			$moveTo = $_REQUEST['S'.$i];
		}
	}
		
	for($i=0;$i<$num_selectedLink;$i++)
	{
		$db_conn=db_connect();
		
		if ($moveTo*1!=$selectedLink[$i]*1) {
			$queryString = "UPDATE location SET pid = ".$moveTo." WHERE (((id)=".$selectedLink[$i]."))";
			$rs = $db_conn->query($queryString);
		}
	}
	ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
}

function DBFolderUpdate ()
{
	// rsFolder, queryString, filteredLink, currentFolder
	//Set rsFolder = Server.CreateObject("ADODB.Recordset")
	$db_conn=db_connect();
	$user = $_REQUEST['user'];
	$parent = $_REQUEST['parent'];
	$isbox = 0;
	$nodeName = $_REQUEST['name'];
	$notes = $_REQUEST['comments'];

	if ($_REQUEST['whichAction'] == "NEWFOLDER")
	{
		$queryString = "INSERT INTO location
		(user,name,pid,isbox,note)
		values
		('$user','$nodeName','$parent','$isbox','$notes')";
	}
	else // It must be EDITFOLDER
	{
		$queryString = "UPDATE location
		SET
		user='$user',
		name='$nodeName',
		note='$notes'
		WHERE (id =" . $_REQUEST['nodeId'] . ")";
		if ($_REQUEST['include_daughter']==1) {
			DBFolderUpdateRecursion($_REQUEST['nodeId'],$user,$db_conn);
		}
	}
	$rs = $db_conn->query($queryString);
	//rsFolder.Open queryString, Conn, adOpenKeyset, adLockOptimistic
	$currentFolder = $_REQUEST['parent'];
	//rsFolder.Close;
	if ($_REQUEST['whichButton'] == "Save")
	{
		ShowLinks ($_REQUEST['user'], $currentFolder);
	}
	else
	{
		AddFolderForm();
	}
	causeTreeToReload();
}

function DBFolderUpdateRecursion($pid,$user,$db_conn) {
	//$db_conn=db_connect();
	$queryString = "SELECT * FROM location WHERE (pid=" . $pid ." AND isbox=0)";
	$rs = $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	if($rs_num) {
	  while ($rsHits=$rs->fetch_array()) {
	  	$queryString = "UPDATE location SET
		user='$user'
		WHERE (id =" . $rsHits['id'] . ")";
		$db_conn->query($queryString);
		DBFolderUpdateRecursion($rsHits['id'],$user,$db_conn);
	  }
	}
}

function RecursiveDeleteOfFolders($folderId)
{
	//dim queryString, i, rsHits, subFolder
	$db_conn=db_connect();
	$queryString = "SELECT * FROM location WHERE ((id=" . $folderId . "))";
	$rs = $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();
	try
	{
	  if($rsHits['pid']==0)
	  {
  		throw new Exception('The root location can not be deleted!');
  	  }
	  $queryString = "SELECT * FROM location WHERE ((pid=" . $folderId . "))";
	  $rs = $db_conn->query($queryString);
	  $rs_num = $rs->num_rows;
	  if($rs_num!=0)
	  {
	  	throw new Exception('This location has sublocation(s),can not be deleted!');
	  }
	  $queryString = "SELECT * FROM storages WHERE ((location_id=" . $folderId . "))";
	  $rs = $db_conn->query($queryString);
	  $rs_num = $rs->num_rows;
	  if($rs_num!=0)
	  {
	  	throw new Exception('This location has storage(s),can not be deleted!');
	  }
	  $queryString = "DELETE FROM location WHERE ((id=" . $folderId . "))";
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
	
	$num_select = $_REQUEST['num_select'];
	for ($i=1;$i<=$num_select;$i++) {
		if ($_REQUEST['S'.$i]!="") {
			$movetoFolder = $_REQUEST['S'.$i];
		}
	}
	
	$db_conn=db_connect();

	$queryString = "SELECT * FROM location WHERE (id=" . $currentFolder .")";
	$rs = $db_conn->query($queryString);
	$rsHits=$rs->fetch_assoc();

	$queryString = "INSERT INTO location
	(name,pid,note)
	VALUES
	('".$rsHits['name']."','".$movetoFolder."','".$rsHits['note']."')";
	$rs = $db_conn->query($queryString);
	$movetoFolder=$db_conn->insert_id;

	CopyFolderRecursion($currentFolder,$movetoFolder,$db_conn);

	ShowLinks  ($_REQUEST['user'], $movetoFolder);
	causeTreeToReload();
}
function CopyFolderRecursion($currentFolder,$movetoFolder,$db_conn)
{
	//$db_conn=db_connect();
	$queryString = "SELECT * FROM location WHERE (pid=" . $currentFolder ." AND isbox=0)";
	$rs = $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	if($rs_num)
	{
	  while ($rsHits=$rs->fetch_array())
	  {
	  	$queryString = "INSERT INTO location
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

	$num_select = $_REQUEST['num_select'];
	for ($i=1;$i<=$num_select;$i++) {
		if ($_REQUEST['S'.$i]!="") {
			$moveTo = $_REQUEST['S'.$i];
		}
	}
	
	if ($moveTo*1!=$currentFolder*1) {
		$queryString = "UPDATE location SET pid = ".$moveTo." WHERE (((id)=".$currentFolder."))";
		$rs = $db_conn->query($queryString);
		ShowLinks  ($_REQUEST['user'], $_REQUEST['parent']);
		causeTreeToReload();
	}
	
}

function DeleteFolder ()
{
	if(!userPermission('2'))
    {
  	  alert_map();
    }
	RecursiveDeleteOfFolders ($_REQUEST['parent']);
	ShowLinks  ($_REQUEST['user'], -10);
	//ShowLinks  $_REQUEST("user"), -10
	causeTreeToReload();
}

function Search() {
	standardForm ("SEARCH",1, "", "");
  echo '<table border=0 width=100% cellspacing=0 cellpadding=5>' .  "\n";
	echo "<tr><td colspan=3 class=separator><font size=+2 color='#3A5AF4'>Search by user:</font>" .  "\n";
	$db_conn = db_connect();
	$query="SELECT id,name FROM people WHERE state=0 ORDER BY CONVERT(name using GBK)";
  $result = $db_conn->query($query);
  $select  = "<select name='user'>";
  $select .= '<option value="all"';
  if($_REQUEST['user']==''||!isset($_REQUEST['user'])||$_REQUEST['user']=='all') $select .= ' selected ';
  $select .= '>all users</option>';
  $select .= '<option value="no"';
  if($_REQUEST['user'] == 'no') $select .= ' selected ';
  $select .= '>no user</option>';
  while ($option = $result->fetch_assoc()) {
    $select .= "<option value='{$option[id]}'";
    if ($option[id] == $_REQUEST['user']) {
      $select .= ' selected';
    }
    $select .=  ">{$option[name]}</option>";
  }
  $select .= "</select>";
	echo $select;
	echo "&nbsp;&nbsp;<input type='submit' value='Go'/>";
	echo "</td></tr>";

	if (isset($_REQUEST['user'])&&$_REQUEST['user']!='0'&&$_REQUEST['user']!='') {
		if ($_REQUEST['user']=="all") {
			$query="SELECT * FROM location WHERE user >0 ORDER BY user";
		}
		elseif ($_REQUEST['user']=="no") {
			$query="SELECT * FROM location WHERE user =0 ORDER BY id";
		}
		else {
			$query="SELECT * FROM location WHERE user LIKE '{$_REQUEST['user']}' ORDER BY user";
		}
		$rs=$db_conn->query($query);
		$num_rows=$rs->num_rows;
		js_selectall();
	?>
	<tr>
	<td class=separator width="10"><input type="checkbox" name="clickall" onclick='selectall(this.checked,form)'></td>
	<td class=separator>Location(<?php echo $num_rows ?>)</td>
	<td class=separator>User</td>
	</tr>
	<?php
	while($match=$rs->fetch_assoc()) {
		echo "<tr>" .  "\n";
		echo "<td class=separator><input type='checkbox' name='selectedItem[]' value={$match['id']}></td>";
		echo "<td class=separator>".getPath($match['id'],$db_conn)."</td>";
		if ($match['user']) {
			$query="SELECT name FROM people WHERE id='{$match['user']}'";
			$rs_people=$db_conn->query($query);
			$people=$rs_people->fetch_assoc();
			echo "<td class=separator>".$people['name']."</td></tr>";
		}
		else {
			echo "<td class=separator>&nbsp;</td></tr>";
		}

	}
?>
<tr><td colspan="3">Selected:
<select name="actionRequest" onchange="javascipt:submitSelected()">
<option value="" selected> -Choose- </option>
<option value="changeUserForm">Change user</option>
</select>
<input type="hidden" name="actionType" value=""></td>
<tr>
<?php
	}
	echo "</table></form>";
}

function ChangeUserForm() {
	standardForm ("CHANGEUSER", 1, "", "");
	echo "<table border=0 cellspacing=0 cellpadding=5>" .  "\n";
	echo "<tr><td colspan=2 class=separator align=left><font size=+2 color='#3A5AF4'>";
	echo "Change user:";
	echo "</font>" .  "\n";
	echo "<tr><td colspan='2'>Location(s):<td>" .  "\n";
  $locations=implode(",",$_REQUEST['selectedItem']);
  echo '<input type=hidden name=locations value='.$locations.">";
  $num_locations=count($_REQUEST['selectedItem']);
  $db_conn=db_connect();
	for($i=0;$i<$num_locations;$i++) {
		$query="SELECT user FROM location WHERE id='{$_REQUEST['selectedItem'][$i]}'";
		$rs=$db_conn->query($query);
		$match=$rs->fetch_assoc();
		$people=get_name_from_id(people,$match[user]);
		echo "<tr><td colspan='2'>";
		echo "&nbsp;".($i+1).". ".getPath($_REQUEST['selectedItem'][$i]).", ".$people[name];
		echo "</td></tr>";
	}
	echo "<tr><td>User:<td>" .  "\n";
	$query="SELECT id,name FROM people WHERE `state`=0 ORDER BY CONVERT(name using GBK)";
	echo query_select_choose('user',$query,'id','name',$user);
	echo "<tr><td> <td align=left>" .  "\n";
	echo '<input type=submit name=whichButton value="Save">';
	echo '<input type=submit name=whichButton value="Cancel">';
	echo "</table>";
	echo "</form>";
}

function ChangeUser() {
	if (!userPermission(2)) {
		exit;
	}
	$locations=split(",",$_REQUEST['locations']);
	$num_locations=count($locations);
	$db_conn=db_connect();
	for($i=0;$i<$num_locations;$i++) {
		$queryString = "UPDATE location SET user = '".$_REQUEST['user']."' WHERE (((id)=".$locations[$i]."))";
		$rs = $db_conn->query($queryString);
	}
	Search();
}

function processRequest()
{
	//Dim act_gl
	//There two types of requests: even triggered by the control buttons (these are all POST submissions of the form)
	//and simple GET queries either started on the left tree or simply for initialization of the frameset, the first time
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if ($_REQUEST['whichButton'] != "Cancel" )
		{
			$act_gl = $_REQUEST['whichAction'];
			if ($act_gl == "NEWFOLDERFORM")
			{
			 	AddFolderForm();
			}
			if ($act_gl == "EDITFOLDERFORM")
			{
				EditFolderForm();
			}
			if ($act_gl == "COPYFOLDERFORM")
			{
				CopyFolderForm();
			}
			if ($act_gl == "MOVEFOLDERFORM")
			{
				MoveFolderForm();
			}
			if ($act_gl == "NEWLINKFORM" )
			{
				AddLinkForm();
			}
			if ($act_gl == "EDITLINKFORM")
			{
				EditLinkForm();
			}
			if ($act_gl == "MOVELINKFORM" )
			{
				MoveLinkForm();
			}
			if ($act_gl == "NEWFOLDER" )
			{
				DBFolderUpdate();
			}
			if ($act_gl == "EDITFOLDER" )
			{
				DBFolderUpdate();
			}
			if ($act_gl == "MOVEFOLDER" )
			{
				MoveFolder();
			}
			if ($act_gl == "COPYFOLDER" )
			{
				CopyFolder();
			}
			if ($act_gl == "DELFOLDER" )
			{
				DeleteFolder();
			}
			if ($act_gl == "NEWLINK" )
			{
				DBLinkUpdate();
			}
			if ($act_gl == "EDITLINK" )
			{
				DBLinkUpdate();
			}
			if ($act_gl == "MOVELINK" )
			{
				MoveLink();
			}
			if ($act_gl == "DELLINK")
			{
				DeleteLink();
			}
			if ($act_gl == "SEARCH")
			{
				Search();
			}
			if ($act_gl == "CHANGEUSERFORM")
			{
				ChangeUserForm();
			}
			if ($act_gl == "CHANGEUSER")
			{
				ChangeUser();
			}
		}
		else
		{
			ShowLinks ($_REQUEST['user'], $_REQUEST['parent']);
		}
	}
	else
	{
		//if (Request.QueryString("p") = "" )
		if ($_REQUEST['p'] == "" )
		{
			ShowLinks ($userId, -10) ;
			//ShowLinks will have to find out the id of the root folder
        }
		else
		{
			ShowLinks ($userId, $_REQUEST['p']);
		}
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