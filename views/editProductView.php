<?php
include "layouts/header.php";
require_once "models/productsModel.php";
require_once "models/CategoryModel.php";

// Load the product to edit using the ID passed in the URL
$id_product = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_product) {
    $product     = Product::getProductByCode($id_product);
    $name        = $product->getProductName();
    $short_name  = $product->getProductShortName();
    $id_category = $product->getProductCategoryId();
    $price       = $product->getProductPvp();
    $categories  = Category::getAllCategories();

    if (!$product) {
        echo "<div class='alert alert-danger'>Product not found.</div>";
        include "layouts/footer.php";
        exit;
    }
}
?>

<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/styles/productForm.css">

<div class="form-page">
    <div class="container-fluid form-header">

        <!-- Page header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Edit Asset Specifications</h2>
                <p class="text-muted mb-0">Modify the details of the selected asset.</p>
            </div>
        </div>

    </div>

    <!-- Two-column layout: form (left) + live preview (right) -->
    <div class="form-scroll-wrapper">
        <div class="asset-form-wrapper premium-grid-container">
            <div class="row product-form-row g-4 mt-2">

                <!-- Form column -->
                <div class="col-12 col-xl-7 d-flex">
                    <div class="card premium-form-card w-100 border-0">
                        <div class="card-header-gradient">
                            <h5 class="m-0 fw-bold text-dark">Modification Panel</h5>
                            <p class="text-muted small m-0 mt-1">Make secure edits to this asset. Changes sync instantly on the right.</p>
                        </div>

                        <div class="card-body-custom">
                            <form action="index.php?action=editProduct" method="POST">
                                <!-- Hidden field carries the product ID to the controller -->
                                <input type="hidden" name="id_product" value="<?php echo $id_product; ?>">

                                <div class="premium-field-group">
                                    <label class="premium-field-label"><i class="bi bi-tag"></i> Product Name</label>
                                    <div class="premium-input-wrapper">
                                        <i class="bi bi-fonts premium-input-icon"></i>
                                        <input class="premium-input-field" type="text" name="name" id="input-name"
                                               value="<?php echo htmlspecialchars($name); ?>"
                                               autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="premium-field-group">
                                    <label class="premium-field-label"><i class="bi bi-hash"></i> Short Name</label>
                                    <div class="premium-input-wrapper">
                                        <i class="bi bi-qr-code premium-input-icon"></i>
                                        <input class="premium-input-field" type="text" name="short_name" id="input-short"
                                               value="<?php echo htmlspecialchars($short_name); ?>"
                                               maxlength="20" autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="premium-field-group">
                                            <label class="premium-field-label"><i class="bi bi-grid"></i> Category</label>
                                            <div class="premium-select-wrapper">
                                                <select class="premium-select-field" name="id_category" id="input-category" required>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <!-- data-color and data-label are read by productForm.js to update the live preview -->
                                                        <option value="<?= $cat->getCategoryId() ?>"
                                                                data-color="<?= $cat->getCategoryColor() ?>"
                                                                data-label="<?= htmlspecialchars($cat->getCategoryIcon() . ' ' . $cat->getCategoryName()) ?>"
                                                                <?= ($id_category == $cat->getCategoryId()) ? 'selected' : ''; ?>>
                                                            <?= $cat->getCategoryIcon() . ' ' . $cat->getCategoryLongName() ?>
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
                                                <input class="premium-input-field" type="number" name="price" id="input-price"
                                                       step="0.01" min="0"
                                                       value="<?php echo htmlspecialchars($price); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="premium-divider"></div>

                                <button type="submit" class="premium-btn-submit">
                                    <i class="bi bi-check-circle-fill"></i> Save Specifications
                                </button>
                                <a href="index.php?action=products" class="premium-btn-cancel">
                                    <i class="bi bi-arrow-left"></i> Cancel and return
                                </a>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live preview column: mirrors the product card shown in productsView -->
                <div class="col-12 col-xl-5 d-flex">
                    <div class="preview-studio-container w-100">
                        <div class="preview-studio-bg-pattern"></div>

                        <div class="preview-studio-header">
                            <span class="preview-studio-title"><i class="bi bi-eye-fill"></i> Live Preview Card</span>
                            <span class="preview-studio-status"><span class="pulse-dot"></span> Active Sync</span>
                        </div>

                        <div class="preview-card-wrap">
                            <div class="preview-product-card">
                                <div class="preview-product-card__header">
                                    <span class="badge preview-category-badge" id="preview-badge"
                                          style="background-color: rgba(111, 66, 193, 0.12); color: #6F42C1; border: 1px solid rgba(111, 66, 193, 0.3);">
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
                            <i class="bi bi-info-circle-fill text-primary"></i> As you edit, the card reflects the precise visual representation in the Inventory.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="assets/js/productForm.js"></script>

<?php include "layouts/footer.php"; ?>