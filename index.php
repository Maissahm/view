<?php
require_once '../Controller/StreamController.php';
$streamCtrl = new StreamController();
$streams = $streamCtrl->getAllStreams();

session_start();



// streamer
//$_SESSION['id_user'] = 2;
//$_SESSION['role'] = 'streamer';

// VIEWER
//$_SESSION['id_user'] = 50;
//$_SESSION['role'] = 'viewer';

// ASSOCIATION
//$_SESSION['id_user'] = 60;
 //$_SESSION['role'] = 'association';

// TESTER COMME ADMIN
 //$_SESSION['id_user'] = 2;
 //$_SESSION['role'] = 'admin';


?>



<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="author" content="templatemo">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <title>GAMING FOR SOCIETY</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-liberty-market.css?v=2">

    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
<!--

TemplateMo 577 Liberty Market

https://templatemo.com/tm-577-liberty-market

-->
  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="glass">
        <nav>
            <a href="index.html" class="logo">
                <img src="images/page.png" style="height: 50px; width: 50x; ">
            </a>
            <a href="portefeuille/portefeuille.html"  style="margin-left: -400px;">
                <img src="images/stouch.png" style="height: 25px; width: 25x; ">
            </a>
            <ul class="nav-links">
<li><a href="index.php#streams" class="nav-glow" id="streamsLinkMobile">Streams</a></li>



                <li><a href="">Associations</a></li>
                <li><a href="donation.php">Dons</a></li>
                <li><a href="events.html">Events</a></li>
                <li><a href="reclam/reclam.html">Réclamations</a></li>
</ul>
            </ul>
            <div class="mobile-menu-toggle">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </nav>
        <div class="mobile-nav">



                <li><a href="">Associations</a></li>
                <li><a href="">Dons</a></li>
                <li><a href="">Events</a></li>
                <li><a href="reclam/reclam.html">Réclamations</a></li>
        </div>
    </header>

  <div class="main-banner" >
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="header-text">
            <h6>Gaming for Society</h6>
          
            <p>
              🎮 Le streaming engagé et communautaire

la 1ère plateforme qui réunit jeux vidéo, innovation et solidarité !
Ici, chaque stream compte : les spectateurs et les créateurs se rassemblent pour partager leur passion tout en soutenant des initiatives sociales et environnementales.
<br>
Notre mission est simple :<br>
👉 Offrir une expérience de streaming fluide et interactive.<br>
👉 Mettre en avant des streamers engagés.<br>
👉 Créer un lien fort entre le divertissement et l’action solidaire.</p>
            <div class="buttons">
              <div class="border-button">
                <a href="index.php#streams">Consulter les lives actifs</a>
              </div>
              <div class="main-button">
                <a href="addstream.php" target="_blank">créer votre live</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 offset-lg-1">
          <div class="owl-banner owl-carousel">
            <div class="item">
              <img src="assets/images/banner-01.png" alt="">
            </div>
            <div class="item">
              <img src="assets/images/banner-02.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ***** Main Banner Area End ***** -->
  
  <div class="categories-collections" >
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="categories">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-heading">
                  <div class="line-dec"></div>
                  <h2>Browse Through Our <em>Categories</em> Here.</h2>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-01.png" alt="">
                  </div>
                  <h4>Blockchain</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-02.png" alt="">
                  </div>
                  <h4>Digital Art</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-03.png" alt="">
                  </div>
                  <h4>Music Art</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-04.png" alt="">
                  </div>
                  <h4>Virtual World</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-05.png" alt="">
                  </div>
                  <h4>Valuable</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/icon-06.png" alt="">
                  </div>
                  <h4>Triple NFT</h4>
                  <div class="icon-button">
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="collections">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-heading">
                  <div class="line-dec"></div>
                  <h2>Explore Some Hot <em>Collections</em> In Market.</h2>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="owl-collection owl-carousel">
                  <div class="item">
                    <img src="assets/images/collection-01.jpg" alt="">
                    <div class="down-content">
                      <h4>Mutant Bored Ape Yacht Club</h4>
                      <span class="collection">Items In Collection:<br><strong>310/340</strong></span>
                      <span class="category">Category:<br><strong>Digital Crypto</strong></span>
                      <div class="main-button">
                        <a href="explore.html">Explore Mutant</a>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <img src="assets/images/collection-01.jpg" alt="">
                    <div class="down-content">
                      <h4>Bored Ape Kennel Club</h4>
                      <span class="collection">Items In Collection:<br><strong>324/324</strong></span>
                      <span class="category">Category:<br><strong>Visual Art</strong></span>
                      <div class="main-button">
                        <a href="explore.html">Explore Bored Ape</a>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <img src="assets/images/collection-01.jpg" alt="">
                    <div class="down-content">
                      <h4>Genesis Collective Statue</h4>
                      <span class="collection">Items In Collection:<br><strong>380/394</strong></span>
                      <span class="category">Category:<br><strong>Music Art</strong></span>
                      <div class="main-button">
                        <a href="explore.html">Explore Genesis</a>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <img src="assets/images/collection-01.jpg" alt="">
                    <div class="down-content">
                      <h4>Worldwide Artwork Ground</h4>
                      <span class="collection">Items In Collection:<br><strong>426/468</strong></span>
                      <span class="category">Category:<br><strong>Blockchain</strong></span>
                      <div class="main-button">
                        <a href="explore.html">Explore Worldwide</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
