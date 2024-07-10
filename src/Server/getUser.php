<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$db = 'shopdb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_GET['user_id'];

    $query = "SELECT u.id, u.username, u.email, u.role, 
                     COALESCE(p.image_path, 'src/assets/images/defaultPicture.png') as image 
              FROM users u 
              LEFT JOIN profile_pictures p ON u.id = p.user_id 
              WHERE u.id = :user_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
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
