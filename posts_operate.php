<?php
include('include/includes.php');
include("fckeditor/fckeditor.php") ;
if (!check_auth_user()) {
  header('Location: '.'login.php');
  exit;
}
?>
<?php
  do_html_header('Posts-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(3)) {
   echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_rightbar();
   do_footer();
   do_html_footer();
   exit;
  }
?>
<?php
	$action=$_REQUEST['action'];
	switch ($action) {
		case "add":add();break;
		case "edit":edit();break;
		case "add_comment":add_comment();break;
		case "edit_comment":edit_comment();break;
		default:break;
	}
	$type=$_REQUEST['type'];
	switch ($type) {
		case "add_form":add_form();break;
		case "edit_form":edit_form();break;
		case "detail":detail();break;
		case "add_comment_form":add_comment_form();break;
		case "edit_comment_form":edit_comment_form();break;
		default:break;
	}

function add_form() {
	if(!userPermission(3)) {
  	alert();
  }
	js_selectall();
	?>
<script>
function submitAddAtch () {
	document.add_form.atch_count.value = "<?php echo $_REQUEST['atch_count']+1?>";
	document.add_form.submit();
}
function submitPost () {
	if (document.add_form.name.value=="") {
		alert("Enter title please.")
		return
	}
	document.add_form.action.value = "add";
	document.add_form.submit();
}
</script>
<div id="contents">
<table width="100%" class="standard" style="margin-top:3pt">
<form name='add_form' id="add_form" method="POST" target="_self" enctype="multipart/form-data">
<tr><td><div align='center'><h2>Post add</h2></div></td><tr>
<tr><td>Title: <input type="text" name="name" size="40" value="<?php  echo $_REQUEST['name'] ?>"/></td></tr>
<tr><td>
	<?php
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value		= stripslashes( $_REQUEST['content'] );
	$oFCKeditor->Config['EnterMode'] = 'br';
	//$oFCKeditor->Height = '500';
	$oFCKeditor->ToolbarSet= "Basic";
	$oFCKeditor->Create() ;
	?>
</td></tr>
<tr><td>Attachment:  <a href='#' onclick='javascipt:submitAddAtch()'><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td></tr>
	<?php
	for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
		echo "<tr><td><input type=\"file\" name=\"attachment_$i\" /></td></tr>";
	}
	?>
<input type="hidden" name="atch_count" value="<?php echo $_REQUEST['atch_count'];?>"/>
<tr><td>
<input type="button" value="Submit" onclick="javascipt:submitPost()"/>
</td></tr>
<tr><td>Send mail?</td></tr>
<tr><td>
<table width="100%" class="standard">
<tr><td>
<input type="checkbox" name='clickall' onclick="selectall(this.checked,form)" />&nbsp;Select all
</td></tr>
<tr><td width="100%" style="word-break: break-all">
	<?php
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
<input type="checkbox" onclick=changechecked(this,form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
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
<input type="hidden" name="action" value="">
</form>
</table>
	<?php
}

function edit_form () {
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT * FROM posts WHERE id='{$_REQUEST['id']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	if(!userPermission(0,$match['created_by'])) {
  	alert();
  }
	?>
<script>
function submitAddAtch () {
	document.edit_form.atch_count.value = "<?php echo $_REQUEST['atch_count']+1?>";
	document.edit_form.submit();
}
function submitPost() {
	if (document.edit_form.name.value=="") {
		alert("Enter title please.")
		return
	}
	document.edit_form.action.value = "edit";
	document.edit_form.submit();
}
</script>
<div id="contents">
<table width="100%" class="standard" style="margin-top:3pt">
<form name='edit_form' id="edit_form" method="POST" target="_self" enctype="multipart/form-data">
<tr><td><div align='center'><h2>Post edit</h2></div></td></tr>
<tr><td>Title: <input type="text" name="name" size="40" value="<?php echo stripslashes(htmlspecialchars( $match['name']))?>"/></td></tr>
<tr><td>
	<?php
	$oFCKeditor = new FCKeditor('content') ;
	$oFCKeditor->BasePath	= './fckeditor/';
	$oFCKeditor->Value		=  stripslashes($match['content']) ;
	$oFCKeditor->Config['EnterMode'] = 'br';
	//$oFCKeditor->Height = '500';
	$oFCKeditor->ToolbarSet= "Basic";
	$oFCKeditor->Create() ;
	?>
