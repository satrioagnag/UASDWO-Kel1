<%@ page session="true" contentType="text/html; charset=UTF-8" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
    jdbcUrl="jdbc:mysql://localhost:3306/dw_adventureworks?user=root&password=" 
    catalogUri="/WEB-INF/queries/dwfpdwo.xml">

    SELECT
        {[Customer].[Customer Type].Members} ON COLUMNS,
        Order(
            [Product].[Category].Members, 
            [Measures].[Order Count], 
            BDESC
        ) ON ROWS
    FROM [SalesCube]

</jp:mondrianQuery>

<c:set var="title01" scope="session">3. Kategori Produk Order Frequency Tertinggi per Segmen Pelanggan</c:set>