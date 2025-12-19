<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib uri="http://www.tonbeller.com/wcf" prefix="wcf" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

<html>
<head>
  <title>Mondrian/JPivot AdventureWorks</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <link rel="stylesheet" type="text/css" href="jpivot/table/mdxtable.css">
  <link rel="stylesheet" type="text/css" href="jpivot/navi/mdxnavi.css">
  <link rel="stylesheet" type="text/css" href="wcf/form/xform.css">
  <link rel="stylesheet" type="text/css" href="wcf/table/xtable.css">
  <link rel="stylesheet" type="text/css" href="wcf/tree/xtree.css">
</head>
<body bgcolor=white>

<form action="testpage.jsp" method="post">

<wcf:include id="include01" httpParam="query" prefix="/WEB-INF/queries/" suffix=".jsp"/>


<c:choose>
  <c:when test="${param.query == 'fpdwo_rm1'}">
    <c:set var="title01" value="1. Analisis Top Revenue (AdventureWorks)"/>
  </c:when>
  <c:when test="${param.query == 'fpdwo_rm2'}">
    <c:set var="title01" value="2. Analisis Diskon (AdventureWorks)"/>
  </c:when>
  <c:when test="${param.query == 'fpdwo_rm3'}">
    <c:set var="title01" value="3. Customer vs Produk (AdventureWorks)"/>
  </c:when>
  <c:when test="${param.query == 'fpdwo_rm4'}">
    <c:set var="title01" value="4. Peringkat Sales Person (AdventureWorks)"/>
  </c:when>
  <c:when test="${param.query == 'fpdwo_rm5'}">
    <c:set var="title01" value="5. Analisis Inventory Stok (AdventureWorks)"/>
  </c:when>
  <c:otherwise>
    <c:redirect url="index_fpdwo.html"/>
  </c:otherwise>
</c:choose>


<jp:table id="table01" query="#{query01}"/>
<jp:navigator id="navi01" query="#{query01}" visible="false"/>
<wcf:form id="mdxedit01" xmlUri="/WEB-INF/jpivot/table/mdxedit.xml" model="#{query01}" visible="false"/>
<wcf:form id="sortform01" xmlUri="/WEB-INF/jpivot/table/sortform.xml" model="#{table01}" visible="false"/>
<jp:print id="print01"/>
<wcf:form id="printform01" xmlUri="/WEB-INF/jpivot/print/printpropertiesform.xml" model="#{print01}" visible="false"/>
<jp:chart id="chart01" query="#{query01}" visible="false"/>
<wcf:form id="chartform01" xmlUri="/WEB-INF/jpivot/chart/chartpropertiesform.xml" model="#{chart01}" visible="false"/>
<wcf:table id="query01.drillthroughtable" visible="false" selmode="none" editable="true"/>

<h2><c:out value="${title01}"/></h2>

