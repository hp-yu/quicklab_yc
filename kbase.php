<?php
include('./include/includes.php');
if (!check_auth_user()) {
  login();
}
$id=$_GET['id'];
$p=$_GET['p'];
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Knowledge base-Quicklab</title>
<script>
function submitShowForm(commitType) {
	window.basefrm.submitShowForm(commitType)
}
function refreshLeftFrame() {
	window.treeframe.location.reload()
}
</script>
</head>

<frameset rows="30,*" framespacing="0" frameborder="0">
<frame name="controlFrame" src="kbase_control.php" noresize frameborder="0" scrolling="No">
<frameset cols="20%,*" border="1" framespacing="1" frameborder="1">
<frame name="treeframe" src="kbase_leftnav.php" frameborder="1">
<?php
if (isset($id)&&$id!="") {
	echo "<frame name=\"basefrm\" src=\"kbase_content.php?action=LINKDETAILFORM&id=$id\" >";
}
elseif (isset($p)&&$p!="") {
	echo "<frame name=\"basefrm\" src=\"kbase_content.php?action=FORDERDETAILFORM&p=$p\" >";
}
else {
	echo "<frame name=\"basefrm\" src=\"kbase_content.php\" >";
}
?>
</frameset>
</frameset><noframes></noframes>


</html>
