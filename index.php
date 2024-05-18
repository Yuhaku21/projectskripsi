<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>

<body>
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

    <!--Tabel Hasil Perangkingan SAW-->
    <div class="container mt-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Tabel Hasil Perangkingan SAW</b></h3>
                <?php
                // Bobot untuk setiap kriteria
                $bobot = [0.2, 0.15, 0.2, 0.2, 0.1, 0.15];

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
                    echo "<h5>Ini dia hasil perangkingan menggunakan metode SAW (Simple Addictive Weight) ✨</h5>";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>