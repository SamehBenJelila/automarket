<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-title">Connexion</div>

    <?php if (!empty($erreur)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['ok'])): ?>
      <div class="alert alert-success">Compte créé ! Vous pouvez vous connecter.</div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required autofocus placeholder="votre@email.com">
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary btn-block" style="margin-top:.4rem;">Se connecter</button>
    </form>

    <div class="auth-footer">
      Pas de compte ? <a href="<?= BASE_URL ?>?page=register">S'inscrire</a>
    </div>
    <div class="auth-hint">
      🔑 Admin : admin@auto.tn / password<br>
      👤 User : user@auto.tn / password
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
