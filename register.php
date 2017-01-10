<?php
	header('Content-Type: text/xml');
?>
<?php
/** 
	Author: Vishvas Handa
	register.php receives/responds to the requests from register.htm
	and store customer data to the customers.xml file.
*/
	
	session_start();
	$uid=0;
	$customerFile = "../../data/customer.xml";
	if($_POST)
	{
		$fName = $_POST['fName'];
		$sName = $_POST['sName'];
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$phone = $_POST['phone'];

		if(checkIfEmailExists($email, $customerFile))
		{
			addUserToDatabase($fName, $sName, $email, $pass, $phone, $customerFile);
			$_SESSION['email']=$email;
			$_SESSION['status']=1;
			echo(toXml(1, $uid, $fName, "User Registered Successfuly."));
		}
		else
		{
			session_destroy();
			echo(toXml(0, "", "", "Email already registered."));
		}
	}

	function checkIfEmailExists($eMail, $file)
	{
		if(file_exists($file))
		{
			$doc = new DOMDocument();
			$doc->load($file);

			$userEmails = $doc->getElementsByTagName('email');
			foreach ($userEmails as $value) 
			{
				if($value->nodeValue==$eMail)
					return false;
			}
		}
		return true;
	}

	function addUserToDatabase($fName, $sName, $eMail, $pass, $phon, $file)
	{
		$doc = new DOMDocument();
		$customers; $cid;
		if(file_exists($file))
		{
			$doc->load($file);
			$custNodes = $doc->getElementsByTagName("customers");
			if($custNodes->length>0)
			{
				$customers = $custNodes->item(0);
				$lastCustomer = $customers->lastChild;
				$cid = $lastCustomer->firstChild->nodeValue;
				$cid = $cid+1;
			}
			else
			{
				$customers = $doc->createElement('customers');
				$customers = $doc->appendChild($customers);
				$cid = "1";
			}
		}
		else
		{
			$customers = $doc->createElement('customers');
			$customers = $doc->appendChild($customers);
			$cid = "1";
		}

		$customer = $doc->createElement('customer');
		$customer = $customers->appendChild($customer);

		$custid = $doc->createElement('custid');
		$custid = $customer->appendChild($custid);
		$vl0 = $doc->createTextNode($cid);
		$vl0 = $custid->appendChild($vl0);

		$firstName = $doc->createElement('firstname');
		$firstName = $customer->appendChild($firstName);
		$vl1 = $doc->createTextNode($fName);
		$vl1 = $firstName->appendChild($vl1);

		$lastName = $doc->createElement('lastname');
		$lastName = $customer->appendChild($lastName);
		$vl2 = $doc->createTextNode($sName);
		$vl2 = $lastName->appendChild($vl2);

		$email = $doc->createElement('email');
		$email = $customer->appendChild($email);
		$vl3 = $doc->createTextNode($eMail);
		$vl3 = $email->appendChild($vl3);

		$password = $doc->createElement('password');
		$password = $customer->appendChild($password);
		$vl4 = $doc->createTextNode($pass);
		$vl4 = $password->appendChild($vl4);

		$phone = $doc->createElement('phone');
		$phone = $customer->appendChild($phone);
		$vl5 = $doc->createTextNode($phon);
		$vl5 = $phone->appendChild($vl5);

		$doc->save($file);
		$uid = $cid;	
	}

	function toXml($status, $cid, $nam, $data)
	{
		$doc = new DomDocument('1.0');
		$session = $doc->createElement('session');
		$session = $doc->appendChild($session);

		$stat = $doc->createElement('status');
		$stat = $session->appendChild($stat);
		$val = $doc->createTextNode($status);
		$val = $stat->appendChild($val);

		$id = $doc->createElement('cid');
		$id = $session->appendChild($id);
		$val2 = $doc->createTextNode($cid);
		$val2 = $id->appendChild($val2);

		$name = $doc->createElement('cname');
		$name = $session->appendChild($name);
		$val4 = $doc->createTextNode($nam);
		$val4 = $name->appendChild($val4);

		$report = $doc->createElement('response');
		$report = $session->appendChild($report);
		$val3 = $doc->createTextNode($data);
		$val3 = $report->appendChild($val3);

		$strXml = $doc->saveXML();
		return $strXml;
	}
?>
