<?php
require './sess.php';
if (!isset($_GET['idprod'])) {
  header('Location: dashboard.php');
}
$idprod = $_GET['idprod'];
$query = "SELECT * FROM produk WHERE ID_produk = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idprod);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$inwl = false;
$iduser = $_SESSION['id'];
$stmtwl = $conn->prepare("SELECT * FROM wishlist WHERE ID_user = ? AND ID_produk = ?");
$stmtwl->bind_param("ii", $iduser, $idprod);
$stmtwl->execute();
$resultwl = $stmtwl->get_result();
$inwl = $resultwl->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Detail</title>
  <link rel="stylesheet" href="./css/style3.css">
  <link rel="icon" href="./photo/ciG.png">
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
  </style>
</head>

<body class="font-sans bg-yellow-50">
  <!-- Navbar -->
  <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
    <a href="dashboard.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
      <form action="" class="flex items-center w-full">
        <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                  echo $_GET['search'];
                                                } ?>"
          placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
        <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
            alt="Search"></button>
      </form>
      <?php if (isset($_GET['search'])) {
        $searched = urlencode($_GET['search']);
        header("Location: dashboard.php?search={$searched}");
        exit();
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center mr-6 space-x-6">
      <a href="cart.php"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
      <div class="relative">
        <img src="./photouser/<?php echo $_SESSION['fotouser']; ?>" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
        <!-- Dropdown menu -->
        <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
          <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
          <a href="wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
          <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
        </div>
      </div>
    </div>
  </div>

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
          <button id="heartButton" onclick="toggleHeart()"
            class="ml-4 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 heart-transition">
            <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 <?php echo $inwl ? 'text-red-600 fill-current' : 'text-gray-600' ?>" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318C5.084 5.553 6.048 5 7.05 5c1.003 0 1.967.553 2.733 1.318L12 8.536l2.217-2.218C15.953 5.553 16.917 5 17.919 5c1.003 0 1.967.553 2.733 1.318 1.466 1.467 1.466 3.843 0 5.31L12 21l-8.652-8.672c-1.466-1.467-1.466-3.843 0-5.31z" />
            </svg>
          </button>
        </div>

        <div>
          <p class="text-lg font-semibold text-green-600 mt-2">Rp.
            <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
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
              <span id="quantity" class="text-lg text-gray-800">1</span>
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
          <button class="w-full mt-4 bg-green-600 text-white py-2 px-4 rounded-full hover:bg-green-700" onclick="addtocart()">
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
      <!-- Review 1 -->
      <div class="flex items-start mb-6">
        <img src="./photo/Herbud.jpg" alt="Herbud" class="w-12 h-12 rounded-full mr-2">
        <div class="ml-4">
          <h3 class="font-semibold text-gray-800">Heru Budi</h3>
          <p class="text-sm text-gray-500">T1 Worlds Jacket 2024</p>
          <p class="mt-2 text-gray-700">Pesanan sudah sampai, sudah pasti original, rasanya seperti pemenang, rekomended.</p>
        </div>
      </div>
      <hr class="border-gray-300 mb-6">
      <!-- Review 2 -->
      <div class="flex items-start mb-6">
        <img src="./photo/Pham.jpg" alt="Pham" class="w-12 h-12 rounded-full mr-2">
        <div class="ml-4">
          <h3 class="font-semibold text-gray-800">Pham Hanni</h3>
          <p class="text-sm text-gray-500">T1 Worlds Jacket 2024</p>
          <p class="mt-2 text-gray-700">Pesanan sudah sampai, barang yang sangat bagus sesuai dengan gambar, rekomended.</p>
        </div>
      </div>
      <hr class="border-gray-300 mb-6">
      <!-- Review 3 -->
      <div class="flex items-start mb-6">
        <img src="./photo/foto1.png" alt="Demogorgon" class="w-12 h-12 rounded-full mr-2">
        <div class="ml-4">
          <h3 class="font-semibold text-gray-800">Demogorgon</h3>
          <p class="text-sm text-gray-500">T1 Worlds Jacket 2024</p>
          <p class="mt-2 text-gray-700">Pesanan sudah sampai, barang yang sangat bagus sesuai dengan gambar, rekomended.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- JavaScript -->
  <script>
    const productPrice = <?php echo $row['harga']; ?>; // Product price in IDR
    const stock = <?php echo $row['stok']; ?>; // Total stock available
    var hearttoggled = true;
    let quantity = 1; // Initial quantity

    function addtocart() {
      const finalqty = document.getElementById("quantity").textContent;

      // Send data to the server using AJAX
      fetch("add_to_cart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          idprod: "<?php echo $row['ID_produk']; ?>",
          iduser: "<?php echo $_SESSION['id']; ?>",
          qty: finalqty,
          harga: "<?php echo $row['harga']; ?>"
        }),
      });
    }


    // Initialize the subtotal on page load
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

      // Update quantity and subtotal
      quantityElement.textContent = quantity;
      subtotalElement.textContent = `Rp. ${(quantity * productPrice).toLocaleString('id-ID')},00`;
    }

    function toggleHeart() {
      const heartIcon = document.getElementById('heartIcon');
      heartIcon.classList.toggle('text-red-600');
      heartIcon.classList.toggle('fill-current');

      fetch('add_to_wishlist.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idprod: <?php echo $row['ID_produk']; ?>
          }),
        });
    }

    document.addEventListener('DOMContentLoaded', initializeSubtotal);
  </script>

  <!-- Dropdown Menu Function -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const profileIcon = document.getElementById('profileIcon');
      const dropdownMenu = document.getElementById('dropdownMenu');

      // Toggle dropdown visibility when mouse enters the profile icon
      profileIcon.addEventListener('mouseenter', function() {
        dropdownMenu.classList.remove('hidden'); // Show dropdown
      });

      // Hide dropdown when mouse leaves the profile icon or the dropdown menu
      profileIcon.addEventListener('mouseleave', function() {
        setTimeout(() => {
          if (!dropdownMenu.matches(':hover')) {
            dropdownMenu.classList.add('hidden'); // Hide dropdown
          }
        }, 100); // Small delay to allow mouse to hover over dropdown menu
      });

      // Hide dropdown when mouse leaves the dropdown menu
      dropdownMenu.addEventListener('mouseleave', function() {
        dropdownMenu.classList.add('hidden'); // Hide dropdown
      });
    });
  </script>
</body>

</html>