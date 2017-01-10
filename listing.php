<?php
	header('Content-Type: text/xml');
?>
<?php
/** 
	Author: Vishvas Handa
	listing.php receives and responds to all the requests from listing.htm
	file and adds new items to the goods.xml file.
*/
	
	session_start();
	$itid=0;
	$goodsFile = "../../data/goods.xml";
	if($_POST)
	{
		$name = $_POST['name'];
		$price = $_POST['price'];
		$quantity = $_POST['quantity'];
		$description = $_POST['description'];

		$itid = addItemToDatabase($name, $price, $quantity, $description, $goodsFile);
		echo(toXml(1, $itid, $name, "Item added Successfuly."));
	}
	else
	{
		echo(toXml(0, "", "", "No item received."));
	}

	function addItemToDatabase($iname, $iprice, $iquantity, $idescription, $file)
	{
		$doc = new DOMDocument();
		$items; $iid;
		if(file_exists($file))
		{
			$doc->load($file);
			$itemNodes = $doc->getElementsByTagName("items");
			if($itemNodes->length>0)
			{
				$items = $itemNodes->item(0);
				$lastItem = $items->lastChild;
				$iid = $lastItem->firstChild->nodeValue;
				$iid = $iid+1;
			}
			else
			{
				$items = $doc->createElement('items');
				$items = $doc->appendChild($items);
				$iid = "1";
			}
		}
		else
		{
			$items = $doc->createElement('items');
			$items = $doc->appendChild($items);
			$iid = "1";
		}

		$item = $doc->createElement('item');
		$item = $items->appendChild($item);

		$itemid = $doc->createElement('id');
		$itemid = $item->appendChild($itemid);
		$vl0 = $doc->createTextNode($iid);
		$vl0 = $itemid->appendChild($vl0);

		$name = $doc->createElement('name');
		$name = $item->appendChild($name);
		$vl1 = $doc->createTextNode($iname);
		$vl1 = $name->appendChild($vl1);

		$price = $doc->createElement('price');
		$price = $item->appendChild($price);
		$vl2 = $doc->createTextNode($iprice);
		$vl2 = $price->appendChild($vl2);

		$quantity = $doc->createElement('quantity');
		$quantity = $item->appendChild($quantity);
		$vl3 = $doc->createTextNode($iquantity);
		$vl3 = $quantity->appendChild($vl3);

		$description = $doc->createElement('description');
		$description = $item->appendChild($description);
		$vl4 = $doc->createTextNode($idescription);
		$vl4 = $description->appendChild($vl4);

		$hold = $doc->createElement('hold');
		$hold = $item->appendChild($hold);
		$vl5 = $doc->createTextNode('0');
		$vl5 = $hold->appendChild($vl5);

		$sold = $doc->createElement('sold');
		$sold = $item->appendChild($sold);
		$vl6 = $doc->createTextNode('0');
		$vl6 = $sold->appendChild($vl6);

		$doc->save($file);
		return $iid;	
	}

	function toXml($status, $iid, $nam, $data)
	{
		$doc = new DomDocument('1.0');
		$session = $doc->createElement('session');
		$session = $doc->appendChild($session);

		$stat = $doc->createElement('status');
		$stat = $session->appendChild($stat);
		$val = $doc->createTextNode($status);
		$val = $stat->appendChild($val);

		$id = $doc->createElement('iid');
		$id = $session->appendChild($id);
		$val2 = $doc->createTextNode($iid);
		$val2 = $id->appendChild($val2);

		$name = $doc->createElement('iname');
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
