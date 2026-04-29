<?php
// =====================================================
// models/VoitureManager.php
// CLASSE MANAGER : toutes les opérations SQL sur les voitures
// CRUD = Create, Read, Update, Delete
// =====================================================

class VoitureManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Lire toutes les voitures avec le nom de la marque (JOIN)
    public function findAll(): array
    {
        $sql  = "SELECT v.*, m.nom AS marque_nom
                 FROM voitures v
                 JOIN marques m ON v.marque_id = m.id
                 ORDER BY v.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lire UNE voiture par son id
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT v.*, m.nom AS marque_nom
             FROM voitures v
             JOIN marques m ON v.marque_id = m.id
             WHERE v.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Recherche multicritères — SQL dynamique selon les filtres actifs
    public function search(array $filtres): array
    {
        $sql    = "SELECT v.*, m.nom AS marque_nom FROM voitures v JOIN marques m ON v.marque_id=m.id WHERE 1=1";
        $params = [];

        if (!empty($filtres['q'])) {
            $sql     .= " AND (v.titre LIKE ? OR v.modele LIKE ?)";
            $params[] = "%" . $filtres['q'] . "%";
            $params[] = "%" . $filtres['q'] . "%";
        }
        if (!empty($filtres['marque_id'])) {
            $sql     .= " AND v.marque_id = ?";
            $params[] = $filtres['marque_id'];
        }
        if (!empty($filtres['carburant'])) {
            $sql     .= " AND v.carburant = ?";
            $params[] = $filtres['carburant'];
        }
        if (!empty($filtres['prix_max'])) {
            $sql     .= " AND v.prix <= ?";
            $params[] = $filtres['prix_max'];
        }
        if (!empty($filtres['annee_min'])) {
            $sql     .= " AND v.annee >= ?";
            $params[] = $filtres['annee_min'];
        }

        $sql .= " ORDER BY v.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // CREATE — Ajouter une nouvelle voiture
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO voitures
             (titre, marque_id, modele, annee, kilometrage, prix, carburant, transmission, description, image, statut, user_id)
             VALUES
             (:titre, :marque_id, :modele, :annee, :kilometrage, :prix, :carburant, :transmission, :description, :image, :statut, :user_id)"
        );
        return $stmt->execute($data);
    }

    // UPDATE — Modifier une voiture (texte + image dans une seule requête)
    // BUG FIXÉ : description et image étaient absentes de la clause SET
    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            "UPDATE voitures SET
             titre=:titre, marque_id=:marque_id, modele=:modele, annee=:annee,
             kilometrage=:kilometrage, prix=:prix, carburant=:carburant, transmission=:transmission,
             description=:description, image=:image, statut=:statut
             WHERE id=:id"
        );
        return $stmt->execute($data);
    }

    // DELETE — Supprimer une voiture
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM voitures WHERE id=?");
        return $stmt->execute([$id]);
    }

    // COUNT — Nombre total de voitures
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM voitures")->fetchColumn();
    }

    // STATS — Compter par statut (pour le dashboard admin)
    public function statsParStatut(): array
    {
        return $this->db->query(
            "SELECT statut, COUNT(*) as total FROM voitures GROUP BY statut"
        )->fetchAll();
    }
}
