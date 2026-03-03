<?php 
$etudiants = $etudiants ?? [];
$filieres = $filieres ?? [];
$filiereId = $filiereId ?? 0;
$q = $q ?? '';
$page = $page ?? 1;
$size = $size ?? 5;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Étudiants</h2>
        <a href="/etudiants/create" class="btn btn-primary">+ Nouveau</a>
    </div>

    <!-- 🔎 Formulaire filtre -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="/etudiants" class="row g-3">
                
                <div class="col-md-4">
                    <input type="text" 
                           name="q" 
                           class="form-control"
                           placeholder="Rechercher (nom, prénom, email, CNE)" 
                           value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="col-md-4">
                    <select name="filiere_id" class="form-select">
                        <option value="">Toutes filières</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?php echo (int)$f['id']; ?>" 
                                <?php echo ((int)$filiereId === (int)$f['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f['code'] . ' — ' . $f['libelle'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="size" value="<?php echo (int)$size; ?>">

                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Filtrer</button>
                </div>

            </form>
        </div>
    </div>

    <div class="mb-2 text-muted">
        Total: <?php echo (int)$total; ?> — 
        Page <?php echo (int)$page; ?>/<?php echo (int)$totalPages; ?>
    </div>

    <?php if (empty($etudiants)): ?>
        <div class="alert alert-warning">Aucun étudiant.</div>
    <?php else: ?>

        <!-- 📋 Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>CNE</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Filière</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $e): ?>
                        <tr>
                            <td><?php echo (int)$e['id']; ?></td>
                            <td><?php echo htmlspecialchars($e['cne'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($e['nom'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($e['prenom'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($e['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($e['filiere_code'] . ' — ' . $e['filiere_libelle'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-center">

                                <a href="/etudiants/<?php echo (int)$e['id']; ?>" 
                                   class="btn btn-info btn-sm">Voir</a>

                                <a href="/etudiants/<?php echo (int)$e['id']; ?>/edit" 
                                   class="btn btn-warning btn-sm">Éditer</a>

                                <form action="/etudiants/<?php echo (int)$e['id']; ?>/delete" 
                                      method="post" 
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ?');">
                                    <input type="hidden" name="_csrf" 
                                           value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Supprimer
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- 📄 Pagination -->
        <?php 
        $base = '/etudiants?size=' . (int)$size . 
                '&q=' . urlencode($q) . 
                '&filiere_id=' . (int)$filiereId . 
                '&page='; 
        ?>

        <nav>
            <ul class="pagination justify-content-center">

                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $base . ($page - 1); ?>">
                            « Préc.
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo $base . $p; ?>">
                            <?php echo $p; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $base . ($page + 1); ?>">
                            Suiv. »
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>

    <?php endif; ?>

</div>