<%-- toolbar --%>
<wcf:toolbar id="toolbar01" bundle="com.tonbeller.jpivot.toolbar.resources">
  <wcf:scriptbutton id="cubeNaviButton" tooltip="toolb.cube" img="cube" model="#{navi01.visible}"/>
  <wcf:scriptbutton id="mdxEditButton" tooltip="toolb.mdx.edit" img="mdx-edit" model="#{mdxedit01.visible}"/>
  <wcf:scriptbutton id="sortConfigButton" tooltip="toolb.table.config" img="sort-asc" model="#{sortform01.visible}"/>
  <wcf:separator/>
  <wcf:scriptbutton id="levelStyle" tooltip="toolb.level.style" img="level-style" model="#{table01.extensions.axisStyle.levelStyle}"/>
  <wcf:scriptbutton id="hideSpans" tooltip="toolb.hide.spans" img="hide-spans" model="#{table01.extensions.axisStyle.hideSpans}"/>
  <wcf:scriptbutton id="propertiesButton" tooltip="toolb.properties"  img="properties" model="#{table01.rowAxisBuilder.axisConfig.propertyConfig.showProperties}"/>
  <wcf:scriptbutton id="nonEmpty" tooltip="toolb.non.empty" img="non-empty" model="#{table01.extensions.nonEmpty.buttonPressed}"/>
  <wcf:scriptbutton id="swapAxes" tooltip="toolb.swap.axes"  img="swap-axes" model="#{table01.extensions.swapAxes.buttonPressed}"/>
  <wcf:separator/>
  <wcf:scriptbutton model="#{table01.extensions.drillMember.enabled}" tooltip="toolb.navi.member" radioGroup="navi" id="drillMember" img="navi-member"/>
  <wcf:scriptbutton model="#{table01.extensions.drillPosition.enabled}" tooltip="toolb.navi.position" radioGroup="navi" id="drillPosition" img="navi-position"/>
  <wcf:scriptbutton model="#{table01.extensions.drillReplace.enabled}" tooltip="toolb.navi.replace" radioGroup="navi" id="drillReplace" img="navi-replace"/>
  <wcf:scriptbutton model="#{table01.extensions.drillThrough.enabled}" tooltip="toolb.navi.drillthru" id="drillThrough01" img="navi-through"/>
  <wcf:separator/>
  <wcf:scriptbutton id="chartButton01" tooltip="toolb.chart" img="chart" model="#{chart01.visible}"/>
  <wcf:scriptbutton id="chartPropertiesButton01" tooltip="toolb.chart.config" img="chart-config" model="#{chartform01.visible}"/>
  <wcf:separator/>
  <wcf:scriptbutton id="printPropertiesButton01" tooltip="toolb.print.config" img="print-config" model="#{printform01.visible}"/>
  <wcf:imgbutton id="printpdf" tooltip="toolb.print" img="print" href="./Print?cube=01&type=1"/>
  <wcf:imgbutton id="printxls" tooltip="toolb.excel" img="excel" href="./Print?cube=01&type=0"/>
</wcf:toolbar>

<wcf:render ref="toolbar01" xslUri="/WEB-INF/jpivot/toolbar/htoolbar.xsl" xslCache="true"/>
<p>

<%-- overflow message --%>
<c:if test="${query01.result.overflowOccured}">
  <p><strong style="color:red">Resultset overflow occured</strong></p>
</c:if>

<wcf:render ref="navi01" xslUri="/WEB-INF/jpivot/navi/navigator.xsl" xslCache="true"/>

<c:if test="${mdxedit01.visible}">
  <h3>MDX Query Editor</h3>
  <wcf:render ref="mdxedit01" xslUri="/WEB-INF/wcf/wcf.xsl" xslCache="true"/>
</c:if>

<wcf:render ref="sortform01" xslUri="/WEB-INF/wcf/wcf.xsl" xslCache="true"/>
<wcf:render ref="chartform01" xslUri="/WEB-INF/wcf/wcf.xsl" xslCache="true"/>
<wcf:render ref="printform01" xslUri="/WEB-INF/wcf/wcf.xsl" xslCache="true"/>

<p>
<wcf:render ref="table01" xslUri="/WEB-INF/jpivot/table/mdxtable.xsl" xslCache="true"/>
<p>Slicer:
<wcf:render ref="table01" xslUri="/WEB-INF/jpivot/table/mdxslicer.xsl" xslCache="true"/>
<p>
<wcf:render ref="query01.drillthroughtable" xslUri="/WEB-INF/wcf/wcf.xsl" xslCache="true"/>
<p>
<wcf:render ref="chart01" xslUri="/WEB-INF/jpivot/chart/chart.xsl" xslCache="true"/>
<p>
<a href="index_fpdwo.html" style="font-size: 16px; font-weight: bold; text-decoration: none; color: blue;">&laquo; Kembali Ke Menu Utama</a>

</form>
</body>
</html>