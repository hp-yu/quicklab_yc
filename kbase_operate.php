<?php
include('include/includes.php');
include("fckeditor/fckeditor.php") ;
if (!check_auth_user()) {
  header('Location: login.php');
  exit;
}
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Knowledge base-Quicklab</title>
  <link href="CSS/general.css" rel="stylesheet" type="text/css" />
</head>

<?php
function EditForm() {
  $id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  if(!userPermission('2',$match['created_by'])) {
  	alert();
  }
  ?>
  <p>
  <font size=+2 color='#3A5AF4'>Edit Article:</font>&nbsp;&nbsp;
  <a href='kbase_operate.php?id=<?php echo $id?>&type=detail' target="_self"><img src='./assets/image/general/back.gif' title='Back' border='0'/></a>
  </p>
  <form name='edit' method='post' action='' enctype='multipart/form-data'>
  <p>
  <?php echo getPath($match['cat_id']);?>
  <br>Article name:&nbsp;&nbsp;<input name="name" type="text" size="60" value="<?php echo $match['name'];?>"/>
  <br>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
$oFCKeditor->Value		= stripslashes( $match['content'] );
$oFCKeditor->Config['EnterMode'] = 'br';
$oFCKeditor->Height = '500';
$oFCKeditor->Width = "100%";
$oFCKeditor->ToolbarSet= "Quicklab";
$oFCKeditor->Create() ;
?>
	</p>
  <input type="hidden" name="action" value="edit">
	</from>
  <?php
}

function EditCommentForm() {
  $id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase_comments WHERE id=$id";
  $rs=$db_conn->query($query);
  $comment=$rs->fetch_assoc();
  $article_id=$comment['article_id'];
  if(!userPermission(0,$comment['created_by'])) {
  	alert();
  }
  $query="SELECT * FROM kbase WHERE id='$article_id'";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
  ?>
  <p>
  <font size=+2 color='#3A5AF4'>Edit comment:</font>&nbsp;&nbsp;
  <a href='kbase_operate.php?id=<?php echo $article_id?>&type=detail' target="_self"><img src='./assets/image/general/back.gif' title='Back' border='0'/></a>
  </p>
  <form name='edit' method='post' action='' enctype='multipart/form-data'>
  <p>
  <?php echo getPath($article['cat_id']);?>
  <br>Article name: <?php echo $article['name'];?>
  <br>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
$oFCKeditor->Value		= stripslashes( $comment['content'] );
$oFCKeditor->Config['EnterMode'] = 'br';
$oFCKeditor->Height = '500';
$oFCKeditor->Width = "100%";
$oFCKeditor->ToolbarSet= "Quicklab";
$oFCKeditor->Create() ;
?>
	</p>
  <input type="hidden" name="action" value="edit_comment">
	</from>
  <?php
}

function AddCommentForm() {
  if(!userPermission(3)){
  	alert();
  }
  $id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  ?>
  <p>
  <font size=+2 color='#3A5AF4'>Add comment:</font>&nbsp;&nbsp;
  <a href='kbase_operate.php?id=<?php echo $id?>&type=detail' target="_self"><img src='./assets/image/general/back.gif' title='Back' border='0'/></a>
  </p>
  <form name='edit' method='post' action='' enctype='multipart/form-data'>
  <p>
  <?php echo getPath($match['cat_id']);?>
  <br>Article name: <?php echo $match['name'];?>
  <br>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
//$oFCKeditor->Value		= stripslashes( $match['content'] );
$oFCKeditor->Config['EnterMode'] = 'br';
$oFCKeditor->Height = '500';
$oFCKeditor->Width = "100%";
$oFCKeditor->ToolbarSet= "Quicklab";
$oFCKeditor->Create() ;
?>
	</p>
  <input type="hidden" name="action" value="add_comment">
	</from>
  <?php
}

