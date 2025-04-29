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
                        <div class="card">
                            <div class="card-header">
                                <h1>Daftar</h1>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-bold fs-4" for="name">Nama Lengkap: </label>
                                            <input type="text" name="name" id="name" class="form-control" required placeholder="Masukkan Nama Lengkap Anda!">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label fw-bold fs-4" for="email">E-mail (Surel): </label>
                                            <input type="email" name="email" id="email" class="form-control" required placeholder="Masukkan E-mail (Surel) Anda!">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-bold fs-4" for="password">Password (Kata Sandi): </label>
                                            <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan Password Anda!">
                                        </div>
                                        <div class="col-sm-6">
                                        <label class="form-label fw-bold fs-4" for="role">Peran: </label>
                                        <select name="role" id="role" class="form-select">
                                            <option value="">Pilih Peran</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Kasir">Kasir</option>
                                            <option value="User">User</option>
                                            <option value="Pimpinan">Pimpinan</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success" name="daftar">Daftar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer bg-warning">
                                <div class="row mt-4">
                                    <div class="col-sm-12">
                                        <h5>Sudah mempunyai akun? <a href="login.php" title="Klik untuk masuk ke akun Anda" class="text-danger text-decoration-none">Login</a></h5>
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
