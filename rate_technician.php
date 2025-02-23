<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'emetteur') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $technician_id = $_POST['technician_id'];

    $stmt = $conn->prepare("INSERT INTO ratings (ticket_id, technician_id, rating, comment) VALUES (:ticket_id, :technician_id, :rating, :comment)");
    $stmt->bindParam(':ticket_id', $ticket_id);
    $stmt->bindParam(':technician_id', $technician_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Merci pour votre notation !</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erreur lors de l'enregistrement de la notation.</div>";
    }
}

// Récupérer les tickets résolus par l'émetteur
$emitter_id = $_SESSION['user_id'];
$tickets = $conn->query("SELECT * FROM tickets WHERE emitter_id = $emitter_id AND status = 'ferme'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter un Technicien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Noter un Technicien</h1>
        <?php if (empty($tickets)) : ?>
            <div class="alert alert-info">Aucun ticket résolu à noter.</div>
        <?php else : ?>
            <form action="rate_technician.php" method="POST">
                <div class="mb-3">
                    <label for="ticket_id" class="form-label">Ticket</label>
                    <select class="form-select" id="ticket_id" name="ticket_id" required>
                        <?php foreach ($tickets as $ticket) : ?>
                            <option value="<?php echo $ticket['id']; ?>">Ticket #<?php echo $ticket['id']; ?> - <?php echo $ticket['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rating" class="form-label">Note (1-5)</label>
                    <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Commentaire</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                </div>
                <input type="hidden" name="technician_id" value="<?php echo $ticket['technician_id']; ?>">
                <button type="submit" class="btn btn-primary">Noter</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
