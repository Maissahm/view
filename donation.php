<?php
session_start();

require_once '../Controller/DonController.php';
$donCtrl = new DonController();

// 🔹 Récupération des dons
$id_user = $_SESSION['id_user'] ?? 2;

if (!$id_user) {
    die("Erreur : utilisateur non connecté.");
}

$dons = $donCtrl->getDonsByUser($id_user);


// 🔹 Récupération des projets avec montants (menu + bars)
$projetsInfos = $donCtrl->getAllProjetsInfos();
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
    /* ⭐ TON CSS ORIGINAL — INCHANGÉ */

    .mobile-nav { display: none; }

    body {
      background: linear-gradient(135deg, #1A1F35, #13172A);
      color: white; font-family: 'Poppins', sans-serif;
    }

    header.glass {
      width: 80%; margin:10px auto;
      border-radius: 0 0 15px 15px; padding: 6px;
      background: rgba(35, 42, 69, 0.35);
      backdrop-filter: blur(18px);
      box-shadow: 0 0 25px rgba(110, 95, 255, 0.25);
      border: 1px solid rgba(255,255,255,0.06);
    }

    header.glass nav {
      display: flex; align-items: center;
      justify-content: space-between; padding: 8px 25px;
    }

    header .logo img {
      height: 55px; width: 55px; border-radius: 50%;
      background: radial-gradient(circle, #0A0D21, #000);
      box-shadow: 0 0 20px #6a5df9, 0 0 45px #6a5df955;
      padding: 5px; transition: .2s;
    }

    header .logo img:hover { transform: scale(1.08); }

    ul.nav-links {
      display: flex; gap: 45px; list-style: none;
      margin: 0 auto; padding: 0; flex-grow: 1;
      justify-content: center;
    }

    ul.nav-links li a {
      font-size: 17px; font-weight: 600;
      color: #ffffffcc; text-decoration: none;
      transition: .2s;
    }

    ul.nav-links li a:hover {
      color: white; text-shadow: 0 0 12px #6a5df9;
    }

    ul.nav-links a.active-link {
      background: linear-gradient(135deg, #7c4dff, #6a5df9, #9f4dff);
      color:white !important; padding:10px 24px;
      border-radius:18px; box-shadow:0 0 15px #7c4dff88;
    }

    .description-box {
      padding: 25px; margin: 30px auto;
      background: #232A45;
      border-radius: 16px; text-align: center;
      max-width: 900px; font-size: 18px;
      animation: rgbGlow 4s linear infinite;
    }

    @keyframes rgbGlow {
      0%,100% { box-shadow: 0 0 12px #ff004d; }
      25% { box-shadow: 0 0 12px #00d1ff; }
      50% { box-shadow: 0 0 12px #6a5df9; }
      75% { box-shadow: 0 0 12px #ff5e7e; }
    }

    .container-dons { max-width:1200px; margin:50px auto; padding:10px; }

    .don-form, .projet-card {
      background:#232A45; padding:20px; border-radius:16px;
      box-shadow: 0 0 20px rgba(106,93,249,0.25);
      animation: rgbGlow 4s linear infinite;
    }

    select, input {
      width:100%; padding:12px; margin-bottom:15px;
      background:#1D2236; border:none; border-radius:12px;
      color:white;
    }

    .progress-container {
      background:#1d2236; height:16px; border-radius:12px;
      overflow:hidden; width:100%; margin-top:10px;
    }

    .progress-bar {
      height:100%;
      background: linear-gradient(135deg, #ff004d, #00d1ff, #6a5df9);
      animation: rgbMove 3s infinite linear;
      background-size:300% 300%;
    }

    @keyframes rgbMove {
      0%{background-position:0% 50%;}
      50%{background-position:100% 50%;}
      100%{background-position:0% 50%;}
    }
    .bouton-rgb {
    position: relative;
    width: 100%;
    padding: 14px 20px;
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    background: #14172b;
    border-radius: 12px;
    border: 2px solid transparent;
    cursor: pointer;
    overflow: hidden;
    transition: 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Éclairage RGB autour */
.bouton-rgb::before {
    content: "";
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(90deg, #ff004d, #00d1ff, #6a5df9, #ff5e7e, #ff004d);
    background-size: 400%;
    border-radius: 14px;
    z-index: -1;
    filter: blur(8px);
    animation: rgbGlowBorder 6s linear infinite;
}

/* Hover = plus de glow */
.bouton-rgb:hover {
    transform: scale(1.03);
    box-shadow: 0 0 22px #6a5df9cc;
}

/* Animation RGB autour du bouton */
@keyframes rgbGlowBorder {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
/* 🟣 TABLE GAMING NEON */
.table-gaming {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #1A1F35;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 0 25px rgba(106,93,249,0.25);
    animation: rgbGlow 4s linear infinite;
}

.table-gaming thead {
    background: rgba(110, 95, 255, 0.15);
}

.table-gaming th {
    padding: 14px;
    text-align: left;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.table-gaming td {
    padding: 14px;
    font-size: 15px;
    color: #cfd4ff;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

/* Hover sur les lignes */
.table-gaming tbody tr:hover {
    background: rgba(110, 95, 255, 0.25);
    color: #fff;
    transition: 0.25s ease;
}

/* BOUTONS actions gaming */
.btn-gaming {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: .2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Modifier */
.btn-edit {
    background: linear-gradient(135deg, #00d1ff, #6a5df9);
    color: white;
}
.btn-edit:hover {
    box-shadow: 0 0 12px #00d1ffaa;
}

/* Supprimer */
.btn-delete {
    background: linear-gradient(135deg, #ff004d, #ff5e7e);
    color: white;
}
.btn-delete:hover {
    box-shadow: 0 0 12px #ff004daa;
}

  </style>
</head>

<body>

<header class="glass">
  <nav>
    <a href="index.html" class="logo">
      <img src="images/page.png">
    </a>

<ul class="nav-links">
  <li><a href="index.php">Streams</a></li>
  <li><a href="#">Associations</a></li>
  <li><a href="donation.php" class="active-link">Dons</a></li>
  <li><a href="#">Events</a></li>
  <li><a href="reclam/reclam.html">Réclamations</a></li>
</ul>

  </nav>
</header>

<!-- Description -->
<div class="description-box">
  <h2>🟣 Espace Dons</h2>
  <p>Votre générosité permet à G4S de redonner espoir...</p>
</div>

<div class="container-dons">

  <!-- FORMULAIRE -->
  <div class="don-form">
    <h3>🎁 Faire un don</h3>

<form action="donss.php" method="POST">

<select name="id_projet" required>
    <option disabled selected>-- POUR QUEL PROJET ? --</option>

    <?php foreach ($projetsInfos as $p): ?>

        <?php if ($p['montant_collecte'] < $p['montant_estimé']): ?>
            <option value="<?= $p['id_projet'] ?>">
                <?= htmlspecialchars($p['titre_projet']) ?>
            </option>
        <?php endif; ?>

    <?php endforeach; ?>
</select>
<?php foreach ($projetsInfos as $p): ?>
    <?php if ($p['montant_collecte'] >= $p['montant_estimé']): ?>
        <p style="color:#00ff88;">
            ✔ Le projet <strong><?= htmlspecialchars($p['titre_projet']) ?></strong> a atteint son objectif 🎉
        </p>
    <?php endif; ?>
<?php endforeach; ?>



<input type="hidden" name="id_stream" value="51">
<input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?? 2?>">

<input type="number" name="montant" placeholder="TAPER VOTRE MONTANT (€)" required>

<button class="bouton-rgb">PASSER AU PAIEMENT</button>

</form>
</div>

<hr>

<!-- HISTORIQUE -->
<div class="don-form">
    <h3>📜 Historique de vos dons</h3>

<?php if (!$dons): ?>
    <p>Aucun don trouvé.</p>
<?php else: ?>

<table class="table-gaming">

<tr>
  <th>Projet</th>
  <th>Montant</th>
  <th>Actions</th>
</tr>

<?php foreach ($dons as $d): ?>
<tr>
    <td><?= htmlspecialchars($d['titre_projet']) ?></td>
    <td><?= htmlspecialchars($d['montant']) ?> €</td>
    <td>
     <a href="updateDon.php?id=<?= $d['id_dons'] ?>" class="btn-gaming btn-edit">Modifier</a>
<a href="deletedon.php?id=<?= $d['id_dons'] ?>" class="btn-gaming btn-delete">Supprimer</a>

    </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>
</div>


<!-- 📊 PROGRESSION DES PROJETS -->
<div class="don-form" style="margin-top:30px;">
  <h3>📊 Progression des projets</h3>

<?php foreach ($projetsInfos as $p): ?>

<?php
  $collecte = $p['montant_collecte'];
  $objectif = $p['montant_estimé'];

  $percent = ($objectif > 0) ? round(($collecte / $objectif) * 100) : 0;
  if ($percent > 100) $percent = 100;
?>

<div class="projet-card" style="margin:20px 0;">
  <h4><?= htmlspecialchars($p['titre_projet']) ?></h4>

  <p>
    Collecté : <strong><?= $collecte ?> €</strong> /
    Objectif : <strong><?= $objectif ?> €</strong>
    (<?= $percent ?> %)
  </p>

  <div class="progress-container">
      <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
  </div>
</div>

<?php endforeach; ?>

</div>

</div>

<footer>© 2025 G4S Streaming Platform</footer>

</body>
</html>
