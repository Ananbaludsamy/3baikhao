<?php
header('Content-Type: application/json');
$file = __DIR__ . '/../config/shop_settings.json';
$defaults = [
    'shop_name'      => 'ຮ້ານເຝີເຮືອ 3ໃບເຂົາ',
    'shop_phone'     => '020-XXXX-XXXX',
    'shop_address'   => 'ນະຄອນຫຼວງວຽງຈັນ',
    'point_value'    => 1000,
    'earn_rate'      => 10000,
    'tables_count'   => 20,
    'vip_threshold'  => 500000,
    'gold_threshold' => 2000000,
    'vip_discount'   => 5,
    'gold_discount'  => 10,
];
if (!file_exists($file)) {
    echo json_encode(['success' => true, 'settings' => $defaults]);
} else {
    $settings = json_decode(file_get_contents($file), true) ?: [];
    echo json_encode(['success' => true, 'settings' => array_merge($defaults, $settings)]);
}
