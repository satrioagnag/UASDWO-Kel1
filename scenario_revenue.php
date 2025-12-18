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

$topRevenue = fetchData(
    $conn,
    "SELECT ps.Name AS product, SUM(fs.TotalDue) AS revenue\n     FROM fact_sales fs\n     JOIN product_sales ps ON ps.SalesOrderID = fs.SalesOrderID\n     JOIN date d ON d.DateID = fs.dateID\n     WHERE d.Tahun >= (SELECT MAX(Tahun)-2 FROM date)\n     GROUP BY ps.Name\n     ORDER BY revenue DESC\n     LIMIT 10"
);

$yearlyRevenue = fetchData(
    $conn,
    "SELECT d.Tahun AS year, SUM(fs.TotalDue) AS revenue\n     FROM fact_sales fs\n     JOIN date d ON d.DateID = fs.dateID\n     GROUP BY d.Tahun\n     ORDER BY d.Tahun"
);

$monthlyRevenue = fetchData(
    $conn,
    "SELECT d.Tahun AS year, d.Bulan AS month, SUM(fs.TotalDue) AS revenue\n     FROM fact_sales fs\n     JOIN date d ON d.DateID = fs.dateID\n     GROUP BY d.Tahun, d.Bulan\n     ORDER BY d.Tahun, d.Bulan"
);

$specialOfferImpact = fetchData(
    $conn,
    "SELECT ps.Name AS product, SUM(ps.OrderQty) AS qty, SUM(fs.TotalDue) AS revenue,\n            CASE WHEN ps.DaysToManufacture > 4 THEN 'Dengan SpecialOffer' ELSE 'Tanpa SpecialOffer' END AS offer_bucket\n     FROM fact_sales fs\n     JOIN product_sales ps ON ps.SalesOrderID = fs.SalesOrderID\n     GROUP BY product, offer_bucket"
);
?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Skenario 1 - Revenue & Special Offer</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Visualisasi menjawab: Produk mana yang memberi kontribusi revenue tertinggi? & Pengaruh SpecialOffer terhadap penjualan.</li>
        </ol>
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Produk mana yang memberikan kontribusi revenue tertinggi dalam 3 tahun terakhir (Top 10)?</div>
                    <div class="card-body">
                        <canvas id="topRevenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Seberapa besar pengaruh penggunaan diskon (SpecialOffer) terhadap peningkatan jumlah penjualan?</div>
                    <div class="card-body">
                        <canvas id="specialOfferChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">Drill-down Revenue: klik tahun untuk melihat tren bulanan</div>
                    <div class="card-body">
                        <canvas id="yearlyChart"></canvas>
                        <div class="mt-3">
                            <canvas id="monthlyChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Detail produk hasil cross-filter</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="detailTable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Bucket SpecialOffer</th>
                                <th>Qty</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
const topRevenueData = <?php echo json_encode($topRevenue); ?>;
const specialOfferData = <?php echo json_encode($specialOfferImpact); ?>;
const yearlyData = <?php echo json_encode($yearlyRevenue); ?>;
const monthlyData = <?php echo json_encode($monthlyRevenue); ?>;

const detailTable = document.querySelector('#detailTable tbody');

function renderTable(productFilter = null) {
    const rows = specialOfferData
        .filter(item => !productFilter || item.product === productFilter)
        .map(item => `<tr><td>${item.product}</td><td>${item.offer_bucket}</td><td>${Number(item.qty).toLocaleString()}</td><td>${Number(item.revenue).toLocaleString()}</td></tr>`) //
        .join('');
    detailTable.innerHTML = rows || '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
}

const topRevenueChart = new Chart(document.getElementById('topRevenueChart'), {
    type: 'bar',
    data: {
        labels: topRevenueData.map(item => item.product),
        datasets: [{
            label: 'Revenue ($)',
            data: topRevenueData.map(item => item.revenue),
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    },
    options: {
        onClick: (_, elements) => {
            if (elements.length > 0) {
                const index = elements[0]._index;
                const selectedProduct = topRevenueData[index].product;
                renderTable(selectedProduct);
                highlightSpecialOffer(selectedProduct);
            }
        },
        tooltips: { callbacks: { label: (tooltipItem) => tooltipItem.yLabel.toLocaleString() }},
        scales: { yAxes: [{ ticks: { beginAtZero: true } }]}
    }
});

const specialOfferChart = new Chart(document.getElementById('specialOfferChart'), {
    type: 'horizontalBar',
    data: {
        labels: specialOfferData.map(item => `${item.product} (${item.offer_bucket})`),
        datasets: [{
            label: 'Qty',
            data: specialOfferData.map(item => item.qty),
            backgroundColor: 'rgba(255, 159, 64, 0.7)'
        }]
    },
    options: {
        onClick: (_, elements) => {
            if (elements.length > 0) {
                const index = elements[0]._index;
                const selectedProduct = specialOfferData[index].product;
                renderTable(selectedProduct);
            }
        },
        scales: { xAxes: [{ ticks: { beginAtZero: true } }]}
    }
});

function highlightSpecialOffer(product) {
    specialOfferChart.data.datasets[0].backgroundColor = specialOfferData.map(item => item.product === product ? 'rgba(255,99,132,0.8)' : 'rgba(255, 159, 64, 0.5)');
    specialOfferChart.update();
}

const yearlyChart = new Chart(document.getElementById('yearlyChart'), {
    type: 'line',
    data: {
        labels: yearlyData.map(item => item.year),
        datasets: [{
            label: 'Revenue Tahunan',
            data: yearlyData.map(item => item.revenue),
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }]
    },
    options: {
        onClick: (_, elements) => {
            if (elements.length > 0) {
                const year = yearlyData[elements[0]._index].year;
                renderMonthly(year);
            }
        },
        tooltips: { callbacks: { label: (tooltipItem) => tooltipItem.yLabel.toLocaleString() } }
    }
});

const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
let monthlyChart;
function renderMonthly(year) {
    const filtered = monthlyData.filter(item => item.year === year);
    const labels = filtered.map(item => `Bulan ${item.month}`);
    const values = filtered.map(item => item.revenue);
    if (monthlyChart) monthlyChart.destroy();
    monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: `Revenue Bulanan ${year}`,
                data: values,
                backgroundColor: 'rgba(99, 132, 255, 0.6)'
            }]
        }
    });
}

renderTable();
renderMonthly(yearlyData.length ? yearlyData[yearlyData.length - 1].year : null);
</script>
