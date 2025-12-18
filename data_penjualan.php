                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Penjualan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Penjualan</a></li>
                            <li class="breadcrumb-item active">Data Penjualan</li>
                        </ol>
                        <div class="card mb-4">
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabel Penjualan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID Penjualan</th>
                                            <th>Nama Produk</th>
                                            <th>ID Pegawai</th>
                                            <th>Nama Pegawai</th>
                                            <th>Tanggal</th>
                                            <th>Total Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include "koneksi.php"; ?>
                                        <?php

          $sql = 'select ps.SalesOrderID, ps.Name as NamaProduk, fs.TotalDue, s.SalesPersonID, s.Name as NamaPegawai, d.tanggal_lengkap
          from product_sales ps 
          join fact_sales fs  on ps.SalesOrderID = fs.SalesPersonID 
          join salesperson s on fs.SalesPersonID = s.SalesPersonID 
          join date d on fs.dateID = d.DateID;';

          $query = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
	    		<td>' . $row['SalesOrderID'] . '</td>
		    	<td>' . $row['NamaProduk'] . '</td>
		    	<td>' . $row['SalesPersonID'] . '</td>
		    	<td>' . $row['NamaPegawai'] . '</td>
		    	<td>' . $row['tanggal_lengkap'] . '</td>
                <td>' . $row['TotalDue'] . '</td>
		      </tr>';
          }
          ?>                                 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
        </main>