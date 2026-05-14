<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['price']) || empty($_POST['cat'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat_id = $_POST['cat'];

    // ดึงข้อมูลเดิมเพื่อเช็คเรื่องรูปภาพ
    $stmt_old = $conn->prepare("SELECT Product_img FROM product_db WHERE Product_id = ?");
    $stmt_old->execute([$id]);
    $old_img = $stmt_old->fetchColumn();
    $img_path = $old_img;

    // จัดการการอัปโหลดรูปภาพใหม่ (ถ้ามี)
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img_path = 'assets/img/products/' . $new_file_name;
            // ลบรูปเก่าทิ้งถ้ามีการเปลี่ยนรูปใหม่ (ยกเว้นรูปเริ่มต้น)
            if ($old_img && file_exists('../' . $old_img)) {
                unlink('../' . $old_img);
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE product_db SET Product_name = ?, ProductType_id = ?, Product_Sale = ?, Product_img = ? WHERE Product_id = ?");
    $stmt->execute([$name, $cat_id, $price, $img_path, $id]);

    echo json_encode([
        'success' => true, 
        'product' => [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'cat' => $cat_id,
            'img' => $img_path
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}