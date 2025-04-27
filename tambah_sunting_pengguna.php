<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";

    if(isset($_POST['tambah'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone_number'];
        $profile_picture = $_FILES['profile_picture']['name'];
        $profile_picture_size = $_FILES['profile_picture']['size'];
        $role = $_POST['role'];

        $extension = array('png', 'jpg', 'jpeg');
        $ekstensi = pathinfo($profile_picture, PATHINFO_EXTENSION);

        if (!in_array($ekstensi, $extension)) {
            echo "Mohon maaf ektensi tidak terdaftar";
        } else {
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/profile_pictures/' . $profile_picture);
            $insert = mysqli_query($koneksi, "INSERT INTO users (name, email, password, role, profile_picture, phone_number) VALUES ('$name', '$email', '$password', '$role', '$profile_picture', '$phone_number');");

        if ($insert) {
            header("Location: daftar_pengguna.php?tambah=sukses");
        } else {
            header("Location: tambah_sunting_pengguna.php?tambah=error");
        }
    }
}

    if (isset($_GET['id-pengguna']) && $_GET['id-pengguna']) {
        if (isset($_GET['id-pengguna'])) {
            $id = $_GET['id-pengguna'];
            $kueri_edit = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id';");
            $row_edit = mysqli_fetch_assoc($kueri_edit);

            if(isset($_POST['simpan'])){
                $id = $_GET['id-pengguna'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $phone_number = $_POST['phone_number'];
                $profile_picture = $_FILES['profile_picture']['name'];
                $profile_picture_size = $_FILES['profile_picture']['size'];
                $role = $_POST['role'];

                $extension = array('png', 'jpg', 'jpeg');
                $ekstensi = pathinfo($profile_picture, PATHINFO_EXTENSION);

                if (!in_array($ekstensi, $extension)) {
                    echo "Mohon maaf ektensi tidak terdaftar";
                } else {
                    unlink("uploads/profile_pictures/" . $row_edit['profile_picture']);
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/profile_pictures/' . $profile_picture);
                    $update = mysqli_query($koneksi, "UPDATE users SET name = '$name', email = '$email', password = '$password', role = '$role', profile_picture = '$profile_picture', phone_number = '$phone_number' WHERE id = '$id';");
        
                if ($update) {
                    header("Location: daftar_pengguna.php?edit=sukses");
                } else {
                    header("Location: tambah_sunting_pengguna.php?edit=error");
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
                            <h1><?php echo isset($_GET['id-kategori']) ? 'Ubah Data ' : 'Tambah '?> Kategori Produk</h1>
                        </div>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold fs-30 text-light">Nama Lengkap Pengguna (wajib diisi): </label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?php echo isset($_GET['id-pengguna']) ? $row_edit['name'] : ''; ?>">
                        </div> 
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <label for="email" class="form-label fw-bold fs-30 text-light">E-mail (Surel) Pengguna (wajib diisi): </label>
                            <input type="email" name="email" id="email" class="form-control" required value="<?php echo isset($_GET['id-pengguna']) ? $row_edit['email'] : ''; ?>">
                        </div> 
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <label for="password" class="form-label fw-bold fs-30 text-light">Password (Kata Sandi) Pengguna (wajib diisi): </label>
                            <input type="password" name="password" id="password" class="form-control" required value="<?php echo isset($_GET['id-pengguna']) ? $row_edit['password'] : ''; ?>">
                        </div> 
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <label for="profile_picture" class="form-label fw-bold fs-30 text-light">Foto Profil Pengguna (wajib diisi): </label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control" <?php echo isset($_GET['id-pengguna']) ? '' : 'required'; ?>>
                            <?php if (isset($_GET['id-pengguna'])) { ?>
                                <small class="text-light">Nama file sebelumnya: <?php echo $row_edit['profile_picture']; ?></small><br>
                                <img src="uploads/profile_pictures/<?php echo $row_edit['profile_picture']; ?>" class="mt-2" width="200" alt="Foto tidak tersedia">
                        <?php } ?>
                        </div> 
                </div>
                <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="role" class="form-label fw-bold fs-30 text-light">Peran Pengguna (wajib dipilih): </label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Pilih Peran</option>
                            <?php $peran = ["Admin", "Kasir", "Pimpinan"]; 
                            foreach ($peran as $peran_pengguna) {?>
                                <option value="<?php echo $peran_pengguna; ?>" <?php echo isset($_GET['id-pengguna']) && $peran_pengguna == $row_edit['role'] ? 'selected' : '' ?>><?php echo $peran_pengguna; ?></option>
                            <?php } ?>
                            </select>
                        </div> 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="phone_number" class="form-label fw-bold fs-30 text-light">Nomor Telepon (wajib diisi): </label>
                        <input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" required value="<?php echo isset($_GET['id-pengguna']) ? $row_edit['phone_number'] : '' ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <?php if (isset($_GET['id-pengguna'])) { ?>
                            <button type="submit" class="btn btn-success" name="simpan">Simpan</button>
                            <?php } else { ?>
                            <button type="submit" class="btn btn-success" name="tambah">Tambah</button>
                            <?php } ?>
                    </div>
                    <div class="col-md-6 justify-content-center mt-4 d-flex flex-wrap">
                        <a href="daftar_pengguna.php" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
                </form>
        </div>
</main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>