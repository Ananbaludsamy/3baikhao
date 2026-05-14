<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

// เปลี่ยนมารับค่าจาก $_POST แทน JSON เนื่องจากมีการส่งไฟล์
if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['cat'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat_id = $_POST['cat'];
    $img_path = '';

    // จัดการการอัปโหลดรูปภาพ
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img_path = 'assets/img/products/' . $new_file_name; // เก็บ Path สัมพัทธ์ไว้ใน DB
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO product_db (Product_name, ProductType_id, Product_unit, Product_Sale, Product_img) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $cat_id, 1, $price, $img_path]);
    
    $product_id = $conn->lastInsertId();

    echo json_encode([
        'success' => true, 
        'product' => [
            'id' => $product_id,
            'name' => $name,
            'price' => $price,
            'cat' => $cat_id,
            'img' => $img_path
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}