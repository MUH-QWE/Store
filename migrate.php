<<<<<<< HEAD
<?php



require_once __DIR__ . '/app/bootstrap.php';

use App\Helpers\Database;

echo "Starting Database Migration...\n";

try {
    $sql = file_get_contents(__DIR__ . '/app/sql/setup_database.sql');
    if (!$sql) {
        die("Error: Could not read sql file.");
    }

    $pdo = Database::getConnection();
    $pdo->exec($sql);
    
    echo "Tables created successfully.\n";
    
    
    $adminEmail = 'admin@store.com';
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    
    if (!$stmt->fetch()) {
        $pass = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['Admin', $adminEmail, $pass]);
        echo "Default admin user created (admin@store.com / admin123).\n";
    }

    echo "Migration Complete.\n";

} catch (PDOException $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
=======
<?php



require_once __DIR__ . '/app/bootstrap.php';

use App\Helpers\Database;

echo "Starting Database Migration...\n";

try {
    $sql = file_get_contents(__DIR__ . '/app/sql/setup_database.sql');
    if (!$sql) {
        die("Error: Could not read sql file.");
    }

    $pdo = Database::getConnection();
    $pdo->exec($sql);
    
    echo "Tables created successfully.\n";
    
    
    $adminEmail = 'admin@store.com';
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    
    if (!$stmt->fetch()) {
        $pass = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['Admin', $adminEmail, $pass]);
        echo "Default admin user created (admin@store.com / admin123).\n";
    }

    echo "Migration Complete.\n";

} catch (PDOException $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
>>>>>>> e27ec8f7fb7c7231818d1513b72f8cc50b28affd
