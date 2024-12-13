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
    <div class="flex flex-col max-w-5xl gap-10 p-6 mx-auto bg-white rounded-lg shadow-lg lg:flex-row">
      <!-- Product Image -->
      <div class="relative lg:w-1/3">
        <img
          src="./products/<?php echo $row['foto']; ?>"
          alt="Jacket"
          class="rounded-lg shadow-md" />
        <!-- Wishlist Heart Icon -->

      <!-- Product Info -->
      <div class="flex flex-col justify-between lg:w-2/3">
        <div>
          <h1 class="text-2xl font-bold text-gray-800"><?php echo $row['nama']; ?></h1>
          <p class="mt-2 text-lg font-semibold text-green-600">Rp. <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
          <p class="mt-4 leading-relaxed text-gray-700">
            <?php echo $row['deskripsi']; ?>
          </p>
          <hr class="my-6 border-gray-300">

          
        </div>

        <!-- Quantity and Cart -->
        <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
          <h2 class="text-lg font-semibold text-gray-800">Atur jumlah dan catatan</h2>
          <div class="flex items-center justify-between mt-4">
            <!-- Quantity Controls -->
            <div class="flex items-center space-x-4">
              <button
                onclick="updateQuantity('decrease')"
                class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">
                <span class="text-xl font-semibold">-</span>
              </button>
              <span id="quantity" class="text-lg text-gray-800">1</span>
              <button
                onclick="updateQuantity('increase')"
                class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">
                <span class="text-xl font-semibold">+</span>
              </button>
            </div>
            <span class="text-sm text-gray-500">Stok total: <?php echo $row['stok']; ?></span>
          </div>
          <!-- Subtotal -->
          <div class="flex items-center justify-between mt-4">
            <span class="text-gray-600">Subtotal</span>
            <span id="subtotal" class="font-semibold text-green-600">Rp. 0,00</span>
          </div>
          <button class="w-full px-4 py-2 mt-4 text-white bg-green-600 rounded-full hover:bg-green-700" onclick="addtocart()">
            Tambahkan ke Keranjang
          </button>
        </div>
      </div>
    </div>
  </main>

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
    
    function checkwl() {
      const hearticon = document.getElementById('heartIcon');
      hearticon.classList.toggle('text-red-600');
      hearticon.classList.toggle('fill-current');
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