<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AutoMarket — Voitures d'Occasion</title>
  <style>
    /* ========== RESET ========== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f5f7;
      color: #1a1a2e;
      min-height: 100vh;
    }
    a { text-decoration: none; color: inherit; }
    img { display: block; max-width: 100%; }

    /* ========== NAVBAR ========== */
    .navbar {
      background: #fff;
      border-bottom: 1px solid #e8eaed;
      position: sticky; top: 0; z-index: 200;
      box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .navbar-inner {
      max-width: 1180px; margin: 0 auto;
      padding: 0 1.5rem;
      height: 62px;
      display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .logo {
      font-size: 1.35rem; font-weight: 800;
      letter-spacing: -0.5px; color: #1a1a2e;
      white-space: nowrap;
    }
    .logo span { color: #e53935; }
    .nav-links { display: flex; align-items: center; gap: .5rem; }
    .nav-link {
      padding: .45rem .85rem; border-radius: 6px;
      font-size: .875rem; font-weight: 500; color: #555;
      transition: background .15s, color .15s;
    }
    .nav-link:hover { background: #f0f1f3; color: #1a1a2e; }
    .nav-user { font-size: .82rem; color: #888; padding: 0 .5rem; }

    /* ========== BOUTONS ========== */
    .btn {
      display: inline-flex; align-items: center; gap: .35rem;
      padding: .5rem 1.1rem; border-radius: 7px;
      font-size: .875rem; font-weight: 600;
      cursor: pointer; border: none; transition: all .18s;
      white-space: nowrap;
    }
    .btn-primary   { background: #e53935; color: #fff; }
    .btn-primary:hover { background: #c62828; }
    .btn-outline   { background: transparent; color: #555; border: 1.5px solid #ddd; }
    .btn-outline:hover { border-color: #aaa; color: #222; }
    .btn-ghost     { background: #f0f1f3; color: #444; border: none; }
    .btn-ghost:hover { background: #e4e5e8; }
    .btn-danger-sm { background: #fff0f0; color: #c62828; border: 1.5px solid #fcc; font-size: .8rem; padding: .3rem .7rem; }
    .btn-danger-sm:hover { background: #fdd; }
    .btn-edit-sm   { background: #f0f4ff; color: #3355cc; border: 1.5px solid #ccd6ff; font-size: .8rem; padding: .3rem .7rem; }
    .btn-edit-sm:hover { background: #dde5ff; }
    .btn-sm        { padding: .35rem .85rem; font-size: .82rem; }
    .btn-block     { width: 100%; justify-content: center; }

    /* ========== LAYOUT ========== */
    .page { max-width: 1180px; margin: 0 auto; padding: 2rem 1.5rem; }

    /* ========== PAGE HEADER ========== */
    .page-header { margin-bottom: 1.8rem; }
    .page-header h1 { font-size: 1.6rem; font-weight: 800; color: #1a1a2e; }
    .page-header p  { color: #777; margin-top: .3rem; font-size: .9rem; }

    /* ========== ALERTS ========== */
    .alert { padding: .8rem 1rem; border-radius: 7px; font-size: .875rem; margin-bottom: 1.2rem; }
    .alert-error   { background: #fff3f3; border: 1px solid #fcc; color: #c62828; }
    .alert-success { background: #f0faf0; border: 1px solid #b8e0b8; color: #2a7a2a; }

    /* ========== FILTER BAR ========== */
    .filter-bar {
      background: #fff;
      border: 1px solid #e8eaed;
      border-radius: 10px;
      padding: 1.2rem 1.4rem;
      margin-bottom: 1.8rem;
      box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .filter-bar form {
      display: flex; flex-wrap: wrap; gap: .7rem; align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: .3rem; flex: 1; min-width: 130px; }
    .filter-group label { font-size: .75rem; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: .4px; }
    .filter-group input,
    .filter-group select {
      height: 38px; padding: 0 .75rem;
      border: 1.5px solid #e0e2e6; border-radius: 6px;
      font-size: .875rem; color: #222;
      background: #fafbfc;
      transition: border-color .15s;
    }
    .filter-group input:focus,
    .filter-group select:focus { outline: none; border-color: #e53935; background: #fff; }

    /* ========== CARS GRID ========== */
    .cars-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.2rem;
    }

    /* ========== CAR CARD ========== */
    .car-card {
      background: #fff;
      border: 1px solid #e8eaed;
      border-radius: 10px;
      overflow: hidden;
      transition: transform .18s, box-shadow .18s;
      cursor: pointer;
    }
    .car-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }
    .car-card-img {
      width: 100%; height: 185px; object-fit: cover;
      background: #f0f1f3;
    }
    .car-card-img-placeholder {
      width: 100%; height: 185px;
      background: linear-gradient(135deg, #eef0f3, #dfe2e8);
      display: flex; align-items: center; justify-content: center;
      font-size: 3rem;
    }
    .car-card-body { padding: 1rem 1.1rem 1.1rem; }
    .car-card-brand { font-size: .72rem; font-weight: 700; color: #e53935; text-transform: uppercase; letter-spacing: .6px; margin-bottom: .25rem; }
    .car-card-title { font-size: .95rem; font-weight: 700; color: #1a1a2e; margin-bottom: .6rem; line-height: 1.3; }
    .car-tags { display: flex; flex-wrap: wrap; gap: .35rem; margin-bottom: .8rem; }
    .car-tag {
      background: #f0f1f3; color: #555;
      padding: .18rem .55rem; border-radius: 4px; font-size: .73rem; font-weight: 500;
    }
    .car-card-footer { display: flex; justify-content: space-between; align-items: center; }
    .car-price { font-size: 1.15rem; font-weight: 800; color: #e53935; }
    .status-badge {
      font-size: .7rem; font-weight: 700; padding: .22rem .6rem;
      border-radius: 20px; text-transform: uppercase; letter-spacing: .3px;
    }
    .status-disponible { background: #e8f5e9; color: #2e7d32; }
    .status-vendu      { background: #ffebee; color: #c62828; }
    .status-reservé    { background: #fff8e1; color: #f57f17; }

    /* ========== DETAIL PAGE ========== */
    .detail-wrap { background: #fff; border: 1px solid #e8eaed; border-radius: 12px; overflow: hidden; }
    .detail-img  { width: 100%; max-height: 420px; object-fit: cover; }
    .detail-img-placeholder {
      height: 300px; background: linear-gradient(135deg,#eef0f3,#dfe2e8);
      display:flex; align-items:center; justify-content:center; font-size:5rem;
    }
    .detail-body { padding: 2rem; }
    .detail-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .detail-title  { font-size: 1.6rem; font-weight: 800; color: #1a1a2e; }
    .detail-subtitle { color: #888; font-size: .9rem; margin-top: .3rem; }
    .detail-price { font-size: 2rem; font-weight: 900; color: #e53935; }
    .specs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px,1fr)); gap: .8rem; margin-bottom: 1.5rem; }
    .spec-box {
      background: #f8f9fb; border: 1px solid #e8eaed; border-radius: 8px;
      padding: .8rem; text-align: center;
    }
    .spec-label { font-size: .72rem; color: #999; text-transform: uppercase; letter-spacing: .4px; }
    .spec-value { font-size: .95rem; font-weight: 700; margin-top: .25rem; color: #1a1a2e; }
    .detail-desc-title { font-size: .75rem; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .6rem; }
    .detail-desc { color: #444; line-height: 1.75; font-size: .925rem; }
    .divider { border: none; border-top: 1px solid #e8eaed; margin: 1.5rem 0; }
    .back-link { display: inline-flex; align-items: center; gap: .35rem; color: #888; font-size: .85rem; margin-bottom: 1.2rem; }
    .back-link:hover { color: #333; }

    /* ========== FORM ========== */
    .form-card {
      background: #fff; border: 1px solid #e8eaed; border-radius: 12px;
      padding: 2rem; max-width: 700px;
      box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
      display: block; margin-bottom: .35rem;
      font-size: .8rem; font-weight: 600; color: #666;
      text-transform: uppercase; letter-spacing: .4px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%; padding: .6rem .9rem;
      border: 1.5px solid #e0e2e6; border-radius: 7px;
      font-size: .9rem; color: #222; background: #fafbfc;
      font-family: inherit; transition: border-color .15s, background .15s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #e53935; background: #fff; }
    .form-group textarea { resize: vertical; min-height: 95px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    /* Image upload zone */
    .upload-zone {
      border: 2px dashed #d8dae0; border-radius: 8px;
      padding: 1.2rem; text-align: center;
      background: #fafbfc; cursor: pointer;
      transition: border-color .15s, background .15s;
    }
    .upload-zone:hover { border-color: #e53935; background: #fff9f9; }
    .upload-zone input[type="file"] { display: none; }
    .upload-preview { width: 100%; height: 160px; object-fit: cover; border-radius: 6px; margin-bottom: .8rem; }
    .upload-hint { font-size: .8rem; color: #aaa; margin-top: .4rem; }

    /* ========== ADMIN TABLE ========== */
    .table-card { background: #fff; border: 1px solid #e8eaed; border-radius: 10px; overflow: hidden; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    thead th {
      padding: .75rem 1rem; background: #f8f9fb;
      font-size: .72rem; font-weight: 700; color: #999;
      text-transform: uppercase; letter-spacing: .5px;
      border-bottom: 1px solid #e8eaed; text-align: left;
    }
    tbody td { padding: .8rem 1rem; border-bottom: 1px solid #f0f1f3; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: #fafbfc; }
    .td-actions { display: flex; gap: .4rem; }

    /* ========== STATS ========== */
    .stats-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px,1fr)); gap: 1rem; margin-bottom: 1.8rem; }
    .stat-card { background: #fff; border: 1px solid #e8eaed; border-radius: 10px; padding: 1.2rem; text-align: center; }
    .stat-num   { font-size: 2rem; font-weight: 900; color: #e53935; line-height: 1; }
    .stat-label { font-size: .78rem; color: #aaa; margin-top: .35rem; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }

    /* ========== AUTH CARDS ========== */
    .auth-wrap { max-width: 400px; margin: 3.5rem auto; }
    .auth-card { background: #fff; border: 1px solid #e8eaed; border-radius: 14px; padding: 2.2rem; box-shadow: 0 4px 20px rgba(0,0,0,.07); }
    .auth-title { font-size: 1.5rem; font-weight: 800; color: #1a1a2e; text-align: center; margin-bottom: 1.6rem; }
    .auth-footer { text-align: center; margin-top: 1.2rem; font-size: .875rem; color: #888; }
    .auth-footer a { color: #e53935; font-weight: 600; }
    .auth-hint { background: #f8f9fb; border-radius: 7px; padding: .7rem 1rem; font-size: .78rem; color: #aaa; text-align: center; margin-top: .9rem; line-height: 1.6; }

    /* ========== SECTION TITLES ========== */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .section-title  { font-size: 1rem; font-weight: 700; color: #1a1a2e; }

    /* ========== EMPTY STATE ========== */
    .empty-state { text-align: center; padding: 3rem 1rem; color: #bbb; }
    .empty-state div { font-size: 2.5rem; margin-bottom: .8rem; }

    /* ========== FOOTER ========== */
    footer { border-top: 1px solid #e8eaed; background: #fff; text-align: center; padding: 1.5rem; color: #bbb; font-size: .8rem; margin-top: 3rem; }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="<?= BASE_URL ?>" class="logo">Auto<span>Market</span></a>
    <div class="nav-links">
      <a href="<?= BASE_URL ?>?page=voitures"  class="nav-link">Annonces</a>
      <a href="<?= BASE_URL ?>?page=recherche" class="nav-link">Recherche</a>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
          <a href="<?= BASE_URL ?>?page=admin" class="nav-link" style="color:#e53935; font-weight:700;">⚙ Admin</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?page=ajouter" class="btn btn-primary btn-sm">+ Annonce</a>
        <span class="nav-user"><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
        <a href="<?= BASE_URL ?>?page=logout" class="btn btn-outline btn-sm">Déconnexion</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>?page=login"    class="btn btn-outline btn-sm">Connexion</a>
        <a href="<?= BASE_URL ?>?page=register" class="btn btn-primary btn-sm">S'inscrire</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="page">
