<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];

        $stmt = $conn->prepare("SELECT items.id, items.item_name, items.description, items.price, items.merchant_id, images.image_path
                                FROM items 
                                LEFT JOIN images ON items.id = images.item_id 
                                WHERE items.id = :id");
        $stmt->bindParam(':id', $item_id);
        $stmt->execute();

        $item = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($item) {
            $merchant_id = $item[0]['merchant_id'];

            // Fetch merchant name
            $merchant_stmt = $conn->prepare("SELECT company_name FROM merchants WHERE id = :merchant_id");
            $merchant_stmt->bindParam(':merchant_id', $merchant_id);
            $merchant_stmt->execute();
            $merchant = $merchant_stmt->fetch(PDO::FETCH_ASSOC);

            $result = [
                'id' => $item[0]['id'],
                'name' => $item[0]['item_name'],
                'description' => $item[0]['description'],
                'price' => $item[0]['price'],
                'merchant_name' => $merchant ? $merchant['company_name'] : 'Unknown',
                'images' => [],
            ];

            foreach ($item as $image) {
                if ($image['image_path']) {
                    $baseURL = 'http://localhost/Server/';
                    $result['images'][] = $baseURL . $image['image_path'];
                }
            }

            echo json_encode(['success' => true, 'item' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No item ID provided']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
?>
