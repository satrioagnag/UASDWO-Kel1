                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Vendor</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Vendor</a></li>
                            <li class="breadcrumb-item active">Data Vendor</li>
                        </ol>
                        <div class="card mb-4">
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabel Vendor
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Vendor ID</th>
                                            <th>Nama Vendor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include "koneksi.php"; ?>
                                        <?php
          $sql = 'select vn.VendorID, vn.Name
          from vendor_name vn';

          $query = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
	    		<td>' . $row['VendorID'] . '</td>
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