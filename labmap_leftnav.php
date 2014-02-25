<?php 
include('./include/includes.php');
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="CSS/general.css" rel="stylesheet" type="text/css" />
<link href="CSS/jquery.treeview.css" rel="stylesheet" type="text/css" />
<BASE target='_self'>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
<!--<script src="include/jquery/jquery.cookie.js" type="text/javascript"></script>-->
<script src="include/jquery/jquery.treeview.js" type="text/javascript"></script>
<script src="include/jquery/jquery.treeview.edit.js" type="text/javascript"></script>
<script src="include/jquery/jquery.treeview.async.js" type="text/javascript"></script>
<script type="text/javascript">
function initTrees() {
	$("#black").treeview({
		url: "labmap_leftnav_source.php?ts="+new Date().getTime()
	})
}
$(document).ready(function(){
	initTrees();
	$("#refresh").click(function(rand) {
		$("#black").empty();
		initTrees();
	});
});
</script>
</head>

<body topmargin=16 marginheight=16 bgcolor=white>
<div id="main">

<ul id="black">
</ul>
<button id="refresh">Refresh tree</button>
</div>




</body>
</html>
