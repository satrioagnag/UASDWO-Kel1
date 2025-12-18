<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>


<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
jdbcUrl="jdbc:mysql://localhost/fpdwo?user=root&password=" catalogUri="/WEB-INF/queries/purchase.xml">

select {[Measures].[StockedQty]} ON COLUMNS,
  {([vendor_name].[All vendor],[shipmethod].[All shipmethod],[product_purchase].[All product_purchase],[date].[All date])} ON ROWS
from [Purchase]


</jp:mondrianQuery>





<c:set var="title01" scope="session">Query WHSAKILA using Mondrian OLAP</c:set>
