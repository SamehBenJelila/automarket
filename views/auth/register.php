<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-title">Créer un compte</div>

    <?php if (!empty($erreur)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Votre nom</label>
        <input type="text" name="nom" required placeholder="ex: Mohamed Ali">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="votre@email.com">
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required placeholder="Minimum 8 caractères" minlength="8">
      </div>
      <button type="submit" class="btn btn-primary btn-block" style="margin-top:.4rem;">Créer mon compte</button>
    </form>

    <div class="auth-footer">
      Déjà un compte ? <a href="<?= BASE_URL ?>?page=login">Se connecter</a>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
