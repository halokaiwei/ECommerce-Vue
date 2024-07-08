<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

//add new items, item images
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shopdb";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO items (item_name, description, price) VALUES (:name, :description, :price)");
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->execute();
        $item_id = $conn->lastInsertId();

        if (isset($_FILES['images'])) {
            $images = $_FILES['images'];
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($images['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($images['name'][$key]);
                $targetFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmp_name, $targetFilePath)) {
                    $imagePath = $targetFilePath;
                    $stmt = $conn->prepare("INSERT INTO images (item_id, image_path) VALUES (:item_id, :image_path)");
                    $stmt->bindParam(':item_id', $item_id);
                    $stmt->bindParam(':image_path', $imagePath);
                    $stmt->execute();
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Failed to move uploaded file'));
                    http_response_code(500);
                    exit;
                }
            }
        }

        echo json_encode(array('status' => 'success'));
    } catch (PDOException $e) {
        echo json_encode(array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage()));
        http_response_code(500);
    } finally {
        $conn = null; 
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Only POST requests are allowed'));
    http_response_code(405);
}

?>
