<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    require_once "../koneksi.php";

    $query = "SELECT * FROM products WHERE is_available = 1;";

    $hasil = mysqli_query($koneksi, $query);

    $products = [];

    if (mysqli_num_rows($hasil) > 0) {
        while ($row = mysqli_fetch_assoc($hasil)) {
           $products['id'] = $row['id'];
           $products['name'] = $row['name'];
           $products['price'] = $row['price'];
           $products['image'] = $row['image'];
           $products['option'] = null;
        }
    }

    echo json_encode($products);
?>