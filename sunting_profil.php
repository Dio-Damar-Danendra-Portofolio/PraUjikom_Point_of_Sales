<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";

    if (!isset($_SESSION['NAME'])) {
        header("Location: login.php");
        exit;
    }

    if (isset($_POST['simpan'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone_number'];

        $id_user = $_SESSION['ID'];
    
        // --- Proses Upload Gambar ---
        $profile_picture = $_FILES['profile_picture']['name'];
        $tmp = $_FILES['profile_picture']['tmp_name'];
        $folder = "uploads/profile_pictures/";
    
        // Ambil data lama dulu kalau ada
        $query_old = mysqli_query($koneksi, "SELECT profile_picture FROM users WHERE id='$id_user'");
        $data_old = mysqli_fetch_assoc($query_old);
        $image_old = $data_old['profile_picture'];
    
        // Jika ada file baru diupload
        if ($profile_picture != "") {
            // Ganti nama file supaya unik
            $ext = pathinfo($profile_picture, PATHINFO_EXTENSION);
            $new_image_name = "Profile_" . time() . "." . $ext;
    
            // Pindahkan file ke folder upload
            move_uploaded_file($tmp, $folder . $new_image_name);
    
            // Hapus file lama jika ada
            if (!empty($image_old) && file_exists($folder . $image_old)) {
                unlink($folder . $image_old);
            }
        } else {
            $new_image_name = $image_old;
        }
    
        if (!empty($password)) {
            $query = "UPDATE users SET name='$name', email='$email', password='$password', role='$role', profile_picture='$new_image_name', phone_number='$phone_number' WHERE id='$id_user'";
        } else {
            $query = "UPDATE users SET name='$name', email='$email', role='$role', profile_picture='$new_image_name', phone_number='$phone_number' WHERE id='$id_user'";
        }
    
        $result = mysqli_query($koneksi, $query);
    
        if ($result) {
            $_SESSION['NAME'] = $name;
            $_SESSION['EMAIL'] = $email;
            $_SESSION['ROLE'] = $role;
            $_SESSION['PROFILE_PICTURE'] = $new_image_name;
            $_SESSION['PHONE_NUMBER'] = $phone_number;
            header("Location: profil.php");
            exit;
        } else {
            echo "Gagal update: " . mysqli_error($koneksi);
        }
    }
    
    

?>
<!DOCTYPE html>
<html lang="id-ID">
<?php include "include/head_dasbor.php"; ?>
<body class="bg-info">
<main class="bg-info pt-5 pb-5" style="margin-top: 140px; margin-bottom: 100px;"> 
        <?php include "include/header_dasbor.php"; ?>
        <div class="container">
                <div class="row bg-info justify-content-left">
                <div class="col-lg-12">
                        <div class="text-black text-left">
                            <h1>Sunting Profil</h1>
                        </div>
                </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-sm-6">
                            <label for="name" class="form-label fw-bold text-black">
                            Nama: 
                            </label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $_SESSION['NAME'];?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="email" class="form-label fw-bold text-black">
                            E-mail: 
                            </label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['EMAIL'];?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="password" class="form-label fw-bold text-black">
                            Password: 
                            </label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Isi">
                        </div>
                        <div class="col-sm-4">
                            <label for="role" class="form-label fw-bold text-black">
                            Peran: 
                            </label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Pilih Peran</option>
                                <option value="Admin" <?php if ($_SESSION['ROLE'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                <option value="Pimpinan" <?php if ($_SESSION['ROLE'] == 'Pimpinan') echo 'selected'; ?>>Pimpinan</option>
                                <option value="Kasir" <?php if ($_SESSION['ROLE'] == 'Kasir') echo 'selected'; ?>>Kasir</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="profile_picture" class="form-label fw-bold fs-30 text-black">Foto Profil (wajib diisi): </label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control" <?php echo isset($_SESSION['ID']) ? '' : 'required'; ?>>
                            <?php if (isset($_SESSION['ID'])) { ?>
                                <small class="text-black">Nama file sebelumnya: <?php echo $_SESSION['PROFILE_PICTURE']; ?></small><br>
                                <img src="uploads/profile_pictures/<?php echo $_SESSION['PROFILE_PICTURE']; ?>" class="mt-4" width="200" alt="Foto tidak tersedia">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="phone_number" class="form-label fw-bold fs-30 text-black">Nomor Telepon (wajib diisi): </label>
                            <input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" required value="<?php echo $_SESSION['PHONE_NUMBER']; ?>">
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-info" name="simpan">Simpan</button>
                        </div>
                        <div class="col-sm-6">
                            <a href="profil.php" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </form>
        </div>
        </main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>