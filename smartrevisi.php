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

// Fungsi untuk menambah data
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
if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dataWisata[] = $row;
    }
}

// Mengambil bobot dari database
$sql = "SELECT * FROM bobot_kriteria_smart";
$result = $conn->query($sql);
$bobot = [];
if ($result !== false && $result->num_rows > 0) {
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>

    <div class="content">
        <div class="container">
            <!--Tabel Input Data Kriteria dan Alternatif-->
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3><b>Tabel Kriteria dan Alternatif</b></h3>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="namaWisata" class="form-label">Nama Wisata</label>
                                <select class="form-select" name="nama_wisata" required>
                                    <option selected>Pilih disini</option>
                                    <option value="Desa Sade">Desa Sade</option>
                                    <option value="Pantai Mawun">Pantai Mawun</option>
                                    <option value="Selong Belanak">Selong Belanak</option>
                                    <option value="Pantai Tanjung Aan">Pantai Tanjung Aan</option>
                                    <option value="Pantai Kuta">Pantai Kuta</option>
                                    <option value="Pantai Mawi">Pantai Mawi</option>
                                    <option value="Bukit Merese">Bukit Merese</option>
                                    <option value="Pantai Semeti">Pantai Semeti</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keindahanwisata" class="form-label">Nilai Keindahan</label>
                                <select class="form-select" name="keindahan" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat tidak Indah</option>
                                    <option value="2">Tidak indah</option>
                                    <option value="3">Cukup Indah</option>
                                    <option value="4">Indah</option>
                                    <option value="5">Sangat Indah</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kebersihanwisata" class="form-label">Nilai Kebersihan</label>
                                <select class="form-select" name="kebersihan" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat tidak bersih</option>
                                    <option value="2">Tidak bersih</option>
                                    <option value="3">Cukup bersih</option>
                                    <option value="4">Bersih</option>
                                    <option value="5">Sangat Bersih</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fasilitaswisata" class="form-label">Nilai Fasilitas</label>
                                <select class="form-select" name="fasilitas" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat tidak lengkap</option>
                                    <option value="2">Tidak lengkap</option>
                                    <option value="3">Cukup lengkap</option>
                                    <option value="4">Lengkap</option>
                                    <option value="5">Sangat Lengkap</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hargawisata" class="form-label">Nilai Harga</label>
                                <select class="form-select" name="harga" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat Mahal</option>
                                    <option value="2">Mahal</option>
                                    <option value="3">Cukup Murah</option>
                                    <option value="4">Murah</option>
                                    <option value="5">Sangat Murah</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jarakwisata" class="form-label">Nilai Jarak</label>
                                <select class="form-select" name="jarak" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat Jauh</option>
                                    <option value="2">Jauh</option>
                                    <option value="3">Cukup Dekat</option>
                                    <option value="4">Dekat</option>
                                    <option value="5">Sangat Dekat</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keamananwisata" class="form-label">Nilai Keamanan</label>
                                <select class="form-select" name="keamanan" required>
                                    <option selected>Pilih disini</option>
                                    <option value="1">Sangat tidak Aman</option>
                                    <option value="2">Tidak Aman</option>
                                    <option value="3">Cukup Aman</option>
                                    <option value="4">Aman</option>
                                    <option value="5">Sangat Aman</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Wisata -->
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3><b>Tabel Data Wisata</b></h3>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Wisata</th>
                                    <th>Keindahan</th>
                                    <th>Kebersihan</th>
                                    <th>Fasilitas</th>
                                    <th>Harga</th>
                                    <th>Jarak</th>
                                    <th>Keamanan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataWisata as $index => $wisata) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $wisata['nama_wisata'] ?></td>
                                        <td><?= $wisata['keindahan'] ?></td>
                                        <td><?= $wisata['kebersihan'] ?></td>
                                        <td><?= $wisata['fasilitas'] ?></td>
                                        <td><?= $wisata['harga'] ?></td>
                                        <td><?= $wisata['jarak'] ?></td>
                                        <td><?= $wisata['keamanan'] ?></td>
                                        <td>
                                            <a href="?delete_smart.php?id=<?= $wisata['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                            <a href="edit_smart.php?id=<?= $wisata['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil Perhitungan -->
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3><b>Hasil Perhitungan SMART</b></h3>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Wisata</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nilai_akhir as $nama_wisata => $nilai) : ?>
                                    <tr>
                                        <td><?= $nama_wisata ?></td>
                                        <td><?= number_format($nilai, 4) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
