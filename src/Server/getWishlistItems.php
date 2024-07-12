<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    if ($user_id > 0) {
        $sql = "
            SELECT 
                wishlist.user_id,wishlist.item_id,
                items.item_name,items.price,
                images.image_path
            FROM wishlist
            INNER JOIN items ON wishlist.item_id = items.id
            LEFT JOIN images ON items.id = images.item_id
            WHERE wishlist.user_id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $baseURL = 'http://localhost/Server/';
            $groupedItems = [];

            foreach ($result as $item) {
                $itemId = $item['item_id'];
                if (!isset($groupedItems[$itemId])) {
                    $groupedItems[$itemId] = [
                        'user_id' => $item['user_id'],
                        'item_id' => $item['item_id'],
                        'item_name' => $item['item_name'],
                        'price' => $item['price'],
                        'images' => []
                    ];
                }

                if ($item['image_path']) {
                    $groupedItems[$itemId]['images'][] = $baseURL . $item['image_path'];
                }
            }

            $response = [
                'success' => true,
                'items' => array_values($groupedItems)
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'no item in wishlist'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'ivalid user id'
        ];
    }
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Connection failed: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
