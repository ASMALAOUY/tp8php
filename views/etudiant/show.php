<small class="error" ><?php="" echo="" htmlspecialchars($errors['cne'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['nom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['prenom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['email'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['filiere_id'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['cne'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['nom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['prenom'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['email'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><small class="error" ><?php="" echo="" htmlspecialchars($errors['filiere_id'],="" ent_quotes,="" 'utf-8');="" ?><="" small=""><?php // views/etudiant/show.php /** @var array $e */ ?>
<h2>Étudiant #<?php echo (int)$e['id']; ?></h2>
<ul>
  <li>CNE : <?php echo htmlspecialchars($e['cne'], ENT_QUOTES, 'UTF-8'); ?></li>
  <li>Nom : <?php echo htmlspecialchars($e['nom'], ENT_QUOTES, 'UTF-8'); ?></li>
  <li>Prénom : <?php echo htmlspecialchars($e['prenom'], ENT_QUOTES, 'UTF-8'); ?></li>
  <li>Email : <?php echo htmlspecialchars($e['email'], ENT_QUOTES, 'UTF-8'); ?></li>
  <li>Filière : <?php echo htmlspecialchars($e['filiere_code'].' — '.$e['filiere_libelle'], ENT_QUOTES, 'UTF-8'); ?></li>
</ul>
<p>
  <a role="button" href="/etudiants/<?php echo (int)$e['id']; ?>/edit">Éditer</a>
  <a role="button" class="secondary" href="/etudiants">Retour à la liste</a>


<form action="/etudiants/<?php echo (int)$e['id']; ?>/delete" method="post" onsubmit="return confirm('Supprimer ?');">
  <button type="submit" class="contrast">Supprimer</button
  <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

</form></p></small></small></small></small></small></small></small></small></small></small>