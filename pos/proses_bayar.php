<?php
require "../koneksi.php";
session_start();

// Ambil data JSON dari fetch
$data = json_decode(file_get_contents('../api/get-products.php'), true);

if (!$data || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert ke tabel orders
    $stmt = $pdo->prepare("INSERT INTO orders (order_code, order_date, total_amount, cash_received, change_returned) 
        VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['receiptNo'],
        $data['receiptDate'],
        $data['total'],
        $data['amount'],
        $data['change']
    ]);

    $orderId = $pdo->lastInsertId();

    // Insert ke order_details
    $stmtDetail = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)");

    foreach ($data['cart'] as $item) {
        $stmtDetail->execute([
            $orderId,
            $item['id'],      // pastikan cart item ada id
            $item['qty'],
            $item['price']
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
