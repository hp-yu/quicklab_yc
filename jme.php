<?php
include_once('include/includes.php');
?>
<?php
  do_html_header('Chemical structure editor-Quicklab');
  do_header();
  //do_leftnav();
?>
<SCRIPT LANGUAGE="JavaScript">

function getSmiles() {
  var drawing = document.JME.smiles();
  document.editor.smi.value = drawing;
}

function getMolFile() {
  var mol = document.JME.molFile();
  document.editor.mol_output.value = mol;
}

function processJme() {
  document.JME.readMolecule(document.editor.jme_output.value);
}

function useMol() {
  document.JME.readMolFile(document.editor.mol_output.value);
}
</SCRIPT>
<script> 
  function check_ss() {
    var smiles = document.JME.smiles();
    var jme = document.JME.jmeFile(); 
    var mol = document.JME.molFile();
    
    if (smiles.length < 1) {
      alert("No molecule!");
    }
    else {
      document.editor.smiles.value = smiles;
      document.editor.jme.value = jme;
      document.editor.mol.value = mol;
      var info = document.referrer;
      info += " - " + navigator.appName + " - " + navigator.appVersion;
      info += " " + screen.width + "x" + screen.height;
      document.editor.rinfo.value = info;
      document.editor.submit();
    }
  }
</script>
<form action="" method="POST" name="editor" target="_self"/> 
<table class="standard" width="100%">
	<tr>
		<td align="center" colspan="2"><h2>Chemical structure editor</h2></td>
	</tr>
<?php
if (isset($_REQUEST['chem_id'])&&$_REQUEST['chem_id']!="") {
?>
	<tr>
		<td colspan="2">
		Chemical id: <?php echo $_REQUEST['chem_id'];?>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td>
    <applet name="JME" code="JME.class" archive="./include/JME.jar" width="400" height="400">
    <?php
    if (isset($jme)&&$jme!='') {
    	echo "<param name=\"jme\" value=\"$jme\">";
    }
    elseif (isset($_REQUEST['chem_id'])&&$_REQUEST['chem_id']!='') {
    	$db_conn=db_connect();
    	$query="SELECT * FROM chem_structures WHERE chem_id LIKE '{$_REQUEST['chem_id']}'";
    	$rs_str=$db_conn->query($query);
    	$match_str=$rs_str->fetch_assoc();
    	$mol=ereg_replace(chr(10),'|',$match_str['structure']);
    	if ($mol!='') {
    		echo "<param name=\"mol\" value=\"$mol\">";
    	}
    }
    ?>
		<param name=\"options\" value=\"xbutton, query, hydrogens\">
		<param name=\"options\" value=\"xbutton, hydrogens\">
		You have to enable Java in your browser.
		</applet>
		<input type="hidden" name="smiles">
    <input type="hidden" name="jme">
    <input type="hidden" name="mol">
    <input type="hidden" name="rinfo">
		</td>
		<td>
		<CENTER>
<b>smiles</b><BR>
<INPUT TYPE="text" NAME="smi" SIZE="48"><BR>
<P>
<b>mol file</b><BR>
<TEXTAREA NAME="mol_output" ROWS=5 COLS=48></TEXTAREA>
<P>
<INPUT TYPE="button" VALUE="Get SMILES" onClick="getSmiles()">
&nbsp; 
<INPUT TYPE="button" VALUE="Get mol file" onClick="getMolFile()">
&nbsp;
<INPUT TYPE="button" VALUE="Use mol file" onClick="useMol()">
<P>
<INPUT TYPE="button" VALUE="Clear Editor" onClick="document.JME.reset()">
&nbsp;
<INPUT TYPE="reset" VALUE="Clear Fields"><BR>
<P>
<small>
<a href="http://www.molinspiration.com/jme/" target=\"blank\">JME applet</a> courtesy of Peter Ertl, Novartis
</small>
</CENTER>
		</td>
	</tr>
		<?php
		if (isset($_REQUEST['chem_id'])&&$_REQUEST['chem_id']!="") {
		?>
			<tr>
				<td>
					<input type="button" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"   onClick="check_ss()">
				</td>
			</tr>
		<?php
		}
		?>
</table>
</form>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
