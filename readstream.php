<?php
session_start();
require_once '../Controller/StreamController.php';

$controller = new StreamController();
$streams = $controller->getAllStreams();
$role = $_SESSION['role'] ?? 'streamer';   // role par défaut = viewer
$userId = $_SESSION['id_user'] ?? null; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>🎮 G4S Live – Liste des Streams</title>

  
  <link rel="stylesheet" href="assets/css/rs.css">
</head>

<body>

  <h2>📺 Liste des Streams</h2>

  <a class="add-btn" href="addstream.php">➕ Ajouter un Stream</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Titre</th>
      <th>Viewers</th>
      <th>Dons</th>
      <th>User</th>
      <th>Actions</th>
    </tr>

    <?php foreach ($streams as $s): ?>
    <tr>
      <td><?= htmlspecialchars($s['id_stream']) ?></td>
      <td><?= htmlspecialchars($s['titre']) ?></td>
      <td>👁️ <?= htmlspecialchars($s['nb_viewers']) ?></td>
      <td>💰 <?= htmlspecialchars($s['total_dons']) ?></td>
      <td>#<?= htmlspecialchars($s['id_user']) ?></td>

      <td>
        <a class="action-link"
           href="viewer.php?id=<?= $s['id_stream'] ?>">🎥 Regarder</a>

        <a class="action-link"
           href="updatestream.php?id=<?= $s['id_stream'] ?>">✏ Modifier</a>

        <a class="action-link delete-link"
           href="deletestream.php?id=<?= $s['id_stream'] ?>"
           onclick="return confirm('Supprimer ce stream ?');">
           ❌ Supprimer
        </a>
      </td>
    </tr>
    <?php endforeach; ?>

  </table>

</body>
</html>
