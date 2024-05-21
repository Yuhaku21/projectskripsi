<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
            height: 400px;
            width: 600px;
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
                <a class="nav-link" href="msaw.php">Perhitungan SAW</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="msmart.php">Perhitungan SMART</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pemetaan Peta SIG</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login-admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>

    <div class="content">
    <div class="container mt-5">
      <div class="row">
        <div class="col">
        <div class="container">
        <div class="card">
            <div class="card-body">
            <h3 class="mt-4"><b>Tambah Wisata</b></h3>
        <form action="tambah_wisata.php" method="post">
            <div class="form-group">
                <label for="nama">Nama Wisata:</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude:</label>
                <input type="text" class="form-control" id="latitude" name="latitude" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude:</label>
                <input type="text" class="form-control" id="longitude" name="longitude" required>
            </div>
            <button type="submit" class="btn btn-success">Tambah Wisata</button>
        </form>
        </div>
        </div>
       </div>
        </div>
        <div class="col"><div id="map"></div></div>
      </div>
    </div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var map = L.map('map').setView([-8.7979955,116.2082728], 11.17); // Set View di Lombok Tengah

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Fetch wisata data from PHP
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "wisata";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $sql = "SELECT id, nama, latitude, longitude FROM sig";
        $result = $conn->query($sql);

        $wisata_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $wisata_data[] = $row;
            }
        }

        $conn->close();
        ?>

        var wisataData = <?php echo json_encode($wisata_data); ?>;

        wisataData.forEach(function(wisata) {
            var marker = L.marker([wisata.latitude, wisata.longitude])
                .bindPopup(`<b>${wisata.nama}</b><br>Latitude: ${wisata.latitude}<br>Longitude: ${wisata.longitude}<br><button onclick="hapusWisata(${wisata.id})" class='btn btn-danger btn-sm mt-2'>Hapus</button>`)
                .addTo(map);
        });

        function hapusWisata(id) {
            if(confirm("Apakah Anda yakin ingin menghapus wisata ini?")) {
                window.location.href = 'hapus_wisata.php?id=' + id;
            }
        }
    </script>
</body>

</html>