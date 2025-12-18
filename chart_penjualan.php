<div class="container-fluid px-4">
                        <h1 class="mt-4">Grafik Penjualan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Penjualan</a></li>
                            <li class="breadcrumb-item active">Grafik Penjualan</li>
                        </ol>
                        <div class="card mb-4">
                        </div>

                        <?php
$link = mysqli_connect("localhost", "root", "");
mysqli_select_db($link, "fpdwo");

$test = array();
$count = 0;

$res = mysqli_query($link, "SELECT d.Tahun, sum(fs.TotalDue) as Total FROM date d JOIN fact_sales fs ON d.DateID=fs.dateID GROUP BY Tahun;");
while($row=mysqli_fetch_array($res)) {
  $test[$count]["label"]=$row["Tahun"];
  $test[$count]["y"]=$row["Total"];
  $count=$count+1;
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Grafik Tahun dengan Penjualan Tertinggi"
	},
	axisY: {
		title: "Total Penjualan"
	},
	data: [{
		type: "area",
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