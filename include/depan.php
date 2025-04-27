<?php 
    require "koneksi.php";
    if (isset($_POST['daftar'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $register_query = mysqli_query($koneksi, "INSERT INTO users (name, email, password, role) 
        VALUES ('$name', '$email', '$password', '$role')");

        if(!$register_query){
            header("Location: index.php?daftar=error");
        }
        else{
            header("Location: index.php?daftar=sukses");
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $login_query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email';");
        $login_row = mysqli_num_rows($login_query);

        if ($login_row > 0) {
            $user_row = mysqli_fetch_assoc($login_query);
            if ($user_row['password'] == $password) {
                $_SESSION['ID_USER'] = $user_row['id'];
                $_SESSION['NAME'] = $user_row['name'];

                header("Location: ?halaman=dashboard.php");
                
            } else {
                header("Location: ?halaman=login.php&login_status=error");
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "laman/judul_laman.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="css_js/tombol.js"></script>
</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="row p-2 bg-warning d-flex flex-wrap justify-content-center align-self-lg-center fixed-top">
                <div class="col-lg-12 text-center">
                    <a href="?halaman=index.php" class="text-decoration-none text-black"><h1>Resto PPKD Jakarta Pusat</h1></a>
                </div>
            </div>
        </div>
    </header>
    <?php if (isset($_GET['halaman'])){
        include $_GET['halaman'] . ".php";
    }?>
    <footer>
        <div class="container-fluid">
            <div class="row p-2 d-flex flex-wrap justify-content-center align-self-center">
                <div class="col-lg-8 text-center bg-warning w-100 align-items-center fixed-bottom">
                    <h4>&copy; <?php echo date('Y'); ?> Resto PPKD Jakarta Pusat</h4>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>