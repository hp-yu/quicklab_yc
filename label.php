<?php
include('include/includes.php');
?>
<?php
if ($_REQUEST['action']=="preview") {
	require('include/fpdf/chinese.php');
	class PDF extends PDF_Chinese {
		function Header() {
		}
		
		function Footer() {
		}
	}

	$db_conn=db_connect();
	$num_items=$_REQUEST['num'];
	$position=$_REQUEST['position'];
	$lines=$_REQUEST['lines'];
	$fontSize=$_REQUEST['fontSize'];
	
	$query="SELECT * FROM label_sheets WHERE id={$_REQUEST['sheet']}";
	$rs=$db_conn->query($query);
	$sheet=$rs->fetch_assoc();
	$labels_total=$sheet['labels_total'];
	$labels_row=$sheet['labels_row'];
	$labels_column=$sheet['labels_column'];
	$label_width=$sheet['label_width'];
	$label_height=$sheet['label_height'];
	$margin_left=$sheet['margin_left'];
	$margin_top=$sheet['margin_top'];
	$pitch_vert=$sheet['pitch_vert'];
	$pitch_horiz=$sheet['pitch_horiz'];
	$padding_left=$sheet['padding_left'];
	$padding_top=$sheet['padding_top'];
	$line_height=($label_height-2*$padding_top)/$lines;
	$line_width=($label_width-2*$padding_left);
	$sheet_width=$sheet['sheet_width'];
	$sheet_height=$sheet['sheet_height'];
	
	$pdf=new PDF();
	$pdf->FPDF(P,mm,array($sheet_width,$sheet_height));
	//$pdf->AliasNbPages();
	$pdf->AddGBFont('simhei','ºÚÌå');
	
	$page_old=0;
	for ($n=1;$n<=$num_items;$n++) {
		//page break, instead of AutoPageBreak
		$page_new=intval(($n+$position-2)/$labels_total)+1;
		if ($page_new>$page_old) {
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false,0);
		}
		$page_old=$page_new;
		//SetXY of each label
		$colume=($n+$position-2)%$labels_row;
		$row=intval(($n+$position-2)/$labels_row)-$labels_column*($page_new-1);
		$x=$margin_left+($colume)*($pitch_horiz);
		$y=$margin_top+($row)*($pitch_vert);
		$pdf->SetXY($x,$y);		
		
		//depict a label
		$pdf->SetFillColor(255,255,255);
		//left padding
		$pdf->Cell($padding_left,$label_height,"",0,0,'L',1);
		//top padding
		$pdf->Cell($line_width,$padding_top,"",0,2,'L',1);
		//lines
		$pdf->SetFont('simhei','B',$fontSize);
		for ($l=1;$l<=$lines;$l++) {
			$pdf->Cell($line_width,$line_height,mb_convert_encoding($_REQUEST['line_'.$l.'_'.$n],"gb2312","utf-8"),0,2,'L',1);
		}
		//bottom padding
		$pdf->Cell($line_width,$padding_top,"",0,2,'L',1);
		//right padding
		$x=$x+$label_width-$padding_left;
		$pdf->SetXY($x,$y);
		$pdf->Cell($padding_left,$label_height,"",0,0,"",1);
		//cell spacing
		if ($colume==$labels_row-1||$n==$num_items) {
			$pdf->Cell($sheet_width-$x,$label_height,"",0,2,"",1);
		}
		else {
			$pdf->Cell(($pitch_horiz-$label_width),$label_height,"",0,2,"",1);
		}
	}
	$pdf->Output("label.pdf",I);
	exit;
}
?>
<?php
  do_html_header('Label printer-Quicklab');
  do_header();
  do_leftnav();
  js_selectall();
?>
<table width="100%" class="standard"/>
	<tr>
		<td colspan="2"><div align='center'><h2>Label printer</h2></td>
	</tr>
	<tr>
		<td colspan="2">
		<from id="steps"/>
		<table width="100%" class="calc"/>
			<tr>
				<td><label id="step1_label" style="font-size:12pt;"/>STEP 1</label></td>
				<td><label id="step2_label" style="font-size:12pt;"/>STEP 2</label></td>
				<td><label id="step3_label" style="font-size:12pt;"/>STEP 3</label></td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
	<tr>
		<td colspan="2">
  		<form id="step1_form"  method="POST" target="_self" action="" enctype="multipart/form-data">
  			<table id="step1_table" width="100%">
  				<tr>
  					<td width="20%">Label sheet type:</td>
  					<td>
