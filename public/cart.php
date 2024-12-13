<?php
require './sess.php';

if ($_SESSION['login'] === 'false') {
  header('Location: login.php');
} else if ($_SESSION['login'] === 'trueadmin') {
  header('Location: admin.php');
}

$iduser = $_SESSION['id'];
$query = 'SELECT * FROM cart NATURAL JOIN produk WHERE ID_user = ?';

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $iduser);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout Page</title>
  <link rel="icon" href="./photo/ciG.png">
  <link rel="stylesheet" href="./css/style5.css">
  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-yellow-50 font-sans">
  <!-- Navbar -->
  <div class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="dashboard.html">
      <img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
    </a>

    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
      <form action="" class="flex items-center w-full">
        <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
          echo $_GET['search'];
        } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
        <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20" alt="Search"></button>
      </form>
      <?php if (isset($_GET['search'])) {
        $filtervalues = $_GET['search'];
        $query = 'SELECT * FROM cart NATURAL JOIN produk WHERE (ID_user = ?) AND (CONCAT(nama, nama_kategori, deskripsi) LIKE ' % $filtervalues % ')';
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $iduser);
        $stmt->execute();
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center space-x-6 mr-6">
      <a href="cart.html">
        <img src="./photo/cart.png" class="w-12 cursor-pointer">
      </a>
      <div class="relative">
        <img src="./photo/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
        <!-- Dropdown menu -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
          <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
          <a href="wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
          <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <main class="max-w-5xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Items in Cart</h1>
    <section class="flex gap-6">
      <div class="w-2/3">
        <!-- All Items Checkbox -->
        <div class="bg-white shadow-md rounded-md p-4 mb-4">
          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" id="selectAll" class="mr-2">
              <span class="font-semibold">All Items</span>
            </label>
            <button class="text-gray-500">Delete..</button>
          </div>
        </div>


        <?php
        $total = 0;
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($items as $product => $value) {
          $sr = $product + 1;
          $total += $value['harga'];
          echo "
          <div class='bg-white shadow-md rounded-md p-4 flex items-center gap-4 mb-4'>
            <input type='checkbox' class='itemCheckbox self-start'>
            <img src='./products/$value[foto]' alt='Jacket' class='w-28 h-28 rounded-md object-cover'>
            <div class='flex-1'>
              <h2 class='font-semibold'> $value[nama]</h2>
              <div class='flex items-center mt-2 gap-4 py-4'>
                <button class='bg-gray-200 rounded-full px-2 quantity-button decrease onclick='updateSubtotal();''>-</button>
                <span class='quantity'>1</span>
                <button class='bg-gray-200 rounded-full px-2 quantity-button increase onclick='updateSubtotal();''>+</button>
              </div>
            </div>
            <div class='text-right flex items-center gap-4'>
              <button>
                <i class='fas fa-trash text-gray-600'></i>
              </button>
              <button id='loveButton1'>
                <i id='heartIcon1' class='far fa-heart text-red-600'></i>
              </button>
              <p class='font-semibold price'>Rp. " . number_format($value['harga'], 0, ',', '.') . "<input type='hidden' class='iprice' id='iprice' value='$value[harga]'></p>
            </div>
          </div>";
        } ?>
      </div>

      <!-- Summary -->
      <div class="w-1/3">
        <div class="bg-white shadow-md rounded-md p-4">
          <h2 class="text-lg font-bold">Summary</h2>
          <div class="flex justify-between items-center mt-4">
            <span>Total</span>
            <span id="totalPrice">Rp. 0</span>
          </div>
          <a href="#checkout">
            <button class="bg-red-600 text-white w-full py-2 mt-4 rounded-md shadow-md hover:bg-red-700">
              Checkout
            </button>
          </a>
        </div>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const decreaseButtons = document.querySelectorAll(".quantity-button.decrease"); // Select all decrease buttons
      const increaseButtons = document.querySelectorAll(".quantity-button.increase"); // Select all increase buttons
      const itemQuantities = document.querySelectorAll(".quantity"); // Quantity elements
      const itemPrices = document.querySelectorAll(".iprice"); // Price elements
      const subtotals = document.querySelectorAll(".price"); // Subtotal elements
      const checkboxes = document.querySelectorAll(".itemCheckbox"); // Item checkboxes
      const totalPriceElement = document.getElementById("totalPrice"); // Total price element
      let totalPrice = 0;

      // Function to update subtotal for an item
      function updateSubtotal(index) {
        const quantity = parseInt(itemQuantities[index].innerText); // Get current quantity
        const price = parseInt(itemPrices[index].value); // Get item price
        const subtotal = quantity * price; // Calculate subtotal
        subtotals[index].innerText = `Rp. ${subtotal.toLocaleString("id-ID")}`; // Update subtotal in the DOM
        updateTotalPrice(); // Update the total price
      }

      // Function to update the total price for selected items only
      function updateTotalPrice() {
        totalPrice = 0;
        checkboxes.forEach((checkbox, index) => {
          if (checkbox.checked) {
            const subtotal = parseInt(subtotals[index].innerText.replace(/[^\d]/g, "")) || 0; // Extract number from text
            totalPrice += subtotal;
          }
        });
        totalPriceElement.innerText = `Rp. ${totalPrice.toLocaleString("id-ID")}`;
      }

      // Add event listeners to decrease buttons
      decreaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
          let quantity = parseInt(itemQuantities[index].innerText);
          if (quantity > 1) {
            quantity -= 1;
            itemQuantities[index].innerText = quantity;
            updateSubtotal(index);
          }
        });
      });

      // Add event listeners to increase buttons
      increaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
          let quantity = parseInt(itemQuantities[index].innerText);
          quantity += 1;
          itemQuantities[index].innerText = quantity;
          updateSubtotal(index);
        });
      });

      // Add event listeners to checkboxes to recalculate total price
      checkboxes.forEach((checkbox, index) => {
        checkbox.addEventListener("change", function () {
          updateTotalPrice(); // Update the total price when a checkbox is clicked
        });
      });

      // Initialize the total price on page load
      updateTotalPrice();
    });


  </script>

  <script>
    // Love Button Functionality
    document.addEventListener("DOMContentLoaded", function () {
      const loveButtons = document.querySelectorAll("[id^='loveButton']");
      loveButtons.forEach((button, index) => {
        const heartIcon = document.getElementById(`heartIcon${index + 1}`);
        button.addEventListener("click", function () {
          heartIcon.classList.toggle("fas");
          heartIcon.classList.toggle("far");
        });
      });
    });

    // Profile Dropdown Menu
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

    // Select All Checkbox Functionality
    document.addEventListener("DOMContentLoaded", function () {
      const selectAllCheckbox = document.getElementById("selectAll");
      const itemCheckboxes = document.querySelectorAll(".checkbox");

      // Toggle all item checkboxes when "All Items" is clicked
      selectAllCheckbox.addEventListener("change", function () {
        const isChecked = selectAllCheckbox.checked;
        itemCheckboxes.forEach((checkbox) => {
          checkbox.checked = isChecked;
        });
        updateTotalPrice();
      });

      // Update "All Items" checkbox state based on individual item checkboxes
      itemCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
          const allChecked = Array.from(itemCheckboxes).every((cb) => cb.checked);
          selectAllCheckbox.checked = allChecked;
        });
        updateTotalPrice();
      });

    });
  </script>
</body>

</html>