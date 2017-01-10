<?php
/**
    Author: Vishvas Handa (100044749)
    Version: 1.0
    
    logoutt.php is used to remove user session credentials and 
    to redirect the user to login page without displaying any information.
*/
  session_start();
  
  if(session_destroy())
  {
    header("Location: buyonline.htm")
  }
?>
