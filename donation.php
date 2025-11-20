<?php
session_start();

// ❤️ Charger le DonController (et non StreamController)
require_once '../Controller/DonController.php';
$donCtrl = new DonController();

$dons = $donCtrl->getAllDons(); // récupération des dons


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>G4S - Dons</title>
  <link rel="icon" href="assets/images/logo site.png">
  

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

 

  <style>
    .mobile-nav {
  display: none;
}

    body {
      background: linear-gradient(135deg, #1A1F35, #13172A);
      color: white;
      font-family: 'Poppins', sans-serif;
    }

    /* ✅ NAVBAR CONTAINER (forme arrondie + glass) */
header.glass {
     width: 80%;        /* rétrécit la barre */
    margin : 10px auto;    /* centrée */
    border-radius: 0 0 15px 15px;
  padding: 6px;
  background: rgba(35, 42, 69, 0.35);
  
  backdrop-filter: blur(18px);
  box-shadow: 0 0 25px rgba(110, 95, 255, 0.25);
  border: 1px solid rgba(255,255,255,0.06);
}

/* ✅ NAVBAR CONTENT */
header.glass nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 25px;
}

/* ✅ LOGO AVEC GLOW COMME L'IMAGE */
header .logo img {
  height: 55px;
  width: 55px;
  border-radius: 50%;
  background: radial-gradient(circle at center, #0A0D21 60%, #000 100%);
  box-shadow: 0 0 20px #6a5df9, 0 0 45px #6a5df955;
  padding: 5px;
  transition: .2s;
}

header .logo img:hover {
  transform: scale(1.08);
}

/* ✅ ICÔNE WALLET */
.wallet-icon img {
  height: 32px;
  width: 32px;
  margin-left: 20px;
  opacity: 0.9;
  transition: .2s;
}

.wallet-icon img:hover {
  transform: scale(1.12);
  opacity: 1;
}

/* ✅ NAV LINKS CENTRÉS */
ul.nav-links {
  display: flex;
  gap: 45px;
  list-style: none;
  margin: 0 auto;
  padding: 0;
  flex-grow: 1;
  justify-content: center;
}

ul.nav-links li a {
  font-size: 17px;
  font-weight: 600;
  color: #ffffffcc;
  text-decoration: none;
  transition: .2s;
}

ul.nav-links li a:hover {
  color: white;
  text-shadow: 0 0 12px #6a5df9;
}

/* ✅ Liens actifs (page actuelle) */
ul.nav-links a.active-link {
  background: linear-gradient(135deg, #7c4dff, #6a5df9, #9f4dff);
  color: white !important;
  padding: 10px 24px;
  border-radius: 18px;
  box-shadow: 0 0 15px #7c4dff88;
}

ul.nav-links a.active-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px #7c4dffcc;
}

ul.nav-links li:last-child a:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px #7c4dffcc;
}

/* ✅ MOBILE */
.mobile-menu-toggle {
  display: none;
}

