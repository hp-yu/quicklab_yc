<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('solutions');?>
<?php
  do_html_header('Solution center-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
?>
<SCRIPT language="JavaScript" src="include/calc.js"></SCRIPT>
<SCRIPT language="JavaScript">
function choosestocktype() {
  	var e1 = document.getElementById("insert");
  	var e2 = document.getElementById("isinsert");
  	if(e2.checked == true) {
  		e1.style.display = "";
  	}
  	else {
  		e1.style.display = "none";
  	}
  }
</script>
<table width="100%" class="standard">
  <tr>
    <td colspan="2" align="center"><h2>Solution calculator</h2>
    </td>
  </tr>
  <tr><td colspan="2">
  <form id="stock_type_form">
  <table width="100%" class="calc">
  <tr>
    <td>Stock type:</td>
    <td>
	  <input name="stock_type" type="radio" value="1" checked onclick='JavaScript:document.getElementById("powder").style.display="";document.getElementById("liquid").style.display="none";document.getElementById("conversion").style.display="none"'/>
	  <label for="stock_type" >Powder</label>
	  <input name="stock_type" type="radio" value="2" onclick='JavaScript:document.getElementById("powder").style.display="none";document.getElementById("liquid").style.display="";document.getElementById("conversion").style.display="none"'/>
	  <label for="stock_type">Liquid</label>
	  <input name="stock_type" type="radio" value="3" onclick='JavaScript:document.getElementById("powder").style.display="none";document.getElementById("liquid").style.display="none";document.getElementById("conversion").style.display=""'/>
	  <label for="stock_type">Concentration conversion</label>
	  </td>
  </tr>
  </table>
  </form>
  </td></tr>
  <tr><td colspan="2">
  <form id="powder_form">
  <table id="powder" width="100%">
  <tr>
    <td>M.W.:</td>
    <td><input name="calc_which" id="calc_which_1" type="radio" value="1" onclick="calc()"<?php
		if ($_REQUEST['calc_which']=='1')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='mw_value' onchange="calc()" value="<?php echo $_REQUEST['mw']?>">
		<select name="mw_unit" onchange="calc()">
      <option value="1" selected>Da</option>
      <option value="1000">kDa</option>
    </select></td>
  </tr>
  <tr>
    <td>Stock purity:</td>
    <td><input name="calc_which" id="calc_which_2" type="radio" value="2" onclick="calc()"<?php
		if ($_REQUEST['calc_which']=='2')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='stock_purity_value' value="100" onchange="calc()">
    <select name="stock_purity_unit" onchange="calc()">
      <option value="0.01" selected>% (w/w)</option>
    </select></td>
  <tr>
    <td>Final concentration:</td>
    <td><input name="calc_which" id="calc_which_3" type="radio" value="3" onclick="calc()"<?php
		if ($_REQUEST['calc_which']=='3')
		echo 'checked="checked"';
		?>/>
    <input type='text' name='final_conc_value' onchange='calc()'>
    <?php
    $query="SELECT * FROM solution_units WHERE stocktype=0 ORDER BY id";
    //echo query_select_choose('final_unit',$query,name,name);
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if ($result) {
    	$select  = "<select name='final_conc_unit' onchange='calc()'>";
    	for ($i=0; $i < $result->num_rows; $i++) {
    		$option = $result->fetch_assoc();
    		$select .= "<option value='{$option['conversion']}'";
    		if ($option['name'] == $default) {
    			$select .= ' selected';
    		}
    		$select .=  ">{$option['name']}</option>";
    	}
    	$select .= "</select>\n";
    	echo $select;
    }
    ?></td>
  </tr>
  <tr>
    <td>Final volume:</td>
    <td><input name="calc_which" id="calc_which_4" type="radio" value="4" onclick="calc()"<?php
		if ($_REQUEST['calc_which']=='4')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='final_volume_value' onchange="calc()">
    <select name="final_volume_unit" onchange="calc()">
      <option value="1">L</option>
      <option value="0.001" selected>mL</option>
      <option value="0.000001">μL</option>
      <option value="0.000000001">nL</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Stock weight:</td>
    <td><input name="calc_which" id="calc_which_5" type="radio" value="5" onclick="calc()"<?php
		if (!$_REQUEST['calc_which']||$_REQUEST['calc_which']=='5')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='stock_weight_value' readonly style="background:yellow" onchange="calc()">
    <select name="stock_weight_unit" onchange="calc()">
      <option value="1" selected>g</option>
      <option value="0.001">mg</option>
      <option value="0.000001">μg</option>
      <option value="0.000000001">ng</option>
    </select>
    </td>
  </tr>
  </table>
  </form>
  <form id="liquid_form">
  <table id="liquid"  style="display:none;" width="100%">
  <tr>
    <td>M.W.:</td>
    <td><input name="calc_which_liquid" id="calc_which_liquid_1" type="radio" value="1" onclick="calc()"<?php
		if ($_REQUEST['calc_which_liquid']=='1')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='mw_value_liquid' value="<?php echo $_REQUEST['mw']?>" onchange="calc()">
		<select name="mw_unit_liquid" onchange="calc()">
      <option value="1" selected>Da</option>
      <option value="1000">kDa</option>
    </select></td>
  </tr>
  <tr>
    <td>Stock concentration:</td>
    <td><input name="calc_which_liquid" id="calc_which_liquid_2" type="radio" value="2" onclick="calc()"<?php
		if ($_REQUEST['calc_which_liquid']=='2')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='stock_conc_value_liquid' onchange="calc()">
		<?php
    $query="SELECT * FROM solution_units ORDER BY id";
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if ($result) {
    	$select  = "<select name='stock_conc_unit_liquid' onchange='calc()'>";
    	for ($i=0; $i < $result->num_rows; $i++) {
    		$option = $result->fetch_assoc();
    		$select .= "<option value='{$option['conversion']}'";
    		if ($option['name'] == $default) {
    			$select .= ' selected';
    		}
    		$select .=  ">{$option['name']}</option>";
    	}
    	$select .= "</select>\n";
    	echo $select;
    }
    ?>
    </td>
  <tr>
    <td>Final concentration:</td>
    <td><input name="calc_which_liquid" id="calc_which_liquid_3" type="radio" value="3" onclick="calc()"<?php
		if ($_REQUEST['calc_which_liquid']=='3')
		echo 'checked="checked"';
		?>/>
    <input type='text' name='final_conc_value_liquid' onchange='calc()'>
    <?php
    $query="SELECT * FROM solution_units ORDER BY id";
    //echo query_select_choose('final_unit',$query,name,name);
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if ($result) {
    	$select  = "<select name='final_conc_unit_liquid' onchange='calc()'>";
    	for ($i=0; $i < $result->num_rows; $i++) {
    		$option = $result->fetch_assoc();
    		$select .= "<option value='{$option['conversion']}'";
    		if ($option['name'] == $default) {
    			$select .= ' selected';
    		}
    		$select .=  ">{$option['name']}</option>";
    	}
    	$select .= "</select>\n";
    	echo $select;
    }
    ?></td>
  </tr>
  <tr>
    <td>Final volume:</td>
    <td><input name="calc_which_liquid" id="calc_which_liquid_4" type="radio" value="4" onclick="calc()"<?php
		if ($_REQUEST['calc_which_liquid']=='4')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='final_volume_value_liquid' onchange="calc()">
    <select name="final_volume_unit_liquid" onchange="calc()">
      <option value="1">L</option>
      <option value="0.001" selected>mL</option>
      <option value="0.000001">μL</option>
      <option value="0.000000001">nL</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Stock volume:</td>
    <td><input name="calc_which_liquid" id="calc_which_liquid_5" type="radio" value="5" onclick="calc()"<?php
		if (!$_REQUEST['calc_which_liquid']||$_REQUEST['calc_which_liquid']=='5')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='stock_volume_value_liquid' readonly style="background:yellow" onchange="calc()">
    <select name="stock_volume_unit_liquid" onchange="calc()">
      <option value="1">L</option>
      <option value="0.001" selected>mL</option>
      <option value="0.000001">μL</option>
      <option value="0.000000001">nL</option>
    </select>
    </td>
  </tr>
  </table>
  </form>
  <form id="conversion_form">
  <table id="conversion"  style="display:none;" width="100%">
  <tr>
    <td>M.W.:</td>
    <td><input name="calc_which_conversion" id="calc_which_conversion_1" type="radio" value="1" onclick="calc()"<?php
		if ($_REQUEST['calc_which_conversion']=='1')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='mw_value_conversion' value="<?php echo $_REQUEST['mw']?>" onchange="calc()">
		<select name="mw_unit_conversion" onchange="calc()">
      <option value="1" selected>Da</option>
      <option value="1000">kDa</option>
    </select></td>
  </tr>
  <tr>
    <td rowspan="2">Concentration:</td>
    <td><input name="calc_which_conversion" id="calc_which_conversion_2" type="radio" value="2" onclick="calc()"<?php
		if ($_REQUEST['calc_which_conversion']=='2')
		echo 'checked="checked"';
		?>/>
		<input type='text' name='stock_conc_value_conversion' onchange="calc()">
		<?php
    $query="SELECT * FROM solution_units WHERE stocktype=0 ORDER BY id";
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if ($result) {
    	$select  = "<select name='stock_conc_unit_conversion' onchange='calc()'>";
    	for ($i=0; $i < $result->num_rows; $i++) {
    		$option = $result->fetch_assoc();
    		$select .= "<option value='{$option['conversion']}'";
    		if ($option['name'] == $default) {
    			$select .= ' selected';
    		}
    		$select .=  ">{$option['name']}</option>";
    	}
    	$select .= "</select>\n";
    	echo $select;
    }
    ?>
    </td>
  <tr>
    <td><input name="calc_which_conversion" id="calc_which_conversion_3" type="radio" value="3" onclick="calc()"<?php
		if (!$_REQUEST['calc_which_conversion']||$_REQUEST['calc_which_conversion']=='3')
		echo 'checked="checked"';
		?>/>
    <input type='text' name='final_conc_value_conversion' readonly style="background:yellow" onchange='calc()'>
    <?php
    $query="SELECT * FROM solution_units WHERE stocktype=0 ORDER BY id";
    //echo query_select_choose('final_unit',$query,name,name);
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if ($result) {
    	$select  = "<select name='final_conc_unit_conversion' onchange='calc()'>";
    	for ($i=0; $i < $result->num_rows; $i++) {
    		$option = $result->fetch_assoc();
    		$select .= "<option value='{$option['conversion']}'";
    		if ($option['name'] == $default) {
    			$select .= ' selected';
    		}
    		$select .=  ">{$option['name']}</option>";
    	}
    	$select .= "</select>\n";
    	echo $select;
    }
    ?></td>
  </tr>
  </table>
  </form>
  </td>
  <td>
  <FORM NAME="Calc">
<TABLE>
<TR>
<TD>
<INPUT TYPE="text"   NAME="Input" >
<br>
</TD>
</TR>
<TR>
<TD align="center">
<INPUT TYPE="button" NAME="one"  style="width:30" VALUE="  1  " OnClick="Calc.Input.value += '1'">
<INPUT TYPE="button" NAME="two"   style="width:30" VALUE="  2  " OnCLick="Calc.Input.value += '2'">
<INPUT TYPE="button" NAME="three" style="width:30" VALUE="  3  " OnClick="Calc.Input.value += '3'">
<INPUT TYPE="button" NAME="plus"  style="width:30" VALUE="  +  " OnClick="Calc.Input.value += ' + '">
<br>
<INPUT TYPE="button" NAME="four"  style="width:30" VALUE="  4  " OnClick="Calc.Input.value += '4'">
<INPUT TYPE="button" NAME="five"  style="width:30" VALUE="  5  " OnCLick="Calc.Input.value += '5'">
<INPUT TYPE="button" NAME="six"   style="width:30" VALUE="  6  " OnClick="Calc.Input.value += '6'">
<INPUT TYPE="button" NAME="minus" style="width:30" VALUE="  -  " OnClick="Calc.Input.value += ' - '">
<br>
<INPUT TYPE="button" NAME="seven" style="width:30" VALUE="  7  " OnClick="Calc.Input.value += '7'">
<INPUT TYPE="button" NAME="eight" style="width:30" VALUE="  8  " OnCLick="Calc.Input.value += '8'">
<INPUT TYPE="button" NAME="nine"  style="width:30" VALUE="  9  " OnClick="Calc.Input.value += '9'">
<INPUT TYPE="button" NAME="times" style="width:30" VALUE="  x  " OnClick="Calc.Input.value += ' * '">
<br>
<INPUT TYPE="button" NAME="clear" style="width:30" VALUE="  c  " OnClick="Calc.Input.value = ''">
<INPUT TYPE="button" NAME="zero"  style="width:30" VALUE="  0  " OnClick="Calc.Input.value += '0'">
<INPUT TYPE="button" NAME="div"   style="width:30" VALUE="  .  " OnClick="Calc.Input.value += '.'">
<INPUT TYPE="button" NAME="div"   style="width:30" VALUE="  /  " OnClick="Calc.Input.value += ' / '">
<br>
<INPUT TYPE="button" NAME="DoIt"  style="width:132" VALUE="  =  " OnClick="Calc.Input.value = eval(Calc.Input.value)">
<br>
</TD>
</TR>
</TABLE>
</FORM>
  </td>
  </tr>
</table>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
