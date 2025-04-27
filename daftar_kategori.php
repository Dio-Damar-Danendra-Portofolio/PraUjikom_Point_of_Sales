<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    $kueri_kategori = mysqli_query($koneksi, "SELECT * FROM categories;");
    $row_kategori = mysqli_fetch_all($kueri_kategori, MYSQLI_ASSOC);

    if (isset($_GET['id-hapus'])) {
        $id = $_GET['id-hapus'];
        $hapus = mysqli_query($koneksi, "DELETE FROM categories WHERE id = '$id';");
        header("Location: daftar_kategori.php?hapus=sukses");
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
                            <h1>Daftar Kategori Produk</h1>
                        </div>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="tambah_sunting_kategori.php" class="btn btn-light btn-md">Tambah Kategori Baru</a>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>No. </th>
                            <th>Nama Kategori</th>
                            <th>Aksi (Tindakan)</th>
                        </tr>
                        <?php $i = 0; foreach($row_kategori as $kategori) {?>
                            <tr>
                                <td><?php echo ++$i; ?></td>
                                <td><?php echo $kategori['name']; ?></td>
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
