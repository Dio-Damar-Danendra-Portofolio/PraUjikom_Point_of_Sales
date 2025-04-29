<?php 
    require "koneksi.php";
    session_start();
    session_regenerate_id();
    ob_start();
    include "include/nav.php";
    $kueri_pengguna = mysqli_query($koneksi, "SELECT * FROM users;");
    $row_pengguna = mysqli_fetch_all($kueri_pengguna, MYSQLI_ASSOC);
    if (!isset($_SESSION['NAME'])) {
        header("Location: login.php");
        exit;
    }
    if (isset($_GET['id-hapus'])) {
        $id = $_GET['id-hapus'];
        $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id';");
        header("Location: daftar_pengguna.php?hapus=sukses");
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
                <div class="col-lg-6">
                        <div class="text-black text-left">
                            <h1>Daftar Pengguna</h1>
                        </div>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="tambah_sunting_pengguna.php" class="btn btn-light btn-md">Tambah Pengguna Baru</a>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>No. </th>
                            <th>Nama Lengkap Pengguna</th>
                            <th>E-mail Pengguna</th>
                            <th>Peran Pengguna</th>
                            <th>Nomor Ponsel Pengguna</th>
                            <th>Foto Profil Pengguna</th>
                            <th>Aksi (Tindakan)</th>
                        </tr>
                        <?php $i = 0; foreach($row_pengguna as $user) {?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td><?php echo $user['phone_number']; ?></td>
                            <td><img src="uploads/profile_pictures/<?php echo $user['profile_picture']; ?>" width="100" alt="Foto Tidak Tersedia"></td>
                            <td>
                                <a class="btn btn-info btn-md" title="Edit Data" href="tambah_sunting_pengguna.php?id-pengguna=<?php echo $user['id']; ?>">
                                    <i class="bi bi-gear-fill"></i>
                                </a>
                                <a class="btn btn-danger btn-md" title="Hapus Data" href="daftar_pengguna.php?id-hapus=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus data ini?'); ">
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
