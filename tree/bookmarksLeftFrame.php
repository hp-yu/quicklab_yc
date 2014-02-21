<?php 
/*
<% 
option explicit 
%>
*/
include('../include/includes.php');
include('common_functions.php');

//Globals in common_functions: Conn, userId 


//(Please keep all copyright notices.)
//This frameset document includes the Treeview script
//Script found in: http://www.treeview.net
//Author: Marcelino Martins
?>
<?php

function  defineBookmarksFolder($user, $parentId, $parentObject, $rsHits,$db_conn)
{
    $query = "SELECT * FROM location WHERE pid=$parentId and IsBox = 0 ORDER BY name";
    $rs= $db_conn->query($query);
    $rs_num = $rs->num_rows;
    
	for ($i=0;$i< $rs_num;$i++)
	{
		$rsHits=$rs->fetch_assoc();
			$nodeId = $rsHits['id'];
			$gFldStr = "gFld('".FixUpItems($rsHits['name'])."', 'links.php?p=". $nodeId ."')";
			//can't move function FixUpItems
			if ($parentId == 0)
			{
				echo "foldersTree = " . $gFldStr  . "\n";
				echo "f = foldersTree" . "\n";
				//localRSBkmk = rsHits.Bookmark;
				defineBookmarksFolder ($user, $nodeId, "f", $rsHits,$db_conn);
				//rsHits.Bookmark = localRSBkmk;
			}
			else
			{
				echo $parentObject . "Sub" . " = insFld(" . $parentObject . ",".$gFldStr.")" ."\n";
				//localRSBkmk = rsHits.Bookmark;
				defineBookmarksFolder ($user, $nodeId, $parentObject . "Sub" , $rsHits,$db_conn);
				//rsHits.Bookmark = localRSBkmk;
			}
	}
}

/*
sub defineBookmarksTree(user)
	dim rsHits, queryString

	queryString = "SELECT nodeId, nodeName, parent FROM bookmarksTree WHERE ((nodeIsFolder=True) AND (user="&user&")) ORDER BY nodeName"
	Set rsHits = Server.CreateObject("ADODB.Recordset")
	rsHits.Open queryString , Conn, adOpenStatic 'to get record count
	defineBookmarksFolder user, -1, "", rsHits
	rsHits.close
end sub
*/
?>


<html>

<head>

<title>Bookmarks on the Web</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- if you want black backgound, remove this style block -->
<style>
   BODY {background: URL(SkyBgr.jpg); 
   background-repeat: no-repeat; 
   background-color = white;}
   TD {font-size: 10pt; 
       font-family: verdana,helvetica; 
	   text-decoration: none;
	   white-space:nowrap;}
   A  {text-decoration: none;
       color: black} 
   A:hover {
       text-decoration: underline;
       color: black;}

</style>

<!-- if you want black backgound, remove this line and the one marked XXXX and keep the style block below 

<style>
   BODY {background-color: black}
   TD {font-size: 10pt; 
       font-family: verdana,helvetica 
	   text-decoration: none;
	   white-space:nowrap;}
   A  {text-decoration: none;
       color: white}
   A:hover {
       text-decoration: underline;
       color: white;}
</style>

XXXX -->


<!-- NO CHANGES PAST THIS LINE -->


<!-- Code for browser detection -->
<script src="ua.js"></script>

<!-- Infrastructure code for the tree -->
<script src="ftiens4.js"></script>


<!-- Execution of the code that actually builds the specific tree.
     The variable foldersTree creates its structure with calls to
	 gFld, insFld, and insDoc -->

<script>
STARTALLOPEN=0
USETEXTLINKS=1

<?php 
  $db_conn = db_connect();
  defineBookmarksFolder ($user, 0, "", $rsHits,$db_conn);
?>

/*
<%
	Dim errorVal
	errorVal = authorizedLogin()
	if errorVal="" then
		defineBookmarksTree userId
	end if
%>
*/
</script>


</head>

<body topmargin=16 marginheight=16 bgcolor=white>

<!-- 
    Removing this link will make the tree stop from working. Check instructions here:
    http://www.treeview.net/treemenu/faq.asp#toplink

    Favorites Manager does not include the registered version of Treeview for two reasons:
	  - Some Favorites Manager customers don't mind the link and prefer to pay only for the
        ASP code and get the JavaScript code for free.
	  - Other customers are buying Favorites Manager after they already registered the 
        Treeview and don't want to pay for the JavaScript code again.
-->
<div style="position:absolute; top:0; left:0; "><table border=0><tr><td><font size=-2><a style="font-size:7pt;text-decoration:none;color:#5a5a5a" href="http://www.treemenu.net/" target=_top>Tree Menu Help</a></font></td></table></div>

<!-- Build the browser's objects and display default view of the tree. -->
        
<script>
initializeDocument()
/*
<%
	if errorVal="" then
		response.write "initializeDocument()"
	end if
%>
*/
</script>

<noscript>
These <i>Bookmarks</i> pages require the use of JavaScript. Please enable JavaScript in your browser.
</noscript>

<%
if errorVal="" then
	Conn.Close
else
	response.write errorVal
end if
%>
</html>
