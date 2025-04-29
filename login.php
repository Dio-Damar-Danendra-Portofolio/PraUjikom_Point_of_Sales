<?php 
 require "koneksi.php";
 include "include/nav.php";
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login_query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' AND password = '$password';");
    if (mysqli_num_rows($login_query) > 0) {
        $user_row = mysqli_fetch_assoc($login_query);
        if ($user_row['password'] == $password) {
            session_start();
            $_SESSION['ID'] = $user_row['id'];
            $_SESSION['NAME'] = $user_row['name'];
            $_SESSION['ROLE'] = $user_row['role'];
            $_SESSION['PROFILE_PICTURE'] = $user_row['profile_picture'];
            $_SESSION['PHONE_NUMBER'] = $user_row['phone_number'];
            $_SESSION['EMAIL'] = $user_row['email'];


            if (isset($_POST['remember'])) {
                setcookie('email', $email, time() + (366 * 24 * 60 * 60), "/");
                setcookie('password', $password, time() + (366 * 24 * 60 * 60), "/");
            }

            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: login.php?login_status=error");
        }
    } else {
        header("Location: login.php?login_status=error");
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
                        <div class="card">
                            <div class="card-header">
                                <h1>Login</h1>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="form-label fw-bold fs-4" for="email">E-mail (Surel): </label>
                                            <input type="email" name="email" id="email" class="form-control" required placeholder="Masukkan E-mail Anda">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="form-label fw-bold fs-4" for="password">Password (Kata Sandi): </label>
                                            <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan Password Anda">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Ingat saya
                                        </label>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-4">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success" name="login">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer bg-warning">
                                <div class="row mt-4">
                                    <div class="col-sm-12">
                                        <h5>Belum mempunyai akun? <a href="register.php" title="Klik untuk mendaftarkan akun Anda" class="text-danger text-decoration-none">Daftarkan</a> akun Anda!</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
</main>
<?php include "include/footer.php";?>
</body>
</html>
