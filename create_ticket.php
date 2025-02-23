<?php
session_start();
include 'db.php';
include 'mail_config.php'; // Inclure la configuration de PHPMailer

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'emetteur') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $technician_id = $_POST['technician_id'];
    $emitter_id = $_SESSION['user_id'];

    // Insérer le ticket dans la base de données
    $stmt = $conn->prepare("INSERT INTO tickets (title, description, emitter_id, technician_id) VALUES (:title, :description, :emitter_id, :technician_id)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':emitter_id', $emitter_id);
    $stmt->bindParam(':technician_id', $technician_id);

    if ($stmt->execute()) {
        // Récupérer l'e-mail du technicien
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = :technician_id");
        $stmt->bindParam(':technician_id', $technician_id);
        $stmt->execute();
        $technician = $stmt->fetch(PDO::FETCH_ASSOC);

        // Envoyer une notification par e-mail
        $to = $technician['username']; // Supposons que l'e-mail est stocké dans le champ 'username'
        $subject = "Nouveau ticket assigné : $title";
        $body = "Un nouveau ticket vous a été assigné :<br><br>
                 <strong>Titre :</strong> $title<br>
                 <strong>Description :</strong> $description<br><br>
                 Connectez-vous pour voir les détails.";

        if (sendEmail($to, $subject, $body)) {
            echo "<div class='alert alert-success mt-3'>Ticket créé avec succès et notification envoyée !</div>";
        } else {
            echo "<div class='alert alert-warning mt-3'>Ticket créé, mais l'envoi de la notification a échoué.</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Erreur lors de la création du ticket.</div>";
    }
}

// Récupérer la liste des techniciens
$technicians = $conn->query("SELECT * FROM users WHERE role = 'technicien'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Créer un Ticket</h1>
        <form action="create_ticket.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="technician_id" class="form-label">Technicien</label>
                <select class="form-select" id="technician_id" name="technician_id" required>
                    <?php foreach ($technicians as $technician) : ?>
                        <option value="<?php echo $technician['id']; ?>"><?php echo $technician['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</body>
</html>
