<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asset HUB</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/styles/main.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Fixed sidebar navigation -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3"
                style="position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto;">

                <div class="app-logo" tabindex="0">
                    <img src="assets/img/logo.png" alt="ASSET HUB" class="app-logo__img">
                    <span class="logo-text">ASSET HUB</span>
                </div>

                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a class="nav-link <?= (!isset($_GET['action']) || $_GET['action'] == 'dashboard') ? 'active' : '' ?>"
                           href="index.php">
                            <i class="bi bi-graph-up-arrow"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'createProduct') ? 'active' : '' ?>"
                           href="index.php?action=createProduct">
                            <i class="bi bi-plus-lg"></i> New Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'products') ? 'active' : '' ?>"
                           href="index.php?action=products">
                            <i class="bi bi-boxes"></i> Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'categories') ? 'active' : '' ?>"
                           href="index.php?action=categories">
                            <i class="bi bi-tags"></i> Categories
                        </a>
                    </li>
                </ul>
            </nav>

            <main class="main-content">
                <div class="container-fluid pt-4">

                    <?php if (isset($_SESSION['message'])): ?>
                        <!-- Flash message: displayed once, then removed from session -->
                        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert"
                             style="border: none;
                                    border-left: 4px solid <?= $_SESSION['message_type'] == 'success' ? 'var(--success)' : 'var(--accent)' ?>;
                                    background: <?= $_SESSION['message_type'] == 'success' ? 'rgba(36, 161, 72, 0.05)' : 'rgba(255, 91, 0, 0.05)' ?>;
                                    border-radius: 8px;">
                            <i class="bi <?= $_SESSION['message_type'] == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"
                               style="color: <?= $_SESSION['message_type'] == 'success' ? 'var(--success)' : 'var(--accent)' ?>;"></i>
                            <span style="color: var(--text-primary); font-weight: 500;">
                                <?= $_SESSION['message'] ?>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>

                </div>