$to = $ticket['emitter_email']; // Récupérer l'e-mail de l'émetteur
$subject = "Nouveau commentaire sur le ticket : {$ticket['title']}";
$body = loadEmailTemplate('comment_added.html', [
    'title' => $ticket['title'],
    'comment' => $comment,
]);

sendEmail($to, $subject, $body);
