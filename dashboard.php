<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
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
    </style>
  </head>
  <body>
    <div class="sidebar d-flex flex-column p-3">
      <h4 class="text-center">Halo Admin !</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="#">Dashboard</a>
        </li>  
        <li class="nav-item">
          <a class="nav-link" href="msaw.php">Perhitungan SAW</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="msmart.php">Perhitungan SMART</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sig.php">Pemetaan Peta SIG</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login-admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
      </ul>
    </div>

    <div class="content">
          <div class="container">
            <!--Section Dashboard-->
            <div class="card">
                <div class="card-body">
                    <h1><b>Halaman Dashboard Admin</b></h1>
                    <p>Manage By Admin</p>
                </div>
            </div>
            <!--Section Dashboard-->
            <!--Section Metode SAW-->
            <div class="card mt-5">
                <div class="card-body">
                <h3><b>Metode Simple Addictive Weight (SAW)</b></h3>
                <p>Metode SAW (Simple Additive Weighting) adalah salah satu metode dalam pengambilan keputusan multikriteria (MCDM - Multi-Criteria Decision Making). Metode ini sering digunakan untuk mengevaluasi dan memilih alternatif terbaik berdasarkan beberapa kriteria yang telah ditentukan. </p>
                <p>Penelitian ini menggunakan beberapa bobot yang sudah ditentukan, antara lain</p>
                <p><b>C1 = 20%, C2 = 15%, C3 = 20%, C4 = 20%, C5 = 10%, C6 =15 %</b></p>
                <p>Penelitian ini menggunakan beberapa kriteri, seperti:</p>
                <ul>
                    <li><p>Keindahan <b>(Benefit)</b></p></li>
                    <li><p>Kebersihan <b>(Benefit)</b></p></li>
                    <li><p>Fasilitas <b>(Benefit)</b></p></li>
                    <li><p>Harga <b>(Cost)</b></p></li>
                    <li><p>Jarak <b>(Cost)</b></p></li>
                    <li><p>Keamanan <b>(Benefit)</b></p></li>
                </ul>
                <p>Kriteria <b>keindahan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak indah</b></li>
                    <li>2 <b>Tidak indah</b></li>
                    <li>3 <b>Cukup indah</b></li>
                    <li>4 <b>Indah</b></li>
                    <li>5 <b>Sangat indah</b></li>
                </ul>
                <p>Kriteria <b>kebersihan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak bersih</b></li>
                    <li>2 <b>Tidak bersih</b></li>
                    <li>3 <b>Cukup bersih</b></li>
                    <li>4 <b>Bersih</b></li>
                    <li>5 <b>Sangat bersih</b></li>
                </ul>
                <p>Kriteria <b> fasilitas</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak lengkap</b></li>
                    <li>2 <b>Tidak lengkap</b></li>
                    <li>3 <b>Cukup lengkap</b></li>
                    <li>4 <b>lengkap</b></li>
                    <li>5 <b>Sangat lengkap</b></li>
                </ul>
                <p>Kriteria <b>harga</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat Murah</b></li>
                    <li>2 <b>Murah</b></li>
                    <li>3 <b>Cukup Murah</b></li>
                    <li>4 <b>Mahal</b></li>
                    <li>5 <b>Sangat mahal</b></li>
                </ul>
                <p>Kriteria <b>jarak</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat dekat</b></li>
                    <li>2 <b>Dekat</b></li>
                    <li>3 <b>Cukup dekat</b></li>
                    <li>4 <b>Tida dekat</b></li>
                    <li>5 <b>Sangat tidak dekat</b></li>
                </ul>
                <p>Kriteria <b>keamanan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak aman</b></li>
                    <li>2 <b>Tidak aman</b></li>
                    <li>3 <b>Cukup aman</b></li>
                    <li>4 <b>Aman</b></li>
                    <li>5 <b>Sangat aman</b></li>
                </ul>
                </div>
            </div>
            <!--Section Metode SAW-->
            <!--Section Metode SMART-->
            <div class="card mt-5">
                <div class="card-body">
                <h3><b>Metode Simple Multi Attribut Rating Technique</b></h3>
                <p>Metode SMART (Simple Multi-Attribute Rating Technique) adalah salah satu teknik dalam pengambilan keputusan multikriteria yang digunakan untuk menilai dan memilih alternatif terbaik berdasarkan beberapa kriteria.</p>
                <p>Penelitian ini menggunakan beberapa bobot yang sudah ditentukan, antara lain</p>
                <p><b>C1 = 20%, C2 = 15%, C3 = 20%, C4 = 20%, C5 = 10%, C6 =15 %</b></p>
                <p>Penelitian ini menggunakan beberapa kriteri, seperti:</p>
                <ul>
                    <li><p>Keindahan <b>(Benefit)</b></p></li>
                    <li><p>Kebersihan <b>(Benefit)</b></p></li>
                    <li><p>Fasilitas <b>(Benefit)</b></p></li>
                    <li><p>Harga <b>(Cost)</b></p></li>
                    <li><p>Jarak <b>(Cost)</b></p></li>
                    <li><p>Keamanan <b>(Benefit)</b></p></li>
                </ul>
                <p>Kriteria <b>keindahan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak indah</b></li>
                    <li>2 <b>Tidak indah</b></li>
                    <li>3 <b>Cukup indah</b></li>
                    <li>4 <b>Indah</b></li>
                    <li>5 <b>Sangat indah</b></li>
                </ul>
                <p>Kriteria <b>kebersihan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak bersih</b></li>
                    <li>2 <b>Tidak bersih</b></li>
                    <li>3 <b>Cukup bersih</b></li>
                    <li>4 <b>Bersih</b></li>
                    <li>5 <b>Sangat bersih</b></li>
                </ul>
                <p>Kriteria <b> fasilitas</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak lengkap</b></li>
                    <li>2 <b>Tidak lengkap</b></li>
                    <li>3 <b>Cukup lengkap</b></li>
                    <li>4 <b>lengkap</b></li>
                    <li>5 <b>Sangat lengkap</b></li>
                </ul>
                <p>Kriteria <b>harga</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat Murah</b></li>
                    <li>2 <b>Murah</b></li>
                    <li>3 <b>Cukup Murah</b></li>
                    <li>4 <b>Mahal</b></li>
                    <li>5 <b>Sangat mahal</b></li>
                </ul>
                <p>Kriteria <b>jarak</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat dekat</b></li>
                    <li>2 <b>Dekat</b></li>
                    <li>3 <b>Cukup dekat</b></li>
                    <li>4 <b>Tida dekat</b></li>
                    <li>5 <b>Sangat tidak dekat</b></li>
                </ul>
                <p>Kriteria <b>keamanan</b> memiliki nilai parameter dari 1 sampai dengan 5, berikut penjelasannya</p>
                <ul>
                    <li>1 <b>Sangat tidak aman</b></li>
                    <li>2 <b>Tidak aman</b></li>
                    <li>3 <b>Cukup aman</b></li>
                    <li>4 <b>Aman</b></li>
                    <li>5 <b>Sangat aman</b></li>
                </ul>
                </div>
            </div>
            <!--Section Metode SMART-->
          </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
