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
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
        }
        
        .footer {
            background-color: white;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            margin-top: auto;
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .badge-filiere {
            background-color: #e9ecef;
            color: #495057;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .pagination {
            gap: 0.25rem;
        }
        
        .page-link {
            border-radius: 0.5rem;
            color: var(--primary-color);
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
        }
        
        .page-link.active,
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .page-link:focus {
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        
        .alert {
            border-radius: 0.75rem;
            border: none;
        }
        
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 0.5rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
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
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/etudiants') === 0 && !strpos($_SERVER['REQUEST_URI'], '/create') ? 'active' : ''; ?>" 
                       href="/etudiants">
                        <i class="bi bi-list-ul me-1"></i>Liste
                    </a>
                </li>
                <?php if (!empty($_SESSION['admin_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/create') ? 'active' : ''; ?>" 
                       href="/etudiants/create">
                        <i class="bi bi-plus-circle me-1"></i>Ajouter
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <form method="post" action="/logout" class="d-inline">
                        <input type="hidden" name="_csrf" 
                               value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                        </button>
                    </form>
                </li>
                <?php else: ?>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm <?php echo strpos($_SERVER['REQUEST_URI'], '/login') ? 'active' : ''; ?>" 
                       href="/login">
                        <i class="bi bi-person me-1"></i>Connexion
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<main>
    <div class="container">
        <?php 
        // Afficher les erreurs flash si elles existent
        if (isset($_SESSION['flash_error'])): 
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php 
                echo htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['flash_error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php 
        if (isset($_SESSION['flash_success'])): 
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php 
                echo htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['flash_success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Le contenu de la vue sera injecté ici -->
        <?php echo $content ?? ''; ?>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container text-center">
        <p class="text-muted mb-0">
            <small>
                <i class="bi bi-c-circle me-1"></i>
                <?php echo date('Y'); ?> Gestion des Étudiants - Tous droits réservés
            </small>
        </p>
    </div>
</footer>

<!-- Bootstrap JS Bundle avec Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts communs -->
<script>
    // Activer tous les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Auto-fermeture des alertes après 5 secondes
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);
    });
    
    // Confirmation avant suppression
    function confirmDelete(event, message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
        if (!confirm(message)) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>

</body>
</html>