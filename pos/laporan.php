<?php 
    session_start();
    session_regenerate_id();
    ob_start();
  require "../koneksi.php";

  $product_query = "SELECT * FROM products";
  
  $search_keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : "";

  // Query ambil data produk
  $product_query = "SELECT * FROM products";
  if ($search_keyword) {
    $product_query .= " WHERE name LIKE '%$search_keyword%'";
  }
  $hasil_pencarian = $koneksi->query($product_query);

  $order_query = mysqli_query($koneksi, "SELECT * FROM orders JOIN order_details ON order_details.order_id = orders.id;");

  if (!isset($_SESSION['NAME'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id-ID">
<?php include "inc/head.php"; ?>
<body class="bg-blue-gray-50" x-data="initApp()" x-init="initDatabase()">
  <!-- noprint-area -->
  <div class="hide-print flex flex-row h-screen antialiased text-blue-gray-800">
    <?php include "inc/left-sidebar.php"; ?>
    <!-- page content -->
    <div class="flex-grow flex">
      <?php include "inc/store-menu.php"; ?>
      <!-- end of store menu -->

      <?php include "inc/right-sidebar.php"; ?>

      <!-- end of right sidebar -->
    </div>
    <!-- include "inc/modal.php"  -->
  </div>
  <!-- end of noprint-area -->

  <div id="print-area" class="print-area"></div>
  <script>
   
  </script>
</body>
</html>
