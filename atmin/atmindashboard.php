<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="icon" href="../public/photo/ciG.png">
  <link rel="stylesheet" href="../public/css/style13.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-yellow-50 font-sans">
  <!-- Navbar -->
  <div class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="atmindashboard.php">
      <img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
    </a>

    <!-- Search Bar -->
    <div class="relative bg-gray-100 p-2 rounded-full flex items-center w-3/4 max-w-xl mx-auto">
      <form action="" class="flex items-center w-full">
        <input type="text" placeholder="Search" class="bg-transparent outline-none w-full text-center text-lg">
        <button type="submit" class="p-2">
          <img src="../public/photo/search.png" width="20" height="20" alt="Search">
        </button>
      </form>
    </div>

    <!-- User and Dropdown Menu -->
    <div class="relative">
      <img src="../public/photouser/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile"
        id="profileIcon">
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
        <a href="../atmin/admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
          Managing Page</a>
        <a href="../atmin/discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
          Managing Page</a>
        <a href="../atmin/orderadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
          Managing Page</a>
        <a href="../atmin/usercontroller.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User
          Managing Page</a>
        <a href="../atmin/admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
          Managing Page</a>
        <a href="../public/profilepage.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
        <a href="../public/dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
          User Dashboard</a>
        <a href="../public/wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
        <a href="../public/orderlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
          List</a>
        <a href="../public/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </div>

  <!-- Stats Section -->
  <p class="font-bold text-left mx-6 p-4 text-3xl">Dashboard</p>
  <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4 mx-6">
    <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
      <i class="fas fa-money-bill-wave text-3xl"></i>
      <div>
        <p class="text-sm">Total Revenue</p>
        <p class="text-xl font-bold">Rp. 17.500.000</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
      <i class="fas fa-shopping-cart text-3xl"></i>
      <div>
        <p class="text-sm">Total Orders</p>
        <p class="text-xl font-bold">47</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
      <i class="fas fa-users text-3xl"></i>
      <div>
        <p class="text-sm">Total Customers</p>
        <p class="text-xl font-bold">12</p>
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
      <i class="fa-solid fa-box text-3xl"></i>
      <div>
        <p class="text-sm">Total Products</p>
        <p class="text-xl font-bold">12</p>
      </div>
    </div>
  </div>

  <!-- Recent Orders and Top Selling Products -->
  <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mx-6">
    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
      <table class="w-full table-auto border-b">
        <thead>
          <tr class="border-b">
            <th class="py-2">Order ID</th>
            <th class="py-2">Customer</th>
            <th class="py-2">Amount</th>
            <th class="py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b">
            <td class="py-2 text-center">#1</td>
            <td class="py-2 text-center">Pham Hanni</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-green-200 text-green-700 px-3 py-1 rounded-full">Completed</span></td>
          </tr>
          <tr class="border-b">
            <td class="py-2 text-center">#2</td>
            <td class="py-2 text-center">Heru Budi</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-orange-200 text-orange-700 px-3 py-1 rounded-full">Pending</span></td>
          </tr>
          <tr class="border-b">
            <td class="py-2 text-center">#3</td>
            <td class="py-2 text-center">Pham Minji</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-blue-200 text-blue-700 px-3 py-1 rounded-full">Processing</span></td>
          </tr>
          <tr class="border-b">
            <td class="py-2 text-center">#4</td>
            <td class="py-2 text-center">Pham Uri</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-orange-200 text-orange-700 px-3 py-1 rounded-full">Pending</span></td>
          </tr>
          <tr class="border-b">
            <td class="py-2 text-center">#5</td>
            <td class="py-2 text-center">Pham Haerin</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-green-200 text-green-700 px-3 py-1 rounded-full">Completed</span></td>
          </tr>
          <tr class="border-b">
            <td class="py-2 text-center">#6</td>
            <td class="py-2 text-center">Ban Heesoo</td>
            <td class="py-2 text-center">Rp. 1.700.000</td>
            <td class="py-2 text-center"><span
                class="bg-green-200 text-green-700 px-3 py-1 rounded-full">Completed</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold mb-4">Top Selling Products</h2>
      <div class="space-y-4">
        <div class="flex items-center space-x-4 border-b pb-4">
          <img src="./photo/hoodie.jpg" alt="Hoodie" class="w-16 h-16 rounded">
          <div>
            <p class="font-bold">Mclaren F1 Hoodie</p>
            <p>Sold: 10 Units</p>
            <p class="font-bold">Rp. 27.000.000</p>
          </div>
        </div>
        <div class="flex items-center space-x-4 border-b pb-4">
          <img src="./photo/JACKET.png" alt="Jacket" class="w-16 h-16 rounded">
          <div>
            <p class="font-bold">T1 Worlds Jacket 2024</p>
            <p>Sold: 10 Units</p>
            <p class="font-bold">Rp. 17.000.000</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <img src="./photo/superlight.jpeg" alt="Mouse" class="w-16 h-16 rounded">
          <div>
            <p class="font-bold">Logitech Superlight 2</p>
            <p>Sold: 10 Units</p>
            <p class="font-bold">Rp. 20.000.000</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const profileIcon = document.getElementById('profileIcon');
      const dropdownMenu = document.getElementById('dropdownMenu');

      profileIcon.addEventListener('mouseenter', function () {
        dropdownMenu.classList.remove('hidden');
      });

      profileIcon.addEventListener('mouseleave', function () {
        setTimeout(() => {
          if (!dropdownMenu.matches(':hover')) {
            dropdownMenu.classList.add('hidden');
          }
        }, 100);
      });

      dropdownMenu.addEventListener('mouseleave', function () {
        dropdownMenu.classList.add('hidden'); 
      });
    });
  </script>
</body>

</html>