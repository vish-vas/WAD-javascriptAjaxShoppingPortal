<?php
	header('Content-Type: text/xml');
	session_register('cart');
?>
<?php
/** 
	Author: Vishvas Handa
	buying.php receives and responds to the requests from buying.htm.
*/
	
	if($_POST)
	{
		$flag=true;
		$goodsFile = "../../data/goods.xml";
		if($_POST['req'] == 'get')
		{
			if(file_exists($goodsFile))
			{
				$xmlDoc = new DOMDocument();
				$xmlDoc->load($goodsFile);
				$xslDoc = new DomDocument('1.0');
				$xslDoc->load("catalog.xsl");
				$proc = new XSLTProcessor;
				$proc->importStyleSheet($xslDoc);
				$strXml = $proc->transformToXML($xmlDoc);

				echo($strXml);
			}
			else
			{
				echo("File not found");
			}
		}
		else if($_POST['req'] == 'update')
		{
			$action = $_POST['action'];
			$itemId = $_POST['iid'];
			$itemName = $_POST['iname'];
			$itemPrice = $_POST['iprice'];

			if ($_SESSION["cart"] != "")
	        {
	            $cart = $_SESSION["cart"];
	            if ($action == "add")
	            {
	            	if(updateGoodsFile($goodsFile,$itemId,'add',1))
	            	{
		                if (isset($cart[$itemId]['qty']))
		                {  
		                    $cart[$itemId]['qty'] = $cart[$itemId]['qty'] + 1;
		                    $cart[$itemId]['total'] = $cart[$itemId]['total'] + $itemPrice;
		                }
		                else
		                {
		                    $cart[$itemId]['qty'] = 1;
		                    $cart[$itemId]['name'] = $itemName;
		                    $cart[$itemId]['total']= $itemPrice;
		                    $cart[$itemId]['price'] = $itemPrice;
		                }
	                }
	                else
	                {
	                	//send item not available xml
	                	echo (toXml(0, $action, "QTY0"));
	                	$flag = false;
	                }
	            }
	            else
	            {
	            	if(updateGoodsFile($goodsFile, $itemId,'remove', $cart[$itemId]['qty']))
	            	{
		                unset($cart[$itemId]);
	                }
				}
	        }
	        else
	        {
	        	if(updateGoodsFile($goodsFile,$itemId,'add',1))
	            {
		            $cart[$itemId]['qty'] = 1;
	                $cart[$itemId]['name'] = $itemName;
	                $cart[$itemId]['total'] = $itemPrice;
	                $cart[$itemId]['price'] = $itemPrice;
	        	}
	        }
	        $_SESSION["cart"] = $cart; 
	        if(count($cart)>0 && $flag)
	        {
	        	ECHO (transformCart($cart));       
			}
			else
			{
				echo "";
			}
		}
		else if($_POST['req'] == 'checkout')
		{
			$cart = $_SESSION["cart"];
			$action = $_POST['action'];
			if(updateGoodsFileForCheckout($goodsFile, $cart, $action))
			{
				unset($_SESSION["cart"]);
				if($action=="confirm")
				{
					echo (toXml(1, $action, $_POST['total']));
				}
				else
				{
					echo (toXml(1, $action, "0"));
				}
			}
			else
			{
				echo (toXml(0, $action, "0"));
			}
			
		}
		else
		{
			echo("req is not set");
		}
	}

	function updateGoodsFileForCheckout($file, $cart, $operation)
	{
		if(file_exists($file))
		{
			$doc = new DOMDocument();
			$doc->load($file);
			$items = $doc->getElementsByTagName("item");
			foreach ($items as $item) 
			{
				$itemNo = $item->getElementsByTagName("id")->item(0)->nodeValue;
				if($cart==null) return true;
				foreach ($cart as $key => $value) 
				{
					if($itemNo==$key)
					{
						if($operation=="confirm")
						{
							$item->getElementsByTagName("sold")->item(0)->nodeValue += $value['qty'];
							$item->getElementsByTagName("hold")->item(0)->nodeValue -= $value['qty'];
						}
						else
						{
							$item->getElementsByTagName("quantity")->item(0)->nodeValue += $value['qty'];
							$item->getElementsByTagName("hold")->item(0)->nodeValue -= $value['qty'];
						}
					}
				}
			}
			$doc->save($file);
			return true;
		}
		return false;
	}


	function updateGoodsFile($file, $itemno, $operation, $qty)
	{
		if(file_exists($file))
		{
			$doc = new DOMDocument();
			$doc->load($file);
			$items = $doc->getElementsByTagName("item");
			foreach ($items as $item) 
			{
				$itemNo = $item->getElementsByTagName("id")->item(0)->nodeValue;
				if($itemNo==$itemno)
				{
					if($operation == 'add' && $item->getElementsByTagName("quantity")->item(0)->nodeValue>0)
					{
						$item->getElementsByTagName("hold")->item(0)->nodeValue += 1;
						$item->getElementsByTagName("quantity")->item(0)->nodeValue -= 1;
						$doc->save($file);
						return true;
					}
					else if($operation == 'remove' && $item->getElementsByTagName("hold")->item(0)->nodeValue>0)
					{
						$item->getElementsByTagName("hold")->item(0)->nodeValue -= $qty;
						$item->getElementsByTagName("quantity")->item(0)->nodeValue += $qty;
						$doc->save($file);
						return true;
					}
					
				}
			}
		}
		return false;
	}

	function transformCart($cartArr)
    {
        $doc = new DomDocument('1.0');
        $cart = $doc->createElement('cart');
        $cart = $doc->appendChild($cart);
        
        foreach ($cartArr as $key => $value)
        {
        
	        $item = $doc->createElement('item');
	        $item = $cart->appendChild($item);

	        $iid = $doc->createElement('id'); 
	        $iid = $item->appendChild($iid);   
	        $value0 = $doc->createTextNode($key);
	        $value0 = $iid->appendChild($value0);

	        $iname = $doc->createElement('name'); 
	        $iname = $item->appendChild($iname);   
	        $value3 = $doc->createTextNode($value['name']);
	        $value3 = $iname->appendChild($value3);

	        $quantity = $doc->createElement('quantity');
	        $quantity = $item->appendChild($quantity);
	        $value2 = $doc->createTextNode($value['qty']);
	        $value2 = $quantity->appendChild($value2);

	        $total = $doc->createElement('total'); 
	        $total = $item->appendChild($total);   
	        $value4 = $doc->createTextNode($value['total']);
	        $value4 = $total->appendChild($value4);

	     	$price = $doc->createElement('price'); 
	        $price = $item->appendChild($price);   
	        $value5 = $doc->createTextNode($value['price']);
	        $value5 = $price->appendChild($value5);
      	}

        //$xmlDoc = $doc->save();
		$xslDoc = new DomDocument('1.0');
		$xslDoc->load("cart.xsl");
		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xslDoc);
		$strXml = $proc->transformToXML($doc);
        return $strXml;
    }

    function toXML($status, $action, $data)
	{
		$doc = new DomDocument('1.0');
		$session = $doc->createElement('session');
		$session = $doc->appendChild($session);

		$stat = $doc->createElement('status');
		$stat = $session->appendChild($stat);
		$val = $doc->createTextNode($status);
		$val = $stat->appendChild($val);

		$id = $doc->createElement('action');
		$id = $session->appendChild($id);
		$val2 = $doc->createTextNode($action);
		$val2 = $id->appendChild($val2);

		$report = $doc->createElement('response');
		$report = $session->appendChild($report);
		$val3 = $doc->createTextNode($data);
		$val3 = $report->appendChild($val3);

		$strXml = $doc->saveXML();
		return $strXml;
	}
?>