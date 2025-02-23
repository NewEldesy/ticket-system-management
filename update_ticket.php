<?php include 'db.php'; ?>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tickets SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Ticket mis à jour avec succès !</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erreur lors de la mise à jour du ticket.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Modifier un Ticket</h1>
        <form action="update_ticket.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $ticket['id']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" value="<?php echo $ticket['title']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" disabled><?php echo $ticket['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select" id="status" name="status">
                    <option value="ouvert" <?php echo ($ticket['status'] == 'ouvert') ? 'selected' : ''; ?>>Ouvert</option>
                    <option value="en_cours" <?php echo ($ticket['status'] == 'en_cours') ? 'selected' : ''; ?>>En cours</option>
                    <option value="ferme" <?php echo ($ticket['status'] == 'ferme') ? 'selected' : ''; ?>>Fermé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
