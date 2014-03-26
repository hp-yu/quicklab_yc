<?php
function query_select_choose($name, $query, $value, $display, $default='')
{
  $db_conn = db_connect();
  $result = $db_conn->query($query);
  if (!$result)
  {
    return('');
  }

  $select  = "<select id ='$name' name='$name'>";
  $select .= '<option value=""';
  if($default == '') $select .= ' selected ';
  $select .= '>- Choose -</option>';

  for ($i=0; $i < $result->num_rows; $i++)
  {
    $option = $result->fetch_assoc();
    $select .= "<option value='{$option[$value]}'";
    if ($option[$value] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$display]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}
function query_select_choose_numeric($name, $query, $value, $display, $default='0')
{
  $db_conn = db_connect();
  $result = $db_conn->query($query);
  if (!$result)
  {
    return('');
  }

  $select  = "<select id ='$name' name='$name'>";
  $select .= '<option value="0"';
  if($default == '') $select .= ' selected ';
  $select .= '>- Choose -</option>';

  for ($i=0; $i < $result->num_rows; $i++)
  {
    $option = $result->fetch_assoc();
    $select .= "<option value='{$option[$value]}'";
    if ($option[$value] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$display]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}
function query_select($name, $query, $value, $display, $default='')
{
  $db_conn = db_connect();
  $result = $db_conn->query($query);
  if (!$result) {
    return('');
  }
  $select  = "<select name='$name'  id ='$name'>";
  for ($i=0; $i < $result->num_rows; $i++) {
    $option = $result->fetch_assoc();
    $select .= "<option value='{$option[$value]}'";
    if ($option[$value] == $default) {
      $select .= ' selected';
    }
    $select .=  ">{$option[$display]}</option>";
  }
  $select .= "</select>\n";
  return($select);
}

function query_select_all($name, $query, $value, $display, $default='')
{
  $db_conn = db_connect();
  $result = $db_conn->query($query);
  if (!$result)
  {
    return('');
  }

  $select  = "<select name='$name'  id ='$name'>";
  $select .= '<option value="%"';
  if($default == '') $select .= ' selected ';
  $select .= '>- Select all -</option>';

  for ($i=0; $i < $result->num_rows; $i++)
  {
    $option = $result->fetch_assoc();
    $select .= "<option value='{$option[$value]}'";
    if ($option[$value] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$display]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}
function option_select($name, $option, $num, $default='')
{
  $select  = "<select name='$name'  id ='$name'>";
  for ($i=0; $i<$num; $i++)
  {
    $select .= "<option value='{$option[$i][0]}'";
    if ($option[$i][0] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$i][1]}</option>";
  }
  $select .= "</select>\n";
  return($select);
}
function array_select($name, $array, $default='') {
  $select  = "<select name='$name'  id ='$name'>";
  foreach ($array as $key=>$value) {
    $select .= "<option value='$value'";
    if ($value == $default) {
      $select .= ' selected';
    }
    $select .=  ">$key</option>";
  }
  $select .= "</select>\n";
  return($select);
}
function array_select_choose($name, $array, $default='')
{
  $select  = "<select name='$name' id ='$name'>";
  $select .= '<option value=""';
  if($default == '') $select .= ' selected ';
  $select .= '>- Choose -</option>';
  foreach ($array as $key=>$value)
  {
    $select .= "<option value=$value";
    if ($value == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">$key</option>";
  }
  $select .= "</select>\n";
  return($select);
}
function array_select_all($name, $array, $default='')
{
  $select  = "<select name='$name' id ='$name'>";
  $select .= '<option value=""';
  if($default == '') $select .= ' selected ';
  $select .= '>- Select all -</option>';
  foreach ($array as $key=>$value)
  {
    $select .= "<option value='".$value."'";
    if ($value == $default)
    {
      $select .= " selected";
    }
    $select .=  ">$key</option>";
  }
  $select .= "</select>\n";
  return($select);
}
function option_select_choose($name, $option, $num, $default='')
{
  $select  = "<select name='$name' id ='$name'>";
  $select .= '<option value=""';
  if($default == '') $select .= ' selected ';
  $select .= '>- Choose -</option>';

  for ($i=0; $i<$num; $i++)
  {
    $select .= "<option value='{$option[$i][0]}'";
    if ($option[$i][0] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$i][1]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}
function option_select_all($name, $option, $num, $default='')
{
  $select  = "<select name='$name' id ='$name'>";
  $select .= '<option value="%"';
  if($default == '') $select .= ' selected ';
  $select .= '>- Select all -</option>';

  for ($i=0; $i<$num; $i++)
  {
    $select .= "<option value='{$option[$i][0]}'";
    if ($option[$i][0] == $default)
    {
      $select .= ' selected';
    }
    $select .=  ">{$option[$i][1]}</option>";
  }
  $select .= "</select>\n";

  return($select);
}
function get_record_from_id($tablename,$id)
{
  $db_conn = db_connect();
  $query = "select * from $tablename where id = '$id'";
  $result = $db_conn->query($query);
  return($result->fetch_assoc());
}
function get_name_from_id($tablename,$id)
{
  $db_conn = db_connect();
  $query = "select name from $tablename where id = '$id'";
  $result = $db_conn->query($query);
  return($result->fetch_assoc());
}
function get_id_from_name($tablename,$name)
{
  $db_conn = db_connect();
  $query = "select id from $tablename where name = '$name'";
  $result = $db_conn->query($query);
  return($result->fetch_assoc());
}
function get_record_from_mi($tablename,$module_id,$item_id,$sort,$order)
{
  $db_conn = db_connect();
  if($sort==null||$order==null)
  {
  	$query = "select * from $tablename where module_id='$module_id'
                                           and item_id = '$item_id'";
  }
  else
  {
    $query = "select * from $tablename where module_id='$module_id'
                                           and item_id = '$item_id'
                                           order by $sort $order";
  }
  $result = $db_conn->query($query);
  return $result;
}
function get_record_from_name($tablename,$name)
{
  $db_conn = db_connect();
  $query = "select * from $tablename where name = '$name'";
  $result = $db_conn->query($query);
  return($result->fetch_assoc());
}
function get_quick_id ($module_id,$item_id)
{
		while(strlen($module_id)<2) $module_id="0".$module_id;
		while(strlen($item_id)<6) $item_id="0".$item_id;
		return $module_id.$item_id;
}
?>