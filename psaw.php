<?php
include 'db.php';

// Memastikan koneksi ke database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $nama_wisata = $conn->real_escape_string($_POST['nama_wisata']);
    $keindahan = (float) $_POST['keindahan'];
    $kebersihan = (float) $_POST['kebersihan'];
    $fasilitas = (float) $_POST['fasilitas'];
    $harga = (float) $_POST['harga'];
    $jarak = (float) $_POST['jarak'];
    $keamanan = (float) $_POST['keamanan'];

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
        $row['nilai_smart'] = isset($nilai_akhir[$nama_wisata]) ? $nilai_akhir[$nama_wisata] : 0;
        $row['peringkat'] = isset($peringkat[$nama_wisata]) ? $peringkat[$nama_wisata] : 0;
        $wisata_data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Informasi Wisata</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <style>
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            bottom: 0;
            height: 50px;
        }

        #map {
            width: 80%;
            height: 400px;
        }
    </style>
</head>

<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding-bottom: 30px; padding-top: 30px">
        <div class="container">
            <a class="navbar-brand" href="#"><b>Wisata<span style="color: purple">Loteng</span></b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.html">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Informasi SIG
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Berdasarkan SAW</a></li>
                            <li><a class="dropdown-item" href="psmart.php">Berdasarkan SMART</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rekomendasi.html">Rekomendasi</a>
                    </li>
                    <div class="spacer" style="margin-right: 10px; margin-top: 10px"></div>
                    <li class="nav-item">
                        <a class="btn btn-outline-success" href="index.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--Navbar-->
    <!--Informasi Wisata-->
    <div class="container mt-5 mb-5">
        <!--Peta wisata-->
        <h2 class="mb-3"><b>Rekomendasi Wisata Berdasarkan SAW</b></h2>
        <div class="row mt-4">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        <p><b>Keterangan</b></p>
                        <ul>
                            <li><b>Marker <span style="color: green;">Hijau</span></b>: Menandakan lokasi wisata ranking 1</li>
                            <li><b>Marker <span style="color: blue;">Biru</span></b>: Menandakan lokasi wisata ranking 2</li>
                            <li><b>Marker <span style="color: red;">Merah</span></b>: Menandakan lokasi wisata ranking 3</li>
                            <li><b>Marker <span style="color: gray;">Abu Abu</span></b>: Menandakan lokasi wisata ranking dibawah 3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="map" style="border-radius: 20px;" class="shadow"></div>
        </div>
        <div class="row mt-4">
           <div class="col">
            <div class="card">
                <div class="card-body">
                <h4><b>Ranking Wisata Berdasarkan SAW</b></h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama Wisata</th>
                        <th>Nilai SAW</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($R as $index => $row) {
                        echo "<tr>";
                        echo "<td>" . ($index + 1) . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['nilai'] . "</td>";
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
    <!--Footer-->
    <div class="footer">
        <p>&copy; 2023 Wisata Loteng</p>
    </div>
    <!--Footer-->
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

            L.marker([wisata.latitude, wisata.longitude], {
                    icon: icon
                }).addTo(map)
                .bindPopup('<b>' + wisata.nama + '</b><br>Nilai SAW: ' + Number(wisata.nilai_smart).toFixed(2));
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

