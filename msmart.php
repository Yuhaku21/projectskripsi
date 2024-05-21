<?php
include 'db.php';

// Fungsi untuk menghapus data
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM kriteria_smart WHERE id = $id";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fungsi untuk mengedit data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama_wisata = $_POST['nama_wisata'];
    $keindahan = $_POST['keindahan'];
    $kebersihan = $_POST['kebersihan'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $jarak = $_POST['jarak'];
    $keamanan = $_POST['keamanan'];

    $sql = "UPDATE kriteria_smart SET nama_wisata='$nama_wisata', keindahan='$keindahan', kebersihan='$kebersihan', fasilitas='$fasilitas', harga='$harga', jarak='$jarak', keamanan='$keamanan' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['submit'])) {
    $nama_wisata = $_POST['nama_wisata'];
    $keindahan = $_POST['keindahan'];
    $kebersihan = $_POST['kebersihan'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $jarak = $_POST['jarak'];
    $keamanan = $_POST['keamanan'];

    $sql = "INSERT INTO kriteria_smart (nama_wisata, keindahan, kebersihan, fasilitas, harga, jarak, keamanan) VALUES ('$nama_wisata', '$keindahan', '$kebersihan', '$fasilitas', '$harga', '$jarak', '$keamanan')";

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mengambil data dari database
$sql = "SELECT * FROM kriteria_smart";
$result = $conn->query($sql);
$dataWisata = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dataWisata[] = $row;
    }
}

// Mengambil bobot dari database
$sql = "SELECT * FROM bobot_kriteria_smart";
$result = $conn->query($sql);
$bobot = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bobot = [
            'keindahan' => $row['keindahan'],
            'kebersihan' => $row['kebersihan'],
            'fasilitas' => $row['fasilitas'],
            'harga' => $row['harga'],
            'jarak' => $row['jarak'],
            'keamanan' => $row['keamanan']
        ];
    }
} else {
    // Default bobot jika tidak ada di database
    $bobot = [
        'keindahan' => 0.2,
        'kebersihan' => 0.15,
        'fasilitas' => 0.2,
        'harga' => 0.2,
        'jarak' => 0.1,
        'keamanan' => 0.15
    ];
}

// Fungsi untuk menghitung nilai utility
function hitungUtility($nilai, $max, $min, $sifat)
{
    if ($max == $min) {
        return 1; // Jika max dan min sama, kembalikan nilai default 1 untuk menghindari pembagian dengan nol
    }

    if ($sifat == 'Benefit') {
        return ($nilai - $min) / ($max - $min);
    } else { // Cost
        return ($max - $nilai) / ($max - $min);
    }
}

// Mencari nilai max dan min untuk setiap kriteria
$maxMin = [];
foreach (['keindahan', 'kebersihan', 'fasilitas', 'harga', 'jarak', 'keamanan'] as $kriteria) {
    $values = array_column($dataWisata, $kriteria);
    if (!empty($values)) {
        $maxMin[$kriteria] = ['max' => max($values), 'min' => min($values)];
    } else {
        // Berikan nilai default jika values kosong
        $maxMin[$kriteria] = ['max' => 0, 'min' => 0];
    }
}

// Hitung nilai utility untuk setiap alternatif dan kriteria
$utility = [];
foreach ($dataWisata as $wisata) {
    $nama_wisata = $wisata['nama_wisata'];
    foreach (['keindahan', 'kebersihan', 'fasilitas', 'harga', 'jarak', 'keamanan'] as $kriteria) {
        $sifat = ($kriteria == 'harga' || $kriteria == 'jarak') ? 'Cost' : 'Benefit';
        $utility[$nama_wisata][$kriteria] = hitungUtility($wisata[$kriteria], $maxMin[$kriteria]['max'], $maxMin[$kriteria]['min'], $sifat);
    }
}

