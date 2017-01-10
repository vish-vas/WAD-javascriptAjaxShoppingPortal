<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!--cart.xsl used to transform the user cart data into a table form-->
<!-- Author: Vishvas Handa-->
	<xsl:output method="html" indent="yes" version="4.0" />
	<xsl:template match="/">
				<h2>Shopping Cart</h2><br/>
		<table class="itemTable" border="1" cellspacing="0">
			<tr>
				<th>Item Number</th><th>Name</th><th>Price</th><th>Quantity</th><th>Remove</th>
			</tr>
			<xsl:for-each select="//item">
				<tr>
					<td><xsl:value-of select="id"/></td>
					<td style="text-align:left; padding-left:10;"><xsl:value-of select="name"/></td>
					<td><xsl:value-of select="price"/></td>
					<td><xsl:value-of select="quantity"/></td>
					<td><input type="button" value="Remove from cart" onclick="requestUpdate('remove', '{id}', '{name}', '{price}')"/></td>
				</tr>
			</xsl:for-each>
			<tr>
				<td colspan="4" style="text-align: right;">Total:</td>
				<td>
					$<xsl:value-of select="sum(//total)"/>
				</td>
			</tr>
			<tr>
				<td colspan="5" style="background-color: grey;"><input type="button" value="Confirm Purchase" onclick="cnfCncPurchase('confirm', {sum(//total)})"/><input type="button" value="Cancel Purchase" onclick="cnfCncPurchase('cancel', '0')"/></td>
			</tr>
		</table>
	</xsl:template>
</xsl:stylesheet>