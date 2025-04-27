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
                            <h1>Profil</h1>
                        </div>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="logout.php" class="btn btn-warning btn-md">Logout</a>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 text-light">
                        <?php if (isset($_SESSION['PROFILE_PICTURE']) && !empty($_SESSION['PROFILE_PICTURE'])) { ?>
                            <img src="uploads/profile_pictures/<?php echo htmlspecialchars($_SESSION['PROFILE_PICTURE']); ?>" style="border-radius: 150px;" alt="Foto Profil" width="200">
                        <?php } else { ?>
                            <img src="uploads/profile_pictures/default.png" alt="Default Foto Profil" width="200">
                        <?php } ?>                    
                    </div>
                    <div class="col-xl-6 text-light">
                        <p><span class="fw-bold">Nama: </span> <?php echo isset($_SESSION['NAME']) ? htmlspecialchars($_SESSION['NAME']) : 'Belum login'; ?>                        
                    </p>
                        <p><span class="fw-bold">Peran: </span> <?php echo isset($_SESSION['ROLE']) ? htmlspecialchars($_SESSION['ROLE']) : 'Belum memiliki peran'; ?>        
                    </p>
                        <p><span class="fw-bold">Nomor Telepon: </span> <?php echo isset($_SESSION['PHONE_NUMBER']) ? htmlspecialchars($_SESSION['PHONE_NUMBER']) : 'Belum memiliki ponsel'; ?>        
                    </p>
                    </div>
                </div>
        </div>
        </main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>