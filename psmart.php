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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                            <li><a class="dropdown-item" href="psaw.php">Berdasarkan SAW</a></li>
                            <li><a class="dropdown-item" href="#">Berdasarkan SMART</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rekomendasi-wisata.php">Rekomendasi</a>
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
        <h2 class="mb-3"><b>Rekomendasi Wisata Berdasarkan SMART</b></h2>
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
        <!--Peta wisata-->

        <!-- Tabel Ranking Wisata -->
        <div class="row mt-4">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="mb-3"><b>Ranking Wisata Berdasarkan SMART</b></h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>Nama Wisata</th>
                                    <th>Nilai SMART</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nilai_akhir as $nama_wisata => $nilai) : ?>
                                    <tr>
                                        <td><?php echo $peringkat[$nama_wisata]; ?></td>
                                        <td><?php echo $nama_wisata; ?></td>
                                        <td><?php echo round($nilai, 4); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabel Ranking Wisata -->
    </div>
    <!--Footer-->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-8.836956, 116.3525879], 11.27); // Koordinat awal

        // Tambahkan layer tile dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker untuk setiap wisata dengan nilai SMART
        <?php foreach ($wisata_data as $wisata) : ?>
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
            L.marker([<?php echo $wisata['latitude']; ?>, <?php echo $wisata['longitude']; ?>], {
                    icon: L.divIcon({
                        className: 'custom-icon',
                        html: '<i class="fas fa-map-marker-alt" style="color: <?php echo $markerColor; ?>; font-size: 32px;"></i>'
                    })
                })
                .bindPopup("<b><?php echo $wisata['nama']; ?></b><br>Nilai SMART: <?php echo $wisata['nilai_smart']; ?>")
                .addTo(map);
        <?php endforeach; ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

