<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
  <h1>Recherche Avancée</h1>
</div>

<div class="filter-bar">
  <form method="GET" action="<?= BASE_URL ?>">
    <input type="hidden" name="page" value="recherche">
    <div class="filter-group">
      <label>Mot-clé</label>
      <input type="text" name="q" value="<?= htmlspecialchars($filtres['q']) ?>" placeholder="Titre, modèle...">
    </div>
    <div class="filter-group">
      <label>Marque</label>
      <select name="marque_id">
        <option value="">Toutes</option>
        <?php foreach ($marques as $m): ?>
          <option value="<?= $m['id'] ?>" <?= $filtres['marque_id'] == $m['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label>Carburant</label>
      <select name="carburant">
        <option value="">Tous</option>
        <?php foreach (['Essence','Diesel','Hybride','Electrique'] as $c): ?>
          <option <?= $filtres['carburant'] === $c ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label>Année min</label>
      <input type="number" name="annee_min" value="<?= $filtres['annee_min'] ?: '' ?>" placeholder="ex: 2015">
    </div>
    <div class="filter-group">
      <label>Prix max (TND)</label>
      <input type="number" name="prix_max" value="<?= $filtres['prix_max'] ?: '' ?>" placeholder="ex: 25 000">
    </div>
    <div style="display:flex; align-items:flex-end; gap:.5rem;">
      <button type="submit" class="btn btn-primary">Rechercher</button>
      <a href="<?= BASE_URL ?>?page=recherche" class="btn btn-ghost">Réinitialiser</a>
    </div>
  </form>
</div>

<p style="color:#aaa; font-size:.85rem; margin-bottom:1.2rem;"><?= count($voitures) ?> résultat(s)</p>

<div class="cars-grid">
  <?php foreach ($voitures as $v): ?>
    <a href="<?= BASE_URL ?>?page=detail&id=<?= $v['id'] ?>" class="car-card">
      <?php if (!empty($v['image'])): ?>
        <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($v['image']) ?>" alt="" class="car-card-img">
      <?php else: ?>
        <div class="car-card-img-placeholder">🚗</div>
      <?php endif; ?>
      <div class="car-card-body">
        <div class="car-card-brand"><?= htmlspecialchars($v['marque_nom']) ?></div>
        <div class="car-card-title"><?= htmlspecialchars($v['titre']) ?></div>
        <div class="car-tags">
          <span class="car-tag">📅 <?= $v['annee'] ?></span>
          <span class="car-tag">🛣 <?= number_format($v['kilometrage'],0,',',' ') ?> km</span>
          <span class="car-tag">⛽ <?= $v['carburant'] ?></span>
        </div>
        <div class="car-card-footer">
          <span class="car-price"><?= number_format($v['prix'],0,',',' ') ?> TND</span>
          <span class="status-badge status-<?= $v['statut'] ?>"><?= ucfirst($v['statut']) ?></span>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
  <?php if (empty($voitures)): ?>
    <div class="empty-state" style="grid-column:1/-1">
      <div>🔍</div>
      <p>Aucun résultat pour ces critères.</p>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
