<?php
include('./include/includes.php');
?>
<?php
# configuration data for MolDB database=============================
$prefix        = "chem_";       # this allows to have different data 
                                # collections in one database
                                # e.g., "mb_" for Maybridge, "nci_" for NCI
$molstructable = "${prefix}molstruc";
$moldatatable  = "${prefix}moldata";
$molstattable  = "${prefix}molstat";
$molfgtable    = "${prefix}molfg";
$molfgbtable   = "${prefix}molfgb";
$molbfptable   = "${prefix}molbfp";
$fpdeftable    = "${prefix}fpdef";
$molhfptable   = "${prefix}molhfp";
$bitmapURLdir  = "";  # URL path based on document root
$digits        = 8;  # digits for png filenames (e.g., 00000001.png)
$subdirdigits  = 0;  # uses the first x digits of $digits (0 = no subdirectories)
//$sitename      = "MolDB4 demo"; # appears in title and headline
$CHECKMOL      = ".\\include\\checkmol";
$MATCHMOL      = ".\\include\\matchmol";

if (!isset($digits) || (is_numeric($digits) == false)) { $digits = 8; }
if (!isset($subdirdigits) || (is_numeric($subdirdigits) == false)) { $subdirdigits = 0; }
if ($subdirdigits < 0) { $subdirdigits = 0; }
if ($subdirdigits > ($digits - 1)) { $subdirdigits = $digits - 1; }
?>
<?php 
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('chemicals');?>
<?php
do_html_header('Structure search');
do_header();
do_leftnav();
?>

<?php
$exact  = "n";                # search options
$strict = "n";

$maxhits = 100;               // maximum number of hits we want to allow
$maxcand = 5000;               // maximum number of candidate structures we want to allow

$smiles  = $_POST['smiles'];
$jme     = $_POST['jme'];
$mol     = $_POST['mol'];
$rinfo   = $_POST['rinfo'];
$exact   = $_POST['exact'];
$strict  = $_POST['strict'];
$stereo  = $_POST['stereo'];
//$usebfp  = $_POST['bfp'];
//$usehfp  = $_POST['hfp'];
$usebfp  = 'y';
$usehfp  = 'y';
?>
<form action='' method='POST' name='search' target='_self'>
<table width='100%' class='search'>

<script> 
  function check_ss() {
    var smiles = document.JME.smiles();
    var jme = document.JME.jmeFile(); 
    var mol = document.JME.molFile();
    
    if (smiles.length < 1) {
      alert("No molecule!");
    }
    else {
      document.search.smiles.value = smiles;
      document.search.jme.value = jme;
      document.search.mol.value = mol;
      var info = document.referrer;
      info += " - " + navigator.appName + " - " + navigator.appVersion;
      info += " " + screen.width + "x" + screen.height;
      document.search.rinfo.value = info;
      document.search.submit();
    }
  }
</script>
	<tr>
    <td><h2>Structure search:</h2></td>
  </tr>
	<tr>
    <td>
    <applet name="JME" code="JME.class" archive="./include/JME.jar" width="480" height="360">
