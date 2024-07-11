<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

$response = array();

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
        $item_id = isset($data['item_id']) ? intval($data['item_id']) : 0;
        $action = isset($data['action']) ? $data['action'] : '';

        //Debug use
        $response['user_id get: '] = $user_id;
        $response['item_id get: '] = $item_id;
        $response['action get: '] = $action;

        if ($user_id > 0 && $item_id > 0) {
            if ($action === 'add') {
                $checkSql = "SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id AND item_id = :item_id";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':user_id', $user_id);
                $checkStmt->bindParam(':item_id', $item_id);
                $checkStmt->execute();
                $count = $checkStmt->fetchColumn();

                if ($count == 0) {
                    $sql = "INSERT INTO wishlist (user_id, item_id) VALUES (:user_id, :item_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':item_id', $item_id);

                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = 'Item added to wishlist successfully';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'FAILED Item add to wishlist';
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Item in wishlist alrdy';
                }
            } elseif ($action === 'remove') {
                $sql = "DELETE FROM wishlist WHERE user_id = :user_id AND item_id = :item_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':item_id', $item_id);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Item removed from wishlist successfully';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to remove item from wishlist';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'invalid action';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'invalid id get';
        }

    } else {
        $response['success'] = false;
        $response['message'] = 'only accept POST request';
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = 'Connection failed: ' . $e->getMessage();
}

echo json_encode($response);
?>
