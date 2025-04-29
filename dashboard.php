<?php 
    session_start();
    session_regenerate_id();
    ob_start();
    require "koneksi.php";
    include "include/nav.php";
    if (!isset($_SESSION['NAME'])) {
        header("Location: login.php"); // prevent access if not logged in
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id-ID">
<?php include "include/head_dasbor.php"; ?>
<body class="bg-info">
<main class="bg-info pt-5 pb-5" style="margin-top: 140px; margin-bottom: 100px;"> 
        <?php include "include/header_dasbor.php"; ?>
        <div class="container">
                <div class="row bg-info justify-content-center">
                <div class="col-sm-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h1>Selamat Datang di Aplikasi POS PPKD JakPus!</h1>
                        </div>
                        <div class="card-body text-center">
                            <p>Ini merupakan aplikasi untuk restoran PPKD. Aplikasi ini dibuat untuk Pra-Ujikom.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </main>
<?php include "include/footer_dasbor.php"; ?>
</body>
</html>