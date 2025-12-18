<?php
include 'koneksi.php';

function fetchData(mysqli $conn, string $query): array {
    $result = mysqli_query($conn, $query);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

$salesPerformance = fetchData(
    $conn,
    "SELECT fs.SalesPersonID AS salesperson, COUNT(fs.SalesOrderID) AS orders, SUM(fs.TotalDue) AS revenue
     FROM fact_sales fs
     JOIN date d ON d.DateID = fs.dateID
     WHERE d.Tahun = (SELECT MAX(Tahun) FROM date)
     GROUP BY fs.SalesPersonID"
);

$orderDrill = fetchData(
    $conn,
    "SELECT fs.SalesPersonID AS salesperson, d.Bulan AS month, SUM(fs.TotalDue) AS revenue
     FROM fact_sales fs
     JOIN date d ON d.DateID = fs.dateID
     WHERE d.Tahun = (SELECT MAX(Tahun) FROM date)
     GROUP BY fs.SalesPersonID, d.Bulan
     ORDER BY d.Bulan"
);

$lowStock = fetchData(
    $conn,
    "SELECT Name AS product, SUM(ReceivedQty - RejectedQty) AS qty
     FROM product_purchase
     GROUP BY Name
     HAVING qty < 50
     ORDER BY qty ASC
     LIMIT 10"
);
?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Skenario 3 - Salesperson & Inventory</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Menjawab: hubungan performa Sales Person dengan total penjualan & produk dengan kekurangan stok.</li>
        </ol>
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Apakah terdapat hubungan antara performa Sales Person dengan total penjualan dalam satu tahun terakhir?</div>
                    <div class="card-body">
                        <canvas id="salespersonChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Drill-down: klik sales person untuk melihat tren bulanan</div>
                    <div class="card-body">
                        <canvas id="salespersonDrill"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Produk mana yang paling sering mengalami kekurangan stok (kurang dari batas aman)?</div>
            <div class="card-body">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>
    </div>
</main>
<script>
const salesPerformance = <?php echo json_encode($salesPerformance); ?>;
const orderDrill = <?php echo json_encode($orderDrill); ?>;
const lowStock = <?php echo json_encode($lowStock); ?>;

const salesChart = new Chart(document.getElementById('salespersonChart'), {
    type: 'bar',
    data: {
        labels: salesPerformance.map(item => `Sales ${item.salesperson}`),
        datasets: [{
            label: 'Total Penjualan',
            data: salesPerformance.map(item => item.revenue),
            backgroundColor: 'rgba(75, 192, 192, 0.7)'
        }, {
            label: 'Jumlah Order',
            data: salesPerformance.map(item => item.orders),
            type: 'line',
            borderColor: 'rgba(255, 159, 64, 0.9)',
            fill: false,
            yAxisID: 'y-axis-2'
        }]
    },
    options: {
        scales: {
            yAxes: [
                { id: 'y-axis-1', position: 'left', ticks: { beginAtZero: true } },
                { id: 'y-axis-2', position: 'right', ticks: { beginAtZero: true } }
            ]
        },
        onClick: (_, elements) => {
            if (elements.length > 0) {
                const salesperson = salesPerformance[elements[0]._index].salesperson;
                renderSalespersonDrill(salesperson);
            }
        }
    }
});

const drillCtx = document.getElementById('salespersonDrill').getContext('2d');
let drillChart;
function renderSalespersonDrill(salesperson) {
    const filtered = orderDrill.filter(item => item.salesperson === salesperson);
    if (drillChart) drillChart.destroy();
    drillChart = new Chart(drillCtx, {
        type: 'line',
        data: {
            labels: filtered.map(item => `Bulan ${item.month}`),
            datasets: [{
                label: `Revenue Bulanan Sales ${salesperson}`,
                data: filtered.map(item => item.revenue),
                borderColor: 'rgba(99, 132, 255, 0.9)',
                fill: false
            }]
        }
    });
}

renderSalespersonDrill(salesPerformance.length ? salesPerformance[0].salesperson : null);

new Chart(document.getElementById('inventoryChart'), {
    type: 'horizontalBar',
    data: {
        labels: lowStock.map(item => item.product),
        datasets: [{
            label: 'Qty tersedia',
            data: lowStock.map(item => item.qty),
            backgroundColor: 'rgba(255, 99, 132, 0.7)'
        }]
    },
    options: {
        scales: { xAxes: [{ ticks: { beginAtZero: true } }] }
    }
});
</script>
