<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- Author: Vishvas Handa
	processing.xsl used to transfrom the data from goods.xml to 
	required format to be displayed on processing page in a table.-->
	<xsl:output method="html" indent="yes" version="4.0" />
	<xsl:template match="/">
		<h2>Processing Form</h2><br/>
		<table class="itemTable" border="1" cellspacing="0">
			<tr>
				<th>Item Number</th><th>Name</th><th>Price</th><th>Qty Available</th><th>Qty on Hold</th><th>Qty Sold</th>
			</tr>
			<xsl:choose>
				<xsl:when test="count(//item[sold&gt;0])=0">
					<tr>
						<td colspan="6">All Items Processed!</td>
					</tr>
				</xsl:when>
				<xsl:otherwise>
					<xsl:for-each select="//item[sold&gt;0]">
						<tr>
							<td><xsl:value-of select="id"/></td>
							<td style="text-align:left; padding-left:10;"><xsl:value-of select="name"/></td>
							<td><xsl:value-of select="price"/></td>
							<td><xsl:value-of select="quantity"/></td>
							<td><xsl:value-of select="hold"/></td>
							<td><xsl:value-of select="sold"/></td>
						</tr>
					</xsl:for-each>
					<tr>
						<td colspan="6" style="background-color: grey;"><input type="button" value="Process" onclick="requestProcessing()"/></td>
					</tr>
				</xsl:otherwise>
			</xsl:choose>
		</table>
	</xsl:template>
</xsl:stylesheet>