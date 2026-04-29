<?php
// =====================================================
// models/Uploader.php
// CLASSE UTILITAIRE : gestion de l'upload d'images
// Utilisée dans CarController pour $_FILES
// =====================================================

class Uploader
{
    private string $error = ''; // stocke le message d'erreur s'il y en a un

    // --------------------------------------------------
    // upload() : traite un fichier envoyé via formulaire
    // Paramètre : $file = un élément de $_FILES['image']
    // Retourne  : le nom du fichier sauvegardé, ou false si erreur
    // --------------------------------------------------
    public function upload(array $file): string|false
    {
        // 1. Vérifier qu'il n'y a pas eu d'erreur lors de l'envoi
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = "Erreur lors de l'envoi du fichier.";
            return false;
        }

        // 2. Vérifier la taille du fichier (max 5 Mo)
        if ($file['size'] > MAX_SIZE) {
            $this->error = "Image trop lourde (maximum 5 Mo).";
            return false;
        }

        // 3. Vérifier le vrai type MIME du fichier (sécurité)
        // finfo lit les octets du fichier, pas juste l'extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ALLOWED_TYPES)) {
            $this->error = "Type non autorisé. Utilisez JPG, PNG ou WEBP.";
            return false;
        }

        // 4. Générer un nom unique pour éviter les conflits
        // ex : car_6654a3b2f1234.jpg
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName   = 'car_' . uniqid() . '.' . $extension;

        // 5. Créer le dossier s'il n'existe pas encore
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        // 6. Déplacer le fichier du dossier temporaire vers uploads/cars/
        $destination = UPLOAD_DIR . $newName;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->error = "Impossible de sauvegarder le fichier.";
            return false;
        }

        return $newName; // succès : on retourne le nom du fichier
    }

    // --------------------------------------------------
    // delete() : supprime un fichier image du serveur
    // --------------------------------------------------
    public function delete(string $filename): void
    {
        $path = UPLOAD_DIR . $filename;
        if (file_exists($path)) {
            unlink($path); // supprime le fichier
        }
    }

    // Retourne le message d'erreur (après un upload raté)
    public function getError(): string
    {
        return $this->error;
    }
}
