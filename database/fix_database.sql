-- =====================================================
-- fix_database.sql
-- EXÉCUTER SEULEMENT si vous avez déjà importé l'ancien automarket.sql
-- Ce script corrige les colonnes manquantes / mal nommées
-- =====================================================

USE automarket;

-- 1. Si la colonne s'appelle "boite", la renommer en "transmission"
-- (ignoré si elle s'appelle déjà "transmission")
ALTER TABLE voitures 
  CHANGE COLUMN IF EXISTS boite transmission ENUM('Manuelle','Automatique') NOT NULL DEFAULT 'Manuelle';

-- 2. S'assurer que la colonne description existe et accepte du texte long
ALTER TABLE voitures 
  MODIFY COLUMN description TEXT NULL;

-- 3. S'assurer que la colonne image existe
ALTER TABLE voitures
  MODIFY COLUMN image VARCHAR(255) NOT NULL DEFAULT '';

-- 4. Mettre à jour les descriptions vides avec des valeurs de test
UPDATE voitures SET description = 'Très bon état. Révision faite. Clim, GPS, caméra.'  WHERE titre LIKE '%Peugeot%'  AND (description IS NULL OR description = '');
UPDATE voitures SET description = 'Comme neuve. Première main. Garantie restante.'     WHERE titre LIKE '%Clio%'     AND (description IS NULL OR description = '');
UPDATE voitures SET description = 'Full options. Toit ouvrant. Cuir. Parfait état.'    WHERE titre LIKE '%Golf%'     AND (description IS NULL OR description = '');
UPDATE voitures SET description = 'Faible conso. Très fiable. Garantie constructeur.'  WHERE titre LIKE '%Yaris%'    AND (description IS NULL OR description = '');
UPDATE voitures SET description = 'Cuir, GPS, caméra recul. Entretien BMW.'            WHERE titre LIKE '%BMW%'      AND (description IS NULL OR description = '');

-- 5. Mettre à jour les images
UPDATE voitures SET image = 'car_peugeot308.jpg'  WHERE titre LIKE '%Peugeot%'  AND (image IS NULL OR image = '');
UPDATE voitures SET image = 'car_renaultclio.jpg' WHERE titre LIKE '%Clio%'     AND (image IS NULL OR image = '');
UPDATE voitures SET image = 'car_vwgolf.jpg'      WHERE titre LIKE '%Golf%'     AND (image IS NULL OR image = '');
UPDATE voitures SET image = 'car_toyotayaris.jpg' WHERE titre LIKE '%Yaris%'    AND (image IS NULL OR image = '');
UPDATE voitures SET image = 'car_bmw3.jpg'        WHERE titre LIKE '%BMW%'      AND (image IS NULL OR image = '');

-- Vérification finale
SELECT id, titre, transmission, description, image FROM voitures;
