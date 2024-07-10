<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Replace with your database credentials
$host = 'localhost';
$db = 'shopdb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_GET['username'];

    $query = "SELECT u.id, u.username, u.email, u.role, 
                     COALESCE(p.image_path, 'src/assets/images/defaultPicture.png') as image 
              FROM users u 
              LEFT JOIN profile_pictures p ON u.id = p.user_id 
              WHERE u.username = :username";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $baseURL = 'http://localhost/Server/';
        if ($user['image'] !== 'src/assets/images/defaultPicture.png') {
            $user['image'] = $baseURL . $user['image'];
        }
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>