<?php
$db_conn=db_connect();
$query="SELECT * FROM label_sheets ORDER BY id";
$result = $db_conn->query($query);
$select  = "<select name='sheet'>";
$select .= '<option value=""';
if($_REQUEST['sheet'] == '') $select .= ' selected ';
$select .= '>- Choose -</option>';

while ($option = $result->fetch_assoc()) {
	$select .= "<option value='{$option['id']}'";
	if ($option['id'] == $_REQUEST['sheet']) {
		$select .= ' selected';
	}
	$select .=  ">".$option['name'].". ".$option['label_width']."*".$option['label_height']." mm  ".$option['labels_row']."*".$option['labels_column']."</option>";
}
$select .= "</select>";
echo $select."*";
?>
  					</td>
  				</tr>
  				<tr>
						<td valign="middle">Content from:</td>
						<td>
						<table cellpadding="0" cellpadding="0">
<?php
// from single record
if (isset($_REQUEST['item_id'])&&isset($_REQUEST['module_id'])||$_REQUEST['content']=="single") {
	echo "<input type=\"hidden\" name=\"item_id\" value=\"{$_REQUEST['item_id']}\"/>";
	echo "<input type=\"hidden\" name=\"module_id\" value=\"{$_REQUEST['module_id']}\"/>";
	echo "<tr><td>";
	$module=get_name_from_id(modules,$_REQUEST['module_id']);
	$item=get_name_from_id($module['name'],$_REQUEST['item_id']);
	echo "<input type=\"radio\" name=\"content\" value=\"single\" checked/>";
	echo $module['name'].": ".$item['name'];
	echo "</td></tr>";
}
//from clipboard
if (count($_SESSION['clipboard'])>0||$_REQUEST['content']=="clipboard") {
	echo "<tr><td>";
	echo "<input type=\"radio\" name=\"content\" value=\"clipboard\" ";
	if (!isset($_REQUEST['item_id'])||!isset($_REQUEST['module_id'])) {
		echo " checked";
	}
	echo "/>";
	echo "Clipboard (".count($_SESSION['clipboard']).")";
	echo "</td></tr>";
}
echo "<tr><td>";
echo "<input type=\"radio\" name=\"content\" value=\"manual\" ";
//from manual input
if ((!isset($_REQUEST['item_id'])||!isset($_REQUEST['module_id']))&&(!isset($_SESSION['clipboard'])||count($_SESSION['clipboard'])==0||$_REQUEST['content']=="manual")) {
	echo " checked";
}
echo "/>";
echo "Manual input,&nbsp;number of records:&nbsp;";
echo "<input type='text' name='input_num' size='5' value='{$_REQUEST['input_num']}'/>";
echo "</td></tr>";
echo "<tr><td>";
echo "<input type=\"radio\" name=\"content\" value=\"file\" ";
// from file
if ($_REQUEST['content']=="file") {
	echo " checked";
}
echo "/>";
echo "File: ";
echo "<input type=\"file\" name=\"file\"/>";
echo "</td></tr>";
echo "<tr><td>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Separator:&nbsp;<input type='radio' name='separator' value=\"tab\" checked />tab&nbsp;&nbsp;<input type='radio' name='separator' value=\"comma\" />comma(,)";
echo "</td></tr>";
?>
						</td>
						</table>
					<tr>
  					<td colspan='2'><input type='submit' value='Next'/></td>
  				</tr>  
					<input type="hidden" name="code" value="<?php $_REQUEST['code'] ?>"/>
					<input type="hidden" name="step" value="2" />
  			</table>
  		</form>
  		<form id="step2_form" action="" method="POST" target="_self"/>
  			<table id="step2_table"  style="display:none;" width="100%">
  				<tr>
	  				<td colspan="2">Position from:&nbsp;
	<?php 
	$db_conn=db_connect();
	$query="SELECT * FROM label_sheets WHERE id='{$_REQUEST['sheet']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$labels_total=$match['labels_total'];
	echo "<select name='position'>";
	for($n=1;$n<=$labels_total;$n++) {
		echo "<option value='$n'>$n</option>";
	}
	echo "</select>";
	?>
	  				</td>
					</tr>
					<tr>
						<td colspan="2">
						<table width='100%' class='results'>
							<tr>
								<td class='results_header'>
								<input type='checkbox' name='clickall' onclick=selectall(this.checked,form) checked></td><td class='results_header'>
								<input type='checkbox' name='line1_use' checked>&nbsp;&nbsp;Line 1</td><td class='results_header'>
								<input type='checkbox' name='line2_use' checked>&nbsp;&nbsp;Line 2</td><td class='results_header'>
								<input type='checkbox' name='line3_use' checked>&nbsp;&nbsp;Line 3</td><td class='results_header'>
								<input type='checkbox' name='line4_use' checked>&nbsp;&nbsp;Line 4</td><td class='results_header'>
								Quantity</td>
							</tr>
