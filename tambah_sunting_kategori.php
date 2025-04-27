<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    $kueri_kategori = mysqli_query($koneksi, "SELECT * FROM categories;");
    $row_kategori = mysqli_fetch_all($kueri_kategori, MYSQLI_ASSOC);

    if(isset($_POST['tambah'])){
        $name = $_POST['name'];

        $insert = mysqli_query($koneksi, "INSERT INTO categories (name) VALUES ('$name');");

        if ($insert) {
            header("Location: daftar_kategori.php?tambah=sukses");
        } else {
            header("Location: tambah_sunting_kategori.php?tambah=error");
        }
    }

    if (isset($_GET['id-kategori']) && $_GET['id-kategori']) {
        if (isset($_GET['id-kategori'])) {
            $id = $_GET['id-kategori'];
            $kueri_edit = mysqli_query($koneksi, "SELECT * FROM categories WHERE id = '$id';");
            $row_edit = mysqli_fetch_assoc($kueri_edit);

            if(isset($_POST['simpan'])){
                $id = $_GET['id-kategori'];
                $name = $_POST['name'];

                $update = mysqli_query($koneksi, "UPDATE categories SET name = '$name' WHERE id = '$id';");

                if ($update) {
                    header("Location: daftar_kategori.php?edit=sukses");
                } else {
                    header("Location: tambah_sunting_kategori.php?edit=error");
                } 
        }
        
        }
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
                    <div class="col-lg-12">
                        <div class="text-white text-left">
                            <h1><?php echo isset($_GET['id-kategori']) ? 'Ubah Data ' : 'Tambah '?> Kategori Produk</h1>
                        </div>
                    </div>
                </div>
                <form action="" method="post">
                <div class="row">
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold fs-30 text-light">Nama Kategori (wajib diisi): </label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?php echo isset($_GET['id-kategori']) ? $row_edit['name'] : ''; ?>">
                        </div> 
                </div>
                <div class="row">
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <?php if (isset($_GET['id-kategori'])) { ?>
                            <button type="submit" class="btn btn-success" name="simpan">Simpan</button>
                            <?php } else { ?>
                            <button type="submit" class="btn btn-success" name="tambah">Tambah</button>
                            <?php } ?>
                    </div>
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <a href="daftar_kategori.php" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
                </form>
        </div>
</main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>