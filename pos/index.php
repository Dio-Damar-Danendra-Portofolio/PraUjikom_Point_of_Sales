<?php


require_once '../koneksi.php'; // Ubah path sesuai struktur proyek kamu

// Query ambil data produk aktif
$query = "SELECT * FROM products WHERE stock > 0;";

$result = mysqli_query($koneksi, $query);

$products = [];

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    // $products['id'] = $row['id'];
    // $products['name'] = $row['name'];
    // $products['price'] = $row['price'];
    // $products['image'] = $row['image'];
    // $products['option'] = null;
    $products[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'price' => (int)$row['price'],
      'image' => '../uploads/products/' . $row['image'],
      'option' => null
    ];
  }
}

// Tampilkan hasil dalam bentuk JSON
$product_json = json_encode($products);

$order_query = mysqli_query($koneksi, "SELECT * FROM orders");

$order_row = mysqli_fetch_all($order_query, MYSQLI_ASSOC);

if (isset($_POST['print'])) {
  $cart = $_SESSION['cart']; 
  $total_amount = 0;

  foreach ($cart as $item) {
      $total_amount += $item['price'] * $item['qty'];
  }

  // Insert ke tabel orders
  $order_code = 'ORD' . time(); // kode unik, contoh: ORD1714123456
  $date = date('Y-m-d');
  $status = 1; // contoh 1 = Selesai

  $query_order = "INSERT INTO orders (date, code, status, amount) 
  VALUES ('$date', '$order_code', '$status', '$total_amount')";

  mysqli_query($koneksi, $query_order);

  // Ambil ID order yang baru dibuat
  $order_id = mysqli_insert_id($koneksi);

  // Insert ke order_details
  foreach ($cart as $item) {
      $product_id = $item['id'];
      $category_id = $item['category_id'];
      $qty = $item['qty'];
      $subtotal = $item['price'] * $qty;
      $total = $subtotal; // misal total = subtotal (belum diskon)

      $query_detail = "INSERT INTO order_details (qty, subtotal, total, order_id, product_id, category_id) 
      VALUES ('$qty', '$subtotal', '$total', '$order_id', '$product_id', '$category_id')";

      mysqli_query($koneksi, $query_detail);
  }

  // Bersihkan keranjang
  unset($_SESSION['cart']);

  // Redirect ke halaman struk / sukses
  header("Location: cetak_struk.php?order_id=" . $order_id);
  exit;
} 
?>


