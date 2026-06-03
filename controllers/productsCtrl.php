<?php

require_once "models/productsModel.php";
require_once "models/categoryModel.php";

// Returns false when the string contains only special characters (no alphanumeric content)
function validate_string_only_specialchars($text) {
    return preg_match('/[a-zA-Z0-9]/', $text);
}

class ProductsCtrl {

    // --- Dashboard ---

    public function dashboard() {
        $dashboardStats    = Product::getDashboardStats();
        $categoryStats     = Product::getCategoryStats();
        $priceDistribution = Product::getPriceDistribution();
        $topProducts       = Product::getTopExpensiveProducts(5);
        $categories        = Category::getAllCategories();
        require_once "views/dashboardView.php";
    }

    // --- Product listing ---

    public function products() {
        $id_category = $_GET['id_category'] ?? 'all';
        $categories  = Category::getAllCategories();
        $products    = ($id_category === 'all')
            ? Product::getAllProducts()
            : Product::getProductsByCategory($id_category);
        require_once "views/productsView.php";
    }

    // --- CRUD ---

    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateProductData($_POST);

            if (!empty($errors)) {
                $this->setSessionMessage($errors, "danger");
                require_once "views/addProductView.php";
                return;
            }

            $result = Product::saveProduct($_POST['short_name'], $_POST['price'], $_POST['name'], $_POST['id_category']);
            $this->handleResult($result, "Product registered successfully!", "index.php?action=products", "index.php?action=createProduct");
        }

        require_once "views/addProductView.php";
    }

    public function editProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateProductData($_POST);

            if (!empty($errors)) {
                $this->setSessionMessage($errors, "danger");
                require_once "views/addProductView.php";
                return;
            }

            $result = Product::editProduct($_POST['id_product'], $_POST['name'], $_POST['short_name'], $_POST['price'], $_POST['id_category']);
            $this->handleResult($result, "Product updated successfully!", "index.php?action=products", "index.php?action=createProduct");
        }

        require_once "views/editProductView.php";
    }

    public function deleteProduct() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $result = Product::deleteProduct($id);
            $this->handleResult($result, "Product deleted successfully.", "index.php?action=products", "index.php?action=products");
        }
        header("Location: index.php?action=products");
        exit();
    }

    // --- Validation & helpers ---

    private function validateProductData($data) {
        $errors      = [];
        $short_name  = $data['short_name'] ?? '';
        $name        = $data['name']        ?? '';
        $id_category = $data['id_category'] ?? '';
        $price       = $data['price']       ?? '';

        if (!validate_string_only_specialchars($short_name)) $errors[] = "Short name cannot contain only special characters.";
        if (!validate_string_only_specialchars($name))       $errors[] = "Name cannot contain only special characters.";
        if (empty($name))                                    $errors[] = "Product name is required.";
        if (empty($short_name))                              $errors[] = "Short name is required.";
        if (empty($id_category))                             $errors[] = "You must select a category.";
        if (!is_numeric($price) || $price <= 0)              $errors[] = "Price must be a valid and positive number.";
        if (strlen($short_name) > 20)                        $errors[] = "Short name is too long.";

        return $errors;
    }

    private function setSessionMessage($messages, $type) {
        $_SESSION['message']      = "<br>" . (is_array($messages) ? implode("<br>", $messages) : $messages);
        $_SESSION['message_type'] = $type;
    }

    private function handleResult($result, $successMsg, $successUrl, $errorUrl) {
        if ($result) {
            $this->setSessionMessage($successMsg, "success");
            header("Location: $successUrl");
        } else {
            $this->setSessionMessage("Database operation failed.", "danger");
            header("Location: $errorUrl");
        }
        exit();
    }
}