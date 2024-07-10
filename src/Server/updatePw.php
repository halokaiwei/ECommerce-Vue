<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $oldPassword = $data['oldPassword'];
    $newPassword = $data['newPassword'];
    $username = $data['username']; // 从 session 或其他方式获取当前用户的用户名

    // 获取当前用户的旧密码散列值
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($oldPassword, $user['password'])) {
        // 更新密码
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateStmt = $conn->prepare("UPDATE users SET password = :newPassword WHERE username = :username");
        $updateStmt->bindParam(':newPassword', $newPasswordHash);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Old password is incorrect']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn = null;
?>
