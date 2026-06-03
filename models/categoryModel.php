<?php

require_once "ddbb/DBConexion.php";

class Category {

    // --- Properties ---
    protected $id;
    protected $name;
    protected $long_name;
    protected $color;
    protected $icon;

    // --- Constructor ---
    public function __construct($row) {
        $this->id        = $row["id"];
        $this->name      = $row["name"];
        $this->long_name = $row["long_name"];
        $this->color     = $row["color"];
        $this->icon      = $row["icon"];
    }

    // --- Getters ---
    public function getCategoryId()       { return $this->id; }
    public function getCategoryName()     { return $this->name; }
    public function getCategoryLongName() { return $this->long_name; }
    public function getCategoryColor()    { return $this->color; }
    public function getCategoryIcon()     { return $this->icon; }

    // --- CRUD ---

    public static function saveCategory($name, $long_name, $color, $icon) {
        $db   = DBConexion::connection();
        $sql  = "INSERT INTO category (name, long_name, color, icon) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $name, $long_name, $color, $icon);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function editCategory($id, $name, $long_name, $color, $icon) {
        $db   = DBConexion::connection();
        $sql  = "UPDATE category SET name = ?, long_name = ?, color = ?, icon = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $name, $long_name, $color, $icon, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function deleteCategory($id) {
        $db   = DBConexion::connection();
        $sql  = "DELETE FROM category WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // --- Queries ---

    public static function getAllCategories() {
        $db   = DBConexion::connection();
        $data = $db->query("SELECT id, name, long_name, color, icon FROM category");

        $categories = [];
        while ($row = $data->fetch_assoc()) {
            $categories[] = new Category($row);
        }
        return $categories;
    }

    public static function getCategoryById($id) {
        $db   = DBConexion::connection();
        $sql  = "SELECT id, name, long_name, color, icon FROM category WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        // Return a neutral placeholder when no matching category exists
        return $row
            ? new self($row)
            : new self(["id" => 0, "name" => "Uncategorized", "long_name" => "Uncategorized", "color" => "#6c757d4d", "icon" => "❓"]);
    }

    public static function getCategoryByName($name) {
        $db   = DBConexion::connection();
        $stmt = $db->prepare("SELECT id, name, long_name, color, icon FROM category WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public static function getCategoryByLongName($long_name) {
        $db   = DBConexion::connection();
        $stmt = $db->prepare("SELECT id, name, long_name, color, icon FROM category WHERE long_name = ?");
        $stmt->bind_param("s", $long_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
}