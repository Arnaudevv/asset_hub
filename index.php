<?php
// Front controller: single entry point for all application requests.
// Reads the 'action' GET parameter and delegates to the appropriate controller method.

session_start();
require_once "ddbb/DBConexion.php";
require_once "controllers/productsCtrl.php";
require_once "controllers/categoriesCtrl.php";

$productController  = new ProductsCtrl();
$categoryController = new CategoriesCtrl();
$action             = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':       $productController->dashboard();        break;
    case 'products':        $productController->products();         break;
    case 'createProduct':   $productController->createProduct();    break;
    case 'editProduct':     $productController->editProduct();      break;
    case 'deleteProduct':   $productController->deleteProduct();    break;

    case 'categories':      $categoryController->categories();      break;
    case 'createCategory':  $categoryController->createCategory();  break;
    case 'editCategory':    $categoryController->editCategory();    break;
    case 'deleteCategory':  $categoryController->deleteCategory();  break;

    default:                $productController->dashboard();        break;
}