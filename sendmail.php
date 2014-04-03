<?php
include('include/includes.php');
include("fckeditor/fckeditor.php") ;
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];

  do_html_header('Send mail-Quicklab');
  do_header();
  //do_leftnav();

  if(!userPermission(3)) {
   echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_footer();
   do_html_footer();
   exit;
  }

	js_selectall();
?>
<div id="contents">
<table width="100%" class="standard" style="margin-top:3pt">
<form name='mail' method="POST" target="_top" enctype="multipart/form-data">
<tr>
<td><div align='center'><h2>Send mail</h2></div>
</td>
<tr><td>Subject: <input type="text" name="subject" size="40" value="<?php  echo $_REQUEST['subject'] ?>"/></td></tr>
<tr><td>
<?php
$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= './fckeditor/';
$oFCKeditor->Value = stripslashes( $_REQUEST['content'] );
//$oFCKeditor->Value		= stripslashes( $match['content'] );
$oFCKeditor->Config['EnterMode'] = 'br';
//$oFCKeditor->Height = '500';
$oFCKeditor->ToolbarSet= "Basic";

$oFCKeditor->Create() ;
?>
</td></tr>
<script>
function submitAddAtch () {
	document.mail.atch_count.value = "<?php echo $_REQUEST['atch_count']+1?>";
	document.mail.submit();
}
function sendMail () {
	if (document.mail.subject.value=="") {
		alert("Enter subject please.")
		return
	}
	var selectcount = 0;//check at least select one
	for(i=0;i<document.mail.elements.length;i++){
		if (document.mail.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any recipients.")
		return
	}
	document.mail.action.value = "add";
	document.mail.submit();
}
</script>
<tr><td>Attachment:  <a href='#' onclick='javascipt:submitAddAtch()'><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td></tr>
<?php
for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
	echo "<tr><td>";
	echo "<input type=\"file\" name=\"attachment_$i\" />";
	echo "</td></tr>";
}
?>
<input type="hidden" name="atch_count" value="<?php echo $_REQUEST['atch_count'];?>"/>
<tr><td><input type="button" value="Send mail" onclick="javascipt:sendMail()"/></td></tr>
<input type="hidden" name="action" value="">
<tr><td>Recipients:</td></tr>
<tr><td>
<table width="100%" class="standard">
<tr>
<td><input type="checkbox" name='clickall' onclick="selectall(this.checked,form)" />&nbsp;Select all</td>
</tr>
<tr>
<td width="100%" style="word-break: break-all">
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
</form>
</table>
<?php
$action=$_REQUEST['action'];
if ($action == "add" ) {
	try {
		if (!filled_out(array($_REQUEST['subject'],$_REQUEST['content']))) {
			throw new Exception('You have not filled the form out correctlly,- please try again.');
		}
		$db_conn=db_connect();
		$n=0;
		for ($i=0;$i<$_REQUEST['atch_count'];$i++) {
			if (is_uploaded_file($_FILES['attachment_'.$i]['tmp_name'])) {
				$n++;
				${'attachment_'.$n} = $_FILES['attachment_'.$i]['tmp_name'];
				${'attachment_name_'.$n} = $_FILES['attachment_'.$i]['name'];
			}
		}
		$date = date('Y-m-d G:i:s');
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by = $match['people_id'];
		$people=get_name_from_id('people',$created_by);
		$creator = $people['name'];
		$subject = $_REQUEST['subject'].", written by $creator from Quicklab";
		$content=$_REQUEST['content'];

		if (count($_REQUEST['selectedItem'])>0) {
			$mail= new Mailer();
			$mail->basicSetting();
			$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
			$message=$css.$content;
			$mail->Subject =$subject;
			$mail->MsgHTML($message);
			for ($m=1;$m<=$n;$m++) {
				$mail->AddAttachment(${'attachment_'.$m}, ${'attachment_name_'.$m});
			}
			$people=get_record_from_id('people',$created_by);
			$email=trim($people['email']);
			$mail->AddReplyTo($email,$creator);
			echo "<table><tr><td>";
			foreach ($_REQUEST['selectedItem'] as $key=>$value) {
				$people=get_record_from_id('people',$value);
				$email=trim($people['email']);
				if ($email!=''&&valid_email($email)) {
					$mail->AddAddress($email);
					if (!$mail->Send()) {
						echo "failed to send mail to {$people['name']} ($email)<br>";
					} else {
						echo "successfully send mail to {$people['name']} ($email)<br>";
					}
					$mail -> ClearAddresses();
				}
			}
			echo "<table><tr><td>";
		}
		//header('Location:'.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert" width = "100%"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

//do_rightbar();
//do_footer();
do_html_footer();
?>