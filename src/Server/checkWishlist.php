<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

$response = array();

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

        if ($user_id > 0 && $item_id > 0) {
            $sql = "SELECT COUNT(*) AS count FROM wishlist WHERE user_id = ? AND item_id = ?";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $response['success'] = true;
                $response['isInWishlist'] = true;
            } else {
                $response['success'] = true;
                $response['isInWishlist'] = false;
            }
            $stmt->closeCursor();
        } else {
            $response['success'] = false;
            $response['message'] = 'invalid id';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'only accept GET request';
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = 'Connection failed: ' . $e->getMessage();
}

echo json_encode($response);
?>
