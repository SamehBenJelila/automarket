# 🚗 AutoMarket — Site de Vente de Voitures d'Occasion

> Projet PHP/MVC complet avec explications détaillées dans chaque fichier.

---

## 📁 Structure du projet (Architecture MVC)

```
automarket_easy/
│
├── index.php                  ← POINT D'ENTRÉE UNIQUE (Front Controller)
│                                 Toutes les URLs passent ici : ?page=voitures
│
├── config/
│   ├── config.php             ← Réglages (BDD, URL, session)
│   └── Database.php           ← Connexion PDO (Singleton)
│
├── models/                    ← LOGIQUE MÉTIER (accès BDD)
│   ├── VoitureManager.php     ← CRUD voitures + recherche
│   ├── UserManager.php        ← CRUD utilisateurs
│   ├── MarqueManager.php      ← CRUD marques
│   └── Uploader.php           ← Upload d'images
│
├── controllers/               ← LOGIQUE DE CONTRÔLE
│   ├── AuthController.php     ← Login / Logout / Inscription
│   ├── CarController.php      ← Afficher, ajouter, modifier, supprimer
│   └── AdminController.php    ← Dashboard administrateur
│
├── views/                     ← AFFICHAGE HTML
│   ├── layout/
│   │   ├── header.php         ← Navigation + styles CSS
│   │   └── footer.php         ← Pied de page
│   ├── cars/
│   │   ├── liste.php          ← Page d'accueil avec grille
│   │   ├── detail.php         ← Fiche d'une voiture
│   │   ├── recherche.php      ← Recherche multicritères
│   │   └── formulaire.php     ← Ajouter / Modifier (réutilisé)
│   ├── auth/
│   │   ├── login.php          ← Formulaire connexion
│   │   └── register.php       ← Formulaire inscription
│   └── admin/
│       └── dashboard.php      ← Tableau de bord admin
│
├── uploads/cars/              ← Images uploadées (5 images incluses)
│
└── database/
    └── automarket.sql         ← Script SQL complet (tables + données test)
```

---

## ⚙️ Installation XAMPP (Windows)

### Étape 1 — Copier le dossier
Extraire le ZIP et copier `automarket_easy` dans :
```
C:\xampp\htdocs\automarket_easy\
```

### Étape 2 — Importer la base de données
1. Démarrer **Apache** et **MySQL** dans XAMPP Control Panel
2. Ouvrir `http://localhost/phpmyadmin`
3. Créer une base de données nommée `automarket`
4. Cliquer sur **Importer** → choisir `database/automarket.sql` → **Exécuter**

### Étape 3 — Ouvrir le site
```
http://localhost/automarket_easy/
```

---

## 🔑 Comptes de test

| Rôle  | Email           | Mot de passe |
|-------|-----------------|--------------|
| Admin | admin@auto.tn   | password     |
| User  | user@auto.tn    | password     |

---

## 🗺️ Comment naviguer dans le code

### Flux d'une requête HTTP

```
Navigateur → index.php → Controller → Model → View → Navigateur
```

**Exemple** : l'utilisateur clique sur "Annonces"
1. URL : `index.php?page=voitures`
2. `index.php` lit `$_GET['page']` = `'voitures'`
3. Il charge `CarController` et appelle `liste()`
4. `CarController::liste()` appelle `VoitureManager::findAll()`
5. `VoitureManager` fait la requête SQL, retourne un tableau `$voitures`
6. `CarController` charge la vue `views/cars/liste.php`
7. La vue affiche le HTML avec les données `$voitures`

---

## 🔐 Sécurité

| Risque         | Protection utilisée                          |
|----------------|----------------------------------------------|
| Injection SQL  | Requêtes préparées PDO (`prepare/execute`)   |
| XSS            | `htmlspecialchars()` sur toutes les sorties  |
| Upload malveillant | Vérification du type MIME réel (`finfo`) |
| Accès non autorisé | Vérification `$_SESSION` dans les contrôleurs |
| Mot de passe   | `password_hash()` + `password_verify()`      |

---

## 📚 Superglobales utilisées

| Superglobale | Où | Pourquoi |
|---|---|---|
| `$_GET`     | Tous les contrôleurs | Lire l'URL (`?page=`, `?id=`) |
| `$_POST`    | Auth + Car controllers | Lire les données de formulaire |
| `$_FILES`   | CarController | Récupérer l'image uploadée |
| `$_SESSION` | Partout | Savoir si l'utilisateur est connecté |
