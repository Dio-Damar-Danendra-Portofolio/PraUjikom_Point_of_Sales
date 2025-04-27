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

                header("Location: dashboard.php");
                
            } else {
                header("Location: login.php&login_status=error");
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="id-ID">
<?php include "include/head.php"; ?>
<body>
    <?php include "include/header.php"; ?>
    <main class="bg-info">
        <div class="container">
            <div class="container-fluid">
                <div class="row d-flex flex-wrap justify-content-center align-self-sm-center vh-100">
                    <div class="col-xl-12 align-self-xl-center text-center">
                        <div class="container">
                            <div class="row d-flex flex-wrap justify-content-center p-10 g-10">
                                <div class="col-md-6">
                                    <a href="login.php" class="btn btn-success btn-md fw-bold fs-2">Login</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="register.php" class="btn btn-dark btn-md fw-bold fs-2">Daftar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include "include/footer.php"; ?>
        <div class="container-fluid">
            <div class="row p-2 d-flex flex-wrap justify-content-center align-self-center">
                <div class="col-lg-8 text-center bg-warning w-100 align-items-center fixed-bottom">
                    <h4>&copy; <?php echo date('Y'); ?> Resto PPKD Jakarta Pusat</h4>
                </div>
            </div>
        </div>
</body>
</html>
