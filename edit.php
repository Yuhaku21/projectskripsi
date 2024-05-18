<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM kriteria WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_wisata = $_POST['nama_wisata'];
    $keindahan = $_POST['keindahan'];
    $kebersihan = $_POST['kebersihan'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $jarak = $_POST['jarak'];
    $keamanan = $_POST['keamanan'];

    $sql = "UPDATE kriteria SET nama_wisata='$nama_wisata', keindahan='$keindahan', kebersihan='$kebersihan', fasilitas='$fasilitas', harga='$harga', jarak='$jarak', keamanan='$keamanan' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3><b>Edit Kriteria</b></h3>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="mb-3">
                        <label for="namaWisata" class="form-label">Nama Wisata</label>
                        <input type="text" class="form-control" id="namaWisata" name="nama_wisata" value="<?php echo $row['nama_wisata']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="keindahanwisata" class="form-label">Nilai Keindahan Wisata</label>
                        <input type="number" class="form-control" id="keindahanwisata" name="keindahan" value="<?php echo $row['keindahan']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="kebersihanwisata" class="form-label">Nilai Kebersihan Wisata</label>
                        <input type="number" class="form-control" id="kebersihanwisata" name="kebersihan" value="<?php echo $row['kebersihan']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="fasilitaswisata" class="form-label">Nilai Fasilitas Wisata</label>
                        <input type="number" class="form-control" id="fasilitaswisata" name="fasilitas" value="<?php echo $row['fasilitas']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="hargawisata" class="form-label">Nilai Harga Wisata</label>
                        <input type="number" class="form-control" id="hargawisata" name="harga" value="<?php echo $row['harga']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="jarakwisata" class="form-label">Nilai Jarak Wisata</label>
                        <input type="number" class="form-control" id="jarakwisata" name="jarak" value="<?php echo $row['jarak']; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="keamananwisata" class="form-label">Nilai Keamanan Wisata</label>
                        <input type="number" class="form-control" id="keamananwisata" name="keamanan" value="<?php echo $row['keamanan']; ?>" required />
                    </div>
                    <button type="submit" class="btn btn-success" name="update">Update Kriteria</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

