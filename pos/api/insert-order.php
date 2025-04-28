<?php

require_once '../../koneksi.php';

// Pastikan koneksi sukses
if (!$koneksi) {
    die('Koneksi Gagal: ' . mysqli_connect_error());
}

// Ambil nomor urut
$getdata = mysqli_query($koneksi, "SELECT max(right(code,4)) + 1 as nextnumber FROM orders WHERE left(code, 11) = '$today'; ");
$check_data = mysqli_num_rows($getdata);
if ($check_data > 0) {
    $row_data =  mysqli_fetch_assoc($getdata);
}

$product_order_code = 'ORD_' . date('dmY_His');
$date_today = date('Y-m-d');

// Validasi input
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$change = isset($_POST['change']) ? $_POST['change'] : 0;
$cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

if (empty($cart)) {
    die('Cart kosong.');
}


$result = mysqli_query($koneksi, "INSERT INTO `orders` (`code`, `amount`, `change`, `status`, `date`) 
VALUES ('$product_order_code', '$total', '$change', '1', '$date_today');");

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
    header('Location: ../index.php?order=' . $product_order_code);
    exit();
} else {
    die('Terjadi kesalahan dalam pemesanan: ' . mysqli_error($koneksi));
}
?>
