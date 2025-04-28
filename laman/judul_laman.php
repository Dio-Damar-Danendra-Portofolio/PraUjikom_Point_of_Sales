<?php
    $laman = basename($_SERVER['PHP_SELF']);

    $title = "Laman tidak ditemukan - Resto PPKD JakPus";

    switch ($laman) {
        case 'index.php':
            $title = "Selamat Datang di Resto PPKD Jakarta Pusat!";
            break;
            
        case 'login.php':
            $title = "Laman Login - Resto PPKD Jakarta Pusat";
            break;
        
        case 'register.php':
            $title = "Laman Daftar - Resto PPKD Jakarta Pusat";
            break;

        case 'dashboard.php':
            $title = "Beranda - Resto PPKD Jakarta Pusat";
            break;

        case 'daftar_produk.php':
            $title = "Daftar Produk - Resto PPKD Jakarta Pusat";
            break;
                
        case 'daftar_kategori.php':
            $title = "Daftar Kategori Produk - Resto PPKD Jakarta Pusat";
            break;
                
        case 'daftar_pengguna.php':
            $title = "Daftar Kategori Produk - Resto PPKD Jakarta Pusat";
            break;
                
        case 'tambah_sunting_produk.php':
            $title = isset($_GET['id-produk']) ? "Edit Data Produk - Resto PPKD Jakarta Pusat" : "Tambah Data Produk - Resto PPKD Jakarta Pusat";
            break;
                    
        case 'tambah_sunting_kategori.php':
            $title = isset($_GET['id-kategori']) ? "Edit Kategori Produk - Resto PPKD Jakarta Pusat" : "Tambah Kategori Produk - Resto PPKD Jakarta Pusat";
            break;
                    
        case 'tambah_sunting_pengguna.php':
            $title = isset($_GET['id-pengguna']) ? "Edit Data Pengguna - Resto PPKD Jakarta Pusat" : "Tambah Pengguna - Resto PPKD Jakarta Pusat";
            break;

        case 'profil.php':
            $title = "Profil Anda - Resto PPKD Jakarta Pusat";
            break;
        
        case 'laporan.php':
            $title = "Laporan Transaksi - Resto PPKD Jakarta Pusat";
            break;
            
        default:
            $title = ucfirst($laman);
            break;
    }
?>
    <title><?php echo $title; ?></title>