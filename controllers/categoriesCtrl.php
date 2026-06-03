<?php

require_once "models/categoryModel.php";

class CategoriesCtrl {

    public function categories() {
        require_once "views/categoriesView.php";
    }

    // --- CRUD ---

    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategoryDataForCreate($_POST);

            if (!empty($errors)) {
                $this->setSessionMessage($errors, "danger");
                require_once "views/addCategoryView.php";
                return;
            }

            $result = Category::saveCategory($_POST['name'], $_POST['long_name'], $_POST['color'], $_POST['icon']);
            $this->handleResult($result, "Category registered successfully!", "index.php?action=categories", "index.php?action=createCategory");
        }

        require_once "views/addCategoryView.php";
    }

    public function editCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategoryDataForEdit($_POST);

            if (!empty($errors)) {
                $this->setSessionMessage($errors, "danger");
                require_once "views/addCategoryView.php";
                return;
            }

            $result = Category::editCategory($_POST['id_category'], $_POST['name'], $_POST['long_name'], $_POST['color'], $_POST['icon']);
            $this->handleResult($result, "Category registered successfully!", "index.php?action=categories", "index.php?action=createCategory");
        }

        require_once "views/editCategoryView.php";
    }

    public function deleteCategory() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $result = Category::deleteCategory($id);
            $this->handleResult($result, "Category deleted successfully.", "index.php?action=categories", "index.php?action=categories");
        }
        header("Location: index.php?action=categories");
        exit();
    }

    // --- Validation ---

    // Used on create: also checks for name/long_name duplicates in the database
    private function validateCategoryDataForCreate($data) {
        $errors    = [];
        $name      = $data['name']      ?? '';
        $long_name = $data['long_name'] ?? '';
        $color     = $data['color']     ?? '';
        $icon      = $data['icon']      ?? '';

        if (!validate_string_only_specialchars($name))      $errors[] = "Short name cannot contain only special characters.";
        if (!validate_string_only_specialchars($long_name)) $errors[] = "Name cannot contain only special characters.";
        if (empty($name))                                   $errors[] = "Category name is required.";
        if (empty($long_name))                              $errors[] = "Long name is required.";
        if (empty($color))                                  $errors[] = "You must select a color.";
        if (empty($icon))                                   $errors[] = "You must select an icon.";

        if (Category::getCategoryByName($name))             $errors[] = "This category already exists: {$name}";
        if (Category::getCategoryByLongName($long_name))    $errors[] = "This category already exists: {$long_name}";

        return $errors;
    }

    // Used on edit: skips duplicate checks to allow saving without renaming
    private function validateCategoryDataForEdit($data) {
        $errors    = [];
        $name      = $data['name']      ?? '';
        $long_name = $data['long_name'] ?? '';
        $color     = $data['color']     ?? '';
        $icon      = $data['icon']      ?? '';

        if (!validate_string_only_specialchars($name))      $errors[] = "Short name cannot contain only special characters.";
        if (!validate_string_only_specialchars($long_name)) $errors[] = "Name cannot contain only special characters.";
        if (empty($name))                                   $errors[] = "Category name is required.";
        if (empty($long_name))                              $errors[] = "Long name is required.";
        if (empty($color))                                  $errors[] = "You must select a color.";
        if (empty($icon))                                   $errors[] = "You must select an icon.";

        return $errors;
    }

    // --- Helpers ---

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