<header>
        <div class="container-fluid">
            <div class="row p-2 bg-warning d-flex flex-wrap justify-content-center align-self-lg-center fixed-top">
                <div class="col-lg-12 text-center">
                    <div class="row mb-4 text-center">
                        <div class="col-md-4">
                            <a href="dashboard.php" class="text-decoration-none text-black mt-2"><h1>Resto PPKD Jakarta Pusat</h1></a>
                        </div>
                        <div class="col-md-4">
                            <a href="logout.php" class="btn btn-danger btn-lg mb-2">Logout</a>
                        </div>
                        <div class="col-md-4">
                            <a href="profil.php" class="btn btn-primary btn-lg mb-2">Profil</a>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach($btn as $button) {
                            echo $button->link_navigasi();
                         } ?>
                    </div>
                </div>
            </div>
        </div>
</header>