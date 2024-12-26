<?php
include "../connect.php";
if (!isset($_GET['idprod'])) {
  header('Location: index.php');
}
$idprod = $_GET['idprod'];
$query = "SELECT * FROM ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori) WHERE ID_produk = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idprod);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$price = $row['ID_discount'] > 0 ? $row['discountprice'] : $row['harga'];

$ratingQ = "SELECT * FROM ((rating NATURAL JOIN userdata) NATURAL JOIN produk) WHERE ID_produk = $idprod";
$stmtRQ = $conn->query($ratingQ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Detail</title>
  <link rel="stylesheet" href="./css/style3.css">
  <link rel="icon" href="./photo/ciG.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .bg-beige {
      background-color: #f5ecd4;
    }

    .heart-transition {
      transition: transform 0.2s ease, color 0.2s ease;
    }

    .heart-transition:active {
      transform: scale(1.2);
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }
  </style>
</head>

<body class="font-sans bg-yellow-50">
  <!-- Navbar -->
  <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
    <a href="index.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

    <!-- apalah -->
    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
      <form action="" class="flex items-center w-full">
        <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
          echo $_GET['search'];
        } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
        <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20" alt="Search"></button>
      </form>
      <?php if (isset($_GET['search'])) {
        $searched = urlencode($_GET['search']);
        header("Location: search1.php?search={$searched}");
        exit();
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center space-x-4">
      <a href="login.php" class="font-semibold text-gray-700">LOGIN</a>
      <span class="text-gray-400">|</span>
      <a href="register.php" class="font-semibold text-gray-700">SIGN UP</a>
    </div>
  </header>

  <!-- Product Detail Section -->
  <main class="px-6 py-10">
    <div class="max-w-5xl mx-auto flex flex-col lg:flex-row gap-10 bg-white p-6 rounded-lg shadow-lg">
      <!-- Product Image -->
      <div class="lg:w-1/3">
        <img src="./products/<?php echo $row['foto']; ?>" alt="Jacket" class="rounded-lg shadow-md" />
      </div>

      <!-- Product Info -->
      <div class="lg:w-2/3 flex flex-col justify-between">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold text-gray-800"><?php echo $row['nama']; ?></h1>
          <button id="heartButton" onclick="window.location.href = 'login.php';"
            class="ml-4 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 heart-transition">
            <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318C5.084 5.553 6.048 5 7.05 5c1.003 0 1.967.553 2.733 1.318L12 8.536l2.217-2.218C15.953 5.553 16.917 5 17.919 5c1.003 0 1.967.553 2.733 1.318 1.466 1.467 1.466 3.843 0 5.31L12 21l-8.652-8.672c-1.466-1.467-1.466-3.843 0-5.31z" />
            </svg>
          </button>
        </div>

        <div>
          <p class="text-lg font-semibold text-green-600 mt-2">Rp.
            <?php echo number_format($price, 0, ',', '.'); ?>
          </p>
          <p class="mt-4 text-gray-700 leading-relaxed">
            <?php echo $row['deskripsi']; ?>
          </p>
          <hr class="my-6 border-gray-300">
        </div>

        <!-- Quantity and Cart -->
        <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg shadow-sm">
          <h2 class="text-lg font-semibold text-gray-800">Atur jumlah dan catatan</h2>
          <div class="flex items-center justify-between mt-4">
            <!-- Quantity Controls -->
            <div class="flex items-center space-x-4">
              <button onclick="updateQuantity('decrease')"
                class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300">
                <span class="text-xl font-semibold">-</span>
              </button>
              <input type="number" id="quantity" value="1" min="1" max="<?php echo $row['stok']; ?>"
                class="w-16 text-center border border-gray-300 rounded-md no-arrows" oninput="inputQuantity()" />
              <button onclick="updateQuantity('increase')"
                class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300">
                <span class="text-xl font-semibold">+</span>
              </button>
            </div>
            <span class="text-gray-500 text-sm">Stok total: <?php echo $row['stok']; ?></span>
          </div>
          <!-- Subtotal -->
          <div class="flex justify-between items-center mt-4">
            <span class="text-gray-600">Subtotal</span>
            <span id="subtotal" class="text-green-600 font-semibold">Rp. 0,00</span>
          </div>
          <button class="w-full mt-4 bg-green-600 text-white py-2 px-4 rounded-full hover:bg-green-700"
            onclick="window.location.href= 'login.php';">
            Tambahkan ke Keranjang
          </button>
        </div>
      </div>
    </div>
  </main>

  <!-- Review Section -->
  <section class="px-6 py-10">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Review Product</h2>

      <?php while ($rowRQ = $stmtRQ->fetch_assoc()) { ?>
        <div class="flex items-start mb-6">
          <img src="./photouser/<?php echo $rowRQ['fotouser']; ?>" alt="Herbud" class="w-12 h-12 rounded-full mr-2">
          <div class="ml-4 flex-grow">
            <h3 class="font-semibold text-gray-800"><?php echo $rowRQ['Username']; ?></h3>
            <p class="text-sm text-gray-500"><?php echo $rowRQ['nama']; ?></p>
            <p class="mt-2 text-gray-700"><?php echo $rowRQ['komentar']; ?></p>
          </div>
          <div class="flex items-center">
            <i class="fa fa-star" style="color: gold; margin-right: 5px;"></i>
            <span class="ml-1 text-gray-800"><?php echo $rowRQ['rate']; ?></span>
          </div>
        </div>
        <hr class="border-gray-300 mb-6">
      <?php } ?>
    </div>
  </section>

  <!-- JavaScript -->
  <script>
    const productPrice = <?php echo $price; ?>;
    const stock = <?php echo $row['stok']; ?>;
    let quantity = 1;

    function initializeSubtotal() {
      const subtotalElement = document.getElementById('subtotal');
      subtotalElement.textContent = `Rp. ${(quantity * productPrice).toLocaleString('id-ID')},00`;
    }

    function updateQuantity(action) {
      const quantityElement = document.getElementById('quantity');
      const subtotalElement = document.getElementById('subtotal');

      if (action === 'increase' && quantity < stock) {
        quantity++;
      } else if (action === 'decrease' && quantity > 1) {
        quantity--;
      }

      quantityElement.value = quantity;
      subtotalElement.textContent = `Rp. ${(quantity * productPrice).toLocaleString('id-ID')},00`;
    }

    function inputQuantity() {
      const quantityElement = document.getElementById('quantity');
      const subtotalElement = document.getElementById('subtotal');
      const inputValue = parseInt(quantityElement.value, 10);

      if (isNaN(inputValue) || inputValue < 1) {
        quantity = 1;
      } else if (inputValue > stock) {
        quantity = stock;
      } else {
        quantity = inputValue;
      }

      quantityElement.value = quantity;
      subtotalElement.textContent = `Rp. ${(quantity * productPrice).toLocaleString('id-ID')},00`;
    }

    document.addEventListener('DOMContentLoaded', initializeSubtotal);

  </script>
</body>

</html>