@media (max-width: 820px) {
  ul.nav-links {
    display: none;
  }
  .mobile-menu-toggle {
    display: block;
  }
}


    /* ✅ DESCRIPTION */
    .description-box {
      padding: 25px;
      margin: 30px auto;
      background: #232A45;
      border-radius: 16px;
      text-align: center;
      max-width: 900px;
      font-size: 18px;
      line-height: 1.6;
      animation: rgbGlow 4s linear infinite;
    }

    @keyframes rgbGlow {
      0% { box-shadow: 0 0 12px #ff004d; }
      25% { box-shadow: 0 0 12px #00d1ff; }
      50% { box-shadow: 0 0 12px #6a5df9; }
      75% { box-shadow: 0 0 12px #ff5e7e; }
      100% { box-shadow: 0 0 12px #ff004d; }
    }

    .container-dons {
      max-width: 1200px;
      margin: 50px auto;
      padding: 10px;
    }

    .projet-card,
    .don-form,
    .consult-box {
      background: #232A45;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(106, 93, 249, 0.25);
      animation: rgbGlow 4s linear infinite;
    }

    .progress {
      height: 14px;
      background: #1d2236;
      border-radius: 12px;
      overflow: hidden;
      margin-top: 10px;
    }

    .progress-bar {
      background: linear-gradient(135deg, #ff004d, #00d1ff, #6a5df9);
      animation: rgbBar 3s infinite linear;
    }

    @keyframes rgbBar {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    select,
    input,
    textarea {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      background: #1D2236;
      color: white;
      border: none;
      margin-bottom: 15px;
    }

    .bouton-rgb {
      background: linear-gradient(135deg, #ff004d, #00d1ff, #6a5df9);
      background-size: 300% 300%;
      animation: rgbButton 3s infinite linear;
      border: none;
      color: white;
      font-weight: 700;
      padding: 12px;
      border-radius: 12px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
    }

    @keyframes rgbButton {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    footer {
      text-align: center;
      padding: 20px;
      margin-top: 40px;
      background: rgba(35, 42, 69, 0.5);
      backdrop-filter: blur(10px);
      font-size: 14px;
    }
  </style>
</head>

<body>

<header class="glass">
  <nav>
    <a href="index.html" class="logo">
      <img src="images/page.png" style="height: 50px; width: 50x; ">
    </a>

   <a href="portefeuille/portefeuille.html" class="wallet-icon">

      <img src="images/stouch.png" style="height: 25px; width: 25x; ">
    </a>

<ul class="nav-links">
  <li><a href="index.php">Streams</a></li>
  <li><a href="#">Associations</a></li>
  <li><a href="donation.php" class="active-link">Dons</a></li>
  <li><a href="#">Events</a></li>
  <li><a href="reclam/reclam.html">Réclamations</a></li>
</ul>

    <div class="mobile-menu-toggle" onclick="toggleMenu()">
      <div class="hamburger-line"></div>
      <div class="hamburger-line"></div>
      <div class="hamburger-line"></div>
    </div>
  </nav>

</header>

<!-- 📦 DESCRIPTION -->
<div class="description-box">
  <h2>🟣 Espace Dons</h2>

  <p>
 

Votre générosité permet à G4S de redonner espoir aux personnes les plus fragiles. À travers cette plateforme, chaque don contribue directement au soutien de projets solidaires portés par des streamers engagés. En participant, vous devenez un acteur essentiel du changement en aidant à financer des actions humanitaires concrètes et durables. Ensemble, nous pouvons créer un impact positif réel et soutenir des causes qui comptent.
  </p>
</div>

<div class="container-dons">

  <div class="don-form">
    <h3>🎁 Faire un don</h3>
<form action="donss.php" method="POST">

    <select name="id_projet" required>
        <option disabled selected>--  POUR QUEL PROJET ? --</option>
        <option value="1">Projet 1</option>
        <option value="2">Projet 2</option>
    </select>

    <input type="hidden" name="id_stream" value="51">
    <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?? 3 ?>">

    <input type="number" name="montant" placeholder="TAPER VOTRE MONTANT (€)" min="1" required>

  

    <button type="submit" class="bouton-rgb">PASSER AU PAIEMENT</button>

</form>



</div>
  <hr>

  <!-- 📜 HISTORIQUE DES DONS -->
  <div class="don-form">
    <h3>📜 Historique de vos dons</h3>

    <?php if (!$dons): ?>
      <p>Aucun don trouvé.</p>
    <?php else: ?>

      <table class="table table-dark table-striped">
        <tr>
          <th>Projet</th>
          <th>Montant</th>
          <th>Actions</th>
        </tr>

        <?php foreach ($dons as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['id_projet']) ?></td>
            <td><?= htmlspecialchars($d['montant']) ?> €</td>
      

            <td>
              <a href="updateDon.php?id=<?= $d['id_dons'] ?>" class="btn btn-warning btn-sm">Modifier</a>
              <a href="deletedon.php?id=<?= $d['id_dons'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>

      </table>

    <?php endif; ?>

  </div>
</div>

<footer>© 2025 G4S Streaming Platform - Tous droits réservés.</footer>

</body>
</html>