<?php
$padding_left=$match['padding_left'];
$label_width=$match['label_width'];
$line_width=($label_width-2*$padding_left);
//from single input
if ($_REQUEST['content']=='single') {
	$module_id=$_REQUEST['module_id'];
	$item_id=$_REQUEST['item_id'];
	while(strlen($module_id)<2) $module_id="0".$module_id;
	while(strlen($item_id)<6) $item_id="0".$item_id;
	$quicklab_id=$module_id.$item_id;
	echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this,form)  name='selectedItem[]' value='$quicklab_id' checked></td><td class='results'>";
	echo "<i>Quick ID: ".$quicklab_id."</i></td><td class='results'>";
	echo "<input type='hidden' name='line_1_$quicklab_id' value='Quick ID: $quicklab_id'/>";
	$query = "SELECT people_id FROM users WHERE username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$user=$result->fetch_assoc();
	$query= "SELECT id, name FROM people WHERE id='{$user['people_id']}'";
	$result = $db_conn->query($query);
	$people=$result->fetch_assoc();
	echo "<input type='text' name='line_2_$quicklab_id' size='$line_width' value='".$people['name']." ".date('Y-m-d')."'/></td><td class='results'>";
	$module=get_name_from_id(modules,$module_id);
	$item=get_name_from_id($module['name'],$item_id);
	echo "<input type=\"text\" name=\"line_3_$quicklab_id\" size='$line_width' value='".$module['name'].": ".$item['name']."'/></td><td class='results'>";
	echo "<input type=\"text\" name=\"line_4_$quicklab_id\" size='$line_width' value=\"\"/></td><td class='results'>";
	echo "<input type=\"text\" size=\"5\" name=\"qty_$quicklab_id\" value=\"1\"/></td></tr>";
}
//content from clipboard
if ($_REQUEST['content']=='clipboard') {
	foreach ($_SESSION['clipboard'] as $key=>$value ) {
		$key_array=split("_",$key);
		$module_id=$key_array[0];
		while(strlen($module_id)<2) $module_id="0".$module_id;
		$item_id=$key_array[1];
		while(strlen($item_id)<6) $item_id="0".$item_id;
		$quicklab_id=$module_id.$item_id;
		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this,form)  name='selectedItem[]' value='$quicklab_id' checked></td><td class='results'>";
		echo "<i>Quick ID: ".$quicklab_id."</i></td><td class='results'>";
		echo "<input type='hidden' name='line_1_$quicklab_id' value='Quick ID: $quicklab_id'/>";
		$query = "SELECT people_id FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$user=$result->fetch_assoc();
		$query= "SELECT id, name FROM people WHERE id='{$user['people_id']}'";
		$result = $db_conn->query($query);
		$people=$result->fetch_assoc();
		echo "<input type='text' name='line_2_$quicklab_id' size='$line_width' value='".$people['name']." ".date('Y-m-d')."'/></td><td class='results'>";
		$module=get_name_from_id(modules,$module_id);
		$item=get_name_from_id($module['name'],$item_id);
		echo "<input type=\"text\" name=\"line_3_$quicklab_id\" size='$line_width' value='".$module['name'].": ".$item['name']."'/></td><td class='results'>";
		echo "<input type=\"text\" name=\"line_4_$quicklab_id\" size='$line_width' value=\"\"/></td><td class='results'>";
		echo "<input type=\"text\" size=\"5\" name=\"qty_$quicklab_id\" value=\"1\"/></td></tr>";
	}
}
//content from manual input
if ($_REQUEST['content']=='manual') {
	$row_num=$_REQUEST['input_num'];
	for($n=1;$n<=$row_num;$n++) {
		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this,form)  name='selectedItem[]' value='$n' checked></td><td class='results'>";
		echo "<input type='text' name='line_1_$n' size='$line_width' /></td><td class='results'>";
		echo "<input type='text' name='line_2_$n' size='$line_width' /></td><td class='results'>";
		echo "<input type='text' name='line_3_$n' size='$line_width' /></td><td class='results'>";
		echo "<input type='text' name='line_4_$n' size='$line_width' /></td><td class='results'>";
		echo "<input type=\"text\" size=\"5\" name=\"qty_$n\" value=\"1\"/></td></tr>";
	}
	echo "<input type=\"hidden\" name=\"content\" value=\"manual\"/>";
}
//content from file
if ($_REQUEST['content']=='file') {
	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
		$type = basename($_FILES['file']['type']);
		if ($type=="octet-stream"||$type=="plain") {
			$separator=$_POST['separator'];
			$file=file($_FILES['file']['tmp_name']);
			$row_num=count($file);
			if ($separator=="tab") $separator="\t";
			elseif ($separator=="comma") $separator=",";
			for($n=1;$n<$row_num;$n++) {
				$data=split($separator,$file[$n]);
				$line_1=mb_convert_encoding(trim($data[0]),"utf-8","gb2312");
				$line_2=mb_convert_encoding(trim($data[1]),"utf-8","gb2312");
				$line_3=mb_convert_encoding(trim($data[2]),"utf-8","gb2312");
				$line_4=mb_convert_encoding(trim($data[3]),"utf-8","gb2312");
				echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this,form)  name='selectedItem[]' value='$n' checked></td><td class='results'>";
				echo "<input type='text' name='line_1_$n' size='$line_width' value='".$line_1."'/></td><td class='results'>";
				echo "<input type='text' name='line_2_$n' size='$line_width' value='".$line_2."'/></td><td class='results'>";
				echo "<input type='text' name='line_3_$n' size='$line_width' value='".$line_3."'/></td><td class='results'>";
				echo "<input type='text' name='line_4_$n' size='$line_width' value='".$line_4."'/></td><td class='results'>";
				echo "<input type=\"text\" size=\"5\" name=\"qty_$n\" value=\"1\"/></td></tr>";
			}
			echo "<input type=\"hidden\" name=\"content\" value=\"file\"/>";
		}
	}
}
?>
						</table>
						</td>
					</tr>
					<tr>
  					<td colspan='2'>
  					<input type="button" value="Back" onclick='JavaScript:document.getElementById("step1_table").style.display="";document.getElementById("step2_table").style.display="none";document.getElementById("step3_table").style.display="none";document.getElementById("step1_label").style.font="18pt,bold";document.getElementById("step2_label").style.font="12pt,bold"' />
  					<input type='submit' value='Next' />
  					</td>
	  			</tr>
  				<input type="hidden" name="step" value="3"/>
  				<input type="hidden" name="sheet" value="<?php echo $_REQUEST['sheet']?>"/>
  			</table>
  		</form>
  		<form id="step3_form" action="" method="POST" target="_blank"/>
  			<table id="step3_table"  style="display:none;" width="100%">
  				<tr>
						<td colspan="2">