</td></tr>
<tr><td>Attachment:  <a href='#' onclick='javascipt:submitAddAtch()'><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td></tr>
	<?php
	for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
		echo "<tr><td><input type=\"file\" name=\"attachment_$i\" /></td></tr>";
	}
	?>
<input type="hidden" name="atch_count" value="<?php echo $_REQUEST['atch_count'];?>"/>
<tr><td><input type="button" value="Submit" onclick="javascipt:submitPost()"/></td></tr>
<tr><td>Send mail?</td></tr>
<tr><td>
<table width="100%" class="standard">
<tr><td>
<input type="checkbox" name='clickall' onclick="selectall(this.checked,form)" />&nbsp;Select all
</td></tr>
<tr><td width="100%" style="word-break: break-all">
	<?php
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
<input type="checkbox" onclick=changechecked(this,form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
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
<input type="hidden" name="action" value="">
</form>
</table>
	<?php
}

function edit_comment_form () {
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT a.post_id,a.created_by, a.content, b.name FROM post_comments a,posts b WHERE a.post_id=b.id and a.id='{$_REQUEST['id']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	if(!userPermission(0,$match['created_by'])) {
  	alert();
  }
	?>
<script>
function submitAddAtch () {
	document.edit_comment_form.atch_count.value = "<?php echo $_REQUEST['atch_count']+1?>";
	document.edit_comment_form.submit();
}
function submitPost() {
	document.edit_comment_form.action.value = "edit_comment";
	document.edit_comment_form.submit();
}
</script>
<div id="contents">
<table width="100%" class="standard" style="margin-top:3pt">
<form name='edit_comment_form' id="edit_comment_form" method="POST" target="_self" enctype="multipart/form-data"/>
<tr><td><div align='center'><h2>Post comment edit</h2></div></td>
<tr><td><a href="posts_operate.php?type=detail&id=<?php echo $match['post_id'];?>"/><?php echo stripslashes(htmlspecialchars( $match['name']))?></a></td></tr>
<tr><td>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
$oFCKeditor->Value		=  stripslashes($match['content']) ;
$oFCKeditor->Config['EnterMode'] = 'br';
//$oFCKeditor->Height = '500';
$oFCKeditor->ToolbarSet= "Basic";
$oFCKeditor->Create() ;
?>
</td></tr>
<tr><td>Attachment:  <a href='#' onclick='javascipt:submitAddAtch()'><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td></tr>
	<?php
	for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
		echo "<tr><td><input type=\"file\" name=\"attachment_$i\" /></td></tr>";
	}
	?>
<input type="hidden" name="atch_count" value="<?php echo $_REQUEST['atch_count'];?>"/>
<tr><td><input type="button" value="Submit" onclick="javascipt:submitPost()"/></td></tr>
<tr><td>Send mail?</td></tr>
<tr><td>
<table width="100%" class="standard">
<tr><td>
<input type="checkbox" name='clickall' onclick="selectall(this.checked,form)" />&nbsp;Select all
</td></tr>
<tr><td width="100%" style="word-break: break-all">
	<?php
	$db_conn=db_connect();
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
<input type="checkbox" onclick=changechecked(this,form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
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
<input type="hidden" name="action" value="">
</form>
</table>
	<?php
}

