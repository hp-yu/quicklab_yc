<?php
include('../include/includes.php');
if (!check_auth_user())
{
  header('Location: '.'../login.php');
  exit;
}
?>
  <html>
  <head>
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
  <FRAME SRC="control.html" name="controlFrame" NORESIZE SCROLLING=NO frameborder=NO> 
  <FRAMESET cols="190, *"  border="2" frameborder="1" FRAMESPACING="1" >
	<FRAME SRC="bookmarksLeftFrame.php" name="treeframe">  
    <FRAME SRC="links.php" name="basefrm">
  </FRAMESET> 
</FRAMESET><noframes></noframes>

</html>