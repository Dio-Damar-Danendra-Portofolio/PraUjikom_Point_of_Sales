<?php 
    require "koneksi.php";
    session_start();
    session_regenerate_id();
    ob_start();
    include "include/nav.php";
    $kueri_produk = mysqli_query($koneksi, "SELECT products.*, categories.name AS product_category FROM products 
    JOIN categories ON products.category_id = categories.id;");
    $row_produk = mysqli_fetch_all($kueri_produk, MYSQLI_ASSOC);

    if (isset($_GET['id-hapus'])) {
        $id = $_GET['id-hapus'];
        $hapus = mysqli_query($koneksi, "DELETE FROM products WHERE id = '$id';");
        header("Location: daftar_produk.php?hapus=sukses");
    } 

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
                <div class="col-lg-6">
                        <div class="text-white text-left">
                            <h1>Daftar Produk</h1>
                        </div>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="tambah_sunting_produk.php" class="btn btn-light btn-md">Tambah Produk Baru</a>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>No. </th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Foto Produk</th>
                            <th>Kategori Produk</th>
                            <th>Deskripsi Produk</th>
                            <th>Stok</th>
                            <th>Aksi (Tindakan)</th>
                        </tr>
                        <?php $i = 0; foreach($row_produk as $produk) {?>
                            <tr>
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $produk['name']; ?></td>
                                <td><?php echo $produk['price']; ?></td>
                                <td><img src="uploads/products/<?php echo $produk['image']; ?>" width="100" alt="Foto tidak tersedia"></td>
                                <td><?php echo $produk['product_category']; ?></td>
                                <td><?php echo $produk['description']; ?></td>
                                <td><?php echo $produk['stock']; ?></td>
                                <td>
                                    <a class="btn btn-info btn-md" title="Edit Data" href="tambah_sunting_produk.php?id-produk=<?php echo $produk['id']; ?>">
                                        <i class="bi bi-gear-fill"></i>
                                    </a>
                                    <a class="btn btn-danger btn-md" title="Hapus Data" href="daftar_produk.php?id-hapus=<?php echo $produk['id']; ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus data ini?'); ">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
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
