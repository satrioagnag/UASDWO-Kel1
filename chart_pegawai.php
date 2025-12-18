<div class="container-fluid px-4">
                        <h1 class="mt-4">Grafik Pegawai</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Pegawai</a></li>
                            <li class="breadcrumb-item active">Grafik Pegawai</li>
                        </ol>
                        <div class="card mb-4">
                        </div>

						<?php
$link = mysqli_connect("localhost", "root", "");
mysqli_select_db($link, "fpdwo");

$test = array();
$count = 0;

$res = mysqli_query($link, "SELECT Name, TotalDue FROM salesperson sp JOIN fact_sales fs ON sp.SalesPersonID=fs.SalesPersonID ORDER BY TotalDue DESC;");
while($row=mysqli_fetch_array($res)) {
  $test[$count]["label"]=$row["Name"];
  $test[$count]["y"]=$row["TotalDue"];
  $count=$count+1;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2",
	animationEnabled: true,
	title: {
		text: "Grafik Pegawai dengan Penjualan Terbanyak($)"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "#,##0.0\"\"",
		showInLegend: true,
		legendText: "{label} : {y}",
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