<?php
$db_conn=db_connect();
if (isset($jme)&&$jme!='') {
	echo "<param name=\"jme\" value=\"$jme\">";
}
elseif (isset($_REQUEST['chem_id'])&&$_REQUEST['chem_id']!='') {
	$query="SELECT * FROM chem_molstruc WHERE chem_id LIKE '{$_REQUEST['chem_id']}'";
	$rs_structure=$db_conn->query($query);
	$match_structure=$rs_structure->fetch();
	$structure=ereg_replace(chr(10),'|',$match_structure['struc']);
	if ($structure!='') {
		echo "<param name=\"mol\" value=\"$structure\">";
	}
}
?>
		<param name=\"jme\" value="<?php echo $jme?>">	
		<param name=\"options\" value=\"xbutton, query, hydrogens\">
		<param name=\"options\" value=\"xbutton, hydrogens\">
		You have to enable Java in your browser.
		</applet>
 		</td>
 		<td>
		<br />
		<small>
		special symbols (to be entered via X-button):<br />
		<b>A</b>: any atom except H<br />
		<b>Q</b>: any atom except H and C<br />
		<b>X</b>: any halogen atom<br />
		<b>H</b>: explicit hydrogen<br />
		<br />
		<a href=\"http://www.molinspiration.com/jme/\" target=\"blank\">JME applet</a>
		courtesy of Peter Ertl, Novartis
		</small>
		<small><a href=\"jmehints.html\">JME help</a></small><br />
		</td>
  </tr>
  <tr>
    <td colspan="2">
    <input type="radio" name="exact" value="n" checked>substructure search
    <input type="radio" name="exact" value="y">exact search<br />
    <input type="checkbox" name="strict" value="y">strict atom/bond type comparison<br />
    <input type="checkbox" name="stereo" value="y">check configuration (E/Z and R/S)<br />&nbsp;<br />
    <input type="button"     value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"   onClick="check_ss()">
    <input type="hidden" name="smiles">
    <input type="hidden" name="jme">
    <input type="hidden" name="mol">
    <input type="hidden" name="rinfo">
    </td>
  </tr>
</table>
</form>
<?php
echo "<table width=\"100%\">\n";

function filterThroughCmd($input, $commandLine) {
  $tmpfname = tempnam(realpath("C:/temp/"), "mdb");
  $tfhandle = fopen($tmpfname, "wb");
  
  $myinput = str_replace("\r","",$input);
  $myinput = str_replace("\\\$","\$",$myinput);
  $inputlines = explode("\n",$myinput);
  $newinput = implode("\r\n",$inputlines);
  fwrite($tfhandle, $newinput);
  
  fclose($tfhandle);
  $output = `type $tmpfname | $commandLine `;
  unlink($tmpfname);
  return $output;
}

function showHit($id) {
  global $bitmapURLdir;
  global $molstructable;
  global $moldatatable;
  global $digits;
  global $subdirdigits;
  
  $db_conn=db_connect();
  $query="SELECT name FROM chemicals WHERE id='$id'";
  $rs=$db_conn->query($query);
  $match=$rs->fetch_assoc();
  echo "<tr>\n<td bgcolor=\"#EEEEEE\">\n";
  print "<p><a href=\"chemicals_operate.php?type=detail&id=${id}\" target=\"blank\">{$match['name']}</a>\n";
  echo "</td>\n</tr>\n";
  
  // for faster display, we should have bitmap files (GIF or PNG) of the 2D structures
  // instead of invoking the JME applet:

  echo "<tr>\n<td>\n";
  
  if ((isset($bitmapURLdir)) && ($bitmapURLdir != "")) {
    //while (strlen($id) < $digits) { $id = "0" . $id; }
    $subdir = '';
    if ($subdirdigits > 0) { $subdir = substr($id,0,$subdirdigits) . '/'; }
    print "<img src=\"${bitmapURLdir}/${subdir}${id}.png\" alt=\"hit structure\">\n";
  } 
  else {  
    // if no bitmaps are available, we must invoking another instance of JME 
    // in "depict" mode for structure display of each hit
    $query="SELECT `struc` FROM $molstructable WHERE `chem_id` = '$id';";
    $result3 = $db_conn->query($query) 
    or die("Query failed!");    
    while ($line3 = $result3->fetch_assoc()) {
      $molstruc = $line3["struc"];
    }
  
    // JME needs MDL molfiles with the "|" character instead of linebreaks
    $jmehitmol = strtr($molstruc,"\n","|");
        
    echo "<applet code=\"JME.class\" archive=\"./include/JME.jar\" \n";
    echo "width=\"200\" height=\"200\">";
    echo "<param name=\"options\" value=\"depict,border\"> \n";
    echo "<param name=\"mol\" value=\"$jmehitmol\">\n";
    echo "</applet>\n";
  }
  echo "</td>\n</tr>\n";

}

$options = '';
if ($strict == 'y') {
  $options = $options . 'as';  // a for charges (checkmol v0.3p)
}
if ($exact == 'y') {
  $options = $options . 'x';
}
if ($stereo == 'y') {
  $options = $options . 'gG';
}

