<?php
	header('Content-Type: text/xml');
?>
<?php
/** 
	Author: Vishvas Handa
	login.php facilitates the customer login functionality and receives/
	responds to requests of various files such as login.htm, buying.htm and reqister.htm.
*/

	session_start();
	if($_SESSION)
	{
		if(isset($_SESSION['status']) && $_SESSION['status']==1)
		{
			if(isset($_SESSION['email']))
			{
				echo (toXml(2, $_SESSION['email'], "Already Logged in."));
			}
			else
			{
				echo (toXml(-1, "", "Access denied."));
			}
		}
	}
	elseif($_POST)
	{
		if(isset($_POST['email']) && isset($_POST['pass']))
		{
			$email = $_POST['email'];
			$mPass = $_POST['pass'];
			$customerFile = "../../data/customer.xml";
			if(file_exists($customerFile))
			{
				if(checkUserCredentials($email, $mPass, $customerFile))
				{
					$_SESSION['email']=$email;
					$_SESSION['status']=1;
					echo (toXml(1, $email, "Log in Successful."));
				}
				else
				{
					session_destroy();
					echo (toXml(0, "", "User not found."));
				}
			}
			else
			{
				echo (toXml(0, "", "Error - No user registered yet."));
			}
		}
		else
		{
			echo (toXml(-1, "", ""));
		}
	}
	else
	{
		echo (toXml(-1, "", ""));
	}

	function toXml($status, $email, $data)
	{
		$doc = new DomDocument('1.0');
		$session = $doc->createElement('session');
		$session = $doc->appendChild($session);

		$stat = $doc->createElement('status');
		$stat = $session->appendChild($stat);
		$val = $doc->createTextNode($status);
		$val = $stat->appendChild($val);

		$id = $doc->createElement('email');
		$id = $session->appendChild($id);
		$val2 = $doc->createTextNode($email);
		$val2 = $id->appendChild($val2);

		$report = $doc->createElement('response');
		$report = $session->appendChild($report);
		$val3 = $doc->createTextNode($data);
		$val3 = $report->appendChild($val3);

		$strXml = $doc->saveXML();
		return $strXml;
	}

	function checkUserCredentials($email, $pas, $file)
	{
		$doc = new DOMDocument();
		$doc->load($file);

		$customers = $doc->getElementsByTagName('customer');
		foreach ($customers as $customer) 
		{
			$useremail = $customer->getElementsByTagName('email')->item(0)->nodeValue;
			$userpass = $customer->getElementsByTagName("password")->item(0)->nodeValue;
			if($useremail==$email && $userpass==$pas)
				return true;
		}
		return false;
	}
?>