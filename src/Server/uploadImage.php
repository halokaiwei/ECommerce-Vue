<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $image = $_FILES['image'];

        $targetDir = 'uploads/';
        $imagePath = $targetDir . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $stmt = $conn->prepare("UPDATE profile_pictures SET image_path = :image_path WHERE user_id = :user_id");
            $stmt->bindParam(':image_path', $imagePath);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            echo json_encode(['success' => true, 'imageUrl' => 'http://localhost/Server/' . $imagePath]); // adjust the base URL as needed
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
