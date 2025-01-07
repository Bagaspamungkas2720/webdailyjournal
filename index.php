<?php
include "koneksi.php"; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Daily Journal</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link rel="icon" href="img/logo.png" />
  </head>
  <body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
      <div class="container">
        <a class="navbar-brand" href="#">Daily Journal</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <!-- Change href to #article to link to the Article section on the same page -->
              <a class="nav-link" href="#article">Article</a>
            </li>
            <li class="nav-item">
              <!-- Gallery link now scrolls to the Gallery section -->
              <a class="nav-link" href="#gallery">Gallery</a>
            </li>
          </ul>
          <button type="button" class="btn btn-dark" id="tombol1">
            <i class="bi bi-moon-stars-fill"></i>
          </button>
          <button type="button" class="btn btn-danger" id="tombol2">
            <i class="bi bi-brightness-high-fill"></i>
          </button>
        </div>
      </div>
    </nav>

    <!-- hero -->
    <section id="hero" class="text-center p-5 bg-danger-subtle text-sm-start">
      <div class="container">
        <div class="d-sm-flex flex-sm-row-reverse align-items-center">
          <img
            class="img-fluid"
            src="img/101.jpeg"
            width="300"
          />
          <div id="text">
            <h1 class="fw-bold display-4">BECIKU, Becak Listrik Udinus</h1>
            <h4 class="lead display-6">
              Semarang, Idola 92.6 FM – Dosen dan tim mahasiswa Universitas Dian
              Nuswantoro (Udinus) Semarang baru-baru ini berinovasi membuat
              Becik-KU (Becak Listrik Kampus Universitas Dian Nuswantoro). Tim
              terdiri dari 6 mahasiswa dan 6 dosen Udinus.
            </h4>
            <h6>
              <span id="tanggal"></span>
              <span id="jam"></span>
            </h6>
          </div>
        </div>
      </div>
    </section>

    <!-- article -->
    <!-- article begin -->
<section id="article" class="text-center p-5">
  <div class="container">
    <h1 class="fw-bold display-4 pb-3">article</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
      <?php
      $sql = "SELECT * FROM article ORDER BY tanggal DESC";
      $hasil = $conn->query($sql); 

      while($row = $hasil->fetch_assoc()){
      ?>
        <div class="col">
          <div class="card h-100">
            <img src="img/<?= $row["gambar"]?>" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"><?= $row["judul"]?></h5>
              <p class="card-text">
                <?= $row["isi"]?>
              </p>
            </div>
            <div class="card-footer">
              <small class="text-body-secondary">
                <?= $row["tanggal"]?>
              </small>
            </div>
          </div>
        </div>
        <?php
      }
      ?> 
    </div>
  </div>
</section>
<!-- article end -->

   <!-- gallery -->
   <section id="gallery" class="text-center p-5 bg-danger-subtle">
    <div class="container">
        <h1 class="fw-bold display-4 pb-3">Gallery</h1>

        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
            <?php
        // Koneksi ke database
        include "koneksi.php";

        // Query untuk mengambil data gambar
        $sql = "SELECT * FROM gallery ORDER BY id DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="gallery-item">
                <img src="img/<?php echo $row['nama_gambar']; ?>" alt="<?php echo $row['judul_gambar']; ?>">
            </div>
        <?php } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

    <!-- footer -->
    <footer class="text-center p-5">
      <div>
        <i class="bi bi-whatsapp h2 p-2 text-dark"></i>
        <i class="bi bi-twitter h2 p-2 text-dark"></i>
        <i class="bi bi-instagram h2 p-2 text-dark"></i>
      </div>
      <div>Deranda Bagas Pamungkas 2024</div>
    </footer>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <script type="text/javascript">
  window.setTimeout("tampilWaktu()", 1000);

function tampilWaktu() {
  var waktu = new Date();
  var bulan = waktu.getMonth() + 1;

  // Update the time and date
  setTimeout("tampilWaktu()", 1000);
  document.getElementById("tanggal").innerHTML =
    waktu.getDate() + "/" + bulan + "/" + waktu.getFullYear();
  document.getElementById("jam").innerHTML =
    waktu.getHours() + ":" + waktu.getMinutes() + ":" + waktu.getSeconds();

  // Function to switch to dark mode
  document.getElementById("tombol1").onclick = function () {
    applyDarkMode();
  };

  // Function to switch to light mode
  document.getElementById("tombol2").onclick = function () {
    applyLightMode();
  };
}

// Apply dark mode styles
function applyDarkMode() {
  // Hero section
  document.getElementById("hero").classList.remove("bg-danger-subtle");
  document.getElementById("hero").classList.add("bg-secondary");
  document.getElementById("text").style.color = "white";

  // Article section
  document.getElementById("article").classList.remove("bg-danger-subtle");
  document.getElementById("article").classList.add("bg-dark");
  document.getElementById("h1").style.color = "white";

  // Cards section
  let cards = document.querySelectorAll(".card");
  cards.forEach(function (card) {
    card.classList.add("bg-secondary", "text-white");
  });

  // Gallery section
  document.getElementById("gallery").classList.remove("bg-danger-subtle");
  document.getElementById("gallery").classList.add("bg-secondary");
  document.getElementById("3").style.color = "white";

  // Footer section
  document.querySelector("footer").classList.add("bg-dark");

  // Icons in footer
  let icons = document.querySelectorAll("footer i");
  icons.forEach(function (icon) {
    icon.classList.remove("text-dark");
    icon.classList.add("text-white");
  });

  // Footer text color
  document.querySelector("footer").classList.add("text-white");
}

// Apply light mode styles
function applyLightMode() {
  // Hero section
  document.getElementById("hero").classList.remove("bg-secondary");
  document.getElementById("hero").classList.add("bg-danger-subtle");
  document.getElementById("text").style.color = "black";

  // Article section
  document.getElementById("article").classList.remove("bg-dark");
  document.getElementById("article").classList.add("bg-danger-light");
  document.getElementById("h1").style.color = "black";

  // Cards section
  let cards = document.querySelectorAll(".card");
  cards.forEach(function (card) {
    card.classList.remove("bg-secondary", "text-white");
  });

  // Gallery section
  document.getElementById("gallery").classList.remove("bg-secondary");
  document.getElementById("gallery").classList.add("bg-danger-subtle");
  document.getElementById("3").style.color = "black";

  // Footer section
  document.querySelector("footer").classList.remove("bg-dark");

  // Icons in footer
  let icons = document.querySelectorAll("footer i");
  icons.forEach(function (icon) {
    icon.classList.remove("text-white");
    icon.classList.add("text-dark");
  });

  // Footer text color
  document.querySelector("footer").classList.remove("text-white");
}

</script>


  </body>
</html>