function add_comment_form () {
	if(!userPermission(3)) {
  	alert();
  }
	js_selectall();
	$db_conn=db_connect();
	$query="SELECT * FROM posts WHERE id='{$_REQUEST['id']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	?>
<script>
function submitAddAtch () {
	document.add_comment_form.atch_count.value = "<?php echo $_REQUEST['atch_count']+1?>";
	document.add_comment_form.submit();
}
function submitPost () {
	document.add_comment_form.action.value = "add_comment";
	document.add_comment_form.submit();
}
</script>
<div id="contents">
<table width="100%" class="standard" style="margin-top:3pt">
<form name='add_comment_form' id="add_comment_form" method="POST" target="_self" enctype="multipart/form-data"/>
<tr><td><div align='center'><h2>Post comment add</h2></div></td>
<tr><td><a href="posts_operate.php?type=detail&id=<?php echo $_REQUEST['id'];?>"/><?php echo stripslashes(htmlspecialchars( $match['name']))?></a></td></tr>
<tr><td>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
$oFCKeditor->Value		=  stripslashes( $_REQUEST['content'] );
$oFCKeditor->Config['EnterMode'] = 'br';
//$oFCKeditor->Height = '500';
$oFCKeditor->ToolbarSet= "Basic";
$oFCKeditor->Create() ;
?>
</td></tr>
<tr><td>Attachment:  <a href='#' onclick='javascipt:submitAddAtch()'><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td></tr>
	<?php
	for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
		echo "<tr><td><input type=\"file\" name=\"attachment_$i\" /></td></tr>";
	}
	?>
<input type="hidden" name="atch_count" value="<?php echo $_REQUEST['atch_count'];?>"/>
<tr><td>
<tr><td><input type="button" value="Submit" onclick="javascipt:submitPost()"/></td></tr>
<tr><td>Send mail?</td></tr>
<tr><td>
<table width="100%" class="standard">
<tr><td>
<input type="checkbox" name='clickall' onclick="selectall(this.checked,form)" />&nbsp;Select all
</td></tr>
<tr><td width="100%" style="word-break: break-all">
	<?php
	$query="SELECT id, name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$n++;
		?>
<input type="checkbox" onclick=changechecked(this,form)  name="selectedItem[]" value="<?php echo $match['id']?>" />&nbsp;
		<?php
		echo $match['name'];
		if ($n%10==0) {echo "<br>";}
	}
	?>
</td></tr>
<input type="hidden" name="action" value="">
</form>
</table>
	<?php
}

function detail() {
	?>
	<div style="border:1px solid #006633;margin-top:3px;">
	<?php
	$id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM posts WHERE id='$id'";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  $count=$match['count']+1;
  $query="update `posts` set `count`='$count' where `id`='$id'";
  $db_conn->query($query);
  $created_by=get_name_from_id('people',$match['created_by']);
  echo "<div align='center'><h2>Post detail</h2></div>";
  echo "<div style='float:left;border-bottom:1px solid #006633;border-top:1px solid #006633; margin:3px'/>";
  echo "<table width='100%'><tr><td align='left'><b>".$match['name']."</b>&nbsp;&nbsp;<a href='".$_SESSION['url_1']."'><img src='./assets/image/general/back.gif' alt='Back'  border='0'/></a></td>";
  echo "<td align='right'>";
  if (userPermission(0,$match['created_by'])) {
  	echo "<a href='posts_operate.php?type=edit_form&id=$id' target='_self' ><img src='./assets/image/general/edit.gif' title='Edit' border='0'/></a>&nbsp;&nbsp;";
  }
  if (userPermission(3)) {
		echo "<a href='posts_operate.php?id=$id&type=add_comment_form' target='_self'><img src='./assets/image/general/comments.gif' title='Add comment' border='0'/></a>&nbsp;&nbsp;";
  }
  echo "</td></tr></table></div>";
  echo "<div style='float:left;border-bottom:1px solid #006633; margin:3px'/><table width='100%'><tr><td>".$created_by['name']."&nbsp;&nbsp;".$match['date_create']."</td></tr></table></div>";
  //content
  echo "<p>".$match['content']."</p>";
  //comment
	$query="select a.*, b.name from post_comments a,people b
	where a.post_id=$id
	and a.created_by=b.id
	order by a.id desc";
	$rs=$db_conn->query($query);
	if ($rs->num_rows>0) {
		echo "<div style='float:left;border-top:1px solid #006633; margin:3px'/><table width='100%'><tr><td><b>Comment:</b></td></tr></table></div>";
	}
	while ($match=$rs->fetch_assoc()) {
		echo "<div style='float:left;border-bottom:1px solid #006633;border-top:1px solid #006633; margin:3px'/>";
		echo "<table width='100%'><tr><td align='left'>".$match['name']."&nbsp;&nbsp;".$match['date_create']."</td>";
		if (userPermission(0,$match['created_by'])) {
			echo "<td align='right'><a href='posts_operate.php?id={$match['id']}&type=edit_comment_form' target=\"_self\"><img src='./assets/image/general/edit.gif' title='Edit comment' border='0'/></a>&nbsp;&nbsp;</td></tr></table></div>";
		}
		echo "<p>".$match['content']."</p>";
	}
}

