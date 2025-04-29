function konfirmasi() {
    const konf = window.confirm("Apakah Anda seorang Kasir?");
    if (konf) {
        document.location = "pos/index";
    } else {
        document.location = "";
    }
}