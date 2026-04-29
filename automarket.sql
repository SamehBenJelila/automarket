-- =====================================================
-- AutoMarket - Base de données complète
-- Importer dans phpMyAdmin sur la base "automarket"
-- =====================================================

CREATE DATABASE IF NOT EXISTS automarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE automarket;

-- -----------------------------------------------------
-- TABLE 1 : utilisateurs (admins et clients)
-- -----------------------------------------------------
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,          -- hashé avec password_hash()
    role       ENUM('admin','user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- TABLE 2 : marques de voitures
-- -----------------------------------------------------
CREATE TABLE marques (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- -----------------------------------------------------
-- TABLE 3 : voitures (liée à users et marques)
-- -----------------------------------------------------
CREATE TABLE voitures (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    titre        VARCHAR(200) NOT NULL,
    marque_id    INT NOT NULL,
    modele       VARCHAR(100) NOT NULL,
    annee        YEAR NOT NULL,
    kilometrage  INT NOT NULL,
    prix         DECIMAL(10,2) NOT NULL,
    carburant    ENUM('Essence','Diesel','Hybride','Electrique') NOT NULL,
    transmission ENUM('Manuelle','Automatique') NOT NULL,
    description  TEXT,
    image        VARCHAR(255) DEFAULT '',      -- nom du fichier image uploadé
    statut       ENUM('disponible','vendu','reservé') DEFAULT 'disponible',
    user_id      INT NOT NULL,                 -- qui a posté l'annonce
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marque_id) REFERENCES marques(id),
    FOREIGN KEY (user_id)   REFERENCES users(id)
);

-- -----------------------------------------------------
-- DONNÉES DE TEST
-- -----------------------------------------------------

-- Utilisateurs (mot de passe = "password" pour tous)
INSERT INTO users (nom, email, password, role) VALUES
('Admin',   'admin@auto.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Mohamed', 'user@auto.tn',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Marques
INSERT INTO marques (nom) VALUES
('Peugeot'), ('Renault'), ('Volkswagen'), ('Toyota'), ('BMW'),
('Mercedes'), ('Audi'), ('Fiat'), ('Hyundai'), ('Kia');

-- Voitures avec images
INSERT INTO voitures (titre, marque_id, modele, annee, kilometrage, prix, carburant, transmission, description, image, statut, user_id) VALUES
('Peugeot 308 THP Full Options',  1, '308',      2019,  65000, 14500, 'Essence', 'Manuelle',    'Très bon état. Révision faite. Clim, GPS, caméra.', 'car_peugeot308.jpg',  'disponible', 1),
('Renault Clio 5 Première Main',  2, 'Clio',     2021,  32000, 12800, 'Essence', 'Manuelle',    'Comme neuve. Première main. Garantie restante.',    'car_renaultclio.jpg', 'disponible', 1),
('Volkswagen Golf 7 Toit Ouvrant',3, 'Golf',     2018,  80000, 16200, 'Diesel',  'Manuelle',    'Full options. Toit ouvrant. Cuir. Parfait état.',   'car_vwgolf.jpg',      'disponible', 2),
('Toyota Yaris Hybrid Eco',       4, 'Yaris',    2020,  45000, 13900, 'Hybride', 'Automatique', 'Faible conso. Très fiable. Garantie constructeur.', 'car_toyotayaris.jpg', 'disponible', 1),
('BMW Série 3 Pack Sport',        5, 'Série 3',  2017, 110000, 19500, 'Diesel',  'Automatique', 'Cuir, GPS, caméra recul. Entretien BMW.',           'car_bmw3.jpg',        'reservé',    2);
