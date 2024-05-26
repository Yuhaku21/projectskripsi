<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Informasi Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
      .horizontal-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }

      .horizontal-cards .card {
        margin: 10px;
        width: 200px; /* Lebar kartu */
      }

      #map {
        height: 400px;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand" href="#"
          ><b>Wisata<span style="color: purple">Loteng</span></b></a
        >
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="home.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Informasi Wisata</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="rekomendasi-wisata.html">Rekomendasi</a>
            </li>
            <div class="spacer" style="margin-right: 30px"></div>
            <li class="nav-item">
              <a class="btn btn-success" href="index.html">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!--Navbar-->
    <!--Informasi Wisata-->
    <div class="container mt-5 justify-content-center mb-5">
      <h1 class="text-center">Informasi <span style="color: green">Wisata</span></h1>
      <p class="text-center">Yuk jelajahi tempat wisata yang ada di Lombok Tengah</p>
      <!--Peta wisata-->
      <div id="map" class="mb-5"></div>
      <!--Peta wisata-->
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        var map = L.map('map').setView([-8.7979955, 116.2082728], 11.17); // Set View di Lombok Tengah

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
                .bindPopup(`<b>${wisata.nama}</b><br>Latitude: ${wisata.latitude}<br>Longitude: ${wisata.longitude}`)
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
