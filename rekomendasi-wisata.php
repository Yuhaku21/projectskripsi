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
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $dataWisata[] = $row;
  }
}

// Mengambil bobot dari database
$sql = "SELECT * FROM bobot_kriteria_smart";
$result = $conn->query($sql);
$bobot = [];
if ($result && $result->num_rows > 0) {
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
  <title>Rekomendasi Wisata</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <!--Navbar-->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
                        <a class="nav-link" href="#">Rekomendasi</a>
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

  <!--Section Rekomendasi Wisata-->
  <div class="container mt-4 mb-4">
    <div class="card">
      <div class="card-body">
        <h3><b>Rekomendasi Wisata Berdasarkan SAW</b></h3>
        <?php
        include 'db.php';

        // Mengambil data dari database
        $sql = "SELECT * FROM kriteria";
        $result = $conn->query($sql);
        $dataWisata = [];
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $dataWisata[] = $row;
          }
        }

        // Mengambil bobot dari database
        $sql = "SELECT * FROM bobot_kriteria";
        $result = $conn->query($sql);
        $bobot = [];
        if ($result && $result->num_rows > 0) {
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
  <!--Section Rekomendasi Wisata-->
  <!--Section Rekomendasi Wisata-->
  <!--Menampilkan hasil akhir perhitungan SMART-->
  <div class="container mt-5 mb-4">
    <div class="card">
      <div class="card-body">
        <h3><b>Ranking Hasil Perhitungan SMART</b></h3>
        <h5>Hasil perangkingan menggunakan metode SMART (Simple Multi Attribute Rating Technique)</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Ranking</th>
              <th>Nama Wisata</th>
              <th>Nilai Akhir</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rank = 1;
            foreach ($nilai_akhir as $nama_wisata => $nilai) :
            ?>
              <tr>
                <td><?= $rank++ ?></td>
                <td><?= $nama_wisata ?></td>
                <td><?= $nilai ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--Section Rekomendasi Wisata-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="peta.js"></script>
  <script src="ranking.js"></script>
</body>

</html>
