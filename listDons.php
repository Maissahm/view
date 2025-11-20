<?php
require_once '../../Controller/DonController.php';

$controller = new DonController();
$dons = $controller->getAllDons();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des dons</title>
</head>

<body>

<h2>📄 Liste des dons</h2>

<a href="addDon.php">➕ Ajouter un don</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID Don</th>
        <th>ID Projet</th>
        <th>ID Stream</th>
        <th>ID User</th>
        <th>Montant</th>
        <th>Action</th>
    </tr>

    <?php foreach ($dons as $d): ?>
    <tr>
        <td><?= $d['id_dons'] ?></td>
        <td><?= $d['id_projet'] ?></td>
        <td><?= $d['id_stream'] ?></td>
        <td><?= $d['id_user'] ?></td>
        <td><?= $d['montant'] ?>$</td>

        <td>
            <a href="updateDon.php?id=<?= $d['id_dons'] ?>">✏ Modifier</a>
            |
            <a href="deleteDon.php?id=<?= $d['id_dons'] ?>"
               onclick="return confirm('Supprimer ce don ?')">🗑 Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
