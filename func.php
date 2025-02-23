function loadEmailTemplate($template, $data) {
    $templatePath = __DIR__ . "/emails/$template";
    if (!file_exists($templatePath)) {
        throw new Exception("Modèle d'e-mail introuvable : $template");
    }

    $content = file_get_contents($templatePath);
    foreach ($data as $key => $value) {
        $content = str_replace("{{{$key}}}", $value, $content);
    }

    return $content;
}

function addToEmailQueue($to, $subject, $body) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO email_queue (`to`, subject, body) VALUES (:to, :subject, :body)");
    $stmt->bindParam(':to', $to);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':body', $body);
    return $stmt->execute();
}
