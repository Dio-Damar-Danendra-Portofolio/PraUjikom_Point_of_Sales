<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    $where = "";
$datefrom = date('Y-m-d');
$dateto = date('Y-m-d');

if (isset($_POST['submitreport'])) {
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];

    if (!empty($datefrom) && !empty($dateto)) {
        $where = "WHERE orders.date >= '$datefrom' AND orders.date <= '$dateto'";
    }
}
    $kueri_order = mysqli_query($koneksi, "SELECT products.price AS product_price, 
    products.name AS product_name, order_details.qty AS quantity, order_details.subtotal AS order_subtotal, orders.* FROM orders 
    JOIN order_details ON order_details.order_id = orders.id
    JOIN products ON order_details.product_id = products.id
    ".$where."
    ");
    $row_order = mysqli_fetch_all($kueri_order, MYSQLI_ASSOC);

    if (!isset($_SESSION['NAME'])) {
        header("Location: login.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="id-ID">
<head>
<?php include "include/head_dasbor.php"; ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <!-- Bootstrap (Opsional, jika kamu pakai Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-info">
<main class="bg-info pt-5 pb-5" style="margin-top: 140px; margin-bottom: 100px;"> 
    <?php include "include/header_dasbor.php"; ?>
    <div class="container">
        <div class="row bg-info justify-content-left">
            <div class="col-lg-12">
                <div class="text-black text-left">
                    <h1>Laporan Transaksi Produk</h1>
                </div>
            </div>
        </div>
        <form action="" method="post">
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <label for="datefrom" class="form-label fw-bold text-black">Tanggal Awal: </label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="<?= $datefrom ?>">
                                    </div>
                                    <div class="col-3">
                                        <label for="dateto" class="form-label fw-bold text-black">Tanggal Akhir: </label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="<?= $dateto ?>">
                                    </div>
                                    <div class="col-2 align-self-end">
                                        <button type="submit" class="btn btn-primary" name="submitreport">
                                            <i class="bi bi-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
        <table class="table table-striped datatablebutton text-center">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Produk</th>
                    <th>Harga Produk</th>
                    <th>Kode Transaksi</th>
                    <th>Status Transaksi</th>
                    <th>Jumlah Uang yang Dibayar</th>
                    <th>Subtotal</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($row_order as $order) {
                    echo "<tr>
                            <td>" . ++$i . "</td>
                            <td>{$order['product_name']}</td>
                            <td>{$order['quantity']}</td>
                            <td>{$order['product_price']}</td>
                            <td>{$order['code']}</td>
                            <td>" . ($order['status'] == 1 ? 'Dibayar' : 'Belum Dibayar') . "</td>
                            <td>{$order['amount']}</td>
                            <td>{$order['order_subtotal']}</td>
                            <td></td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="container-fluid">
        <div class="row p-2 d-flex flex-wrap justify-content-center align-self-center">
            <div class="col-lg-8 text-center bg-warning w-100 align-items-center fixed-bottom">
                <h4>&copy; <?php echo date('Y')?> Resto PPKD Jakarta Pusat</h4>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<!-- Export Plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- DataTables Init -->
<script>
    $(document).ready(function() {
        $('.datatablebutton').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            paging: false
        });
    });
</script>

</body>
</html>
