<?php /** @var array $errors, array $old */ ?>
<h2>Connexion admin</h2>

<?php if (!empty($errors['global'])): ?>
  <!-- Ajout de la balise fermante </p> manquante -->
  <p class="error"><?php echo htmlspecialchars($errors['global'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post" action="/login">
  <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  
  <label>Nom d’utilisateur
    <input type="text" name="username" required value="<?php echo htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  </label>
  
  <label>Mot de passe
    <input type="password" name="password" required>
  </label>
  
  <button type="submit">Se connecter</button>
</form>