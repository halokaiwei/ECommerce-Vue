<?php
//connect database

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "Database '$dbname' created successfully<br>";

    $conn->exec("USE $dbname");

    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(50) NOT NULL,
        image BLOB,
        role ENUM('user', 'merchant') DEFAULT 'user',
        registerDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "Table 'users' created successfully<br>";

    $sql = "CREATE TABLE IF NOT EXISTS items (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        merchant_id INT(6) UNSIGNED NOT NULL,
        item_name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (merchant_id) REFERENCES users(id)
    )";
    
    $conn->exec($sql);
    echo "Table 'items' created successfully<br>";

    $sql = "CREATE TABLE IF NOT EXISTS images (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_id INT(11) UNSIGNED NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
    )";

    $conn->exec($sql);
    echo "Table images created successfully<br>";

    $sql = "CREATE TABLE IF NOT EXISTS wishlist (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        item_id INT(6) UNSIGNED NOT NULL,
        added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (item_id) REFERENCES items(id)
    )";
    
    $conn->exec($sql);
    echo "Table 'wishlist' created successfully<br>";

    $sql = "CREATE TABLE IF NOT EXISTS merchant (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        company_name VARCHAR(100) NOT NULL,
        contact_email VARCHAR(50) NOT NULL,
        contact_phone VARCHAR(20) NOT NULL,
        registerDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    
    $conn->exec($sql);
    echo "Table 'merchant' created successfully<br>";

} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
