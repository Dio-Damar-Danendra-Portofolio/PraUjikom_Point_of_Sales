<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    $kueri_produk = mysqli_query($koneksi, "SELECT products.*, categories.name AS product_category FROM products 
    JOIN categories ON products.category_id = categories.id;");
    $row_produk = mysqli_fetch_all($kueri_produk, MYSQLI_ASSOC);

    $kueri_kategori = mysqli_query($koneksi, "SELECT * FROM categories;");
    $row_kategori = mysqli_fetch_all($kueri_kategori, MYSQLI_ASSOC);

    if(isset($_POST['tambah'])){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $category_id = (int) $_POST['category_id'];
        
        $extension = array('png', 'jpg', 'jpeg');
        $ekstensi = pathinfo($image, PATHINFO_EXTENSION);

        if (!in_array($ekstensi, $extension)) {
            echo "Mohon maaf ektensi tidak terdaftar";
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/products/' . $image);

            $insert = mysqli_query($koneksi, "INSERT INTO products (name, price, image, category_id, stock, description) 
            VALUES ('$name', '$price', '$image', '$category_id', '$stock', '$description');");

            if ($insert) {
                header("Location: daftar_produk.php?tambah=sukses");
            } else {
                header("Location: tambah_sunting_produk.php?tambah=error");
            }
        }
    }
    
    if(isset($_GET['id-produk']) && $_GET['id-produk']){
        $id = $_GET['id-produk'];
        $kueri_edit = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id';");
        $row_edit = mysqli_fetch_assoc($kueri_edit);

        if(isset($_POST['simpan'])){
            if (isset($_GET['id-produk'])) {
                $id = $_GET['id-produk'];
                $name = $_POST['name'];
                $price = $_POST['price'];
                $stock = $_POST['stock'];
                $description = $_POST['description'];
                $image = $_FILES['image']['name'];
                $image_size = $_FILES['image']['size'];
                $category_id = (int) $_POST['category_id'];
                
                $extension = array('png', 'jpg', 'jpeg');
                $ekstensi = pathinfo($image, PATHINFO_EXTENSION);

                if (!in_array($ekstensi, $extension)) {
                    echo "Mohon maaf ektensi tidak terdaftar";
                } else {
                    unlink("uploads/products/" . $row_edit['image']);
                    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/products/" . $image);
                    
                    $update = mysqli_query($koneksi, "UPDATE products SET name = '$name', price = '$price', image = '$image', category_id = '$category_id', stock = '$stock', description = '$description' WHERE id = '$id';");

                    if ($update) {
                        header("Location: daftar_produk.php?edit=sukses");
                    } else {
                        header("Location: tambah_sunting_produk.php?edit=sukses");
                    }
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
                            <h1><?php echo isset($_GET['id-produk']) ? 'Ubah Data' : 'Tambah'?> Produk</h1>
                        </div>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold fs-30 text-light">Nama Produk (wajib diisi): </label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?php echo isset($_GET['id-produk']) ? $row_edit['name'] : ''; ?>">
                        </div> 
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-bold fs-30 text-light">Harga Produk (wajib diisi): </label>
                            <input type="number" name="price" id="price" class="form-control" required value="<?php echo isset($_GET['id-produk']) ? $row_edit['price'] : ''; ?>" min="1000" max="9999999999999">
                        </div>   
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="image" class="form-label fw-bold fs-30 text-light">Foto Produk (wajib diisi): </label>
                        <input type="file" name="image" id="image" class="form-control" <?php echo isset($_GET['id-produk']) ? '' : 'required'; ?>>
                        <?php if (isset($_GET['id-produk'])) { ?>
                            <small class="text-light">Nama file sebelumnya: <?php echo $row_edit['image']; ?></small><br>
                            <img src="uploads/products/<?php echo $row_edit['image']; ?>" class="mt-4" width="200" alt="Foto tidak tersedia">
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-bold fs-30 text-light">Kategori Produk (wajib dipilih): </label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Pilih Kategori</option>
                            <?php foreach($row_kategori as $kategori) { ?>
                            <option value="<?php echo $kategori['id']; ?>" <?php echo isset($_GET['id-produk']) && $kategori['id'] == $row_edit['category_id'] ? 'selected' : ''; ?>><?php echo $kategori['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <label class="form-label fw-bold fs-30 text-light" for="stock">Stok: </label>
                    <input type="number" id="stock" name="stock" class="form-control" <?php echo isset($_GET['id-produk']) ? $row_edit['stock'] : ''; ?>>
                    </div>  
                    <div class="col-md-6">
                    <label class="form-label fw-bold fs-30 text-light" for="description">Deskripsi Produk: </label>
                    <textarea name="description" id="description" class="form-control" cols="30" rows="10" value="<?php echo isset($_GET['id-produk']) ? $row_edit['description'] : ''; ?>"></textarea>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <?php if (isset($_GET['id-produk'])) { ?>
                            <button type="submit" class="btn btn-success" name="simpan">Simpan</button>
                            <?php } else { ?>
                            <button type="submit" class="btn btn-success" name="tambah">Tambah</button>
                            <?php } ?>
                    </div>
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <a href="daftar_produk.php" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
                </form>
        </div>
</main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>