<?php

require_once '../../koneksi.php';

// Pastikan koneksi sukses
if (!$koneksi) {
    die('Koneksi Gagal: ' . mysqli_connect_error());
}

// Ambil nomor urut
$getdata = mysqli_query($koneksi, "SELECT MAX(RIGHT(code,4)) + 1 AS nextnumber FROM orders WHERE LEFT(code, 8) = '" . date('dmY') . "' ");
if (!$getdata) {
    die('Query Error: ' . mysqli_error($koneksi));
}

$rows = mysqli_fetch_assoc($getdata);
$nextnum = isset($rows['nextnumber']) && $rows['nextnumber'] != null ? sprintf('%04s', $rows['nextnumber']) : '0001';

$code = 'ORD' . date('dmY') . $nextnum;

// Validasi input
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$change = isset($_POST['change']) ? $_POST['change'] : 0;
$cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

if (empty($cart)) {
    die('Cart kosong.');
}

// Insert ke orders
$query = "INSERT INTO `orders` (`code`, `amount`, `change`, `status`) 
VALUES ('$code', '$total', '$change', '1')";

$result = mysqli_query($koneksi, $query);

if ($result) {
    $last_id = mysqli_insert_id($koneksi);

    foreach ($cart as $item) {
        $subtotal = $item['qty'] * $item['price'];
        $item_query = "INSERT INTO `order_details`(`order_id`, `product_id`, `qty`, `price`, `subtotal`) 
        VALUES ('$last_id', '{$item['productId']}', '{$item['qty']}', '{$item['price']}', '$subtotal')";
        
        $detailinsert = mysqli_query($koneksi, $item_query);
        if ($detailinsert) {
            mysqli_query($koneksi, "UPDATE products SET stock = stock - '{$item['qty']}' WHERE id = '{$item['productId']}'");
        }
    }
    header('location: ../index.php?order=' . $code);
    exit();
} else {
    die('Error Insert Order: ' . mysqli_error($koneksi));
}
?>
