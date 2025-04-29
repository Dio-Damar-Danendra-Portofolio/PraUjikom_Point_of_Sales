<?php 
    class NavLink{
        public $file;
        public $target;
        public $text;
        public $title;
        public $onclick;


        public function __construct($file, $target, $onclick, $title, $text) {
            $this->file = $file;
            $this->target = $target;
            $this->text = $text;
            $this->title = $title;
            $this->onclick = $onclick;

        }

        public function link_navigasi() {
            return "<div class=\"col-md-2\">
                        <a href=\"{$this->file}\" class=\"btn btn-dark\" target=\"{$this->target}\" onclick=\"{$this->onclick}\" title=\"{$this->title}\">{$this->text}</a>
                    </div>";
        }
    }

    $home = new NavLink("dashboard.php", "_self", "", "Dasbor", "<i class=\"bi bi-house-fill\"></i>");
    $products = new NavLink("daftar_produk.php", "_self", "", "Daftar Produk", "<i class=\"bi bi-cup-fill\"></i>");
    $categories = new NavLink("daftar_kategori.php", "_self", "", "Daftar Kategori Produk", "<i class=\"bi bi-collection-fill\"></i>");
    $users = new NavLink("daftar_pengguna.php", "_self", "","Daftar Pengguna", "<i class=\"bi bi-person-fill\"></i>");
    $pos = new NavLink("pos", "_blank", "return confirm('Apakah Anda Seorang Kasir?');", "POS (Point Of Sales)", "<i class=\"bi bi-calculator\"></i>");
    $report = new NavLink("laporan.php", "_self", "", "Laporan Transaksi", "<i class=\"bi bi-file-text-fill\"></i>");


    $btn = array($home, $products, $categories, $users, $pos, $report);

?>
