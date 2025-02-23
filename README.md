# Système de Gestion de Tickets

Un système de gestion de tickets simple et efficace pour les entreprises, permettant de créer, assigner, suivre et résoudre des tickets. Les fonctionnalités incluent la création de tickets, l'assignation à des techniciens, la mise à jour du statut, l'ajout de commentaires, et des notifications par e-mail.

## Fonctionnalités

- **Création de tickets** : Les utilisateurs peuvent créer des tickets avec un titre, une description et assigner un technicien.
- **Assignation de tickets** : Les tickets peuvent être directement assignés à un technicien spécifique.
- **Mise à jour du statut** : Les techniciens peuvent mettre à jour le statut des tickets (ouvert, en cours, fermé).
- **Notation des techniciens** : Les émetteurs de tickets peuvent noter l'intervention des techniciens.
- **Notifications par e-mail** : Des notifications sont envoyées pour les événements suivants :
  - Nouveau ticket assigné.
  - Mise à jour du statut d'un ticket.
  - Ticket résolu.
  - Ajout d'un commentaire.
- **Modèles d'e-mails personnalisés** : Les notifications utilisent des modèles d'e-mails HTML personnalisables.
- **File d'attente pour les e-mails** : Les e-mails sont envoyés de manière asynchrone via une file d'attente pour améliorer les performances.

## Technologies Utilisées

- **Back-end** : PHP, MySQL
- **Front-end** : HTML, CSS, Bootstrap, jQuery (AJAX)
- **Envoi d'e-mails** : PHPMailer
- **Gestion des dépendances** : Composer

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Composer (pour installer les dépendances)
- Un serveur SMTP (ex : Gmail, SendGrid) pour l'envoi d'e-mails

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/votre-utilisateur/votre-repo.git
   cd votre-repo
   ```
2. **Installer les dépendances** :
    ```bash
    composer install
    ```
3. **Configurer la base de données** :
- **Créez une base de données MySQL nommée** : ticket_system
- **Importez le fichier SQL database.sql pour créer les tables nécessaires** :
    ```bash
    mysql -u utilisateur -p ticket_system < database.sql
    ```
4. **Configurer les variables d'environnement** :
- **Créez un fichier config.php à la racine du projet et ajoutez les informations de connexion à la base de données et les paramètres SMTP** :
```
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ticket_system');
define('DB_USER', 'root');
define('DB_PASS', 'votre_mot_de_passe');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'votre_email@gmail.com');
define('SMTP_PASS', 'votre_mot_de_passe');
define('SMTP_PORT', 587);
?>
```
5. **Configurer le cron job (pour la file d'attente des e-mails)** :
- **Ajoutez la ligne suivante à votre crontab pour exécuter le script de traitement de la file d'attente toutes les minutes** :
    ```bash
    * * * * * /usr/bin/php /chemin/vers/ticket-system/process_email_queue.php
    ```
6. **Démarrer le serveur** :
- **Vous pouvez utiliser le serveur intégré de PHP** :
    ```bash
    php -S localhost:8000
    ```
- **Accédez à l'application**

7. **Structure du Projet** :
```
/ticket-system
    /assets                   # Fichiers CSS & JavaScript
    /BDD                      # Fichier de base de données
    /emails                   # Modèles d'e-mails HTML
    /vendor                   # Dépendances Composer
    create_ticket.php         # Créer un ticket
    db.php                    # Connexion à la base de données
    index.php                 # Page d'accueil
    login.php                 # Connexion utilisateur
    mail_config.php           # Configuration PHPMailer
    process_email_queue.php   # Traitement de la file d'attente des e-mails
    rate_technician.php       # Noter un technicien
    update_ticket.php         # Mettre à jour un ticket
    view_tickets.php          # Voir les tickets
    README.md                 # Documentation
```
