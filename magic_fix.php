<?php
/**
 * TAYEB DJERBA - Deployment Magic Fixer
 * This script automates moving files from /web to root and setting up the database.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🚀 TAYEB DJERBA - Deployment Wizard</h1>";

// 1. Move files from /web/ to root
if (is_dir('web')) {
    echo "<h3>📁 Step 1: Moving files from /web to root...</h3>";
    $files = scandir('web');
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        if (rename("web/$file", "$file")) {
            echo "✅ Moved: $file<br>";
        } else {
            echo "❌ Failed to move: $file<br>";
        }
    }
    // Try to remove web folder
    @rmdir('web');
    echo "<b>Folder structure fixed!</b><br>";
} else {
    echo "ℹ️ /web folder not found. Skipping move step.<br>";
}

// 2. Test Database Connection
echo "<h3>🗄️ Step 2: Testing Database Connection...</h3>";
$config = require_once 'app/config/env.php';

try {
    $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database Connection Successful!<br>";

    // 3. Setup Tables
    echo "<h3>🛠️ Step 3: Setting up Database Tables...</h3>";
    $sqlFile = 'app/sql/setup_database.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Check if tables already exist
        $stmt = $pdo->query("SHOW TABLES LIKE 'products'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec($sql);
            echo "✅ SQL Script Executed Successfully!<br>";
        } else {
            echo "ℹ️ Tables already exist. Skipping creation.<br>";
        }
        
        // Add categories if empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO categories (name) VALUES ('General'), ('Electronics'), ('Clothing')");
            echo "✅ Default categories added.<br>";
        }

        // Add admin user if missing
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(['admin@store.com']);
        if (!$stmt->fetch()) {
            $pass = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@store.com', ?, 'admin')");
            $stmt->execute([$pass]);
            echo "✅ Admin User Created (admin@store.com / admin123).<br>";
        }
    } else {
        echo "❌ SQL Setup file not found at: $sqlFile<br>";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
    echo "<i>Tip: Check your credentials in app/config/env.php</i><br>";
}

echo "<h3>🎉 Done!</h3>";
echo "<p>You can now visit your site: <a href='index.html'>Go to Store</a></p>";
echo "<p style='color:red;'><b>IMPORTANT: Delete this file (magic_fix.php) from your server for security!</b></p>";
