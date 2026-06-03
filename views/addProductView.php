<?php include "layouts/header.php"; require_once "models/CategoryModel.php"; $categories = Category::getAllCategories(); ?>
<link rel="stylesheet" href="assets/styles/main.css">
<link rel="stylesheet" href="assets/styles/productForm.css">

<div class="form-page">
    <div class="container-fluid form-header">
        <!-- HEADER (FIXED) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div class="x">
                <h2 class="fw-bold mb-0">New Asset Creation</h2>
                <p class="text-muted mb-0">Create and register a new asset in the system.</p>
            </div>
        </div>
    </div>

    <!-- FORM & PREVIEW LAYOUT (SCROLLEABLE) -->
    <div class="form-scroll-wrapper">
        <div class="container-fluid">
            <div class="row g-4 mt-2">
            <!-- FORM COLUMN -->
            <div class="col-12 col-xl-7 d-flex">
                <div class="card premium-form-card w-100 border-0">
                    <div class="card-header-gradient">
                        <h5 class="m-0 fw-bold text-dark">Specifications Panel</h5>
                        <p class="text-muted small m-0 mt-1">Configure structural details and pricing for your inventory item.</p>
                    </div>
                    <div class="card-body-custom">
                        <form action="index.php?action=createProduct" method="POST">
                            <!-- PRODUCT NAME -->
                            <div class="premium-field-group">
                                <label class="premium-field-label"><i class="bi bi-tag"></i> Product Name</label>
                                <div class="premium-input-wrapper">
                                    <i class="bi bi-fonts premium-input-icon"></i>
                                    <input class="premium-input-field" type="text" name="name" id="input-name" placeholder="Ex. Mechanical RGB Keyboard" autocomplete="off" required>
                                </div>
                            </div>
                            <!-- SHORT NAME -->
                            <div class="premium-field-group">
                                <label class="premium-field-label"><i class="bi bi-hash"></i> Short Name</label>
                                <div class="premium-input-wrapper">
                                    <i class="bi bi-qr-code premium-input-icon"></i>
                                    <input class="premium-input-field" type="text" name="short_name" id="input-short" placeholder="Ex. KEYBOARD-RGB" maxlength="20" autocomplete="off" required>
                                </div>
                            </div>
                            <!-- CATEGORY & PRICE -->
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="premium-field-group">
                                        <label class="premium-field-label"><i class="bi bi-grid"></i> Category</label>
                                        <div class="premium-select-wrapper">
                                            <select class="premium-select-field" name="id_category" id="input-category" required>
                                                <option value="" selected disabled>Select category</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?= $cat->getCategoryId() ?>" data-color="<?= $cat->getcategoryColor() ?>" data-label="<?= htmlspecialchars($cat->getcategoryIcon() . ' ' . $cat->getCategoryName()) ?>">
                                                        <?= $cat->getCategoryIcon() . $cat->getCategoryLongName() ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="premium-field-group">
                                        <label class="premium-field-label"><i class="bi bi-currency-euro"></i> Market Price</label>
                                        <div class="premium-input-wrapper">
                                            <i class="bi bi-wallet2 premium-input-icon"></i>
                                            <input class="premium-input-field" type="number" name="price" id="input-price" step="0.01" min="0" placeholder="0.00" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ACTIONS -->
                            <div class="premium-divider"></div>
                            <button type="submit" class="premium-btn-submit">
                                <i class="bi bi-plus-circle-fill"></i> Register Asset to Inventory
                            </button>
                            <a href="index.php?action=products" class="premium-btn-cancel">
                                <i class="bi bi-arrow-left"></i> Cancel and return
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PREVIEW COLUMN -->
            <div class="col-12 col-xl-5 d-flex">
                <div class="preview-studio-container w-100">
                    <div class="preview-studio-bg-pattern"></div>
                    <!-- PREVIEW HEADER -->
                    <div class="preview-studio-header">
                        <span class="preview-studio-title"><i class="bi bi-eye-fill"></i> Live Preview Card</span>
                        <span class="preview-studio-status"><span class="pulse-dot"></span> Active Sync</span>
                    </div>
                    <!-- PREVIEW CARD -->
                    <div class="preview-card-wrap">
                        <div class="preview-product-card">
                            <div class="preview-product-card__header">
                                <span class="badge preview-category-badge" id="preview-badge" style="background-color: rgba(111, 66, 193, 0.12); color: #6F42C1; border: 1px solid rgba(111, 66, 193, 0.3);">
                                    Uncategorized
                                </span>
                            </div>
                            <div class="preview-product-card__body">
                                <h5 class="preview-card-title text-truncate" id="preview-title">New Asset Name</h5>
                                <code class="preview-card-code text-uppercase" id="preview-code">ASSET-CODE</code>
                                <div class="preview-price-tag my-3">
                                    <span class="preview-price-amount" id="preview-price">0,00</span>
                                    <span class="preview-price-currency">€</span>
                                </div>
                            </div>
                            <div class="preview-product-card__footer">
                                <div style="flex: 0 0 80%;">
                                    <button type="button" class="btn btn-sm btn-outline-dark w-100 preview-btn-mock">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </div>
                                <div class="flex-grow-1">
                                    <button type="button" class="btn btn-sm btn-danger w-100 preview-btn-mock">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="preview-tip">
                        <i class="bi bi-info-circle-fill text-primary"></i> As you type, the card updates to match the style in your inventory.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/productForm.js"></script>
<?php include "layouts/footer.php"; ?>