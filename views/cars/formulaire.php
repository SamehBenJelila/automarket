<?php
require __DIR__ . '/../layout/header.php';
$isEdit = isset($modeEdit) && $modeEdit === true;
$val    = fn($f) => htmlspecialchars($voiture[$f] ?? '');
?>

<div class="page-header">
  <h1><?= $isEdit ? 'Modifier l\'annonce' : 'Publier une annonce' ?></h1>
</div>

<?php if (!empty($erreur)): ?>
  <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<div class="form-card">
  <!-- enctype="multipart/form-data" obligatoire pour $_FILES -->
  <form method="POST" enctype="multipart/form-data" id="carForm">

    <div class="form-row">
      <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="titre" required value="<?= $val('titre') ?>" placeholder="ex: Peugeot 308 Full Options">
      </div>
      <div class="form-group">
        <label>Marque *</label>
        <select name="marque_id" required>
          <option value="">— Choisir —</option>
          <?php foreach ($marques as $m): ?>
            <option value="<?= $m['id'] ?>" <?= ($voiture['marque_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($m['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Modèle *</label>
        <input type="text" name="modele" required value="<?= $val('modele') ?>" placeholder="ex: 308">
      </div>
      <div class="form-group">
        <label>Année *</label>
        <input type="number" name="annee" required value="<?= $val('annee') ?>" min="1990" max="<?= date('Y') ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Kilométrage (km) *</label>
        <input type="number" name="kilometrage" required value="<?= $val('kilometrage') ?>" min="0">
      </div>
      <div class="form-group">
        <label>Prix (TND) *</label>
        <input type="number" name="prix" required value="<?= $val('prix') ?>" step="0.01" min="0">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Carburant *</label>
        <select name="carburant" required>
          <?php foreach (['Essence','Diesel','Hybride','Electrique'] as $c): ?>
            <option <?= ($voiture['carburant'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Boîte de vitesse *</label>
        <select name="transmission" required>
          <?php foreach (['Manuelle','Automatique'] as $b): ?>
            <option <?= ($voiture['transmission'] ?? '') === $b ? 'selected' : '' ?>><?= $b ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <?php if ($isEdit): ?>
    <div class="form-group">
      <label>Statut</label>
      <select name="statut">
        <?php foreach (['disponible','reservé','vendu'] as $s): ?>
          <option value="<?= $s ?>" <?= ($voiture['statut'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" placeholder="État général, options, historique..."><?= $val('description') ?></textarea>
    </div>

    <!-- ===== UPLOAD IMAGE — zone cliquable avec preview ===== -->
    <div class="form-group">
      <label>Photo (JPG / PNG / WEBP — max 5 Mo)</label>

      <!-- Zone cliquable : clic → ouvre le sélecteur de fichier -->
      <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imageInput').click()">

        <?php if ($isEdit && !empty($voiture['image'])): ?>
          <!-- Image existante affichée en preview -->
          <img
            id="imagePreview"
            src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($voiture['image']) ?>"
            class="upload-preview"
            alt="Image actuelle"
          >
          <p style="font-size:.8rem; color:#aaa;">Cliquer pour changer l'image</p>
        <?php else: ?>
          <!-- Placeholder si pas d'image -->
          <img id="imagePreview" src="" class="upload-preview" style="display:none;" alt="">
          <div id="uploadPlaceholder">
            <div style="font-size:2rem; margin-bottom:.5rem;">📷</div>
            <p style="font-size:.875rem; color:#666; font-weight:500;">Cliquer pour choisir une image</p>
          </div>
        <?php endif; ?>

        <!-- Input file caché — déclenché par le clic sur la zone -->
        <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp">
        <p class="upload-hint">JPG, PNG ou WEBP · Maximum 5 Mo</p>
      </div>
    </div>

    <div style="display:flex; gap:.7rem; margin-top: .5rem;">
      <button type="submit" class="btn btn-primary">
        <?= $isEdit ? '✅ Enregistrer' : '🚗 Publier l\'annonce' ?>
      </button>
      <a href="<?= BASE_URL ?>?page=<?= $isEdit ? 'admin' : 'voitures' ?>" class="btn btn-outline">Annuler</a>
    </div>

  </form>
</div>

<script>
// Preview de l'image avant upload — s'active dès que l'utilisateur choisit un fichier
document.getElementById('imageInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function(evt) {
    const preview     = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    preview.src          = evt.target.result; // affiche l'image choisie
    preview.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
  };
  reader.readAsDataURL(file); // lit le fichier et génère une URL base64
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
