<?php
/**
    Author: Vishvas Handa (100044749)
    Version: 1.0
    
    logout.php is used to remove user session credentials and 
    to redirect the user to login page.
*/
	session_start();
	$id = "";
	if(isset($_SESSION['email']))
	{
		$id = $_SESSION['email'];
	}
	elseif(isset($_SESSION['mid']))
	{
		$id = $_SESSION['mid'];
	}
	if(session_destroy())
	{
		echo("<center>Thank you! ".$id." your id has been logged out.<br/><a href='buyonline.htm'>Buyonline.com</a></center>");
	}
?>