if (strlen($options) > 0) {
  $options = '-' . $options;
}
	

// remove CR if present (IE, Mozilla et al.) and add it again (for Opera)
$mol = str_replace("\r\n","\n",$mol);
$mol = str_replace("\n","\r\n",$mol);

//$safemol = escapeshellcmd($mol);
$safemol = $mol;

// A previous version of JME did not support explicit hydrogens, so we used the Helium
// symbol (He) to define an explicit H  ==> "He" had to be translated back into "H " prior
// to further processing by checkmol/matchmol (this is now obsolete).
//$safemol = str_replace("He","H ",$safemol);


if ($mol !='') { 
  $time_start = getmicrotime();  
  // first step: get the molecular statistics of the input structure
  // by piping it through the checkmol program, then do a search
  // in molstat and the fingerprint table(s) ==> this gives a list of candidates

  $chkresult = filterThroughCmd("$safemol", "$CHECKMOL -axH - ");
  
  // first part of output: molstat, second part: hashed fingerprints
  
  $myres = explode("\n", $chkresult);
  $chkresult1 = $myres[0];
  $chkresult2 = $myres[1];
  
  // strip trailing "newline"
  $chkresult1 = str_replace("\n","",$chkresult1);
  $len = strlen($chkresult1);
  // strip trailing semicolon
  if (substr($chkresult1,($len-1),1) == ";") {
    $chkresult1 = substr($chkresult1,0,($len-1));
  }  

  // determine number of atoms in query structure,
  // reject queries with less than 3 atoms ==>
  // $chkresult contains as the first 2 entries: "n_atoms:nn;n_bonds:nn;"
  $scpos = strpos($chkresult1,";");
  $na_str = substr($chkresult1,8,$scpos-8);
  $na_val = intval($na_str);
  
  if ( $na_val < 3 ) {
    echo "</table>\n<hr>\n";
    echo "Query structure must contain at least 3 atoms!<br />\n</body></html>\n";
    exit;
  }

  $chkresult2 = str_replace("\n","",$chkresult2);
  if (strpos($chkresult2,";") !== false) {
    $chkresult2 = substr($chkresult2,0,strpos($chkresult2,";"));
  }
  $hfp = explode(",",$chkresult2);
  $hfpqstr = "";
  $hfpnum = 0;
  $hfpfield = "";
  for ($i = 0; $i <= 15; $i++) {
    $hfpnum = $i + 1;
    if ($hfpnum < 10) { $hfpfield = "fp0" . $hfpnum; } else { $hfpfield = "fp" . $hfpnum; }
    if ($hfp[$i] > 0) {
      $hfpqstr = $hfpqstr . " AND (${molhfptable}.$hfpfield & $hfp[$i] = $hfp[$i])";
    }
  }
  //echo "$hfpqstr<br>\n";
  
  // now create the SQL query string from checkmol output
  $qstr = str_replace(";"," AND ",$chkresult1);

  // comparison operator for substructure search is ">=", 
  // for exact search it would be "=";
  // in the moment, we always use ">=" and just specify the exact number
  // of atoms and bonds if an exact search is required
  $op = ">=";
  $qstr = str_replace(":",$op,$qstr);
  if ($exact == "y") {
    $qstr = str_replace("n_atoms>=","n_atoms=",$qstr);
    $qstr = str_replace("n_bonds>=","n_bonds=",$qstr);
    $qstr = str_replace("n_rings>=","n_rings=",$qstr);
  }

  //$link = mysql_pconnect($hostname,"$user", "$password")
  //  or die("Could not connect to database server!");
  //mysql_select_db($database)
  //  or die("Could not select database!");    

  // get total number of structures in the database
  $n_qstr = "SELECT COUNT(chem_id) AS count FROM $molstructable;";
  $n_result = $db_conn->query($n_qstr)
      or die("Could not get number of entries!"); 
  while ($n_line = $n_result->fetch_assoc()) {
    $n_structures = $n_line["count"];
  } 

  $bfpusable = 0;
  if ($usebfp == "y") {
    //==================get fingerprint definition=================
    $fqstr = "SELECT COUNT(fp_id) AS count FROM $fpdeftable;";
    $fresult = $db_conn->query($fqstr)
        or die("Could not get fingerprint definition!"); 
    while ($fline = $fresult->fetch_assoc()) {
      $fp_count = $fline["count"];
    } 
    $fpdefqstr = "SELECT fp_id, fpdef FROM $fpdeftable;";
    $fpdefresult = $db_conn->query($fpdefqstr)
        or die("Could not get fingerprint definition!"); 
    $i = -1;
    while ($fpdefline = $fpdefresult->fetch_assoc()) {
      $i++;
      $fpdef[$i] = $fpdefline["fpdef"];
    } 
    if (($fp_count > 0) && ($usebfp == "y")) {
      if (strlen($options) > 0) {
        $fpoptions = $options;
      } else {
        $fpoptions = '-' . $options;
      }
      if (strpos($fpoptions,"s") === false) {
        $fpoptions = $fpoptions . "s";
      }
      $fpoptions1 = $fpoptions . "F";
      $fpoptions2 = str_replace("s","",$fpoptions1);
      $bfpexact  = 0;    
      for ($i = 0; $i < $fp_count; $i++) {
        $fpnum = $i + 1;
        while (strlen($fpnum) < 2) { $fpnum = "0" . $fpnum; }
        $fpsdf = $fpdef[$i];
        $fpsdf = str_replace("\$","\\\$",$fpsdf);
        $fpchunk = $safemol . "\n\\\$\\\$\\\$\\\$\n" . $fpsdf;
        $mmcmd = "$MATCHMOL $fpoptions1 -";
        $bfp[$i] = filterThroughCmd("$fpchunk ", "$mmcmd");
        $bfp[$i] = rtrim($bfp[$i]);
        $truematch = 1;
        $bfpnum = intval($bfp[$i]);
        if (!(1&$bfpnum)) {
          $mmcmd = "$MATCHMOL $fpoptions2 -";  // second choice
          $bfp[$i] = filterThroughCmd("$fpchunk ", "$mmcmd");
          $bfp[$i] = rtrim($bfp[$i]);
          $bfp2num = intval($bfp[$i]);
          if ($bfp2num > 0) {
            $truematch = 0;
            $bfpusable = 1;
          }
        } else { $bfpusable = 1; }
        $str = strval($bfp[$i]);
        $lastdigit = substr($str, -1);
        $num = intval($lastdigit);
        // check if $num is odd or even
        if ((1&$num)) {
          $num--;
          $lastdigit = strval($num);
          $bfp[$i] = substr($str,0,-1) . $lastdigit;
          if ($truematch == 1) {
            $bfpexact    = $bfp[$i];
            $bfpexactnum = $fpnum;
          }
        }
      }   // for ...
      if (($bfpexact > 0) && !($exact == "y") && !($strict == "y")) {
        $limit = $maxhits + 1;
        $fpqstr = "SELECT chem_id FROM $molbfptable WHERE (bfp$bfpexactnum & $bfpexact = $bfpexact) LIMIT $limit";
        //echo "$fpqstr <br />\n";
        $bfpresult = $db_conn->query($fpqstr)
            or die("Query failed! (3a)"); 
          $hits = 0;
          while ($bfpline = $bfpresult->fetch_assoc()) {
            $chem_id = $bfpline["chem_id"];
            $hits++;
            if ( $hits > $maxhits ) {
              echo "</table>\n<hr>\n";
              echo "<br />Too many hits (>$maxhits)! Aborting....<br />\n</body></html>\n";
              exit;
            }
            showHit($chem_id);
          } 
        echo "</table>\n<hr>\n";
        $time_end = getmicrotime();  
        $time = $time_end - $time_start;
        print "<p>number of hits: <b>$hits</b><br />\n";
        print "total number of structures in database: $n_structures <br />\n";
        printf("time used for query: %2.3f seconds </p>\n", $time);
        echo "\n</body></html>\n";
        exit;  
      }  // if ($bfpexact > 0)
    }    // if ($fp_count > 0)...
  }    // if ($usebfp == "y")

  $addtbl = ${molstattable} . '.n_';
  $qstr = str_replace("n_",$addtbl,$qstr);
  $cqstr = $qstr;
  $qhdr = "";
  $cqhdr = "";
  $qftr = " AND (${molstattable}.chem_id = ${molstructable}.chem_id) ";
  $cqftr = "";

  $qstr2 = $qstr;
  $qstr1 = $qstr;
  if ($bfpusable == 0) {
    if ($usehfp == 'y') {
      $qhdr = "SELECT ${molstattable}.chem_id, ${molstructable}.struc FROM $molstattable, $molstructable, $molhfptable WHERE ";
      $cqhdr = "SELECT COUNT(${molstattable}.chem_id) AS count FROM $molstattable, $molhfptable WHERE ";
      $qstr1 = $qstr1 . $hfpqstr;
      $qftr = $qftr . " AND (${molstattable}.chem_id = ${molhfptable}.chem_id) ";
      $cqftr = $cqftr . " AND (${molstattable}.chem_id = ${molhfptable}.chem_id) ";;
    } else {
      $qhdr = "SELECT ${molstattable}.chem_id, ${molstructable}.struc FROM $molstattable, $molstructable WHERE ";
      $cqhdr = "SELECT COUNT(${molstattable}.chem_id) AS count FROM $molstattable WHERE ";
    }
    $cqstr1 = $qstr1;
    //echo "<h2>could not use binary fingerprints...</h2>";   
    
    $cq1 = $cqhdr . $cqstr1 . $cqftr;
    $q1  = $qhdr . $qstr1 . $qftr;
    $cq2 = '';
    $q2  = '';
    
  } else {           // both hfp (1) and bfp (2) are available
    for ($i = 0; $i < $fp_count; $i++) {
      $bfpnum = $i + 1;
      while (strlen($bfpnum) < 2) { $bfpnum = '0' . $bfpnum; }
      $qstr2 = $qstr2 . " AND (${molbfptable}.bfp$bfpnum & $bfp[$i] = $bfp[$i])";
    }
    
    $qftr2 = " AND (${molstattable}.chem_id = ${molbfptable}.chem_id) AND (${molstattable}.chem_id = ${molstructable}.chem_id)";
    $qftr1 = " AND (${molstattable}.chem_id = ${molhfptable}.chem_id) AND (${molstattable}.chem_id = ${molstructable}.chem_id)";
    $cqftr2 = " AND (${molstattable}.chem_id = ${molbfptable}.chem_id) ";
    $cqftr1 = " AND (${molstattable}.chem_id = ${molhfptable}.chem_id) ";
    $qhdr2 = "SELECT ${molstattable}.chem_id, ${molstructable}.struc FROM $molstattable, $molstructable, $molbfptable WHERE "; 
    $qhdr1 = "SELECT ${molstattable}.chem_id, ${molstructable}.struc FROM $molstattable, $molstructable, $molhfptable WHERE ";
    $qstr1 = $qstr1 . $hfpqstr;
    $cqhdr2 = "SELECT COUNT(${molstattable}.chem_id) AS count FROM $molstattable, $molbfptable WHERE ";
    $cqhdr1 = "SELECT COUNT(${molstattable}.chem_id) AS count FROM $molstattable, $molhfptable WHERE ";
    
    $q1 = $qhdr1 . $qstr1 . $qftr1;
    $q2 = $qhdr2 . $qstr2 . $qftr2;
    $cq1 = $cqhdr1 . $qstr1 . $cqftr1;
    $cq2 = $cqhdr2 . $qstr2 . $cqftr2;
  }

  $cand_count1 = $n_structures;
  $cand_count2 = $n_structures;
  
  $cresult1 = $db_conn->query($cq1)    // hfp
      or die("Query failed! (2)"); 
    while ($cline = $cresult1->fetch_assoc()) {
      $cand_count1 = $cline["count"];
    } 

  if (strlen($cq2) > 1) {
    $cresult2 = $db_conn->query($cq2)    // bfp
      or die("Query failed! (3)"); 
    while ($cline = $cresult2->fetch_assoc()) {
      $cand_count2 = $cline["count"];
    } 
  }

  if ($cand_count1 < $cand_count2) {
    $qhdr = $qhdr1;
    $qstr = $q1;
    $qftr = $qftr1;
    $cand_count = $cand_count1;    
    $fplbl = 'h';
  } else {
    $qstr = $q2;
    $cand_count = $cand_count2;
    $fplbl = 'd';
  }
  
  $bs        = 10;                          // block size (number of structures per query SDF)
  $maxbmem   = 0;                           // for diagnostic purposes only
  $sqlbsmult = 10;                          // relates $bs to SQL block size (for LIMIT clause)
  $sqlbs     = $bs * $sqlbsmult;
  $mmcmd = "$MATCHMOL $options -";

  $offsetcount = 0;
  $total_cand  = 0;
  $hits        = 0;
  $n_cand      = 1;

  if ($cand_count > $maxcand ) {
    echo "</table>\n<hr>\n";
    echo "Too many candidate structures ($cand_count)!<br />\n"; 
    echo "Please enter a more specific query.<br />\n</body></html>\n";
    exit; 
  }

  //=============== begin outer loop

  while ($n_cand > 0) {
    $offset  = $offsetcount * $sqlbs;
    $qstrlim = $qstr . " LIMIT $offset, $sqlbs";
    $result = $db_conn->query($qstrlim)
      or die("Query failed! (2)");    
    $offsetcount ++;
    $n_cand  = $result->num_rows;     // number of candidate structures
    $bi      = 0;                           // counter within block
    $n       = 0;                           // number of candidates already processed
    $qstruct = $safemol;                    // query string in SDF format (entry #1 = needle)
    $total_cand = $total_cand + $n_cand;
   
    while ($line = $result->fetch_assoc()) {
      $chem_id = $line["chem_id"];
      $result2 = $db_conn->query("SELECT struc FROM $molstructable WHERE chem_id = '$chem_id'") or die("Query failed!");    
      while ($line2 = $result2->fetch_assoc()) {
        $haystack = $line2["struc"];
      }
        
      // "burst mode":
      // store candidate numbers (chem_id) in array $b
      // store query as $qstruct: consists of 1 needle + several haystack molecules (up to $bs)
      // store result of matchmol invocation in array $br
      $b[$bi] = $chem_id;
      $qstruct = $qstruct . "\n\\\$\\\$\\\$\\\$\n" . $haystack;    
      $bi ++;
      $n ++;
      if (($bi == $bs) || ($n == $n_cand)) {
        if (strlen($qstruct) > $maxbmem) { $maxbmem = strlen($qstruct); }  // for diagnostics only
        $matchresult = filterThroughCmd("$qstruct ", "$mmcmd");
        $br = explode("\n", $matchresult);
        for ($i = 0; $i < $bi; $i++) { 
          if (strstr($br[$i],":T") != FALSE) {
            $hits ++;
            // output of the hits, if they are not too many...
            if ( $hits > $maxhits ) {
              echo "<br />Too many hits (>$maxhits)! Aborting....<br />\n</body></html>\n";
              exit;
            }
            showHit($b[$i]);
          }
        }
        $qstruct = $safemol;
        $bi = 0;
      }
    }              // while ($line ....
  }                // while ($n_cand > 0) ...

  //=============== end outer loop
  echo "</table>\n<hr>\n";

  $time_end = getmicrotime();  
  print "<p>number of hits: <b>$hits</b> (out of $total_cand candidate structures [$fplbl])<br />\n";
  print "<small>dictionary-based fingerprints: $cand_count2 cand., hashed fingerprints: $cand_count1 cand.</small><br />\n";
  $time = $time_end - $time_start;
  print "total number of structures in database: $n_structures <br />\n";
  printf("time used for query: %2.3f seconds </p>\n", $time);
  echo "\n</body></html>\n";
}// if ($mol != '')...       
else {
	echo "</table>";
	do_footer();
	do_html_footer();
}
?>
