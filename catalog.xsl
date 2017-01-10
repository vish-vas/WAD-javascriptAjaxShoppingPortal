<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!--catalog.xsl used to transform the available items from goods.xml file into a presentable html table-->
<!-- Author: Vishvas Handa-->
	<xsl:output method="html" indent="yes" version="4.0" />
	<xsl:template match="/">
		<h2>Shopping Catalog</h2><br/>
		<table class="itemTable" border="1" cellspacing="0">
			<tr>
				<th>Item Number</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Add</th>
			</tr>
			<xsl:choose>
				<xsl:when test="count(//item[quantity&gt;0])=0">
					<tr>
						<td colspan="6">No Items Available!</td>
					</tr>
				</xsl:when>
				<xsl:otherwise>
					<xsl:for-each select="//item[quantity&gt;0]">
						<tr>
							<td><xsl:value-of select="id"/></td>
							<td style="text-align:left; padding-left:10;"><xsl:value-of select="name"/></td>
							<td style="text-align:left; padding-left:10;"><xsl:value-of select="substring(description, 0, 21)"/></td>
							<td><xsl:value-of select="price"/></td>
							<td><xsl:value-of select="quantity"/></td>
							<td><input type="button" value="Add one to cart" onclick="requestUpdate('add', '{id}', '{name}', '{price}')"/></td>
						</tr>
					</xsl:for-each>
				</xsl:otherwise>
			</xsl:choose>
		</table>
	</xsl:template>
</xsl:stylesheet>
