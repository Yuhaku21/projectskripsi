<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <div class="content">
        <div class="container">
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3><b>Edit Kriteria</b></h3>

                        <?php
                        include 'db.php';

                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];

                            $sql = "SELECT * FROM kriteria WHERE id = '$id'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $data = $result->fetch_assoc();
                            } else {
                                echo "Data tidak ditemukan!";
                                exit;
                            }
                        }

                        if (isset($_POST['update'])) {
                            $nama_wisata = $_POST['nama_wisata'];
                            $keindahan = $_POST['keindahan'];
                            $kebersihan = $_POST['kebersihan'];
                            $fasilitas = $_POST['fasilitas'];
                            $harga = $_POST['harga'];
                            $jarak = $_POST['jarak'];
                            $keamanan = $_POST['keamanan'];

                            $sql = "UPDATE kriteria SET nama_wisata='$nama_wisata', keindahan='$keindahan', kebersihan='$kebersihan', fasilitas='$fasilitas', harga='$harga', jarak='$jarak', keamanan='$keamanan' WHERE id='$id'";

                            if ($conn->query($sql) === TRUE) {
                                echo "Data berhasil diupdate!";
                                header('Location: sawrevisi.php');
                                exit;
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        }
                        ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="namaWisata" class="form-label">Pilih tempat wisata anda</label>
                                <select class="form-select" name="nama_wisata" aria-label="Default select example">
                                    <option value="Desa Sade" <?= $data['nama_wisata'] == 'Desa Sade' ? 'selected' : '' ?>>Desa Sade</option>
                                    <option value="Pantai Mawun" <?= $data['nama_wisata'] == 'Pantai Mawun' ? 'selected' : '' ?>>Pantai Mawun</option>
                                    <option value="Selong Belanak" <?= $data['nama_wisata'] == 'Selong Belanak' ? 'selected' : '' ?>>Selong Belanak</option>
                                    <option value="Pantai Tanjung Aan" <?= $data['nama_wisata'] == 'Pantai Tanjung Aan' ? 'selected' : '' ?>>Pantai Tanjung Aan</option>
                                    <option value="Pantai Kuta" <?= $data['nama_wisata'] == 'Pantai Kuta' ? 'selected' : '' ?>>Pantai Kuta</option>
                                    <option value="Pantai Mawi" <?= $data['nama_wisata'] == 'Pantai Mawi' ? 'selected' : '' ?>>Pantai Mawi</option>
                                    <option value="Bukit Merese" <?= $data['nama_wisata'] == 'Bukit Merese' ? 'selected' : '' ?>>Bukit Merese</option>
                                    <option value="Pantai Semeti" <?= $data['nama_wisata'] == 'Pantai Semeti' ? 'selected' : '' ?>>Pantai Semeti</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keindahanwisata" class="form-label">Masukkan Nilai Keindahan Wisata</label>
                                <select class="form-select" name="keindahan" aria-label="Default select example">
                                    <option value="1" <?= $data['keindahan'] == 1 ? 'selected' : '' ?>>Sangat tidak Indah</option>
                                    <option value="2" <?= $data['keindahan'] == 2 ? 'selected' : '' ?>>Tidak indah</option>
                                    <option value="3" <?= $data['keindahan'] == 3 ? 'selected' : '' ?>>Cukup Indah</option>
                                    <option value="4" <?= $data['keindahan'] == 4 ? 'selected' : '' ?>>Indah</option>
                                    <option value="5" <?= $data['keindahan'] == 5 ? 'selected' : '' ?>>Sangat Indah</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kebersihanwisata" class="form-label">Masukkan Nilai Kebersihan Wisata</label>
                                <select class="form-select" name="kebersihan" aria-label="Default select example">
                                    <option value="1" <?= $data['kebersihan'] == 1 ? 'selected' : '' ?>>Sangat tidak bersih</option>
                                    <option value="2" <?= $data['kebersihan'] == 2 ? 'selected' : '' ?>>Tidak bersih</option>
                                    <option value="3" <?= $data['kebersihan'] == 3 ? 'selected' : '' ?>>Cukup bersih</option>
                                    <option value="4" <?= $data['kebersihan'] == 4 ? 'selected' : '' ?>>Bersih</option>
                                    <option value="5" <?= $data['kebersihan'] == 5 ? 'selected' : '' ?>>Sangat Bersih</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fasilitaswisata" class="form-label">Masukkan Nilai Fasilitas Wisata</label>
                                <select class="form-select" name="fasilitas" aria-label="Default select example">
                                    <option value="1" <?= $data['fasilitas'] == 1 ? 'selected' : '' ?>>Sangat tidak lengkap</option>
                                    <option value="2" <?= $data['fasilitas'] == 2 ? 'selected' : '' ?>>Tidak lengkap</option>
                                    <option value="3" <?= $data['fasilitas'] == 3 ? 'selected' : '' ?>>Cukup lengkap</option>
                                    <option value="4" <?= $data['fasilitas'] == 4 ? 'selected' : '' ?>>Lengkap</option>
                                    <option value="5" <?= $data['fasilitas'] == 5 ? 'selected' : '' ?>>Sangat Lengkap</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hargawisata" class="form-label">Masukkan Nilai Harga Wisata</label>
                                <select class="form-select" name="harga" aria-label="Default select example">
                                    <option value="1" <?= $data['harga'] == 1 ? 'selected' : '' ?>>Sangat murah</option>
                                    <option value="2" <?= $data['harga'] == 2 ? 'selected' : '' ?>>Murah</option>
                                    <option value="3" <?= $data['harga'] == 3 ? 'selected' : '' ?>>Cukup murah</option>
                                    <option value="4" <?= $data['harga'] == 4 ? 'selected' : '' ?>>Mahal</option>
                                    <option value="5" <?= $data['harga'] == 5 ? 'selected' : '' ?>>Sangat mahal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jarakwisata" class="form-label">Masukkan Nilai Jarak Wisata</label>
                                <select class="form-select" name="jarak" aria-label="Default select example">
                                    <option value="1" <?= $data['jarak'] == 1 ? 'selected' : '' ?>>Sangat dekat</option>
                                    <option value="2" <?= $data['jarak'] == 2 ? 'selected' : '' ?>>Dekat</option>
                                    <option value="3" <?= $data['jarak'] == 3 ? 'selected' : '' ?>>Cukup dekat</option>
                                    <option value="4" <?= $data['jarak'] == 4 ? 'selected' : '' ?>>Jauh</option>
                                    <option value="5" <?= $data['jarak'] == 5 ? 'selected' : '' ?>>Sangat jauh</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keamananwisata" class="form-label">Masukkan Nilai Keamanan Wisata</label>
                                <select class="form-select" name="keamanan" aria-label="Default select example">
                                    <option value="1" <?= $data['keamanan'] == 1 ? 'selected' : '' ?>>Sangat tidak aman</option>
                                    <option value="2" <?= $data['keamanan'] == 2 ? 'selected' : '' ?>>Tidak aman</option>
                                    <option value="3" <?= $data['keamanan'] == 3 ? 'selected' : '' ?>>Cukup aman</option>
                                    <option value="4" <?= $data['keamanan'] == 4 ? 'selected' : '' ?>>Aman</option>
                                    <option value="5" <?= $data['keamanan'] == 5 ? 'selected' : '' ?>>Sangat aman</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update">Update Kriteria</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
