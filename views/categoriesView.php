<?php
include "layouts/header.php";
require_once "models/categoryModel.php";
$categories = Category::getAllCategories();
?>

<link rel="stylesheet" href="assets/styles/categories.css">

<div class="categories-page">

    <!-- Page header -->
    <div class="categories-header">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h2 class="fw-bold mb-0">Categories</h2>
                    <p class="text-muted mb-0">Manage and organize your product inventory by category</p>
                </div>
                <a href="index.php?action=createCategory"
                   class="btn btn-primary shadow-sm"
                   style="height:42px; display:flex; align-items:center; white-space:nowrap;">
                    <i class="bi bi-plus-lg"></i>&nbsp; New Category
                </a>
            </div>
        </div>
    </div>

    <!-- Category cards (scrollable) -->
    <div class="categories-scroll-wrapper">
        <div class="container-fluid">

            <?php if (empty($categories)): ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill"></i> No categories found in the system.
                </div>
            <?php else: ?>
                <div class="categories-container">
                    <?php foreach ($categories as $category):
                        $bgColor = htmlspecialchars($category->getCategoryColor());
                    ?>
                        <div class="category-card <?= $category->getCategoryName(); ?>"
                             style="background-color: <?= $bgColor ?>; border-left: 4px solid <?= $bgColor ?>;">

                            <div class="category-card-content">
                                <div class="category-card-header">
                                    <h5 class="category-card-title">
                                        <?= htmlspecialchars($category->getCategoryName()); ?>
                                    </h5>
                                    <span class="category-card-emoji">
                                        <?= $category->getCategoryIcon() ?>
                                    </span>
                                </div>

                                <div class="category-card-footer">
                                    <div class="category-card-buttons-container">
                                        <!-- Configure toggles edit/delete buttons via JS -->
                                        <button type="button"
                                                class="category-card-button category-card-button-configure"
                                                title="Manage category settings"
                                                onclick="toggleActionButtons(this)">
                                            <i class="bi bi-gear"></i> Configure
                                        </button>

                                        <a href="index.php?action=editCategory&id=<?= $category->getCategoryId() ?>"
                                           class="category-card-button category-action-button category-button-edit"
                                           title="Edit category">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <form action="index.php?action=deleteCategory" method="POST" class="mb-0"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            <input type="hidden" name="id" value="<?= $category->getCategoryId(); ?>">
                                            <button type="submit"
                                                    class="category-card-button category-action-button category-button-delete"
                                                    title="Delete category">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<script>
    // Collapses any open action buttons on all other cards
    function closeAllActionButtons() {
        document.querySelectorAll('.category-card-button-configure.hide').forEach(button => {
            const container    = button.parentElement;
            const actionButtons = container.querySelectorAll('.category-action-button');
            actionButtons.forEach(btn => btn.classList.remove('show'));
            button.classList.remove('hide');
        });
    }

    // Toggles the edit/delete buttons for the clicked card
    function toggleActionButtons(button) {
        closeAllActionButtons();
        const container     = button.parentElement;
        const actionButtons = container.querySelectorAll('.category-action-button');
        button.classList.toggle('hide');
        actionButtons.forEach(btn => btn.classList.toggle('show'));
    }

    // Close open buttons when clicking outside a card's action area
    document.addEventListener('click', function(event) {
        if (event.target.closest('.category-card-button-configure')) return;
        if (event.target.closest('.category-action-button'))         return;
        if (!event.target.closest('.category-card-buttons-container')) closeAllActionButtons();
    });
</script>

<?php include "layouts/footer.php"; ?>