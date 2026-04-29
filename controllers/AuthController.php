<?php
// =====================================================
// controllers/AuthController.php
// CONTRÔLEUR : gère la connexion et l'inscription
// Superglobales utilisées : $_POST, $_SESSION
// =====================================================

// On charge les classes dont on a besoin
require_once __DIR__ . '/../models/UserManager.php';

class AuthController
{
    private UserManager $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    // --------------------------------------------------
    // login() : affiche le formulaire OU traite la connexion
    // --------------------------------------------------
    public function login(): void
    {
        $erreur = ''; // message d'erreur à afficher

        // Le formulaire a été soumis (méthode POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des données du formulaire via $_POST
            $email    = trim($_POST['email']    ?? '');
            $password =      $_POST['password'] ?? '';

            // Validation simple
            if (empty($email) || empty($password)) {
                $erreur = "Veuillez remplir tous les champs.";
            } else {
                // Chercher l'utilisateur dans la base
                $user = $this->userManager->findByEmail($email);

                // password_verify() compare le mot de passe avec le hash stocké
                if ($user && password_verify($password, $user['password'])) {
                    // Connexion réussie → on remplit la session
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_nom']  = $user['nom'];
                    $_SESSION['user_role'] = $user['role'];

                    // Redirection vers la liste des voitures
                    header('Location: ' . BASE_URL . '?page=voitures');
                    exit;
                } else {
                    $erreur = "Email ou mot de passe incorrect.";
                }
            }
        }

        // Affichage de la vue (avec $erreur disponible dans la vue)
        require __DIR__ . '/../views/auth/login.php';
    }

    // --------------------------------------------------
    // register() : affiche le formulaire OU traite l'inscription
    // --------------------------------------------------
    public function register(): void
    {
        $erreur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nom      = trim(htmlspecialchars($_POST['nom']      ?? ''));
            $email    = trim($_POST['email']                     ?? '');
            $password =      $_POST['password']                  ?? '';

            if (empty($nom) || empty($email) || empty($password)) {
                $erreur = "Tous les champs sont obligatoires.";
            } elseif ($this->userManager->emailExiste($email)) {
                $erreur = "Cet email est déjà utilisé.";
            } else {
                $this->userManager->create($nom, $email, $password);
                // Redirection vers la page de connexion après inscription
                header('Location: ' . BASE_URL . '?page=login&ok=1');
                exit;
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    // --------------------------------------------------
    // logout() : détruit la session et redirige
    // --------------------------------------------------
    public function logout(): void
    {
        session_destroy(); // supprime toutes les variables de session
        header('Location: ' . BASE_URL . '?page=login');
        exit;
    }
}
