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
   <link href="donation.css" rel="stylesheet" />


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

<form action="carte_banc.php" method="POST">

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

<footer style="margin-left:640px;"> 
  © 2025 G4S Streaming Platform</footer>

</body>
</html>