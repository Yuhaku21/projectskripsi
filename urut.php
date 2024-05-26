<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hasil Perangkingan SAW</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container mt-4 mb-4">
      <div class="card">
        <div class="card-body">
          <h3><b>Tabel Hasil Perangkingan SAW</b></h3>
          <?php
          // Fungsi untuk menghitung nilai R untuk setiap kriteria
          function hitungNilaiR($dataWisata, $bobot) {
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
          function tampilkanHasilSAW($dataWisata, $bobot) {
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

          include 'db.php';

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

          // Menampilkan hasil SAW
          tampilkanHasilSAW($dataWisata, $bobot);
          ?>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
