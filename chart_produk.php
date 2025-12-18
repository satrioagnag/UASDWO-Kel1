<div class="container-fluid px-4">
                        <h1 class="mt-4">Grafik Produk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                            <li class="breadcrumb-item active">Grafik Produk</li>
                        </ol>
                        <div class="card mb-4">
                        </div>

						<?php
$link = mysqli_connect("localhost", "root", "");
mysqli_select_db($link, "fpdwo");

$test = array();
$count = 0;

$res = mysqli_query($link, "SELECT Name, SUM(OrderQty) AS total_order FROM product_sales GROUP BY Name ORDER BY total_order DESC LIMIT 10;");
while($row=mysqli_fetch_array($res)) {
  $test[$count]["label"]=$row["Name"];
  $test[$count]["y"]=$row["total_order"];
  $count=$count+1;
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Grafik Produk dengan Penjualan Terbanyak"
	},
	axisY: {
		title: "Jumlah Produk"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## pcs",
		dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>

<h1></h1>
<h1></h1>
<h1></h1>
<h1></h1>
<h1></h1>