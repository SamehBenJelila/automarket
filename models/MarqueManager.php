<?php
// =====================================================
// models/MarqueManager.php
// CLASSE MANAGER : opérations SQL sur les marques
// =====================================================

class MarqueManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Lire toutes les marques (pour les listes déroulantes)
    public function findAll(): array
    {
        return $this->db->query("SELECT * FROM marques ORDER BY nom")->fetchAll();
    }

    // Ajouter une marque
    public function create(string $nom): bool
    {
        $stmt = $this->db->prepare("INSERT INTO marques (nom) VALUES (?)");
        return $stmt->execute([$nom]);
    }

    // Supprimer une marque
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM marques WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
