<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    $userN = $data->userN;
    $passW = password_hash($data->passW, PASSWORD_DEFAULT);

    // Check if username already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $checkStmt->bindParam(':username', $userN);
    $checkStmt->execute();
    if ($checkStmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->bindParam(':username', $userN);
    $stmt->bindParam(':password', $passW);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user_id = $conn->lastInsertId();

    $defaultImagePath = 'src/assets/images/defaultPicture.png'; 
    $stmt = $conn->prepare("INSERT INTO profile_pictures (user_id, image_path) VALUES (:user_id, :image_path)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':image_path', $defaultImagePath);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>