<!DOCTYPE html>
<html lang="id-ID">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PPKD JakPus Resto POS</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="js/jquery-3.7.1.min.js.css">
  <link rel="stylesheet" href="js/script.js">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://unpkg.com/idb/build/iife/index-min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
  <script>
    function initApp() {
      const app = {
        db: null,
        time: null,
        firstTime: localStorage.getItem("first_time") === null,
        activeMenu: "pos",
        loadingSampleData: false,
        moneys: [2000, 5000, 10000, 20000, 50000, 100000],
        products: <?= $product_json; ?>,
        keyword: "",
        cart: [],
        cash: 0,
        change: 0,
        isShowModalReceipt: false,
        receiptNo: null,
        receiptDate: null,
        filteredProducts() {
          const rg = this.keyword ? new RegExp(this.keyword, "gi") : null;
          return this.products.filter((p) => !rg || p.name.match(rg));
        },
        addToCart(product) {
          const index = this.findCartIndex(product);
          if (index === -1) {
            this.cart.push({
              productId: product.id,
              image: product.image,
              name: product.name,
              price: product.price,
              option: product.option,
              qty: 1,
            });
          } else {
            this.cart[index].qty += 1;
          }
          this.beep();
          this.updateChange();
        },
        findCartIndex(product) {
          return this.cart.findIndex((p) => p.productId === product.id);
        },
        addQty(item, qty) {
          const index = this.cart.findIndex((i) => i.productId === item.productId);
          if (index === -1) {
            return;
          }
          const afterAdd = item.qty + qty;
          if (afterAdd === 0) {
            this.cart.splice(index, 1);
            this.clearSound();
          } else {
            this.cart[index].qty = afterAdd;
            this.beep();
          }
          this.updateChange();
        },
        addCash(amount) {
          this.cash = (this.cash || 0) + amount;
          this.updateChange();
          this.beep();
        },
        getItemsCount() {
          return this.cart.reduce((count, item) => count + item.qty, 0);
        },
        updateChange() {
          this.change = this.cash - this.getTotalPrice();
        },
        updateCash(value) {
          this.cash = parseFloat(value.replace(/[^0-9]+/g, ""));
          this.updateChange();
        },
        getTotalPrice() {
          return this.cart.reduce(
            (total, item) => total + item.qty * item.price,
            0
          );
        },
        submitable() {
          return this.change >= 0 && this.cart.length > 0;
        },
        submit() {
          const time = new Date();
          this.isShowModalReceipt = true;
          this.receiptNo = `PPKDPOS-KS-${Math.round(time.getTime() / 1000)}`;
          this.receiptDate = this.dateFormat(time);
        },
        closeModalReceipt() {
          this.isShowModalReceipt = false;
        },
        dateFormat(date) {
          const formatter = new Intl.DateTimeFormat("id", {
            dateStyle: "short",
            timeStyle: "short",
          });
          return formatter.format(date);
        },
        numberFormat(number) {
          return (number || "")
            .toString()
            .replace(/^0|\./g, "")
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        },
        priceFormat(number) {
          return number ? `Rp. ${this.numberFormat(number)}` : `Rp. 0`;
        },
        clear() {
          this.cash = 0;
          this.cart = [];
          this.receiptNo = null;
          this.receiptDate = null;
          this.updateChange();
          this.clearSound();
        },
        beep() {
          this.playSound("sound/beep-29.mp3");
        },
        clearSound() {
          this.playSound("sound/button-21.mp3");
        },
        playSound(src) {
          const sound = new Audio();
          sound.src = src;
          sound.play();
          sound.onended = () => delete sound;
        },
        printAndProceed() {
          const receiptContent = document.getElementById("receipt-content");
          const titleBefore = document.title;
          const printArea = document.getElementById("print-area");

          printArea.innerHTML = receiptContent.innerHTML;
          document.title = this.receiptNo;

          window.print();
          this.isShowModalReceipt = false;

          printArea.innerHTML = "";
          document.title = titleBefore;

          // TODO save sale data to database

          this.clear();
        },
      };

      return app;
    }

    function checkout() {
    let cart = JSON.parse(localStorage.getItem('cart'));
    let totalAmount = calculateTotal(cart);

    $.ajax({
        url: 'checkout.php',
        method: 'POST',
        data: {
            cart: cart,
            total_amount: totalAmount
        },
        success: function(response) {
            alert('Transaksi berhasil!');
            localStorage.removeItem('cart'); // Kosongkan keranjang setelah transaksi
            window.location.reload(); // Reload halaman
        },
        error: function() {
            alert('Terjadi kesalahan (error) saat memproses transaksi Anda.');
        }
    });
}
  </script>
</head>