function add() {
	try {
		if (!filled_out(array($_REQUEST['name']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$db_conn=db_connect();
		$name=$_REQUEST['name'];
		$content=stripslashes($_REQUEST['content']);
		$date = date('Y-m-d G:i:s');
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$people_id = $match['people_id'];
		$query="SELECT * FROM people WHERE id=$people_id";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="INSERT INTO posts (`name`,`content`,`date_create`,`created_by`) VALUES ('$name','$content','$date','$people_id')";
		$result=$db_conn->query($query);
		$post_id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		//send mail
		if (count($_REQUEST['selectedItem'])>0) {
			$mail= new Mailer();
			$mail->basicSetting();
			$subject="Post from Quicklab: ".$name.", created by ".$created_by['name'];
			$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
			$instruction="<p>Do not reply this mail directly, Go to <a href='http://".IP_ADDRESS."/quicklab/posts_operate.php?type=detail&id=$post_id' target='_blank'>Quicklab-Posts</a></p>";
			$title="<p>".$name."</br>created by ".$created_by['name']."&nbsp;&nbsp;".$date."</p>";
			$content="<p>".$content."</p>";
			$message=$css.$instruction.$title.$content;
			$mail->Subject =$subject;
			$mail->MsgHTML($message);
			//attachment
			$n=0;
			for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
				if (is_uploaded_file($_FILES['attachment_'.$i]['tmp_name'])) {
					$n++;
					${'attachment_'.$n} = $_FILES['attachment_'.$i]['tmp_name'];
					${'attachment_name_'.$n} = $_FILES['attachment_'.$i]['name'];
				}
			}
			for ($m=1;$m<=$n;$m++) {
				$mail->AddAttachment(${'attachment_'.$m}, ${'attachment_name_'.$m});
			}
			//address
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
		header('Location:'.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function add_comment() {
	try {
		if (!filled_out(array($_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$db_conn=db_connect();
		$post_id=$_REQUEST['id'];
		$content=stripslashes($_REQUEST['content']);
		$date = date('Y-m-d G:i:s');
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$people_id = $match['people_id'];
		$created_by = get_record_from_id('people',$people_id);
		$query="INSERT INTO post_comments (`content`,`post_id`,`date_create`,`created_by`) VALUES ('$content','$post_id','$date','$people_id')";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		//send mail
		$post=get_name_from_id("posts",$post_id);
		if (count($_REQUEST['selectedItem'])>0) {
			$mail= new Mailer();
			$mail->basicSetting();
			$subject="Post comment from Quicklab: ".$post['name'].", created by ".$created_by['name'];
			$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
			$instruction="<p>Do not reply this mail directly, Go to <a href='http://".IP_ADDRESS."/quicklab/posts_operate.php?type=detail&id=$post_id' target='_blank'>Quicklab-Posts</a></p>";
			$title="<p>".$post['name']."</br>comment created by ".$created_by['name']."&nbsp;&nbsp;".$date."</p>";
			$content="<p>".$content."</p>";
			$message=$css.$instruction.$title.$content;
			$mail->Subject =$subject;
			$mail->MsgHTML($message);
			//attachment
			$n=0;
			for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
				if (is_uploaded_file($_FILES['attachment_'.$i]['tmp_name'])) {
					$n++;
					${'attachment_'.$n} = $_FILES['attachment_'.$i]['tmp_name'];
					${'attachment_name_'.$n} = $_FILES['attachment_'.$i]['name'];
				}
			}
			for ($m=1;$m<=$n;$m++) {
				$mail->AddAttachment(${'attachment_'.$m}, ${'attachment_name_'.$m});
			}
			//address
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
		header('Location:posts_operate.php?type=detail&id='.$post_id);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function edit() {
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$db_conn=db_connect();
		$id=$_REQUEST['id'];
		$name=$_REQUEST['name'];
		$content=stripslashes($_REQUEST['content']);
		$date = date('Y-m-d G:i:s');
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$people_id = $match['people_id'];
		$updated_by=get_record_from_id('people',$people_id);
		$query="UPDATE posts SET
		`name`='$name',
		`content`='$content',
		`date_update`='$date',
		`updated_by`='$people_id'
		WHERE id='$id'";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		//send mail
		if (count($_REQUEST['selectedItem'])>0) {
			$mail= new Mailer();
			$mail->basicSetting();
			$subject="Post from Quicklab: ".$name.", updated by ".$updated_by['name'];
			$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
			$instruction="<p>Do not reply this mail directly, Go to <a href='http://".IP_ADDRESS."/quicklab/posts_operate.php?type=detail&id=$id' target='_blank'>Quicklab-Posts</a></p>";
			$title="<p>".$name."</br>updated by ".$updated_by['name']."&nbsp;&nbsp;".$date."</p>";
			$content="<p>".$content."</p>";
			$message=$css.$instruction.$title.$content;
			$mail->Subject =$subject;
			$mail->MsgHTML($message);
			//attachment
			$n=0;
			for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
				if (is_uploaded_file($_FILES['attachment_'.$i]['tmp_name'])) {
					$n++;
					${'attachment_'.$n} = $_FILES['attachment_'.$i]['tmp_name'];
					${'attachment_name_'.$n} = $_FILES['attachment_'.$i]['name'];
				}
			}
			for ($m=1;$m<=$n;$m++) {
				$mail->AddAttachment(${'attachment_'.$m}, ${'attachment_name_'.$m});
			}
			//address
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
		header('Location:posts_operate.php?type=detail&id='.$id);
	}
	catch (Exception $e){
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function edit_comment() {
	try {
		if (!filled_out(array($_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$db_conn=db_connect();
		$id=$_REQUEST['id'];
		$content=stripslashes($_REQUEST['content']);
		$date = date('Y-m-d G:i:s');
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$people_id = $match['people_id'];
		$updated_by = get_name_from_id("people",$people_id);
		$query="UPDATE post_comments SET
		`content`='$content',
		`date_update`='$date',
		`updated_by`='$people_id'
		WHERE id='$id'";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		$query="SELECT post_id FROM post_comments WHERE id=$id";
		$rs=$db_conn->query($query);
		$match=$rs->fetch_assoc();
		$post_id=$match['post_id'];
		//send mail
		$post=get_name_from_id("posts",$post_id);
		if (count($_REQUEST['selectedItem'])>0) {
			$mail= new Mailer();
			$mail->basicSetting();
			$subject="Post comment from Quicklab: ".$post['name'].", updated by ".$updated_by['name'];
			$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
			$instruction="<p>Do not reply this mail directly, Go to <a href='http://".IP_ADDRESS."/quicklab/posts_operate.php?type=detail&id=$post_id' target='_blank'>Quicklab-Posts</a></p>";
			$title="<p>".$post['name']."</br>comment updated by ".$updated_by['name']."&nbsp;&nbsp;".$date."</p>";
			$content="<p>".$content."</p>";
			$message=$css.$instruction.$title.$content;
			$mail->Subject =$subject;
			$mail->MsgHTML($message);
			//attachment
			$n=0;
			for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
				if (is_uploaded_file($_FILES['attachment_'.$i]['tmp_name'])) {
					$n++;
					${'attachment_'.$n} = $_FILES['attachment_'.$i]['tmp_name'];
					${'attachment_name_'.$n} = $_FILES['attachment_'.$i]['name'];
				}
			}
			for ($m=1;$m<=$n;$m++) {
				$mail->AddAttachment(${'attachment_'.$m}, ${'attachment_name_'.$m});
			}
			//address
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
		header('Location:posts_operate.php?type=detail&id='.$post_id);
	}
	catch (Exception $e){
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

?>
<?php
//do_rightbar();
//do_footer();
//do_html_footer();
?>