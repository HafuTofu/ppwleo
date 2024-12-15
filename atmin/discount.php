on progresss

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discounted Products</title>
  <link rel="icon" href="./photo/ciG.png">
  <link rel="stylesheet" href="../public/css/style6.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-yellow-50 font-sans">
  <!-- Navbar -->
  <div class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="dashboard.php">
      <img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
    </a>

    <!-- Search Bar -->
    <div class="relative bg-gray-100 p-2 rounded-full flex items-center w-3/4 max-w-xl mx-auto">
      <form action="" class="flex items-center w-full">
        <input type="text" placeholder="Search" class="bg-transparent outline-none w-full text-center text-lg">
        <button type="submit" class="p-2">
          <img src="./photo/search.png" width="20" height="20" alt="Search">
        </button>
      </form>
    </div>

    <!-- User and Dropdown Menu -->
    <div class="relative">
      <img src="./photo/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
        <a href="#profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
        <a href="tambahproduk.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Added Product</a>
        <a href="discount.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount Product</a>
        <a href="#Logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </div>

  <!-- Title and Action Buttons (Add Discount and Edit) -->
  <div class="flex justify-between items-center mx-16 mt-8 mb-4">
    <!-- Title -->
    <h1 class="text-2xl font-bold text-center">Discounted Products</h1>

    <!-- Action Buttons -->
    <div class="flex space-x-4">
      <button class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 flex items-center">
        <i class="fas fa-plus mr-2"></i> Add Discount
      </button>
    </div>
  </div>

  <!-- Content Wrapper with Larger Grid Margin -->
  <div class="mx-16">
    <!-- Headers -->
    <div class="grid grid-cols-5 gap-4 text-center font-bold text-gray-700 mb-4">
      <div class="bg-gray-100 py-3 px-6 rounded-tl-lg rounded-bl-lg shadow-md">Product Info</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Discount Price</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Discount</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Stock</div>
      <div class="bg-gray-100 py-3 px-6 rounded-tr-lg rounded-br-lg shadow-md">Action</div>
    </div>

    <!-- Product List -->
    <div class="grid grid-cols-5 gap-4">
      <!-- Product Card 1 -->
      <div class="flex items-center bg-white p-4 rounded-lg shadow-md">
        <img src="./photo/JACKET.png" alt="Product Image" class="w-16 h-16 rounded-lg mr-4">
        <p class="font-semibold">T1 Worlds Jacket 2024</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-red-600 text-xl font-bold">Rp. 510.000</p>
        <p class="line-through text-gray-500">Rp. 1.700.000</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-red-600 text-xl font-bold">70%</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-lg font-semibold">10</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <button class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600">
          <i class="fas fa-edit mr-2"></i> Edit
        </button>
        <button class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 mt-2">
          <i class="fas fa-trash"></i> Delete
        </button>
      </div>

      <!-- Product Card 2 -->
      <div class="flex items-center bg-white p-4 rounded-lg shadow-md">
        <img src="./photo/superlight.jpeg" alt="Product Image" class="w-16 h-16 rounded-lg mr-4">
        <p class="font-semibold">Logitech Superlight 2</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-red-600 text-xl font-bold">Rp. 1.000.000</p>
        <p class="line-through text-gray-500">Rp. 2.000.000</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-red-600 text-xl font-bold">50%</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <p class="text-lg font-semibold">20</p>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <button class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600">
          <i class="fas fa-edit mr-2"></i> Edit
        </button>
        <button class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 mt-2">
          <i class="fas fa-trash"></i> Delete
        </button>
      </div>
    </div>
  </div>

  <!-- Dropdown Menu Script -->
  <script>
    // Profile Dropdown Menu
    document.addEventListener('DOMContentLoaded', function () {
      const profileIcon = document.getElementById('profileIcon');
      const dropdownMenu = document.getElementById('dropdownMenu');

      profileIcon.addEventListener('mouseenter', function () {
        dropdownMenu.classList.remove('hidden'); // Show dropdown
      });

      profileIcon.addEventListener('mouseleave', function () {
        setTimeout(() => {
          if (!dropdownMenu.matches(':hover')) {
            dropdownMenu.classList.add('hidden'); // Hide dropdown
          }
        }, 100);
      });

      dropdownMenu.addEventListener('mouseleave', function () {
        dropdownMenu.classList.add('hidden');
      });

      // Select/Deselect All Functionality
      const selectAllCheckbox = document.getElementById('selectAll');
      const productCheckboxes = document.querySelectorAll('.productCheckbox');

      selectAllCheckbox.addEventListener('change', function () {
        const isChecked = selectAllCheckbox.checked;
        productCheckboxes.forEach(checkbox => {
          checkbox.checked = isChecked;
        });
      });
    });
  </script>
</body>
</html>