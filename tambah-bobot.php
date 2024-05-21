<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bobot Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Tambah Bobot Kriteria</b></h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="keindahan" class="form-label">Bobot Keindahan</label>
                        <input type="number" step="0.01" class="form-control" id="keindahan" name="keindahan" required />
                    </div>
                    <div class="mb-3">
                        <label for="kebersihan" class="form-label">Bobot Kebersihan</label>
                        <input type="number" step="0.01" class="form-control" id="kebersihan" name="kebersihan" required />
                    </div>
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Bobot Fasilitas</label>
                        <input type="number" step="0.01" class="form-control" id="fasilitas" name="fasilitas" required />
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Bobot Harga</label>
                        <input type="number" step="0.01" class="form-control" id="harga" name="harga" required />
                    </div>
                    <div class="mb-3">
                        <label for="jarak" class="form-label">Bobot Jarak</label>
                        <input type="number" step="0.01" class="form-control" id="jarak" name="jarak" required />
                    </div>
                    <div class="mb-3">
                        <label for="keamanan" class="form-label">Bobot Keamanan</label>
                        <input type="number" step="0.01" class="form-control" id="keamanan" name="keamanan" required />
                    </div>
                    <button type="submit" class="btn btn-success" name="submit">Tambah Bobot</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    include 'db.php';

    if (isset($_POST['submit'])) {
        $keindahan = $_POST['keindahan'];
        $kebersihan = $_POST['kebersihan'];
        $fasilitas = $_POST['fasilitas'];
        $harga = $_POST['harga'];
        $jarak = $_POST['jarak'];
        $keamanan = $_POST['keamanan'];

        // Hapus semua bobot kriteria sebelumnya
        $sql = "DELETE FROM bobot_kriteria";
        $conn->query($sql);

        // Tambahkan bobot kriteria baru
        $sql = "INSERT INTO bobot_kriteria (keindahan, kebersihan, fasilitas, harga, jarak, keamanan) VALUES ('$keindahan', '$kebersihan', '$fasilitas', '$harga', '$jarak', '$keamanan')";

        if ($conn->query($sql) === TRUE) {
            echo "Bobot kriteria berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
