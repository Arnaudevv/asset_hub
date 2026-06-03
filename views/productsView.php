<?php include "layouts/header.php"; ?>
<link rel="stylesheet" href="assets/styles/main.css">

<div class="products-page">

    <!-- Page header -->
    <div class="products-header">
        <div class="container-fluid">
            <h2 class="fw-bold mb-0">Inventory Management</h2>
            <p class="text-muted mb-0">Total registered products: <?= count($products); ?></p>
        </div>
    </div>

    <!-- Filter bar + new product button -->
    <div class="products-controls">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-2">
                <!-- Category filter: submits automatically on change -->
                <form method="GET" action="index.php" class="mb-0">
                    <input type="hidden" name="action" value="products">
                    <select class="form-select category-select" name="id_category" onchange="this.form.submit()">
                        <option value="all">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getCategoryId() ?>"
                                    <?= ($id_category == $cat->getCategoryId()) ? 'selected' : ''; ?>>
                                <?= $cat->getCategoryIcon() . $cat->getCategoryLongName() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="index.php?action=createProduct" class="btn btn-primary shadow-sm"
                   style="height:42px; display:flex; align-items:center;">
                    <i class="bi bi-plus-lg"></i>&nbsp; New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Product grid (scrollable) -->
    <div class="products-scroll-wrapper">
        <div class="container-fluid">
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <?php $category = Category::getCategoryById($product->getProductCategoryId()); ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="product-card h-100 shadow-sm border-0 d-flex flex-column">

                            <div class="product-card__header p-3 pb-0">
                                <span class="badge category-badge"
                                      style="background-color: <?= $category->getCategoryColor() ?>;">
                                    <?= $category->getCategoryIcon() . ' ' . $category->getCategoryName() ?>
                                </span>
                            </div>

                            <div class="product-card__body p-3 flex-grow-1">
                                <h5 class="card-title fw-bold text-dark mb-1"><?= $product->getProductName(); ?></h5>
                                <code class="text-accent small mb-3 d-block"><?= $product->getProductShortName(); ?></code>
                                <div class="price-tag">
                                    <span class="price-amount"><?= number_format($product->getProductPvp(), 2, ',', '.'); ?></span>
                                    <span class="price-currency">€</span>
                                </div>
                            </div>

                            <div class="product-card__footer p-3 bg-light border-top d-flex gap-2">
                                <div style="flex: 0 0 80%;">
                                    <a href="index.php?action=editProduct&id=<?= $product->getProductId(); ?>"
                                       class="btn btn-sm btn-outline-dark w-100 py-2">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </div>
                                <div class="flex-grow-1">
                                    <form action="index.php?action=deleteProduct" method="POST" class="mb-0"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        <input type="hidden" name="id" value="<?= $product->getProductId(); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger w-100 py-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<?php include "layouts/footer.php"; ?>