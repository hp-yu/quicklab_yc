<?php
include('include/includes.php');
?>
<?php
  do_html_header('Clipboard-Quicklab');
  do_header();
  //do_leftnav();
?>
  <table width='80%' class='search'>
    <tr>
      <td align='center' valign='middle'><h2>My clipboard</h2></td>
    </tr>
  </table>
  <form action='' method='post' name='cart' target='_self' >
  <table width='80%' class="results" ><tr>
  <td class="results_header"><input type="checkbox" name="clickall" onclick=selectall(this.checked)></td>
  <td class="results_header">Module</td>
  <td class="results_header">Item</td></tr>
  <?php
  if(isset($_SESSION['clipboard']))
  foreach($_SESSION['clipboard'] as $key=>$value)
  {
  	echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this)  name='selectedItem[]' value=$key></td><td class='results'>";
  	$db_conn=db_connect();
  	$key=split("_",$key);
  	$module=get_name_from_id(modules,$key[0]);
  	$item=get_name_from_id($module['name'],$key[1]);
  	echo $module['name']."</td><td class='results'>";
	echo $item['name']."</td></tr>";
  }
  ?>
  </table></form>
<?php
do_footer();
do_html_footer();
?>