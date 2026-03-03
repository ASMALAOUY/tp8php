<?php 
/** @var array $etudiants */
/** @var array $filieres */
/** @var int $filiereId */
/** @var string $q */
/** @var int $page */
/** @var int $size */
/** @var int $total */
/** @var int $totalPages */

// Initialisation des variables par défaut si non définies
$etudiants = $etudiants ?? [];
$filieres = $filieres ?? [];
$filiereId = $filiereId ?? 0;
$q = $q ?? '';
$page = max(1, $page ?? 1);
$size = max(1, $size ?? 5);
$total = $total ?? 0;
$totalPages = max(1, $totalPages ?? 1);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 2rem;
        }
        .navbar-brand {
            font-weight: 600;
            color: #0d6efd !important;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
            border: none;
        }
        .table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        .table td {
            vertical-align: middle;
            color: #334155;
        }
        .pagination {
            gap: 0.25rem;
        }
        .page-link {
            border-radius: 0.5rem;
            color: #0d6efd;
            border: 1px solid #dee2e6;
        }
        .page-link.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .badge-filiere {
            background-color: #e9ecef;
            color: #495057;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #0d6efd;
        }
        .stats-label {
            color: #64748b;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-outline-info, .btn-outline-primary, .btn-outline-danger {
            border-width: 1px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="/etudiants">
            <i class="bi bi-mortarboard-fill me-2"></i>
            Gestion Étudiants
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/etudiants">
                        <i class="bi bi-list-ul me-1"></i>Liste
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/etudiants/create">
                        <i class="bi bi-plus-circle me-1"></i>Ajouter
                    </a>
                </li>
                <?php if (!empty($_SESSION['admin_id'])): ?>
                <li class="nav-item ms-2">
                    <form method="post" action="/logout" class="d-inline">
                        <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                        </button>
                    </form>
                </li>
                <?php else: ?>
                <li class="nav-item ms-2">
                    <a class="btn btn-primary btn-sm" href="/login">
                        <i class="bi bi-person me-1"></i>Connexion
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    
    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <i class="bi bi-people-fill fs-1 text-primary mb-2"></i>
                <div class="stats-number"><?php echo (int)$total; ?></div>
                <div class="stats-label">Total étudiants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="bi bi-building fs-1 text-success mb-2"></i>
                <div class="stats-number"><?php echo count($filieres); ?></div>
                <div class="stats-label">Filières</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="bi bi-file-text fs-1 text-info mb-2"></i>
                <div class="stats-number"><?php echo (int)$totalPages; ?></div>
                <div class="stats-label">Pages</div>
            </div>
        </div>
    </div>

    <!-- Carte de recherche -->
    <div class="card mb-4">
        <div class="card-body p-4">
            <h5 class="card-title mb-3">
                <i class="bi bi-search me-2"></i>
                Rechercher des étudiants
            </h5>
            
            <form method="get" action="/etudiants" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               name="q" 
                               placeholder="Nom, prénom, email ou CNE..." 
                               value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select class="form-select" name="filiere_id">
                        <option value="">Toutes les filières</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?php echo (int)$f['id']; ?>" 
                                <?php echo ((int)$filiereId === (int)$f['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f['code'] . ' - ' . $f['libelle'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <input type="hidden" name="size" value="<?php echo (int)$size; ?>">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- En-tête de la liste -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Liste des étudiants
        </h4>
        <a href="/etudiants/create" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Nouvel étudiant
        </a>
    </div>

    <?php if (empty($etudiants)): ?>
        <!-- Message si aucun résultat -->
        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
            <i class="bi bi-emoji-frown display-1 text-muted mb-3"></i>
            <h5 class="text-muted">Aucun étudiant trouvé</h5>
            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
        </div>
    <?php else: ?>
        <!-- Tableau des étudiants -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>CNE</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Filière</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiants as $e): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold">#<?php echo (int)$e['id']; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo htmlspecialchars($e['cne'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            <?php echo htmlspecialchars($e['nom'] . ' ' . $e['prenom'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($e['email'], ENT_QUOTES, 'UTF-8'); ?>" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($e['email'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge-filiere">
                                            <?php echo htmlspecialchars($e['filiere_code'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($e['filiere_libelle'], ENT_QUOTES, 'UTF-8'); ?>
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-buttons justify-content-end">
                                            <a href="/etudiants/<?php echo (int)$e['id']; ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/etudiants/<?php echo (int)$e['id']; ?>/edit" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" 
                                               title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/etudiants/<?php echo (int)$e['id']; ?>/delete" 
                                                  method="post" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                                <input type="hidden" name="_csrf" 
                                                       value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" 
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination et informations -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <p class="text-muted mb-3 mb-md-0">
                <i class="bi bi-info-circle me-1"></i>
                Affichage de <?php echo count($etudiants); ?> étudiants sur <?php echo (int)$total; ?> 
                (Page <?php echo (int)$page; ?>/<?php echo (int)$totalPages; ?>)
            </p>
            
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Pagination">
                    <ul class="pagination mb-0">
                        <!-- Page précédente -->
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" 
                               href="?size=<?php echo (int)$size; ?>&q=<?php echo urlencode($q); ?>&filiere_id=<?php echo (int)$filiereId; ?>&page=<?php echo $page - 1; ?>"
                               aria-label="Précédent">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        <!-- Pages -->
                        <?php
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        for ($p = $start; $p <= $end; $p++): ?>
                            <li class="page-item <?php echo $p == $page ? 'active' : ''; ?>">
                                <a class="page-link" 
                                   href="?size=<?php echo (int)$size; ?>&q=<?php echo urlencode($q); ?>&filiere_id=<?php echo (int)$filiereId; ?>&page=<?php echo $p; ?>">
                                    <?php echo $p; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Page suivante -->
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" 
                               href="?size=<?php echo (int)$size; ?>&q=<?php echo urlencode($q); ?>&filiere_id=<?php echo (int)$filiereId; ?>&page=<?php echo $page + 1; ?>"
                               aria-label="Suivant">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Lien rapide pour API (optionnel) -->
    <div class="text-center mt-4">
        <small class="text-muted">
            <i class="bi bi-code-slash me-1"></i>
            API disponible : <a href="/api/etudiants" class="text-decoration-none">/api/etudiants</a>
        </small>
    </div>
</main>

<!-- Bootstrap JS Bundle avec Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour activer les tooltips Bootstrap -->
<script>
    // Activer tous les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

</body>
</html>