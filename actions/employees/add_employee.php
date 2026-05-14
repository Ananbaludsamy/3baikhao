<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['name']) || empty($data['card']) || empty($data['address']) || empty($data['tel']) || empty($data['gender']) || empty($data['username']) || empty($data['password']) || empty($data['role'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $name = $data['name'];
    $card = $data['card'];
    $address = $data['address'];
    $tel = $data['tel'];
    $gender = $data['gender'];
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password for security
    $role = $data['role'];

    $stmt = $conn->prepare("INSERT INTO employee_db (Emp_name, Emp_card, Emp_Address, Emp_Tel, Emp_gender, Username, Password, Role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $card, $address, $tel, $gender, $username, $password, $role]);

    echo json_encode(['success' => true, 'message' => 'เพิ่มพนักงานสำเร็จ']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}