<?php 
$db_conn=db_connect();
$query="SELECT * FROM label_sheets WHERE id='{$_REQUEST['sheet']}'";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
$labels_total=$match['labels_total'];
$label_width=$match['label_width'];
$label_height=$match['label_height'];
$labels_row=$match['labels_row'];
$labels_column=$match['labels_column'];
$n=0;
foreach ($_REQUEST['selectedItem'] as $key=>$value ) {
	$id=$value;
	for ($i=0;$i<$_REQUEST['qty_'.$id];$i++) {
		$n++;
	}
}
$position=$_REQUEST['position'];
$pages=intval(($n+$position-2)/$labels_total)+1;
echo "Note:<br>";
echo "Prepare ".$pages." sheet(s), (No:".$_REQUEST['sheet'].". ".$label_width."*".$label_height." mm ".$labels_row."*".$labels_column.")<br>";
echo "Adjust printer document feeder to the correct size.<br>";
echo "Page scaling, please select \"None\" in the print setting.";
?>
		</td>
	</tr>
<?php
if ($_REQUEST['line1_use']==true) {
	$l++;
}
if ($_REQUEST['line2_use']==true) {
	$l++;
}
if ($_REQUEST['line3_use']==true) {
	$l++;
}
if ($_REQUEST['line4_use']==true) {
	$l++;
}
?>
	<tr>
	<td colspan="2">
	<table width='100%' class='results'>
	<tr><td class='results_header'></td>
