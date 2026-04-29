<?php
// =====================================================
// controllers/AdminController.php
// CONTRÔLEUR : tableau de bord administrateur
// =====================================================

require_once __DIR__ . '/../models/VoitureManager.php';
require_once __DIR__ . '/../models/UserManager.php';
require_once __DIR__ . '/../models/MarqueManager.php';

class AdminController
{
    private VoitureManager $voitureManager;
    private UserManager    $userManager;
    private MarqueManager  $marqueManager;

    public function __construct()
    {
        // Vérification immédiate : seul l'admin accède ici
        if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $this->voitureManager = new VoitureManager();
        $this->userManager    = new UserManager();
        $this->marqueManager  = new MarqueManager();
    }

    // Dashboard principal avec toutes les données
    public function dashboard(): void
    {
        $voitures     = $this->voitureManager->findAll();
        $users        = $this->userManager->findAll();
        $marques      = $this->marqueManager->findAll();
        $stats        = $this->voitureManager->statsParStatut();
        $totalVoitures = $this->voitureManager->count();

        require __DIR__ . '/../views/admin/dashboard.php';
    }
}
