<?php // views/etudiant/create.php /** @var array $filieres, array $errors, array $old */ ?>
<h2>Créer un étudiant</h2>
<form method="post" action="/etudiants/store">
    <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

  <label>CNE
    <input name="cne" value="<?php echo htmlspecialchars($old['cne'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
  </label>
  <?php if (!empty($errors['cne'])): ?><small class="error" ><?php="" echo="" htmlspecialchars($errors['cne'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php endif; ?></small><small class="error" ><?php="" echo="" htmlspecialchars($errors['cne'],="" ent_quotes,="" 'utf-8');="" ?><="" small="">

  <label>Nom
    <input name="nom" value="<?php echo htmlspecialchars($old['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
  </label>
  <?php if (!empty($errors['nom'])): ?><small class="error" ><?php="" echo="" htmlspecialchars($errors['nom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php endif; ?></small><small class="error" ><?php="" echo="" htmlspecialchars($errors['nom'],="" ent_quotes,="" 'utf-8');="" ?><="" small="">

  <label>Prénom
    <input name="prenom" value="<?php echo htmlspecialchars($old['prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
  </label>
  <?php if (!empty($errors['prenom'])): ?><small class="error" ><?php="" echo="" htmlspecialchars($errors['prenom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php endif; ?></small><small class="error" ><?php="" echo="" htmlspecialchars($errors['prenom'],="" ent_quotes,="" 'utf-8');="" ?><="" small="">

  <label>Email
    <input type="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
  </label>
  <?php if (!empty($errors['email'])): ?><small class="error" ><?php="" echo="" htmlspecialchars($errors['email'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php endif; ?></small><small class="error" ><?php="" echo="" htmlspecialchars($errors['email'],="" ent_quotes,="" 'utf-8');="" ?><="" small="">

  <label>Filière
    <select name="filiere_id" required>
      <option value="">-- Choisir --</option>
      <?php foreach ($filieres as $f): ?>
        <option value="<?php echo (int)$f['id']; ?>" <?php echo (isset($old['filiere_id']) && (int)$old['filiere_id']===(int)$f['id'])?'selected':''; ?>>
          <?php echo htmlspecialchars($f['code'].' — '.$f['libelle'], ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <?php if (!empty($errors['filiere_id'])): ?><small class="error" ><?php="" echo="" htmlspecialchars($errors['filiere_id'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php endif; ?></small><small class="error" ><?php="" echo="" htmlspecialchars($errors['filiere_id'],="" ent_quotes,="" 'utf-8');="" ?><="" small="">

  <button type="submit">Créer</button>
  <a role="button" class="secondary" href="/etudiants">Annuler</a>
</form></small></small></small></small></small>