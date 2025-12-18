            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Pegawai</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Pegawai</a></li>
                        <li class="breadcrumb-item active">Data Pegawai</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabel Pegawai
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID Pegawai</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "koneksi.php";

                                    $sql = 'SELECT SalesPersonID, Name FROM salesperson;';

                                    $query = mysqli_query($conn, $sql);

                                    while ($row = mysqli_fetch_array($query)) {
                                        echo '<tr>
                                                  <td>' . $row['SalesPersonID'] . '</td>
                                                  <td>' . $row['Name'] . '</td>
                                              </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>