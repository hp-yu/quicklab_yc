<?php
include('./include/includes.php');
check_login_status();
?>
<html>
<head>
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LabMapManager-Quicklab</title>

	
<script>
function submitShowForm(commitType) {
	window.basefrm.submitShowForm(commitType)
}
function refreshLeftFrame() {
	window.treeframe.location.reload()
}
</script>
</head>
<!--
(Please keep all copyright notices.)
This frameset document includes the Treeview script.
Script found in: http://www.treeview.net
Author: Marcelino Martins
-->

<FRAMESET rows=21,*  border="0" FRAMESPACING="0">
  <FRAME SRC="labmap_control.php" name="controlFrame" NORESIZE SCROLLING=NO frameborder=NO> 
  <FRAMESET cols="20%, *"  border="1" frameborder="1" FRAMESPACING="1" >
	<FRAME SRC="labmap_leftnav.php" name="treeframe">  
    <FRAME SRC="labmap_links.php" name="basefrm">
  </FRAMESET> 
</FRAMESET><noframes></noframes>

</html>