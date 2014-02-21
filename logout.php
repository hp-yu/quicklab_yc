<?php
  session_start();
  
  // store to test if they *were* logged in
  //unset($_SESSION['wy_user']);
  setcookie("wy_user", "", time() - 3600);
  unset($_SESSION['clipboard']);
  unset($_SESSION['cart']);
  unset($_SESSION['query']);
  unset($_SESSION['url-1']);
  unset($_SESSION['selecteditemStore']);
  unset($_SESSION['selecteditemDel']);
  unset($_SESSION['selecteditemCK']);
  unset($_SESSION['selecteditemCL']);
  session_destroy();
/*
  if (!empty($old_user))
  {
    echo 'Logged out.<br />';
  }
  else
  {
    // if they weren't logged in but came to this page somehow
    echo 'You were not logged in, and so have not been logged out.<br />'; 
  }*/
  header('Location: '.'index.php');
?> 