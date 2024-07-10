<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "shopdb"; // your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && isset($_POST['user_name'])) {
        $user_name = $_POST['user_name'];

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $user_name, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['id'];
            $image = $_FILES['image'];
            $imagePath = 'uploads/' . basename($image['name']);

            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                $stmt = $pdo->prepare("SELECT id FROM profile_pictures WHERE user_id = :user_id");
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $existingImage = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingImage) {
                    $stmt = $pdo->prepare("UPDATE profile_pictures SET image_path = :image_path WHERE user_id = :user_id");
                    $stmt->bindParam(":image_path", $imagePath, PDO::PARAM_STR);
                    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                } 
                $imageUrl = 'http://localhost/Server/' . $imagePath; 
                echo json_encode(['success' => true, 'imageUrl' => $imageUrl]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
}
?>
