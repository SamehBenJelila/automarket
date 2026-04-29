<?php
// =====================================================
// config/config.php
// POINT CENTRAL : tous les réglages du site ici
// =====================================================

// --- Base de données ---
define('DB_HOST', 'localhost');   // serveur MySQL (toujours localhost sur XAMPP)
define('DB_NAME', 'automarket');  // nom de la base de données
define('DB_USER', 'root');        // utilisateur MySQL (root par défaut sur XAMPP)
define('DB_PASS', '');            // mot de passe (vide par défaut sur XAMPP)

// --- URL du site ---
// IMPORTANT : changer si votre dossier s'appelle autrement
define('BASE_URL', 'http://localhost/automarket_easy/');

// --- Dossier d'upload des images ---
define('UPLOAD_DIR', __DIR__ . '/../uploads/cars/');

// --- Taille max fichier (5 Mo) ---
define('MAX_SIZE', 5 * 1024 * 1024);

// --- Types d'images autorisés ---
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// --- Démarrage automatique de la session ---
// (nécessaire pour $_SESSION)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
