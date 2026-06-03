<?php

require_once "ddbb/DBConexion.php";
require_once "models/CategoryModel.php";

class Product {

    // --- Properties ---
    protected $id;
    protected $name;
    protected $short_name;
    protected $price;
    protected $id_category;

    // --- Constructor ---
    public function __construct($row) {
        $this->id          = $row["id"];
        $this->name        = $row["name"];
        $this->short_name  = $row["short_name"];
        $this->price       = $row["price"];
        $this->id_category = $row["id_category"];
    }

    // --- Getters ---
    public function getProductId()         { return $this->id; }
    public function getProductName()       { return $this->name; }
    public function getProductShortName()  { return $this->short_name; }
    public function getProductPvp()        { return $this->price; }
    public function getProductCategoryId() { return $this->id_category; }

    // --- CRUD ---

    public static function saveProduct($short_name, $price, $name, $id_category) {
        $db   = DBConexion::connection();
        $sql  = "INSERT INTO products (short_name, price, name, id_category) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdsi", $short_name, $price, $name, $id_category);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function editProduct($id_product, $name, $short_name, $price, $id_category) {
        $db   = DBConexion::connection();
        $sql  = "UPDATE products SET short_name = ?, price = ?, name = ?, id_category = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssi", $short_name, $price, $name, $id_category, $id_product);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function deleteProduct($id) {
        $db   = DBConexion::connection();
        $sql  = "DELETE FROM products WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- Queries ---

    public static function getAllProducts() {
        $db   = DBConexion::connection();
        $data = $db->query("SELECT id, short_name, name, price, id_category FROM products");

        $products = [];
        while ($row = $data->fetch_assoc()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    public static function getProductsByCategory($id_category) {
        $db   = DBConexion::connection();
        $sql  = "SELECT id, short_name, name, price, id_category FROM products WHERE id_category = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_category);
        $stmt->execute();
        $result   = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = new Product($row);
        }
        $stmt->close();
        return $products;
    }

    public static function getProductByCode($id) {
        $db   = DBConexion::connection();
        $sql  = "SELECT * FROM products WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        return $row ? new Product($row) : null;
    }

    // --- Dashboard statistics ---

    // Returns aggregate counts and price metrics for the summary cards
    public static function getDashboardStats() {
        $db     = DBConexion::connection();
        $query  = "SELECT COUNT(*) as total_count, COALESCE(SUM(price), 0) as total_value, COALESCE(AVG(price), 0) as avg_price FROM products";
        $result = $db->query($query);
        return $result->fetch_assoc();
    }

    // Returns per-category product counts and total inventory value for chart rendering
    public static function getCategoryStats() {
        $db    = DBConexion::connection();
        $query = "SELECT c.id, c.name, c.long_name, c.icon, COUNT(p.id) as product_count, COALESCE(SUM(p.price), 0) as total_value
                FROM category c
                LEFT JOIN products p ON c.id = p.id_category
                GROUP BY c.id, c.name, c.long_name, c.icon";
        $result = $db->query($query);
        $stats  = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        return $stats;
    }

    // Returns product counts bucketed into four price tiers for the polar-area chart
    public static function getPriceDistribution() {
        $db    = DBConexion::connection();
        $query = "SELECT
                    SUM(CASE WHEN price < 50 THEN 1 ELSE 0 END)                        as under_50,
                    SUM(CASE WHEN price >= 50  AND price < 150 THEN 1 ELSE 0 END)      as range_50_150,
                    SUM(CASE WHEN price >= 150 AND price < 500 THEN 1 ELSE 0 END)      as range_150_500,
                    SUM(CASE WHEN price >= 500 THEN 1 ELSE 0 END)                      as over_500
                    FROM products";
        $result = $db->query($query);
        return $result->fetch_assoc();
    }

    // Returns the N most expensive products with their category details for the dashboard list
    public static function getTopExpensiveProducts($limit = 5) {
        $db    = DBConexion::connection();
        $query = "SELECT p.name, p.short_name, p.price,
                        COALESCE(c.name,      '❓uncategorized') as category_name,
                        COALESCE(c.long_name, '❓uncategorized') as category_long_name,
                        c.icon as category_icon
                    FROM products p
                    LEFT JOIN category c ON p.id_category = c.id
                    ORDER BY p.price DESC
                    LIMIT ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result   = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        return $products;
    }
}