<section class="currently-market" id="streams">



  <div class="container">

    <div class="row">
      <div class="col-lg-12">
        <div class="section-heading">
          <div class="line-dec"></div>
   <h2 class="glow-gamer">Streams <em>actifs</em></h2>


        </div>
      </div>
    </div>

    <div class="row grid">

<?php 
  $id_user_connected = $_SESSION['id_user'] ?? null;
  $role = $_SESSION['role'] ?? 'viewer';
?>

<?php foreach ($streams as $s): ?>
  <div class="col-lg-4 currently-market-item all">
    <div class="item">

      <div class="left-image">
        <img src="assets/images/market-01.jpg" 
             alt="" style="border-radius:20px; width:100%;">
      </div>

      <div class="right-content">

        <h4><?= htmlspecialchars($s['titre']) ?></h4>

        <span class="author">
          <h6>Streamer ID: <?= htmlspecialchars($s['id_user']) ?></h6>
        </span>

        <div class="line-dec"></div>

        <span class="bid">Viewers<br>
          <strong><?= htmlspecialchars($s['nb_viewers']) ?></strong>
        </span>

        <span class="ends">Dons<br>
          <strong><?= htmlspecialchars($s['total_dons']) ?> €</strong>
        </span>

        <!-- LOGIQUE POUR LE BOUTON -->
        <?php if ($id_user_connected == $s['id_user'] && $role === 'streamer'): ?>
          <!-- 🔴 STREAMER : bouton spécial -->
          <div class="text-button mt-3">
          <a href="/projetWEB(front office)/view/streamer.php?id=<?= $s['id_stream'] ?>" 
   style="color:#ff4444; font-weight:bold;">
    🔴 Accéder à votre live
</a>

          </div>

        <?php else: ?>
          <!-- VIEWER ou autre streamer -->
          <div class="text-button mt-3">
            <a href="/projetWEB(front office)/view/viewer.php?id=<?= $s['id_stream'] ?>">
              🎬 Regarder
            </a>
          </div>
        <?php endif; ?>

        <!-- BOUTONS ADMIN / PROPRIÉTAIRE -->
        <?php if ($role === 'admin' || $id_user_connected == $s['id_user']): ?>
          <div class="text-button mt-2">
            <a href="../view/updatestream.php?id=<?= $s['id_stream'] ?>" 
               style="color:#f7b100;">✏ Modifier</a>
          </div>

          <div class="text-button mt-2">
            <a href="../view/deletestream.php?id=<?= $s['id_stream'] ?>"
               onclick="return confirm('Supprimer ce stream ?');"
               style="color:#dc3545;">❌ Supprimer</a>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
<?php endforeach; ?>

    </div>

    <!-- AJOUTER UN STREAM : STREAMER + ADMIN -->
    <?php if ($role === 'admin' || $role === 'streamer'): ?>
      <div class="text-center mt-4">
        <a href="../view/addstream.php" class="btn btn-success">➕ Ajouter un Stream</a>
      </div>
    <?php endif; ?>

  </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const link = document.getElementById("streamsLink");
    const mobileLink = document.getElementById("streamsLinkMobile");

    if (window.location.hash === "#streams") {
        link?.classList.add("active");
        mobileLink?.classList.add("active");
    }

    // Smooth scroll effect
    const scrollLinks = document.querySelectorAll('.nav-glow');
    scrollLinks.forEach(anchor => {
        anchor.addEventListener("click", function(e) {
            if (this.getAttribute("href").includes("#")) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute("href").split("#")[1] ? "#" + this.getAttribute("href").split("#")[1] : "");
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: "smooth"
                    });
                    scrollLinks.forEach(l => l.classList.remove("active"));
                    this.classList.add("active");
                }
            }
        });
    });
});
</script>


  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>

  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
  <script src="assets/js/navbar.js"></script>

</div>
  </body>
  
</html>