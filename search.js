// Fungsi untuk melakukan pencarian
function searchWisata() {
  var input, filter, cards, cardContainer, cardTitle, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  cardContainer = document.querySelector(".informasi-wisata");
  cards = cardContainer.getElementsByClassName("card");

  for (i = 0; i < cards.length; i++) {
    cardTitle = cards[i].querySelector(".card-title");
    if (cardTitle.innerText.toUpperCase().indexOf(filter) > -1) {
      cards[i].style.display = ""; // Menampilkan kartu yang terfilter
    } else {
      cards[i].style.display = "none"; // Menyembunyikan kartu yang tidak terfilter
    }
  }

  // Mengatur ulang tata letak kartu menjadi horizontal setelah filter
  cardContainer.classList.add("horizontal-cards");
}

// Event listener untuk input pencarian
document.getElementById("searchInput").addEventListener("keyup", searchWisata);
