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
        $total = $R1 * $bobot['keindahan'] + $R2 * $bobot['kebersihan'] + $R3 * $bobot['fasilitas'] + $R4 * $bobot['harga'] + $R5 * $bobot['jarak'] + $R6 * $bobot['keamanan'];

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
    <title>Metode SAW</title>
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
        <h2>Rekomendasi Wisata Berdasarkan SAW</h2>
        <div id="map"></div>

        <h4>Hasil Perhitungan SAW:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Wisata</th>
                    <th>Nilai SAW</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ranking = 1;
                foreach ($R as $row) {
                    echo "<tr>
                            <td>" . $ranking++ . "</td>
                            <td>" . $row['nama'] . "</td>
                            <td>" . number_format($row['nilai'], 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.836956, 116.3525879], 11.27);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var wisata_data = <?php echo json_encode($wisata_data); ?>;

        // Tambahkan marker untuk peringkat 1, 2, dan 3 dengan warna yang berbeda
        wisata_data.forEach(function(wisata) {
            var markerColor;
            if (wisata.peringkat === 1) {
                markerColor = 'green';
            } else if (wisata.peringkat === 2) {
                markerColor = 'blue';
            } else if (wisata.peringkat === 3) {
                markerColor = 'red';
            } else {
                markerColor = 'gray';
            }

            var icon = L.divIcon({
                className: 'custom-icon',
                html: '<i class="fas fa-map-marker-alt" style="color: ' + markerColor + '; font-size: 32px;"></i>'
            });

            L.marker([wisata.latitude, wisata.longitude], { icon: icon }).addTo(map)
                .bindPopup('<b>' + wisata.nama + '</b><br>Nilai SAW: ' + wisata.nilai_smart);
        });
    </script>
</body>

</html>
