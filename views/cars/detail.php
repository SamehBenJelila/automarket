<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>?page=voitures" class="back-link">← Retour aux annonces</a>

<div class="detail-wrap">
  <?php if (!empty($voiture['image'])): ?>
    <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($voiture['image']) ?>" alt="" class="detail-img">
  <?php else: ?>
    <div class="detail-img-placeholder">🚗</div>
  <?php endif; ?>

  <div class="detail-body">
    <div class="detail-header">
      <div>
        <div style="font-size:.78rem; font-weight:700; color:#e53935; text-transform:uppercase; letter-spacing:.6px; margin-bottom:.3rem;">
          <?= htmlspecialchars($voiture['marque_nom']) ?>
        </div>
        <div class="detail-title"><?= htmlspecialchars($voiture['titre']) ?></div>
        <div class="detail-subtitle"><?= htmlspecialchars($voiture['modele']) ?></div>
      </div>
      <div style="text-align:right;">
        <div class="detail-price"><?= number_format($voiture['prix'],0,',',' ') ?> TND</div>
        <span class="status-badge status-<?= $voiture['statut'] ?>" style="margin-top:.4rem; display:inline-block;">
          <?= ucfirst($voiture['statut']) ?>
        </span>
      </div>
    </div>

    <!-- Specs -->
    <div class="specs-grid">
      <div class="spec-box"><div class="spec-label">Année</div><div class="spec-value"><?= $voiture['annee'] ?></div></div>
      <div class="spec-box"><div class="spec-label">Kilométrage</div><div class="spec-value"><?= number_format($voiture['kilometrage'],0,',',' ') ?> km</div></div>
      <div class="spec-box"><div class="spec-label">Carburant</div><div class="spec-value"><?= $voiture['carburant'] ?></div></div>
      <div class="spec-box"><div class="spec-label">Boîte</div><div class="spec-value"><?= $voiture['transmission'] ?></div></div>
    </div>

    <!-- Description — FIXED: now always shows correctly -->
    <?php if (!empty($voiture['description'])): ?>
      <hr class="divider">
      <div class="detail-desc-title">Description</div>
      <p class="detail-desc"><?= nl2br(htmlspecialchars($voiture['description'])) ?></p>
    <?php endif; ?>

    <!-- Actions admin -->
    <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
      <hr class="divider">
      <div style="display:flex; gap:.6rem;">
        <a href="<?= BASE_URL ?>?page=modifier&id=<?= $voiture['id'] ?>" class="btn btn-ghost">✏️ Modifier</a>
        <a href="<?= BASE_URL ?>?page=supprimer&id=<?= $voiture['id'] ?>"
           class="btn btn-danger-sm"
           style="padding:.5rem 1rem; border-radius:7px;"
           onclick="return confirm('Supprimer cette annonce ?')">🗑️ Supprimer</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
