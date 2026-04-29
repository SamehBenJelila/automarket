<?php
// =====================================================
// config/Database.php
// CLASSE : connexion à MySQL avec PDO
// PATTERN : Singleton (une seule connexion dans tout le projet)
// =====================================================

class Database
{
    // $instance garde en mémoire la seule connexion créée
    private static ?Database $instance = null;

    // $pdo est l'objet de connexion PDO
    private PDO $pdo;

    // Le constructeur est PRIVÉ → on ne peut pas faire "new Database()" de l'extérieur
    private function __construct()
    {
        // Construction de la chaîne de connexion DSN
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // affiche les erreurs SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // retourne des tableaux associatifs
            ]);
        } catch (PDOException $e) {
            // Si la connexion échoue, on arrête tout et on affiche l'erreur
            die("❌ Connexion base de données échouée : " . $e->getMessage());
        }
    }

    // Méthode publique pour obtenir (ou créer) l'instance unique
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database(); // créée une seule fois
        }
        return self::$instance; // retourne toujours la même
    }

    // Retourne l'objet PDO pour faire les requêtes
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