// Hitung nilai akhir untuk setiap alternatif berdasarkan bobot
$nilai_akhir = [];
foreach ($utility as $nama_wisata => $kriteria) {
    $nilai_akhir[$nama_wisata] = 0;
    foreach ($kriteria as $k => $nilai) {
        $nilai_akhir[$nama_wisata] += $nilai * $bobot[$k];
    }
}

// Urutkan nilai akhir dari terbesar ke terkecil
arsort($nilai_akhir);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Metode SMART</title>
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
          <a class="nav-link" href="msaw.php">Perhitungan SAW</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Perhitungan SMART</a>
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
            <h1 class="mt-4">Halaman Perhitungan SMART</h1>
            <p>Selamat Datang di Halaman Perhitungan SMART</p>
            <!--Tabel Input Data Kriteria dan Alternatif-->
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
                            <button type="submit" name="submit" class="btn btn-success">Tambah Kriteria</button>
                        </form>
                    </div>
                </div>
                <!--Tabel Hasil Perhitungan SMART-->
                <div class="card mt-5">
                    <div class="card-body">
                    <div class="mt-4">
                    <h3><b>Hasil Perhitungan SMART</b></h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Wisata</th>
                                <th>Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($nilai_akhir as $nama_wisata => $nilai) {
                                echo "<tr>";
                                echo "<td>{$nama_wisata}</td>";
                                echo "<td>" . number_format($nilai, 4) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
                <!--Tabel Kriteria dan Alternatif-->
                <div class="card mt-5">
                    <div class="card-body">
                    <div class="mt-4">
                    <h3><b>Tabel Kriteria dan Alternatif</b></h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Wisata</th>
                                <th>N.Keindahan</th>
                                <th>N.Kebersihan</th>
                                <th>N.Fasilitas</th>
                                <th>N.Harga</th>
                                <th>N.Jarak</th>
                                <th>N.Keamanan</th>
                                <th>N.Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataWisata as $wisata) {
                                echo "<tr>";
                                echo "<td>{$wisata['nama_wisata']}</td>";
                                echo "<td>{$wisata['keindahan']}</td>";
                                echo "<td>{$wisata['kebersihan']}</td>";
                                echo "<td>{$wisata['fasilitas']}</td>";
                                echo "<td>{$wisata['harga']}</td>";
                                echo "<td>{$wisata['jarak']}</td>";
                                echo "<td>{$wisata['keamanan']}</td>";
                                echo "<td>";
                                echo "<a href='?delete_id={$wisata['id']}' class='btn btn-danger btn-sm'>Hapus</a>";
                                echo " <button class='btn btn-warning btn-sm' onclick='editData(" . json_encode($wisata) . ")'>Edit</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Wisata</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNamaWisata" class="form-label">Nama Wisata</label>
                            <input type="text" class="form-control" id="editNamaWisata" name="nama_wisata" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeindahan" class="form-label">Keindahan</label>
                            <input type="number" class="form-control" id="editKeindahan" name="keindahan" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKebersihan" class="form-label">Kebersihan</label>
                            <input type="number" class="form-control" id="editKebersihan" name="kebersihan" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFasilitas" class="form-label">Fasilitas</label>
                            <input type="number" class="form-control" id="editFasilitas" name="fasilitas" required>
                        </div>
                        <div class="mb-3">
                            <label for="editHarga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="editHarga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJarak" class="form-label">Jarak</label>
                            <input type="number" class="form-control" id="editJarak" name="jarak" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeamanan" class="form-label">Keamanan</label>
                            <input type="number" class="form-control" id="editKeamanan" name="keamanan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editData(data) {
            $('#editId').val(data.id);
            $('#editNamaWisata').val(data.nama_wisata);
            $('#editKeindahan').val(data.keindahan);
            $('#editKebersihan').val(data.kebersihan);
            $('#editFasilitas').val(data.fasilitas);
            $('#editHarga').val(data.harga);
            $('#editJarak').val(data.jarak);
            $('#editKeamanan').val(data.keamanan);
            $('#editModal').modal('show');
        }
    </script>

</body>

</html>