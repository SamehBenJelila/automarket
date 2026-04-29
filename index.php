<?php
// =====================================================
// index.php — FRONT CONTROLLER (point d'entrée unique)
// =====================================================
// TOUTES les pages passent par ce fichier.
// L'URL : index.php?page=voitures
//                         ↑
//               on lit "page" dans $_GET
// =====================================================

// 1. Chargement de la configuration (constants + session)
require_once __DIR__ . '/config/config.php';

// 2. Chargement de la classe Database (disponible partout)
require_once __DIR__ . '/config/Database.php';

// 3. Lecture du paramètre "page" dans l'URL
//    Si absent → on affiche la liste des voitures par défaut
$page = $_GET['page'] ?? 'voitures';

// 4. Routage : selon la valeur de $page, on charge le bon contrôleur
switch ($page) {

    // --- Pages publiques ---

    case 'voitures':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->liste();
        break;

    case 'detail':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->detail();
        break;

    case 'recherche':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->recherche();
        break;

    // --- Authentification ---

    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // --- Pages protégées (connexion requise) ---

    case 'ajouter':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->ajouter();
        break;

    case 'modifier':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->modifier();
        break;

    case 'supprimer':
        require_once __DIR__ . '/controllers/CarController.php';
        (new CarController())->supprimer();
        break;

    // --- Admin ---

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->dashboard();
        break;

    // --- Page introuvable ---

    default:
        echo "<h1>404 - Page introuvable</h1>";
        break;
}
