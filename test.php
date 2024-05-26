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

// Membuat array peringkat
$peringkat = [];
$rank = 1;
foreach ($nilai_akhir as $nama_wisata => $nilai) {
    $peringkat[$nama_wisata] = $rank;
    $rank++;
}

// Mengambil data wisata beserta koordinatnya dari database
$sql = "SELECT id, nama, latitude, longitude FROM sig";
$result = $conn->query($sql);
$wisata_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nama_wisata = $row['nama'];
        $row['nilai_smart'] = isset($nilai_akhir[$nama_wisata]) ? $nilai_akhir[$nama_wisata] : 0;
        $row['peringkat'] = isset($peringkat[$nama_wisata]) ? $peringkat[$nama_wisata] : 0;
        $wisata_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Metode SMART</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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

        #map {
            width: 100%;
            height: 500px;
        }

        .custom-icon i {
            font-size: 24px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-center">Halo Admin !</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="msaw.php">Metode SAW</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="msmart.php">Metode SMART</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tambahdata.php">Input Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="metaan.php">Metaan SIG</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">Tentang Kami</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h2>Rekomendasi Wisata Berdasarkan SMART</h2>
        <div id="map"></div>

        <h4>Hasil Perhitungan SMART:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Wisata</th>
                    <th>Nilai SMART</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wisata_data as $wisata): ?>
                <tr>
                    <td><?php echo $wisata['peringkat']; ?></td>
                    <td><?php echo $wisata['nama']; ?></td>
                    <td><?php echo $wisata['nilai_smart']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-8.836956, 116.3525879], 11.27); // Koordinat awal

        // Tambahkan layer tile dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker untuk setiap wisata dengan nilai SMART
        <?php foreach ($wisata_data as $wisata): ?>
            <?php
            $peringkat_wisata = $wisata['peringkat'];
            $markerColor = '';
            if ($peringkat_wisata === 1) {
                $markerColor = 'green'; // Marker warna hijau untuk peringkat 1
            } elseif ($peringkat_wisata === 2) {
                $markerColor = 'blue'; // Marker warna biru untuk peringkat 2
            } elseif ($peringkat_wisata === 3) {
                $markerColor = 'red'; // Marker warna merah untuk peringkat 3
            } else {
                $markerColor = 'gray'; // Marker warna abu-abu untuk peringkat lainnya
            }
            ?>
            L.marker([<?php echo $wisata['latitude']; ?>, <?php echo $wisata['longitude']; ?>], { icon: L.divIcon({ className: 'custom-icon', html: '<i class="fas fa-map-marker-alt" style="color: <?php echo $markerColor; ?>; font-size: 32px;"></i>' }) })
                .bindPopup("<b><?php echo $wisata['nama']; ?></b><br>Nilai SMART: <?php echo $wisata['nilai_smart']; ?>")
                .addTo(map);
        <?php endforeach; ?>
    </script>
</body>

</html>
