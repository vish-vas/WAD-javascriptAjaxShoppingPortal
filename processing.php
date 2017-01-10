<?php
	header('Content-Type: text/xml');
?>
<?php
	/** 
	Author: Vishvas Handa
	processing.php facilitates the managers processing functionality.
*/
	
	session_start();
	$goodsFile = "../../data/goods.xml";
	if($_POST)
	{
		if($_POST['req']=="get")
		{
			if(file_exists($goodsFile))
			{
				$xmlDoc = new DOMDocument();
				$xmlDoc->load($goodsFile);
				$xslDoc = new DomDocument('1.0');
				$xslDoc->load("processing.xsl");
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
		else if($_POST['req']=="process")
		{
			if(processGoodsFile($goodsFile))
			{
				echo "Processing Completeted Successfuly!";
			}
			else
			{
				echo "Processing Failed!";
			}

		}
		else
		{
			echo("req is not set");
		}
	}

	function processGoodsFile($file)
	{
		if(file_exists($file))
		{
			$doc = new DOMDocument();
			$doc->load($file);
			$items = $doc->getElementsByTagName("item");
			foreach ($items as $item) 
			{
				$soldNo = $item->getElementsByTagName("sold")->item(0)->nodeValue;
				if($soldNo>0)
				{
					$item->getElementsByTagName("sold")->item(0)->nodeValue = 0;
				}
			}
			$doc->save($file);
			removeFromFile($file);
			return true;
		}
		return false;
	}

	function removeFromFile($file)
	{
		$doc = new DOMDocument();
		$doc->load($file);
		$domAr = array();
		$items = $doc->getElementsByTagName("item");
		foreach ($items as $item) 
		{
			if($item->getElementsByTagName("quantity")->item(0)->nodeValue == 0 && $item->getElementsByTagName("hold")->item(0)->nodeValue == 0)
			{
				$domAr[] = $item;
			}
		}
		foreach ($domAr as $it) 
		{
			$it->parentNode->removeChild($it);
		}
		$doc->save($file);
	}

?>