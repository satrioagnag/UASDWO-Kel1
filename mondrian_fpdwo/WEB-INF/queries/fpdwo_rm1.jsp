<%@ page session="true" contentType="text/html; charset=UTF-8" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
    jdbcUrl="jdbc:mysql://localhost:3306/dw_adventureworks?user=root&password=" 
    catalogUri="/WEB-INF/queries/dwfpdwo.xml">

    SELECT 
        {[Measures].[Total Revenue]} ON COLUMNS,
        TopCount([Product].[Product Name].Members, 10, [Measures].[Total Revenue]) ON ROWS
    FROM [SalesCube]
    WHERE ({[Time].[2002], [Time].[2003], [Time].[2004]})

</jp:mondrianQuery>

<c:set var="title01" scope="session">1. Top Produk Revenue Tertinggi (3 Tahun Terakhir)</c:set>