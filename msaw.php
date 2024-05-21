<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
      body {
        display: flex;
        height: 100vh;
        overflow: hidden;
      }
      .sidebar {
        width: 250px;
        background-color: #343a40;
        color: #fff;
        flex-shrink: 0;
      }
      .sidebar a {
        color: #adb5bd;
        text-decoration: none;
      }
      .sidebar a:hover {
        color: #fff;
        background-color: #495057;
      }
      .sidebar .nav-link.active {
        background-color: #495057;
        color: #fff;
      }
      .sidebar .nav-item {
        margin: 0;
      }
      .content {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        background-color: #f8f9fa;
      }
    </style>
  </head>
  <body>
    <div class="sidebar d-flex flex-column p-3">
      <h4 class="text-center">Halo Admin !</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">Dashboard</a>
        </li>  
        <li class="nav-item">
          <a class="nav-link" href="#">Perhitungan SAW</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="msmart.php">Perhitungan SMART</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sig.php">Pemetaan Peta SIG</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login-admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
      </ul>
    </div>
    
    <div class="content">
      <div class="container-fluid">
        <h1 class="mt-4">Halaman Perhitungan SAW</h1>
        <p>Selamat Datang di Halaman Perhitungan SAW</p>
        <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Tabel Kriteria dan Alternatif</b></h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="namaWisata" class="form-label">Masukkan Nama Wisata</label>
                        <input type="text" class="form-control" id="namaWisata" name="nama_wisata" required />
                    </div>
                    <div class="mb-3">
                        <label for="keindahanwisata" class="form-label">Masukkan Nilai Keindahan Wisata</label>
                        <input type="number" class="form-control" id="keindahanwisata" name="keindahan" required />
                    </div>
                    <div class="mb-3">
                        <label for="kebersihanwisata" class="form-label">Masukkan Nilai Kebersihan Wisata</label>
                        <input type="number" class="form-control" id="kebersihanwisata" name="kebersihan" required />
                    </div>
                    <div class="mb-3">
                        <label for="fasilitaswisata" class="form-label">Masukkan Nilai Fasilitas Wisata</label>
                        <input type="number" class="form-control" id="fasilitaswisata" name="fasilitas" required />
                    </div>
                    <div class="mb-3">
                        <label for="hargawisata" class="form-label">Masukkan Nilai Harga Wisata</label>
                        <input type="number" class="form-control" id="hargawisata" name="harga" required />
                    </div>
                    <div class="mb-3">
                        <label for="jarakwisata" class="form-label">Masukkan Nilai Jarak Wisata</label>
                        <input type="number" class="form-control" id="jarakwisata" name="jarak" required />
                    </div>
                    <div class="mb-3">
                        <label for="keamananwisata" class="form-label">Masukkan Nilai Keamanan Wisata</label>
                        <input type="number" class="form-control" id="keamananwisata" name="keamanan" required />
                    </div>
                    <button type="submit" class="btn btn-success" name="submit">Tambah Kriteria</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    include 'db.php';

    if (isset($_POST['submit'])) {
        $nama_wisata = $_POST['nama_wisata'];
        $keindahan = $_POST['keindahan'];
        $kebersihan = $_POST['kebersihan'];
        $fasilitas = $_POST['fasilitas'];
        $harga = $_POST['harga'];
        $jarak = $_POST['jarak'];
        $keamanan = $_POST['keamanan'];

        $sql = "INSERT INTO kriteria (nama_wisata, keindahan, kebersihan, fasilitas, harga, jarak, keamanan) VALUES ('$nama_wisata', '$keindahan', '$kebersihan', '$fasilitas', '$harga', '$jarak', '$keamanan')";

        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Mengambil data dari database
    $sql = "SELECT * FROM kriteria";
    $result = $conn->query($sql);
    $dataWisata = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dataWisata[] = $row;
        }
    }

    // Mengambil bobot dari database
    $sql = "SELECT * FROM bobot_kriteria";
    $result = $conn->query($sql);
    $bobot = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bobot = [
                $row['keindahan'],
                $row['kebersihan'],
                $row['fasilitas'],
                $row['harga'],
                $row['jarak'],
                $row['keamanan']
            ];
        }
    } else {
        // Default bobot jika tidak ada di database
        $bobot = [1, 1, 1, 1, 1, 1];
    }
    ?>

    <div class="container mt-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Tabel Hasil Kriteria</b></h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nama Wisata</th>
                            <th scope="col">N.Keindahan</th>
                            <th scope="col">N.Kebersihan</th>
                            <th scope="col">N.Fasilitas</th>
                            <th scope="col">N.Harga</th>
                            <th scope="col">N.Jarak</th>
                            <th scope="col">N.Keamanan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($dataWisata)) {
                            foreach ($dataWisata as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['nama_wisata'] . "</td>";
                                echo "<td>" . $row['keindahan'] . "</td>";
                                echo "<td>" . $row['kebersihan'] . "</td>";
                                echo "<td>" . $row['fasilitas'] . "</td>";
                                echo "<td>" . $row['harga'] . "</td>";
                                echo "<td>" . $row['jarak'] . "</td>";
                                echo "<td>" . $row['keamanan'] . "</td>";
                                echo "<td>
                                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Tidak ada data yang ditambahkan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Menampilkan Bobot dengan Kartu -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Bobot Kriteria</b></h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Keindahan</th>
                            <th>Kebersihan</th>
                            <th>Fasilitas</th>
                            <th>Harga</th>
                            <th>Jarak</th>
                            <th>Keamanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM bobot_kriteria";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['keindahan'] . "</td>";
                                echo "<td>" . $row['kebersihan'] . "</td>";
                                echo "<td>" . $row['fasilitas'] . "</td>";
                                echo "<td>" . $row['harga'] . "</td>";
                                echo "<td>" . $row['jarak'] . "</td>";
                                echo "<td>" . $row['keamanan'] . "</td>";
                                echo "<td><a href='edit-bobot.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a> ";
                                echo "<a href='delete-bobot.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Hapus</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Tidak ada data bobot</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="tambah-bobot.php" class="btn btn-success">Tambah Bobot</a>
            </div>
        </div>
    </div>

    <!-- Tabel Hasil Perangkingan SAW -->
    <div class="container mt-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Tabel Hasil Perangkingan SAW</b></h3>
                <?php
                // Fungsi untuk menghitung nilai R untuk setiap kriteria
                function hitungNilaiR($dataWisata, $bobot)
                {
                    $R = [];
                    foreach ($dataWisata as $wisata) {
                        $R1 = $wisata['keindahan'] / max(array_column($dataWisata, 'keindahan'));
                        $R2 = $wisata['kebersihan'] / max(array_column($dataWisata, 'kebersihan'));
                        $R3 = $wisata['fasilitas'] / max(array_column($dataWisata, 'fasilitas'));
                        $R4 = min(array_column($dataWisata, 'harga')) / $wisata['harga'];
                        $R5 = min(array_column($dataWisata, 'jarak')) / $wisata['jarak'];
                        $R6 = $wisata['keamanan'] / max(array_column($dataWisata, 'keamanan'));

                        // Menghitung nilai total dengan bobot terponderasi
                        $total = $R1 * $bobot[0] + $R2 * $bobot[1] + $R3 * $bobot[2] + $R4 * $bobot[3] + $R5 * $bobot[4] + $R6 * $bobot[5];

                        $R[] = ["nama" => $wisata["nama_wisata"], "nilai" => $total];
                    }

                    // Mengurutkan nilai total dari yang tertinggi ke terendah
                    usort($R, function ($a, $b) {
                        return $b["nilai"] - $a["nilai"];
                    });

                    return $R;
                }

                // Menampilkan hasil perangkingan SAW
                function tampilkanHasilSAW($dataWisata, $bobot)
                {
                    echo "<h5>Hasil perangkingan menggunakan metode SAW (Simple Additive Weighting)</h5>";
                    echo "<table class='table'>
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Nama Tempat Wisata</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>";
                    $R = hitungNilaiR($dataWisata, $bobot);

                    // Sort $R by the 'nilai' key in descending order
                    usort($R, function ($a, $b) {
                        return $b["nilai"] <=> $a["nilai"];
                    });

                    $ranking = 1;
                    foreach ($R as $hasil) {
                        echo "<tr>
                            <td>" . $ranking++ . "</td>
                            <td>" . $hasil["nama"] . "</td>
                            <td>" . number_format($hasil["nilai"], 2) . "</td>
                        </tr>";
                    }
                    echo "</tbody></table>";
                }

                // Menampilkan hasil SAW
                tampilkanHasilSAW($dataWisata, $bobot);
                ?>
            </div>
        </div>
    </div>

      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
