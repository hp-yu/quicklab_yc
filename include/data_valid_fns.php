<?php
function valid_email($address)
{
  // check an email address is possibly valid
  if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address))
    return true;
  else 
    return false;
}
function valid_username($username)
{
  // check an email address is possibly valid
  if (ereg('^[a-zA-Z0-9_]+$', $username))
    return true;
  else 
    return false;
}
function filled_out($form_vars)
//$form_vars must be a array
{
  // test that each variable has a value
  foreach ($form_vars as $key => $value)
  {
     if (!isset($key) || ($value == '')) 
        return false;
  } 
  return true;
}
?>