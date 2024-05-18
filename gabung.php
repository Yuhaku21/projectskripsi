<?php
// Data dummy tempat wisata beserta nilai kriteria
$dataWisata = [
  ["nama" => "Selong Belanak", "C1" => 4, "C2" => 4, "C3" => 4, "C4" => 5, "C5" => 5, "C6" => 3],
  ["nama" => "Pantai Kuta", "C1" => 4, "C2" => 3, "C3" => 3, "C4" => 5, "C5" => 4, "C6" => 3],
  ["nama" => "Bukit Merese", "C1" => 5, "C2" => 4, "C3" => 3, "C4" => 3, "C5" => 5, "C6" => 4],
  ["nama" => "Pantai Smeti", "C1" => 5, "C2" => 5, "C3" => 2, "C4" => 3, "C5" => 4, "C6" => 2],
  ["nama" => "Pantai Tanjung Aan", "C1" => 4, "C2" => 3, "C3" => 4, "C4" => 3, "C5" => 4, "C6" => 4],
  ["nama" => "Desa Sade", "C1" => 5, "C2" => 4, "C3" => 4, "C4" => 1, "C5" => 3, "C6" => 4],
  ["nama" => "Pantai Mawun", "C1" => 5, "C2" => 5, "C3" => 5, "C4" => 3, "C5" => 5, "C6" => 4],
  ["nama" => "Pantai Mawi", "C1" => 4, "C2" => 3, "C3" => 2, "C4" => 5, "C5" => 5, "C6" => 3],
];

// Bobot untuk setiap kriteria
$bobot = [0.2, 0.15, 0.2, 0.2, 0.1, 0.15];

// Inisialisasi variabel untuk persentase
$persentase_harga = 0;
$persentase_fasilitas = 0;
$persentase_jarak = 0;
$persentase_rating = 0;

// Fungsi untuk menghitung nilai R untuk setiap kriteria
function hitungNilaiR($dataWisata, $bobot) {
  $R = [];
  foreach ($dataWisata as $wisata) {
    $R1 = $wisata['C1'] / max(array_column($dataWisata, 'C1'));
    $R2 = $wisata['C2'] / max(array_column($dataWisata, 'C2'));
    $R3 = $wisata['C3'] / max(array_column($dataWisata, 'C3'));
    $R4 = min(array_column($dataWisata, 'C4')) / $wisata['C4'];
    $R5 = min(array_column($dataWisata, 'C5')) / $wisata['C5'];
    $R6 = $wisata['C6'] / max(array_column($dataWisata, 'C6'));

    // Menghitung nilai total dengan bobot terponderasi
    $total = $R1 * $bobot[0] + $R2 * $bobot[1] + $R3 * $bobot[2] + $R4 * $bobot[3] + $R5 * $bobot[4] + $R6 * $bobot[5];

    $R[] = ["nama" => $wisata["nama"], "nilai" => $total];
  }

  // Mengurutkan nilai total dari yang tertinggi ke terendah
  usort($R, function ($a, $b) {
    return $b["nilai"] - $a["nilai"];
  });

  return $R;
}

// Menampilkan hasil perangkingan SAW
function tampilkanHasilSAW($dataWisata, $bobot) {
  global $persentase_harga, $persentase_fasilitas, $persentase_jarak, $persentase_rating;
  echo "Hasil Perangkingan SAW:\n";
  echo "Ranking\tKode Wisata\tNama Tempat Wisata\tHasil\n";
  $R = hitungNilaiR($dataWisata, $bobot);
  foreach ($R as $index => $hasil) {
    echo ($index + 1) . "\tKW00" . ($index + 1) . "\t" . $hasil["nama"] . "\t" . number_format($hasil["nilai"], 2) . "\n";
}
}

// Memanggil fungsi untuk menampilkan hasil perangkingan SAW
tampilkanHasilSAW($dataWisata, $bobot);

// Definisi data untuk NCF dan NSF untuk setiap aspek
$harga_ncf = ["KW001" => 4, "KW002" => -1];
$harga_nsf = ["KW001" => 4.25, "KW002" => 0.33];

$fasilitas_ncf = ["KW001" => -0.33, "KW002" => 3.33];
$fasilitas_nsf = ["KW001" => 0, "KW002" => 3];

$jarak_ncf = ["KW001" => 2.16, "KW002" => 2.83];
$jarak_nsf = ["KW001" => 3.75, "KW002" => 3.75];

$rating_ncf = ["KW001" => 3.16, "KW002" => 3.66];
$rating_nsf = ["KW001" => 4.25, "KW002" => 3.75];

// Inisialisasi variabel persentase
$persentase_harga = 0.4;
$persentase_fasilitas = 0.4;
$persentase_jarak = 0.1;
$persentase_rating = 0.1;

// Fungsi untuk perhitungan nilai total dari setiap aspek
function hitungNilaiTotalAspek($ncf, $nsf) {
$total = [];
foreach ($ncf as $k => $v) {
  $total[$k] = $ncf[$k] * 0.6 + $nsf[$k] * 0.4;
}
return $total;
}

// Perhitungan nilai total untuk setiap aspek
$harga_total = hitungNilaiTotalAspek($harga_ncf, $harga_nsf);
$fasilitas_total = hitungNilaiTotalAspek($fasilitas_ncf, $fasilitas_nsf);
$jarak_total = hitungNilaiTotalAspek($jarak_ncf, $jarak_nsf);
$rating_total = hitungNilaiTotalAspek($rating_ncf, $rating_nsf);

// Perangkingan tempat wisata
$ranking = [];

foreach ($harga_total as $k => $v) {
$total = $persentase_harga * $harga_total[$k] + $persentase_fasilitas * $fasilitas_total[$k] + $persentase_jarak * $jarak_total[$k] + $persentase_rating * $rating_total[$k];
$ranking[$k] = $total;
}

// Mengambil tempat wisata teratas dari hasil perangkingan SAW
$topPlace = array_keys($ranking, max($ranking))[0];

// Fungsi Profile Matching untuk satu tempat wisata
function profileMatching($place, $harga_ncf, $harga_nsf, $fasilitas_ncf, $fasilitas_nsf, $jarak_ncf, $jarak_nsf, $rating_ncf, $rating_nsf, $persentase_harga, $persentase_fasilitas, $persentase_jarak, $persentase_rating) {
$hargaScore = $harga_ncf[$place] * 0.6 + $harga_nsf[$place] * 0.4;
$fasilitasScore = $fasilitas_ncf[$place] * 0.6 + $fasilitas_nsf[$place] * 0.4;
$jarakScore = $jarak_ncf[$place] * 0.6 + $jarak_nsf[$place] * 0.4;
$ratingScore = $rating_ncf[$place] * 0.6 + $rating_nsf[$place] * 0.4;

$totalScore = $persentase_harga * $hargaScore + $persentase_fasilitas * $fasilitasScore + $persentase_jarak * $jarakScore + $persentase_rating * $ratingScore;

return $totalScore;
}

// Menampilkan hasil Profile Matching untuk satu tempat wisata teratas dari SAW
echo "\nHasil Profile Matching untuk Tempat Wisata Teratas:\n";
echo "\nTempat Wisata: " . $topPlace . "\n";
echo "Hasil Profile Matching: " . number_format(profileMatching($topPlace, $harga_ncf, $harga_nsf, $fasilitas_ncf, $fasilitas_nsf, $jarak_ncf, $jarak_nsf, $rating_ncf, $rating_nsf, $persentase_harga, $persentase_fasilitas, $persentase_jarak, $persentase_rating), 2) . "\n";
?>
