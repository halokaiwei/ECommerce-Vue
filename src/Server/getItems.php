<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'shopdb';
$username = 'root';
$password = '';

try {
    // 创建PDO实例并设置错误模式为异常
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 准备SQL语句，选择items表的id、item_name、description、price和images表的image_path
    $stmt = $conn->prepare("SELECT items.id, items.item_name, items.description, items.price, images.image_path 
                        FROM items 
                        LEFT JOIN images ON items.id = images.item_id");
    // 执行SQL语句
    $stmt->execute();
    
    // fetchAll代表把所有结果返回，fetch的话只返回第一个结果
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 初始化数组，为了存储查询得到的数据，最后要返回
    $groupedItems = [];

    // 遍历每一个得到的结果items，groupedItems和item的id的索引是一样的，所以如果groupItems[4]的isset返回false的话，代表还没有把数据放进去groupItems，所以会执行把数据放进去groupItems的动作
    foreach($items as $item) {
        if(!isset($groupedItems[$item['id']])) {
            $groupedItems[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['item_name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'images' => []
            ];
        }
        // 这个item是已经和images.image_path JOIN了的，所以item里面会有image_path，这里看的是如果image_path存在（也就是和images表JOIN了后，image_path有数据），就把image_path数据放进去groupItems，因为等下要返回
        if($item['image_path']) {
            $baseURL = 'http://localhost/Server/';
            $groupedItems[$item['id']]['images'][] = $baseURL . $item['image_path'];
        }
    }

    // array_values的意思是把关联数组的键重置为顺序索引，这样返回的数据是一个顺序数组，而不是关联数组
    $groupedItems = array_values($groupedItems);

    echo json_encode(['success' => true, 'items' => $groupedItems]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// 关闭数据库连接
$conn = null;
?>