<?php
for ($ll=1;$ll<=$l;$ll++) {
	echo "<td class='results_header'>Line $ll</td>";
}
?>
	</tr>
	<?php	
	$padding_left=$match['padding_left'];
	$line_width=($label_width-2*$padding_left);
	$n=0;
	foreach ($_REQUEST['selectedItem'] as $key=>$value ) {
		$id=$value;
		for ($i=0;$i<$_REQUEST['qty_'.$id];$i++) {
			$n++;
			echo "<tr><td class='results'>$n</td>";
 			if ($_REQUEST['line1_use']==true) {
				echo "<td class='results'><input type=\"text\" name='line_1_$n' size='$line_width' value='{$_REQUEST['line_1_'.$id]}'/></td>";
			}
			if ($_REQUEST['line2_use']==true) {
				$x=1;
				if ($_REQUEST['line1_use']==true) $x++;
				echo "<td class='results'><input type=\"text\" name='line_".$x."_".$n."' size='$line_width' value='{$_REQUEST['line_2_'.$id]}'/></td>";
			}
			if ($_REQUEST['line3_use']==true) {
				$x=1;
				if ($_REQUEST['line1_use']==true) $x++;
				if ($_REQUEST['line2_use']==true) $x++;
				echo "<td class='results'><input type=\"text\" name='line_".$x."_".$n."' size='$line_width' value='{$_REQUEST['line_3_'.$id]}'/></td>";
			}
			if ($_REQUEST['line4_use']==true) {
				$x=1;
				if ($_REQUEST['line1_use']==true) $x++;
				if ($_REQUEST['line2_use']==true) $x++;
				if ($_REQUEST['line3_use']==true) $x++;
				echo "<td class='results'><input type=\"text\" name='line_".$x."_".$n."' size='$line_width' value=\"{$_REQUEST['line_4_'.$id]}\"/></td>";
			}
			echo "</tr>";
		}
	}
?>
						</table>
						</td>
					</tr>
					<tr>
						<td>Font size: 
						<select name="fontSize"/>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						</td>
					</tr>
					<tr>
  					<td colspan='2'>
  					<input type="button" value="Back" onclick='JavaScript:document.getElementById("step1_table").style.display="";document.getElementById("step2_table").style.display="none";document.getElementById("step3_table").style.display="none";document.getElementById("step1_label").style.font="18pt,bold";document.getElementById("step3_label").style.font="12pt,bold"' />
  					<input type='submit' value='Preview' />
  					</td>
  				</tr>
  				<input type="hidden" name="num" value="<?php echo  $n ?>"/>
  				<input type="hidden" name="action" value="preview"/>
  				<input type="hidden" name="sheet" value="<?php echo $_REQUEST['sheet']?>"/>
  				<input type="hidden" name="position" value="<?php echo $_REQUEST['position']?>"/>
  				<input type="hidden" name="lines" value="<?php echo $l?>/>";
  			</table>
  		</form>
		</td>
	</tr>
</table>
</td></tr>
</table>

<?php
do_rightbar();
do_footer();
do_html_footer();
?>
<script>
<?php
if (!isset($_REQUEST['step'])||$_REQUEST['step']==1) {
?>
document.getElementById("step1_table").style.display="";
document.getElementById("step2_table").style.display="none";
document.getElementById("step3_table").style.display="none";
document.getElementById("step1_label").style.font="18pt,bold";

<?php
}
if ($_REQUEST['step']==2) {
?>
document.getElementById("step1_table").style.display="none";
document.getElementById("step2_table").style.display="";
document.getElementById("step3_table").style.display="none";
document.getElementById("step2_label").style.font="18pt,bold";
<?php
}
if ($_REQUEST['step']==3) {
?>
document.getElementById("step1_table").style.display="none";
document.getElementById("step2_table").style.display="none";
document.getElementById("step3_table").style.display="";
document.getElementById("step3_label").style.font="18pt,bold";
<?php
}
?>
</script>