<body class="bg-blue-gray-50" x-data="initApp()">
  <!-- noprint-area -->
  <div class="hide-print flex flex-row h-screen antialiased text-blue-gray-800">
    <!-- left sidebar -->
    <div class="flex flex-row w-auto flex-shrink-0 pl-4 pr-2 py-4">
      <div class="flex flex-col items-center py-4 flex-shrink-0 w-20 bg-cyan-500 rounded-3xl">
        <a href="#"
          class="flex items-center justify-center h-12 w-12 bg-cyan-50 text-cyan-700 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="123.3" height="123.233" viewBox="0 0 32.623 32.605">
            <path
              d="M15.612 0c-.36.003-.705.01-1.03.021C8.657.223 5.742 1.123 3.4 3.472.714 6.166-.145 9.758.019 17.607c.137 6.52.965 9.271 3.542 11.768 1.31 1.269 2.658 2 4.73 2.57.846.232 2.73.547 3.56.596.36.021 2.336.048 4.392.06 3.162.018 4.031-.016 5.63-.221 3.915-.504 6.43-1.778 8.234-4.173 1.806-2.396 2.514-5.731 2.516-11.846.001-4.407-.42-7.59-1.278-9.643-1.463-3.501-4.183-5.53-8.394-6.258-1.634-.283-4.823-.475-7.339-.46z"
              fill="#fff" />
            <path
              d="M16.202 13.758c-.056 0-.11 0-.16.003-.926.031-1.38.172-1.747.538-.42.421-.553.982-.528 2.208.022 1.018.151 1.447.553 1.837.205.198.415.313.739.402.132.036.426.085.556.093.056.003.365.007.686.009.494.003.63-.002.879-.035.611-.078 1.004-.277 1.286-.651.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.147-.072zM16.22 19.926c-.056 0-.11 0-.16.003-.925.031-1.38.172-1.746.539-.42.42-.554.981-.528 2.207.02 1.018.15 1.448.553 1.838.204.198.415.312.738.4.132.037.426.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.034.61-.08 1.003-.278 1.285-.652.282-.374.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.863-1.31-.977a7.91 7.91 0 00-1.146-.072zM22.468 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.205.198.415.313.739.401.132.037.426.086.556.094.056.003.364.007.685.009.494.003.63-.002.88-.035.611-.078 1.004-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.065-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072z"
              fill="#00dace" />
            <path
              d="M9.937 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.204.198.415.313.738.401.133.037.427.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.035.61-.078 1.003-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072zM16.202 7.59c-.056 0-.11 0-.16.002-.926.032-1.38.172-1.747.54-.42.42-.553.98-.528 2.206.022 1.019.151 1.448.553 1.838.205.198.415.312.739.401.132.037.426.086.556.093.056.003.365.007.686.01.494.002.63-.003.879-.035.611-.079 1.004-.278 1.286-.652.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.505-.228-.547-.653-.864-1.31-.978a7.91 7.91 0 00-1.147-.071z"
              fill="#00bcd4" />
            <g>
              <path
                d="M15.612 0c-.36.003-.705.01-1.03.021C8.657.223 5.742 1.123 3.4 3.472.714 6.166-.145 9.758.019 17.607c.137 6.52.965 9.271 3.542 11.768 1.31 1.269 2.658 2 4.73 2.57.846.232 2.73.547 3.56.596.36.021 2.336.048 4.392.06 3.162.018 4.031-.016 5.63-.221 3.915-.504 6.43-1.778 8.234-4.173 1.806-2.396 2.514-5.731 2.516-11.846.001-4.407-.42-7.59-1.278-9.643-1.463-3.501-4.183-5.53-8.394-6.258-1.634-.283-4.823-.475-7.339-.46z"
                fill="#fff" />
              <path
                d="M16.202 13.758c-.056 0-.11 0-.16.003-.926.031-1.38.172-1.747.538-.42.421-.553.982-.528 2.208.022 1.018.151 1.447.553 1.837.205.198.415.313.739.402.132.036.426.085.556.093.056.003.365.007.686.009.494.003.63-.002.879-.035.611-.078 1.004-.277 1.286-.651.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.147-.072zM16.22 19.926c-.056 0-.11 0-.16.003-.925.031-1.38.172-1.746.539-.42.42-.554.981-.528 2.207.02 1.018.15 1.448.553 1.838.204.198.415.312.738.4.132.037.426.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.034.61-.08 1.003-.278 1.285-.652.282-.374.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.863-1.31-.977a7.91 7.91 0 00-1.146-.072zM22.468 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.205.198.415.313.739.401.132.037.426.086.556.094.056.003.364.007.685.009.494.003.63-.002.88-.035.611-.078 1.004-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.065-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072z"
                fill="#00dace" />
              <path
                d="M9.937 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.204.198.415.313.738.401.133.037.427.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.035.61-.078 1.003-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072zM16.202 7.59c-.056 0-.11 0-.16.002-.926.032-1.38.172-1.747.54-.42.42-.553.98-.528 2.206.022 1.019.151 1.448.553 1.838.205.198.415.312.739.401.132.037.426.086.556.093.056.003.365.007.686.01.494.002.63-.003.879-.035.611-.079 1.004-.278 1.286-.652.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.505-.228-.547-.653-.864-1.31-.978a7.91 7.91 0 00-1.147-.071z"
                fill="#00bcd4" />
            </g>
          </svg>
        </a>
        <ul class="flex flex-col space-y-2 mt-12">
          <li>
            <a href="#" class="flex items-center">
              <span class="flex items-center justify-center h-12 w-12 rounded-2xl"
                x-bind:class="{
                                    'hover:bg-cyan-400 text-cyan-100': activeMenu === 'pos',
                                    'bg-cyan-300 shadow-lg text-white': activeMenu !== 'pos',
                                }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
              </span>
            </a>
          </li>
          <li>
            <a href="../dashboard.php" target="_blank"
               class="flex items-center">
              <span class="flex items-center justify-center text-cyan-100 hover:bg-cyan-400 h-12 w-12 rounded-2xl">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </span>
            </a>
          </li>
        </ul>
        <a href="https://github.com/emsifa/tailwind-pos" target="_blank"
          class="mt-auto flex items-center justify-center text-cyan-200 hover:text-cyan-100 h-10 w-10 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
              clip-rule="evenodd" />
          </svg>
        </a>
      </div>
    </div>

    <!-- page content -->
    <div class="flex-grow flex">
      <!-- store menu -->
      <div class="flex flex-col bg-blue-gray-50 h-full w-full py-4">
        <div class="flex px-2 flex-row relative">
          <div class="absolute left-5 top-3 px-2 py-2 rounded-full bg-cyan-500 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text"
            class="bg-white rounded-3xl shadow text-lg full w-full h-16 py-4 pl-16 transition-shadow focus:shadow-2xl focus:outline-none"
            placeholder="Cari menu ..." x-model="keyword" />
        </div>
        <div class="h-full overflow-hidden mt-4">
          <div class="h-full overflow-y-auto px-2">
            <div class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25"
              x-show="products.length === 0">
              <div class="w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <p class="text-xl">
                  PRODUK
                  <br />
                  TIDAK TERSEDIA
                </p>
              </div>
            </div>
            <div class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25"
              x-show="filteredProducts().length === 0 && keyword.length > 0">
              <div class="w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-xl">
                  HASIL TIDAK DITEMUKAN UNTUK KATA KUNCI
                  <br />
                  "<span x-text="keyword" class="font-semibold"></span>"
                </p>
              </div>
            </div>
            <div x-show="filteredProducts().length" class="grid grid-cols-4 gap-4 pb-3">
              <template x-for="product in filteredProducts()" :key="product.id">
                <div role="button"
                  class="select-none cursor-pointer transition-shadow overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg"
                  :title="product.name" x-on:click="addToCart(product)">
                  <img :src="product.image" :alt="product.name" class="w-24 h-24 object-contain mx-auto mb-2"/>
                  <div class="flex pb-3 px-3 text-sm -mt-3">
                    <p class="flex-grow truncate mr-1" x-text="product.name"></p>
                    <p class="nowrap font-semibold" x-text="priceFormat(product.price)"></p>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <!-- end of store menu -->

      <!-- right sidebar -->
      <div class="w-5/12 flex flex-col bg-blue-gray-50 h-full bg-white pr-4 pl-2 py-4">
        <div class="bg-white rounded-3xl flex flex-col h-full shadow">
          <!-- empty cart -->
          <div x-show="cart.length === 0"
            class="flex-1 w-full p-4 opacity-25 select-none flex flex-col flex-wrap content-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p>KERANJANG KOSONG</p>
          </div>

          <!-- cart items -->
          <div x-show="cart.length > 0" class="flex-1 flex flex-col overflow-auto">
            <div class="h-16 text-center flex justify-center">
              <div class="pl-8 text-left text-lg py-4 relative">
                <!-- cart icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline-block" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div x-show="getItemsCount() > 0"
                  class="text-center absolute bg-cyan-500 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3"
                  x-text="getItemsCount()"></div>
              </div>
              <div class="flex-grow px-8 text-right text-lg py-4 relative">
                <!-- trash button -->
                <button x-on:click="clear()"
                  class="text-blue-gray-300 hover:text-pink-500 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex-1 w-full px-4 overflow-auto">
              <template x-for="item in cart" :key="item.productId">
                <div
                  class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">
                  <img :src="item.image" alt=""
                    class="rounded-lg h-10 w-10 bg-white shadow mr-2" />
                  <div class="flex-grow">
                    <h5 class="text-sm" x-text="item.name"></h5>
                    <p class="text-xs block" x-text="priceFormat(item.price)"></p>
                  </div>
                  <div class="py-1">
                    <div class="w-28 grid grid-cols-3 gap-2 ml-2">
                      <button x-on:click="addQty(item, -1)"
                        class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block"
                          fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input x-model.number="item.qty" type="text"
                        class="bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm" />
                      <button x-on:click="addQty(item, 1)"
                        class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block"
                          fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
          <!-- end of cart items -->

          <!-- payment info -->
          <div class="select-none h-auto w-full text-center pt-3 pb-4 px-4">
            <div class="flex mb-3 text-lg font-semibold text-blue-gray-700">
              <div>TOTAL</div>
              <div class="text-right w-full" x-text="priceFormat(getTotalPrice())"></div>
            </div>
            <div class="mb-3 text-blue-gray-700 px-3 pt-2 pb-3 rounded-lg bg-blue-gray-50">
              <div class="flex text-lg font-semibold">
                <div class="flex-grow text-left">JUMLAH</div>
                <div class="flex text-right">
                  <div class="mr-2">Rp</div>
                  <input x-bind:value="numberFormat(cash)"
                    x-on:keyup="updateCash($event.target.value)" type="text"
                    class="w-28 text-right bg-white shadow rounded-lg focus:bg-white focus:shadow-lg px-2 focus:outline-none" />
                </div>
              </div>
              <hr class="my-2" />
              <div class="grid grid-cols-3 gap-2 mt-2">
                <template x-for="money in moneys">
                  <button x-on:click="addCash(money)"
                    class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">
                    +<span x-text="numberFormat(money)"></span>
                  </button>
                </template>
              </div>
            </div>
            <div x-show="change > 0"
              class="flex mb-3 text-lg font-semibold bg-cyan-50 text-blue-gray-700 rounded-lg py-2 px-3">
              <div class="text-cyan-800">SISA UANG</div>
              <div class="text-right flex-grow text-cyan-600" x-text="priceFormat(change)"></div>
            </div>
            <div x-show="change < 0"
              class="flex mb-3 text-lg font-semibold bg-pink-100 text-blue-gray-700 rounded-lg py-2 px-3">
              <div class="text-right flex-grow text-pink-600" x-text="priceFormat(change)"></div>
            </div>
            <div x-show="change == 0 && cart.length > 0"
              class="flex justify-center mb-3 text-lg font-semibold bg-cyan-50 text-cyan-700 rounded-lg py-2 px-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
            </div>
            <button class="text-white rounded-2xl text-lg w-full py-3 focus:outline-none"
              x-bind:class="{
                                'bg-cyan-500 hover:bg-cyan-600': submitable(),
                                'bg-blue-gray-200': !submitable()
                            }"
              :disabled="!submitable()" x-on:click="submit()">
              BAYAR
            </button>
          </div>
          <!-- end of payment info -->
        </div>
      </div>
      <!-- end of right sidebar -->
    </div>

    <!-- modal first time -->
    

    <!-- modal receipt -->
    <div x-show="isShowModalReceipt"
            class="fixed w-full h-screen left-0 top-0 z-10 flex flex-wrap justify-center content-center p-24">
            <div x-show="isShowModalReceipt" class="fixed glass w-full h-screen left-0 top-0 z-0"
                x-on:click="closeModalReceipt()" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"></div>
            <div x-show="isShowModalReceipt" class="w-96 rounded-3xl bg-white shadow-xl overflow-hidden z-10"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">
                <div id="receipt-content" class="text-left w-full text-sm p-6 overflow-auto">
                    <div class="text-center">
                        <img src="img/assets/Logo PPKD JakPus.jpg" alt="Tailwind POS"
                            class="mb-3 w-8 h-8 inline-block">
                        <h2 class="text-xl font-semibold">PPKD JakPus POS</h2>
                        <p>CABANG KARET TENGSIN</p>
                    </div>
                    <div class="flex mt-4 text-xs">
                        <div class="flex-grow">No: <span x-text="receiptNo"></span></div>
                        <div x-text="receiptDate"></div>
                    </div>
                    <hr class="my-2">
                    <div>
                        <table class="w-full text-xs">
                            <thead>
                                <tr>
                                    <th class="py-1 w-1/12 text-center">#</th>
                                    <th class="py-1 text-left">Item</th>
                                    <th class="py-1 w-2/12 text-center">Qty</th>
                                    <th class="py-1 w-3/12 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in cart" :key="item">
                                    <tr>
                                        <td class="py-2 text-center" x-text="index+1"></td>
                                        <td class="py-2 text-left">
                                            <span x-text="item.name"></span>
                                            <br />
                                            <small x-text="priceFormat(item.price)"></small>
                                        </td>
                                        <td class="py-2 text-center" x-text="item.qty"></td>
                                        <td class="py-2 text-right" x-text="priceFormat(item.qty * item.price)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <hr class="my-2">
                    <div>
                        <div class="flex font-semibold">
                            <div class="flex-grow">TOTAL</div>
                            <div x-text="priceFormat(getTotalPrice())"></div>
                        </div>
                        <div class="flex text-xs font-semibold">
                            <div class="flex-grow">UANG YANG DIBAYAR</div>
                            <div x-text="priceFormat(cash)"></div>
                        </div>
                        <hr class="my-2">
                        <div class="flex text-xs font-semibold">
                            <div class="flex-grow">KEMBALIAN</div>
                            <div x-text="priceFormat(change)"></div>
                        </div>
                    </div>
                </div>
                <div class="p-4 w-full" >
                <form action="api/insert-order.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                    <input type="hidden" name="cash" :value="cash">
                    <input type="hidden" name="change" :value="change">
                    <input type="hidden" name="total" :value="getTotalPrice()">
                    <button class="bg-cyan-500 text-white text-lg px-4 py-3 rounded-2xl w-full focus:outline-none"
                    x-on:click="printAndProceed()">
                    CETAK STRUK
                    </button>
                </form>
                </div>
            </div>
    </div>
  </div>
  <!-- end of noprint-area -->

  <div id="print-area" class="print-area"></div>
</body>

</html>