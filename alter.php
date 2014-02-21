<?php
include_once('include/includes.php');
?>
<?php
  do_html_header('Alter-Quicklab');
  do_header();
  if(!userPermission(1)) {
  	do_html_footer();
  	exit;
  }
  else {
  	processRequest();
  	do_html_footer();
  }
  
?>
 
<?php
function alterForm()
{
?>
  <form method='post' action=''>
    <table class='standard' width="100%" style="margin-top:3px">
   	 <tr><td colspan='2'><h3>Alter database</h3></td></tr>
   	 <tr><td colspan="2">SQL query:</td>
   	 <tr><td colspan="2"><textarea name='query' class="sequence" cols="100" rows="10"><?php echo stripslashes($_POST['query']) ?></textarea></td>
   	 </tr>
   	 <tr><td colspan="2" >
   	 <input type="submit" value="submit">
   	 <input type='hidden' name='action' value='alter'></td></tr>
   </table></form>
<?php
}
function alter()
{
  $db_conn=db_connect();
  $query=stripslashes($_POST['query']);
  $query=split(';',$query);
	  $num_query=count($query);
	  for($i=0;$i<$num_query-1;$i++){
        $result=$db_conn->query($query[$i]);							
  	    if (!$result) {
 	      echo "<table class='alert' width=\"100%\"><tr><td><h3>There was a database error when executing <pre>".$query[$i]."</pre></h3></td></tr></table>";
 	      exit;
 	    }
	  }
	  echo "<table class='alert' width=\"100%\"><tr><td><h3>Query successfully</h3></td></tr></table>";
}
?>
<?php
function processRequest()
{
	if($_REQUEST['type']=='') {
	  alterForm();	
	}
	if ($_POST['action']=='alter') {
		alter();
	}
}
?>