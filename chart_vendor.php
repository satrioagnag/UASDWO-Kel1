<div class="container-fluid px-4">
                        <h1 class="mt-4">Grafik Vendor</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Vendor</a></li>
                            <li class="breadcrumb-item active">Grafik Vendor</li>
                        </ol>
                        <div class="card mb-4">
                        </div>

                        <?php
$link = mysqli_connect("localhost", "root", "");
mysqli_select_db($link, "fpdwo");

$test = array();
$count = 0;

$res = mysqli_query($link, "SELECT Name, COUNT(PurchaseOrderID) AS Total FROM shipmethod sm JOIN fact_purchasing fp ON sm.ShipMethodID=fp.ShipMethodID GROUP BY Name ORDER BY Total ASC;");
while($row=mysqli_fetch_array($res)) {
  $test[$count]["label"]=$row["Name"];
  $test[$count]["y"]=$row["Total"];
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
	title: {
		text: "Grafik Metode Pengiriman yang Paling Banyak Digunakan"
	},
	data: [{
		type: "pyramid",
		indexLabel: "{label} - {y}",
		yValueFormatString: "#,##0",
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