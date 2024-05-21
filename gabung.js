// Data dummy tempat wisata beserta nilai kriteria
var dataWisata = [
  { nama: "Selong Belanak", C1: 4, C2: 4, C3: 4, C4: 5, C5: 5, C6: 3 },
  { nama: "Pantai Kuta", C1: 4, C2: 3, C3: 3, C4: 5, C5: 4, C6: 3 },
  { nama: "Bukit Merese", C1: 5, C2: 4, C3: 3, C4: 3, C5: 5, C6: 4 },
  { nama: "Pantai Smeti", C1: 5, C2: 5, C3: 2, C4: 3, C5: 4, C6: 2 },
  { nama: "Pantai Tanjung Aan", C1: 4, C2: 3, C3: 4, C4: 3, C5: 4, C6: 4 },
  { nama: "Desa Sade", C1: 5, C2: 4, C3: 4, C4: 1, C5: 3, C6: 4 },
  { nama: "Pantai Mawun", C1: 5, C2: 5, C3: 5, C4: 3, C5: 5, C6: 4 },
  { nama: "Pantai Mawi", C1: 4, C2: 3, C3: 2, C4: 5, C5: 5, C6: 3 },
];

// Bobot untuk setiap kriteria
var bobot = [0.2, 0.15, 0.2, 0.2, 0.1, 0.15];

// Fungsi untuk menghitung nilai R untuk setiap kriteria
function hitungNilaiR() {
  var R = [];
  dataWisata.forEach(function (wisata) {
    var R1 =
      wisata.C1 /
      Math.max.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C1;
        })
      );
    var R2 =
      wisata.C2 /
      Math.max.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C2;
        })
      );
    var R3 =
      wisata.C3 /
      Math.max.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C3;
        })
      );
    var R4 =
      Math.min.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C4;
        })
      ) / wisata.C4;
    var R5 =
      Math.min.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C5;
        })
      ) / wisata.C5;
    var R6 =
      wisata.C6 /
      Math.max.apply(
        Math,
        dataWisata.map(function (w) {
          return w.C6;
        })
      );

    // Menghitung nilai total dengan bobot terponderasi
    var total = R1 * bobot[0] + R2 * bobot[1] + R3 * bobot[2] + R4 * bobot[3] + R5 * bobot[4] + R6 * bobot[5];

    R.push({ nama: wisata.nama, nilai: total });
  });

  // Mengurutkan nilai total dari yang tertinggi ke terendah
  R.sort(function (a, b) {
    return b.nilai - a.nilai;
  });

  return R;
}

// Menampilkan hasil perangkingan SAW
function tampilkanHasilSAW() {
  console.log("Hasil Perangkingan SAW:");
  console.log("Ranking\tKode Wisata\tNama Tempat Wisata\tHasil");
  let R = hitungNilaiR();
  R.forEach(function (hasil, index) {
    console.log(`${index + 1}\tKW00${index + 1}\t${hasil.nama}\t${hasil.nilai.toFixed(2)}`);
  });
}

// Memanggil fungsi untuk menampilkan hasil perangkingan SAW
tampilkanHasilSAW();

// Definisi data untuk NCF dan NSF untuk setiap aspek
const harga_ncf = { KW001: 4, KW002: -1 };
const harga_nsf = { KW001: 4.25, KW002: 0.33 };

const fasilitas_ncf = { KW001: -0.33, KW002: 3.33 };
const fasilitas_nsf = { KW001: 0, KW002: 3 };

const jarak_ncf = { KW001: 2.16, KW002: 2.83 };
const jarak_nsf = { KW001: 3.75, KW002: 3.75 };

const rating_ncf = { KW001: 3.16, KW002: 3.66 };
const rating_nsf = { KW001: 4.25, KW002: 3.75 };

// Fungsi untuk perhitungan nilai total dari setiap aspek
function hitungNilaiTotalAspek(ncf, nsf) {
  const total = {};
  for (const k in ncf) {
    total[k] = ncf[k] * 0.6 + nsf[k] * 0.4;
  }
  return total;
}

// Perhitungan nilai total untuk setiap aspek
const harga_total = hitungNilaiTotalAspek(harga_ncf, harga_nsf);
const fasilitas_total = hitungNilaiTotalAspek(fasilitas_ncf, fasilitas_nsf);
const jarak_total = hitungNilaiTotalAspek(jarak_ncf, jarak_nsf);
const rating_total = hitungNilaiTotalAspek(rating_ncf, rating_nsf);

// Perangkingan tempat wisata
const persentase_harga = 0.4;
const persentase_fasilitas = 0.4;
const persentase_jarak = 0.1;
const persentase_rating = 0.1;

const ranking = {};

for (const k in harga_total) {
  const total = persentase_harga * harga_total[k] + persentase_fasilitas * fasilitas_total[k] + persentase_jarak * jarak_total[k] + persentase_rating * rating_total[k];
  ranking[k] = total;
}

// Mengambil tempat wisata teratas dari hasil perangkingan SAW
const topPlace = Object.entries(ranking)
  .sort((a, b) => b[1] - a[1])
  .slice(0, 1)
  .map(([k, v]) => k)[0];

// Fungsi Profile Matching untuk satu tempat wisata
function profileMatching(place) {
  const hargaScore = harga_ncf[place] * 0.6 + harga_nsf[place] * 0.4;
  const fasilitasScore = fasilitas_ncf[place] * 0.6 + fasilitas_nsf[place] * 0.4;
  const jarakScore = jarak_ncf[place] * 0.6 + jarak_nsf[place] * 0.4;
  const ratingScore = rating_ncf[place] * 0.6 + rating_nsf[place] * 0.4;

  const totalScore = persentase_harga * hargaScore + persentase_fasilitas * fasilitasScore + persentase_jarak * jarakScore + persentase_rating * ratingScore;

  return totalScore;
}

// Menampilkan hasil Profile Matching untuk satu tempat wisata teratas dari SAW
console.log("\nHasil Profile Matching untuk Tempat Wisata Teratas:");
console.log(`\nTempat Wisata: ${topPlace}`);
console.log("Hasil Profile Matching:", profileMatching(topPlace).toFixed(2));
