<?php
include 'db.php';

// Memastikan koneksi ke database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menambahkan data wisata baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_wisata = $_POST['nama_wisata'];
    $keindahan = $_POST['keindahan'];
    $kebersihan = $_POST['kebersihan'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $jarak = $_POST['jarak'];
    $keamanan = $_POST['keamanan'];

    $sql = "INSERT INTO kriteria (nama_wisata, keindahan, kebersihan, fasilitas, harga, jarak, keamanan) 
            VALUES ('$nama_wisata', '$keindahan', '$kebersihan', '$fasilitas', '$harga', '$jarak', '$keamanan')";

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
            'keindahan' => $row['keindahan'],
            'kebersihan' => $row['kebersihan'],
            'fasilitas' => $row['fasilitas'],
            'harga' => $row['harga'],
            'jarak' => $row['jarak'],
            'keamanan' => $row['keamanan']
        ];
    }
}

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

        // Menghitung nilai total dengan bobot terponderasi dan format 2 angka di belakang koma
        $total = number_format($R1 * $bobot['keindahan'] + $R2 * $bobot['kebersihan'] + $R3 * $bobot['fasilitas'] + $R4 * $bobot['harga'] + $R5 * $bobot['jarak'] + $R6 * $bobot['keamanan'], 2);

        $R[] = ["nama" => $wisata["nama_wisata"], "nilai" => $total];
    }

    // Mengurutkan nilai total dari yang tertinggi ke terendah
    usort($R, function ($a, $b) {
        return $b["nilai"] <=> $a["nilai"];
    });

    return $R;
}

$R = hitungNilaiR($dataWisata, $bobot);

// Mengambil data wisata beserta koordinatnya dari database
$sql = "SELECT id, nama, latitude, longitude FROM sig";
$result = $conn->query($sql);
$wisata_data = [];
$nilai_akhir = [];
$peringkat = [];
foreach ($R as $index => $row) {
    $nilai_akhir[$row['nama']] = $row['nilai'];
    $peringkat[$row['nama']] = $index + 1;
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nama_wisata = $row['nama'];
        // Memastikan hanya wisata yang ada dalam nilai akhir yang ditampilkan
        if (isset($nilai_akhir[$nama_wisata])) {
            $row['nilai_smart'] = $nilai_akhir[$nama_wisata];
            $row['peringkat'] = $peringkat[$nama_wisata];
            $wisata_data[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SAW Revisi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        #map {
            width: 80%;
            height: 400px;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="container">
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3><b>Tambah Data Wisata</b></h3>
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
                                    <option value="1">Sangat Tidak Aman</option>
                                    <option value="2">Tidak Aman</option>
                                    <option value="3">Cukup Aman</option>
                                    <option value="4">Aman</option>
                                    <option value="5">Sangat Aman</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h3><b>Tabel Nilai Preferensi</b></h3>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Wisata</th>
                                        <th>Keindahan</th>
                                        <th>Kebersihan</th>
                                        <th>Fasilitas</th>
                                        <th>Harga</th>
                                        <th>Jarak</th>
                                        <th>Keamanan</th>
                                        <th>Nilai Preferensi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataWisata as $wisata) { ?>
                                        <tr>
                                            <td><?php echo $wisata['nama_wisata']; ?></td>
                                            <td><?php echo $wisata['keindahan']; ?></td>
                                            <td><?php echo $wisata['kebersihan']; ?></td>
                                            <td><?php echo $wisata['fasilitas']; ?></td>
                                            <td><?php echo $wisata['harga']; ?></td>
                                            <td><?php echo $wisata['jarak']; ?></td>
                                            <td><?php echo $wisata['keamanan']; ?></td>
                                            <td><?php echo $nilai_akhir[$wisata['nama_wisata']]; ?></td>
                                            <td>
                                                <a href="edit.php?id=<?php echo $wisata['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                                                <a href="delete.php?id=<?php echo $wisata['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h3><b>Peta Lokasi Wisata</b></h3>
                        <div id="map"></div>
                    </div>
                </div>
                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var map = L.map('map').setView([-8.836956, 116.3525879], 11.27);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 18,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        var wisataData = <?php echo json_encode($wisata_data); ?>;
                        wisataData.forEach(function (wisata) {
                            var marker = L.marker([wisata.latitude, wisata.longitude]).addTo(map);
                            marker.bindPopup('<b>' + wisata.nama + '</b><br>Nilai SAW: ' + wisata.nilai_smart + '<br>Peringkat: ' + wisata.peringkat);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>
