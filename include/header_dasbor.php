<header>
        <div class="container-fluid">
            <div class="row p-2 bg-warning d-flex flex-wrap justify-content-center align-self-lg-center fixed-top">
                <div class="col-lg-12 text-center">
                    <a href="dashboard.php" class="text-decoration-none text-black"><h1>Resto PPKD Jakarta Pusat</h1></a>
                    <div class="row">
                        <?php foreach($btn as $button) {
                            echo $button->link_navigasi();
                         } ?>
                    </div>
                </div>
            </div>
        </div>
</header>