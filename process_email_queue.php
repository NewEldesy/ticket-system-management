<?php
include 'db.php';
include 'mail_config.php';

// Récupérer les e-mails non envoyés
$emails = $conn->query("SELECT * FROM email_queue WHERE sent = 0 LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

foreach ($emails as $email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'votre_email@gmail.com';
        $mail->Password = 'votre_mot_de_passe';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('votre_email@gmail.com', 'Système de Tickets');
        $mail->addAddress($email['to']);

        $mail->isHTML(true);
        $mail->Subject = $email['subject'];
        $mail->Body = $email['body'];

        if ($mail->send()) {
            // Marquer l'e-mail comme envoyé
            $stmt = $conn->prepare("UPDATE email_queue SET sent = 1 WHERE id = :id");
            $stmt->bindParam(':id', $email['id']);
            $stmt->execute();
        }
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo);
    }
}
?>
