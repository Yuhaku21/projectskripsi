<?php
include 'db.php';

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data dari database berdasarkan ID
    $sql = "SELECT * FROM bobot_kriteria WHERE id='$id'";
    $result = $conn->query($sql);

    // Cek apakah data ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan!";
        exit;
    }
} else {
    echo "ID tidak ditemukan!";
    exit;
}

// Proses update data jika form disubmit
if (isset($_POST['submit'])) {
    $keindahan = $_POST['keindahan'];
    $kebersihan = $_POST['kebersihan'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $jarak = $_POST['jarak'];
    $keamanan = $_POST['keamanan'];

    $sql = "UPDATE bobot_kriteria SET 
                keindahan='$keindahan', 
                kebersihan='$kebersihan', 
                fasilitas='$fasilitas', 
                harga='$harga', 
                jarak='$jarak', 
                keamanan='$keamanan' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate!";
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bobot Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Edit Bobot Kriteria</b></h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="keindahan" class="form-label">Bobot Keindahan</label>
                        <input type="number" step="0.01" class="form-control" id="keindahan" name="keindahan" value="<?php echo isset($row['keindahan']) ? $row['keindahan'] : ''; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="kebersihan" class="form-label">Bobot Kebersihan</label>
                        <input type="number" step="0.01" class="form-control" id="kebersihan" name="kebersihan" value="<?php echo isset($row['kebersihan']) ? $row['kebersihan'] : ''; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Bobot Fasilitas</label>
                        <input type="number" step="0.01" class="form-control" id="fasilitas" name="fasilitas" value="<?php echo isset($row['fasilitas']) ? $row['fasilitas'] : ''; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Bobot Harga</label>
                        <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?php echo isset($row['harga']) ? $row['harga'] : ''; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="jarak" class="form-label">Bobot Jarak</label>
                        <input type="number" step="0.01" class="form-control" id="jarak" name="jarak" value="<?php echo isset($row['jarak']) ? $row['jarak'] : ''; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="keamanan" class="form-label">Bobot Keamanan</label>
                        <input type="number" step="0.01" class="form-control" id="keamanan" name="keamanan" value="<?php echo isset($row['keamanan']) ? $row['keamanan'] : ''; ?>" required />
                    </div>
                    <button type="submit" class="btn btn-success" name="submit">Update Bobot</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
