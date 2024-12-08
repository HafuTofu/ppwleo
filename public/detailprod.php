<?php
 include '../connect.php';
 
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
        <a href="dashboard.html"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>
        
    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
        <form action="" class="flex items-center w-full">
            <input type="text" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
            <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20" alt="Search"></button>
        </form>
    </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="#"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photo/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">            
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="#profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="#settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Settings</a>
                    <a href="#wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="#Help&Support" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Help & Support</a>
                    <a href="#Logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
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
          src="./photo/JACKET.png"
          alt="Jacket"
          class="rounded-lg shadow-md"
        />
        <!-- Wishlist Heart Icon -->
        <button
          id="heartButton"
          onclick="toggleHeart()"
          class="absolute p-2 bg-white rounded-full shadow-md top-3 right-6 hover:bg-gray-100 heart-transition"
        >
          <svg
            id="heartIcon"
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 text-gray-600"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4.318 6.318C5.084 5.553 6.048 5 7.05 5c1.003 0 1.967.553 2.733 1.318L12 8.536l2.217-2.218C15.953 5.553 16.917 5 17.919 5c1.003 0 1.967.553 2.733 1.318 1.466 1.467 1.466 3.843 0 5.31L12 21l-8.652-8.672c-1.466-1.467-1.466-3.843 0-5.31z"
            />
          </svg>
        </button>
      </div>

      <!-- Product Info -->
      <div class="flex flex-col justify-between lg:w-2/3">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">T1 Worlds Jacket 2024</h1>
          <p class="mt-2 text-lg font-semibold text-green-600">Rp. 1.700.000,00</p>
          <p class="mt-4 leading-relaxed text-gray-700">
            The T1 White Jacket White is not simply a form of clothing that one puts on; it is a proclamation of passion.
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
                class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300"
              >
                <span class="text-xl font-semibold">-</span>
              </button>
              <span id="quantity" class="text-lg text-gray-800">1</span>
              <button
                onclick="updateQuantity('increase')"
                class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300"
              >
                <span class="text-xl font-semibold">+</span>
              </button>
            </div>
            <span class="text-sm text-gray-500">Stok total: 10</span>
          </div>
          <!-- Subtotal -->
          <div class="flex items-center justify-between mt-4">
            <span class="text-gray-600">Subtotal</span>
            <span id="subtotal" class="font-semibold text-green-600">Rp. 0,00</span>
          </div>
          <button class="w-full px-4 py-2 mt-4 text-white bg-green-600 rounded-full hover:bg-green-700">
            Tambahkan ke Keranjang
          </button>
        </div>
      </div>
    </div>
  </main>

  <!-- JavaScript -->
  <script>
    const productPrice = 1700000; // Product price in IDR
    const stock = 10; // Total stock available
    let quantity = 1; // Initial quantity

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
    }

    // Ensure the subtotal is correctly initialized on page load
    document.addEventListener('DOMContentLoaded', initializeSubtotal);
  </script>

  <!-- Dropdown Menu Function -->
  <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileIcon = document.getElementById('profileIcon');
            const dropdownMenu = document.getElementById('dropdownMenu');

            // Toggle dropdown visibility when mouse enters the profile icon
            profileIcon.addEventListener('mouseenter', function () {
                dropdownMenu.classList.remove('hidden'); // Show dropdown
            });

            // Hide dropdown when mouse leaves the profile icon or the dropdown menu
            profileIcon.addEventListener('mouseleave', function () {
                setTimeout(() => {
                    if (!dropdownMenu.matches(':hover')) {
                        dropdownMenu.classList.add('hidden'); // Hide dropdown
                    }
                }, 100); // Small delay to allow mouse to hover over dropdown menu
            });

            // Hide dropdown when mouse leaves the dropdown menu
            dropdownMenu.addEventListener('mouseleave', function () {
                dropdownMenu.classList.add('hidden'); // Hide dropdown
            });
        });
    </script>
</body>
</html>