function getPath($folderId) {
	//dim rsHits, queryString, auxStr, parentId
	$auxStr="";
	$db_conn = db_connect();
	while ($folderId != 0 ) {
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

function Detail() {
  if(!userPermission(4)) {
  	alert();
  }
  $id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM kbase WHERE id=$id";
  $rs=$db_conn->query($query);
  $article=$rs->fetch_assoc();
  $views=$article['views']+1;
  $query="update `kbase` set `views`='$views' where `id`='$id'";
  $db_conn->query($query);
  ?>
  <p>
  <font size=+2 color='#3A5AF4'>Article detail:</font>&nbsp;&nbsp;
  <?php
  if (userPermission(2,$article['created_by'])) {
  	echo "<a href='kbase_operate.php?id=$id&type=edit' target=\"_self\"><img src='./assets/image/general/edit.gif' title='Edit' border='0'/></a>&nbsp;&nbsp;";
  } else {
  	echo "<img src='./assets/image/general/edit-grey.gif' title='Edit' border='0'/>&nbsp;&nbsp;";
  }
  if (userPermission(3)) {
		echo "<a href='kbase_operate.php?id=$id&type=add_comment' target='_self'><img src='./assets/image/general/comments.gif' title='Add comment' border='0'/></a>";
  } else {
		echo "<img src='./assets/image/general/comments-grey.gif' title='Add comment' border='0'/>";
  }
  ?>
  </p>
  <p>
  <?php echo getPath($article['cat_id']);?>
  <br>
  Article name: <?php echo $article['name'];?>
  <br>
  <?php
  $query="select a.date_create,b.name from kbase a, people b
  where a.id=$id
  and a.created_by=b.id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  ?>
  Article created by: <?php echo $match['name']." ".$match['date_create'];?>
  <br>
  <?php
  $query="select a.date_update,b.name from kbase a, people b
  where a.id=$id
  and a.updated_by=b.id";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  ?>
  Article last updated by: <?php echo $match['name']." ".$match['date_update'];?>
  </p>
	<p><?php echo $article['content'];?></p>
	<p>Comments:</p>
	<p>
	<?php
	$query="select a.*, b.name from kbase_comments a,people b
	where a.article_id=$id
	and a.created_by=b.id
	order by a.id desc";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		echo "<p>";
		echo $match['name']." ".$match['date_create']."&nbsp;&nbsp;";
		if (userPermission(0,$match['created_by'])) {
			echo "<a href='kbase_operate.php?id={$match['id']}&type=edit_comment' target=\"_self\"><img src='./assets/image/general/edit.gif' title='Edit comment' border='0'/></a>";
		} else {
			echo "<img src='./assets/image/general/edit-grey.gif' title='Edit comment' border='0'/>";
		}
		echo "<br>";
		echo $match['content'];
		echo "</p>";
	}
	?>
	</p>
	<?php
}

function Edit() {
	$id=$_REQUEST['id'];
	$name=$_REQUEST['name'];
	$content=$_POST['content'];

	$db_conn = db_connect();
	$query="SELECT * FROM kbase WHERE id='$id'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	if ($name!=$match['name']||$content!=$match['content']) {
		try {
			if (!filled_out(array($_REQUEST['name']))) {
				throw new Exception('You have not filled the form out correctlly,  please try again.');
			}
			$date = date('Y-m-d G:i:s');
			$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
			$result = $db_conn->query($query);
			$match=$result->fetch_assoc();
			$updated_by = $match['people_id'];
			$query = "UPDATE kbase SET
		name='$name',
		content='$content',
		date_update='$date',
		updated_by='$updated_by'
		WHERE id='$id'";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
			}
			//update log
			$query="INSERT INTO kbase_update_log (article_id,date_update,updated_by) VALUES ('$id','$date','$updated_by')";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
			}
			header("Location:kbase_operate.php?type=detail&id=".$id);
		}
		catch (Exception $e) {
			echo '<table  width="100%" class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
		}
	}
}

function EditComment() {
	$id=$_REQUEST['id'];
	$content=$_POST['content'];
	echo $content;
	$db_conn = db_connect();
	$query="SELECT * FROM kbase_comments WHERE id='$id'";
	$result = $db_conn->query($query);
	$comment=$result->fetch_assoc();
	$article_id=$comment['article_id'];
	try {
		if (!filled_out(array($_POST['content']))) {
			throw new Exception('You have not filled the form out correctlly,  please try again.');
		}
		$date = date('Y-m-d G:i:s');
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$updated_by = $match['people_id'];
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
		header("Location:kbase_operate.php?type=detail&id=".$article_id);
	}
	catch (Exception $e) {
		echo '<table  width="100%" class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function AddComment() {
	$id=$_REQUEST['id'];
	$content=$_POST['content'];
	$db_conn = db_connect();
	try {
		if (!filled_out(array($_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,  please try again.');
		}
		$date = date('Y-m-d G:i:s');
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$updated_by = $match['people_id'];
		$query="INSERT INTO kbase_comments (article_id,content,date_create,created_by) VALUES ('$id','$content','$date','$updated_by')";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header("Location:kbase_operate.php?type=detail&id=".$id);
	}
	catch (Exception $e) {
		echo '<table  width="100%" class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function HiddenInputs($action)
{
	echo "<input type='hidden' name='action' value='$action' >";
	if($_REQUEST['destination'])
	{
		echo "<input type='hidden' name='destination' value='";
		echo $_REQUEST['destination']."'>";
	}
	else
	{
    	echo "<input type='hidden' name='destination' value='";
    	echo $_SERVER['HTTP_REFERER']."'>";
	}
}
function processRequest() {
	$action = $_POST['action'];
	if ($action == "add") Add();
	if ($action == "detail") Detail();
	if ($action == "edit") Edit();
	if ($action == "delete") Delete();
	if ($action == "add_comment") AddComment();
	if ($action == "edit_comment") EditComment();
	$type = $_REQUEST['type'];
	if ($type == "add") AddForm();
	if ($type == "detail") Detail();
	if ($type == "edit") EditForm();
	if ($type == "delete") DeleteForm();
	if ($type == "add_comment") AddCommentForm();
	if ($type == "edit_comment") EditCommentForm();
}

processRequest();
?>

