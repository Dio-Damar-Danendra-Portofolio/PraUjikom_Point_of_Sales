<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    $kueri_order = mysqli_query($koneksi, "SELECT products.price AS product_price, 
    products.name AS product_name, order_details.qty AS quantity, order_details.subtotal AS order_subtotal, orders.* FROM orders 
    JOIN order_details ON order_details.order_id = orders.id
    JOIN products ON order_details.product_id = products.id;");
    $row_order = mysqli_fetch_all($kueri_order, MYSQLI_ASSOC);

    if (!isset($_SESSION['NAME'])) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id-ID">
<?php include "include/head_dasbor.php"; ?>
<body class="bg-danger">
<main class="bg-danger pt-5 pb-5" style="margin-top: 140px; margin-bottom: 100px;"> 
        <?php include "include/header_dasbor.php"; ?>
        <div class="container">
                <div class="row bg-danger justify-content-left">
                <div class="col-lg-12">
                    <div class="text-white text-left">
                            <h1>Laporan Transaksi Produk</h1>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>No. </th>
                            <th>Nama Produk</th>
                            <th>Jumlah Produk</th>
                            <th>Harga Produk</th>
                            <th>Kode Transaksi</th>
                            <th>Status Transaksi</th>
                            <th>Jumlah Uang yang Dibayar</th>
                            <th>Subtotal</th>
                        </tr>
                        <?php $i = 0; foreach($row_order as $order) {?>
                            <tr>
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $order['product_name']; ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td><?php echo $order['product_price']; ?></td>
                                <td><?php echo $order['code']; ?></td>
                                <td><?php echo $order['status'] == 1 ? 'Dibayar' : 'Belum Dibayar'; ?></td>
                                <td><?php echo $order['amount']; ?></td>
                                <td><?php echo $order['order_subtotal']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    </div>
                </div>
        </div>
        </main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>
