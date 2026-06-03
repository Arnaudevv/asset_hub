<div align="center">

# 🖥️ Asset Hub
### IT Inventory Management System

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=flat-square&logo=chartdotjs&logoColor=white)](https://chartjs.org)
[![MVC](https://img.shields.io/badge/Architecture-MVC-0A66C2?style=flat-square)](https://en.wikipedia.org/wiki/Model–view–controller)
[![License](https://img.shields.io/badge/License-MIT-22c55e?style=flat-square)](LICENSE)

*Stop managing IT assets with spreadsheets. Asset Hub gives you a clean, visual dashboard to track everything in one place.*

</div>

---

## ✨ Features

| Feature | Description |
|---|---|
| 📊 **Analytics Dashboard** | Real-time charts showing asset distribution, inventory value and price ranges |
| 📦 **Asset Management** | Add, edit and delete products with a live preview card |
| 🏷️ **Custom Categories** | Create categories with custom colors and emojis |
| 👁️ **Live Preview** | See exactly how your card will look before saving |
| 📱 **Responsive** | Works on desktop and mobile with a collapsible sidebar |

---

## 🗂️ Project Structure

```
asset-hub/
├── controllers/
│   ├── productsCtrl.php       # Product CRUD logic
│   └── categoriesCtrl.php     # Category CRUD logic
├── models/
│   ├── productsModel.php      # Product DB queries
│   └── categoryModel.php      # Category DB queries
├── views/
│   ├── dashboardView.php      # Analytics dashboard
│   ├── productsView.php       # Inventory grid
│   ├── addProductView.php     # Create product form
│   ├── editProductView.php    # Edit product form
│   ├── categoriesView.php     # Category management
│   ├── addCategoryView.php    # Create category form
│   └── editCategoryView.php   # Edit category form
├── assets/
│   ├── styles/                # CSS files
│   └── js/                    # JavaScript files
├── ddbb/
│   └── DBConexion.php         # Database connection
└── index.php                  # Router (front controller)
```

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.x
- MySQL 5.7+
- Apache / Nginx (or XAMPP / WAMP locally)

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/your-username/asset-hub.git
cd asset-hub
```

**2. Import the database**
```bash
mysql -u root -p < database/asset_hub.sql
```

**3. Configure the connection**

Edit `ddbb/DBConexion.php` with your credentials:
```php
$host     = 'localhost';
$database = 'asset_hub';
$user     = 'root';
$password = 'your_password';
```

**4. Start the server and open `index.php`**

---

## 📖 How It Works

### Categories

Create categories to keep your inventory organized. Each one has a **name**, **color** and **emoji** — making items easy to identify at a glance.

> ⚠️ **Deleting a category** will not delete its products. Any item previously linked to it will be automatically marked as **Uncategorized** so nothing is ever lost.

### Products

Each product has a name, short code, category and price. The system generates a visual card for every item so you can scan your inventory instantly.

### Dashboard

The dashboard pulls live data from the database and displays it through three Chart.js charts:

- 🍩 **Doughnut** — asset count by category
- 📊 **Bar** — capital invested per category  
- 🎯 **Polar Area** — price range distribution

Plus a **Top 5 Most Expensive Assets** list.

---

## 🏗️ Architecture

Asset Hub follows a clean **MVC pattern**:

```
Request → index.php (Router) → Controller → Model → View
```

- **Model** — handles all database queries via prepared statements
- **View** — pure presentation, no business logic
- **Controller** — validates input, calls the model, loads the view

---

<div align="center">

*Asset Hub — Simplifying your IT inventory management.*

</div>