<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
  <h1>Tableau de Bord</h1>
  <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?></p>
</div>

<!-- Stats -->
<div class="stats-row">
  <div class="stat-card">
    <div class="stat-num"><?= $totalVoitures ?></div>
    <div class="stat-label">Total</div>
  </div>
  <?php foreach ($stats as $s): ?>
    <div class="stat-card">
      <div class="stat-num"><?= $s['total'] ?></div>
      <div class="stat-label"><?= ucfirst($s['statut']) ?></div>
    </div>
  <?php endforeach; ?>
  <div class="stat-card">
    <div class="stat-num"><?= count($users) ?></div>
    <div class="stat-label">Utilisateurs</div>
  </div>
</div>

<!-- Table voitures -->
<div class="section-header">
  <span class="section-title">📋 Toutes les annonces</span>
  <a href="<?= BASE_URL ?>?page=ajouter" class="btn btn-primary btn-sm">+ Nouvelle</a>
</div>
<div class="table-card" style="margin-bottom:2rem;">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Titre</th><th>Marque</th><th>Année</th><th>Prix</th><th>Statut</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($voitures as $v): ?>
          <tr>
            <td style="color:#ccc;"><?= $v['id'] ?></td>
            <td><a href="<?= BASE_URL ?>?page=detail&id=<?= $v['id'] ?>" style="font-weight:600;"><?= htmlspecialchars($v['titre']) ?></a></td>
            <td style="color:#888;"><?= htmlspecialchars($v['marque_nom']) ?></td>
            <td style="color:#888;"><?= $v['annee'] ?></td>
            <td style="font-weight:700; color:#e53935;"><?= number_format($v['prix'],0,',',' ') ?> TND</td>
            <td><span class="status-badge status-<?= $v['statut'] ?>"><?= ucfirst($v['statut']) ?></span></td>
            <td>
              <div class="td-actions">
                <a href="<?= BASE_URL ?>?page=modifier&id=<?= $v['id'] ?>" class="btn btn-edit-sm">✏️ Modifier</a>
                <a href="<?= BASE_URL ?>?page=supprimer&id=<?= $v['id'] ?>"
                   class="btn btn-danger-sm"
                   onclick="return confirm('Supprimer cette annonce ?')">🗑️</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Table utilisateurs -->
<div class="section-header">
  <span class="section-title">👥 Utilisateurs</span>
</div>
<div class="table-card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Inscription</th></tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td style="color:#ccc;"><?= $u['id'] ?></td>
            <td style="font-weight:600;"><?= htmlspecialchars($u['nom']) ?></td>
            <td style="color:#888;"><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] === 'admin' ? '<span style="color:#e53935;font-weight:700;">Admin</span>' : '<span style="color:#aaa;">User</span>' ?></td>
            <td style="color:#aaa; font-size:.82rem;"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
