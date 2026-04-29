<?php
// =====================================================
// models/UserManager.php
// CLASSE MANAGER : opérations SQL sur les utilisateurs
// =====================================================

class UserManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Trouver un utilisateur par email (pour la connexion)
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Trouver tous les utilisateurs (pour l'admin)
    public function findAll(): array
    {
        return $this->db->query(
            "SELECT id, nom, email, role, created_at FROM users ORDER BY created_at DESC"
        )->fetchAll();
    }

    // Créer un utilisateur (inscription)
    public function create(string $nom, string $email, string $password): bool
    {
        // password_hash() crypte le mot de passe → jamais stocker en clair !
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'user')"
        );
        return $stmt->execute([$nom, $email, $hash]);
    }

    // Vérifier si un email existe déjà
    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn(); // true si count > 0
    }

    // Supprimer un utilisateur
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
