<?php
// =====================================================
// controllers/CarController.php
// CONTRÔLEUR : gère toutes les actions sur les voitures
// Superglobales : $_GET, $_POST, $_FILES, $_SESSION
// =====================================================

require_once __DIR__ . '/../models/VoitureManager.php';
require_once __DIR__ . '/../models/MarqueManager.php';
require_once __DIR__ . '/../models/Uploader.php';

class CarController
{
    private VoitureManager $voitureManager;
    private MarqueManager  $marqueManager;
    private Uploader       $uploader;

    public function __construct()
    {
        $this->voitureManager = new VoitureManager();
        $this->marqueManager  = new MarqueManager();
        $this->uploader       = new Uploader();
    }

    // Afficher toutes les voitures
    public function liste(): void
    {
        $voitures = $this->voitureManager->findAll();
        $marques  = $this->marqueManager->findAll();
        require __DIR__ . '/../views/cars/liste.php';
    }

    // Afficher le détail d'une voiture (?page=detail&id=X)
    public function detail(): void
    {
        $id      = (int) ($_GET['id'] ?? 0);
        $voiture = $this->voitureManager->findById($id);
        if (!$voiture) {
            header('Location: ' . BASE_URL . '?page=voitures');
            exit;
        }
        require __DIR__ . '/../views/cars/detail.php';
    }

    // Recherche multicritères via $_GET
    public function recherche(): void
    {
        $filtres = [
            'q'         => trim($_GET['q']            ?? ''),
            'marque_id' => (int)   ($_GET['marque_id'] ?? 0),
            'carburant' => $_GET['carburant']           ?? '',
            'prix_max'  => (float) ($_GET['prix_max']  ?? 0),
            'annee_min' => (int)   ($_GET['annee_min'] ?? 0),
        ];
        $voitures = $this->voitureManager->search($filtres);
        $marques  = $this->marqueManager->findAll();
        require __DIR__ . '/../views/cars/recherche.php';
    }

    // Ajouter une annonce
    public function ajouter(): void
    {
        $this->exigerConnexion();
        $marques = $this->marqueManager->findAll();
        $erreur  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement upload image ($_FILES)
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploader->upload($_FILES['image']);
                if ($image === false) {
                    $erreur = $this->uploader->getError();
                    require __DIR__ . '/../views/cars/formulaire.php';
                    return;
                }
            }

            $data = [
                'titre'       => htmlspecialchars(trim($_POST['titre']       ?? '')),
                'marque_id'   => (int)   ($_POST['marque_id']                ?? 0),
                'modele'      => htmlspecialchars(trim($_POST['modele']       ?? '')),
                'annee'       => (int)   ($_POST['annee']                    ?? 0),
                'kilometrage' => (int)   ($_POST['kilometrage']              ?? 0),
                'prix'        => (float) ($_POST['prix']                     ?? 0),
                'carburant'   => $_POST['carburant']                         ?? '',
                'transmission'       => $_POST['transmission']                             ?? '',
                'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
                'image'       => $image,
                'statut'      => 'disponible',
                'user_id'     => $_SESSION['user_id'],
            ];

            $this->voitureManager->create($data);
            header('Location: ' . BASE_URL . '?page=admin');
            exit;
        }

        require __DIR__ . '/../views/cars/formulaire.php';
    }

    // Modifier une annonce — BUG FIXÉ : image + description maintenant inclus
    public function modifier(): void
    {
        $this->exigerConnexion();
        $id      = (int) ($_GET['id'] ?? 0);
        $voiture = $this->voitureManager->findById($id);
        $marques = $this->marqueManager->findAll();
        $erreur  = '';

        if (!$voiture) {
            header('Location: ' . BASE_URL . '?page=admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Garder l'image actuelle par défaut
            $image = $voiture['image'];

            // Si l'utilisateur a choisi une nouvelle image → on l'upload
            if (!empty($_FILES['image']['name'])) {
                $nouvelleImage = $this->uploader->upload($_FILES['image']);
                if ($nouvelleImage !== false) {
                    // Supprimer l'ancienne image du disque
                    if ($image) $this->uploader->delete($image);
                    $image = $nouvelleImage;
                } else {
                    $erreur = $this->uploader->getError();
                    $modeEdit = true;
                    require __DIR__ . '/../views/cars/formulaire.php';
                    return;
                }
            }

            // Toutes les données en un seul tableau (image incluse — BUG FIX)
            $data = [
                'titre'       => htmlspecialchars(trim($_POST['titre'])),
                'marque_id'   => (int)   $_POST['marque_id'],
                'modele'      => htmlspecialchars(trim($_POST['modele'])),
                'annee'       => (int)   $_POST['annee'],
                'kilometrage' => (int)   $_POST['kilometrage'],
                'prix'        => (float) $_POST['prix'],
                'carburant'   => $_POST['carburant'],
                'transmission'       => $_POST['transmission'],
                'description' => htmlspecialchars(trim($_POST['description'])),
                'image'       => $image,   // ← inclus ici (BUG FIX)
                'statut'      => $_POST['statut'],
            ];

            $this->voitureManager->update($id, $data);
            header('Location: ' . BASE_URL . '?page=admin');
            exit;
        }

        $modeEdit = true;
        require __DIR__ . '/../views/cars/formulaire.php';
    }

    // Supprimer une annonce (admin uniquement)
    public function supprimer(): void
    {
        $this->exigerConnexion('admin');
        $id      = (int) ($_GET['id'] ?? 0);
        $voiture = $this->voitureManager->findById($id);
        if ($voiture) {
            if ($voiture['image']) $this->uploader->delete($voiture['image']);
            $this->voitureManager->delete($id);
        }
        header('Location: ' . BASE_URL . '?page=admin');
        exit;
    }

    // Sécurité : redirige si pas connecté (ou pas le bon rôle)
    private function exigerConnexion(string $role = 'user'): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }
        if ($role === 'admin' && $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '?page=voitures');
            exit;
        }
    }
}
