                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Produk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                            <li class="breadcrumb-item active">Data Produk</li>
                        </ol>
                        <div class="card mb-4">
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabel Produk
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Nomor Produk</th>
                                            <th>Nama Produk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include "koneksi.php"; ?>
                                        <?php
          $sql = 'SELECT pp.Name, pp.ProductNumber, pp.ReceivedQty, pp.RejectedQty
					from product_purchase pp 
					group by pp.ProductNumber;';

          $query = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
		    	<td>' . $row['ProductNumber'] . '</td>
	    		<td>' . $row['Name'] . '</td>
		      </tr>';
          }
          echo '
          </tbody>
        </table>';
          ?>                             
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>