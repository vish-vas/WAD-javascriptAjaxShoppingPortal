<?php
	header('Content-Type: text/xml');
?>
<?php
/** 
	Author: Vishvas Handa
	mlogin.php facilitates the manager login functionality and receives/
	responds to requests from multiple files such as mlogin.htm, listing.htm
	and processing.htm.
*/

	session_start();
	if($_SESSION)
	{
		if(isset($_SESSION['status']) && $_SESSION['status']==1)
		{
			if(isset($_SESSION['mid']))
			{
				echo (toXml($_SESSION['status'], $_SESSION['mid'], "Already Logged in."));
			}
			else
			{
				echo (toXml(-1, "", "Access denied."));
			}
		}
	}
	elseif($_POST)
	{
		$mId = $_POST['mid'];
		$mPass = $_POST['pass'];
		$flag = false;
		$managerFile = "../../data/manager.txt";
		$fileDelimiter=", ";
		if(file_exists($managerFile))
		{
			$fileData = file($managerFile, FILE_IGNORE_NEW_LINES);
			$users = array();
			foreach ($fileData as $value) 
			{
				$values = explode($fileDelimiter, $value);
				if($values[0]==$mId && $values[1]==$mPass)
				{
					$flag = true;
				}
			}
			if($flag)
			{
				$_SESSION['mid']=$mId;
				$_SESSION['status']=1;
				echo (toXml(1, $mId, "Log in Successful."));
			}
			else
			{
				session_destroy();
				echo (toXml(0, "", "User not found."));
			}
		}
		else
		{
			echo (toXml(0, "", "Error - File not found."));
		}
	}
	else
	{
		echo (toXml(-1, "", ""));
	}

	function toXml($status, $mid, $data)
	{
		$doc = new DomDocument('1.0');
		$session = $doc->createElement('session');
		$session = $doc->appendChild($session);

		$stat = $doc->createElement('status');
		$stat = $session->appendChild($stat);
		$val = $doc->createTextNode($status);
		$val = $stat->appendChild($val);

		$id = $doc->createElement('mid');
		$id = $session->appendChild($id);
		$val2 = $doc->createTextNode($mid);
		$val2 = $id->appendChild($val2);

		$report = $doc->createElement('response');
		$report = $session->appendChild($report);
		$val3 = $doc->createTextNode($data);
		$val3 = $report->appendChild($val3);

		$strXml = $doc->saveXML();
		return $strXml;
	}

?>