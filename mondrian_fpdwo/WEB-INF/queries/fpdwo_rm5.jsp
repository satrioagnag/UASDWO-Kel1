<%@ page session="true" contentType="text/html; charset=UTF-8" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
    jdbcUrl="jdbc:mysql://localhost:3306/dw_adventureworks?user=root&password=" 
    catalogUri="/WEB-INF/queries/dwfpdwo.xml">

    WITH
    MEMBER [Measures].[Stok Tampil] AS
    'CoalesceEmpty([Measures].[Stock Quantity], 0)', 
    FORMAT_STRING = "#,##0"

    MEMBER [Measures].[Batas Aman] AS
    'CoalesceEmpty(CDbl([Product].CurrentMember.Properties("Safety Stock")), 0)', 
    FORMAT_STRING = "#,##0"

    MEMBER [Measures].[Frekuensi Kekurangan] AS
    'Sum(
        Descendants([Time].[All Periods], [Time].[Date]),
        IIf(
            -- Syarat: Stok ada isinya (Aktif) DAN Stok < Batas Aman
            NOT IsEmpty([Measures].[Stock Quantity]) AND 
            [Measures].[Stock Quantity] < [Measures].[Batas Aman],
            1,
            NULL
        )
    )', 
    FORMAT_STRING = "#,##0"

SELECT
    {[Measures].[Frekuensi Kekurangan], [Measures].[Stok Tampil], [Measures].[Batas Aman]} ON COLUMNS,
    TopCount(
        Filter(
            [Product].[Product Name].Members, 
            [Measures].[Frekuensi Kekurangan] > 0
        ),
        10,
        [Measures].[Frekuensi Kekurangan]
    ) ON ROWS

FROM [InventoryCube]
</jp:mondrianQuery>

<c:set var="title01" scope="session">5. Analisis Produk yang Sering Kekurangan Stok</c:set>