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
    <a href="dashboard.php">
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
        $searchkey = urlencode($_GET['search']);
        header("Location: dashboard.php?{$searchkey}");
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center space-x-6 mr-6">
      <a href="cart.php">
        <img src="./photo/cart.png" class="w-12 cursor-pointer">
      </a>
      <div class="relative">
        <img src="./photo/<?php echo $_SESSION['fotouser'];?>" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
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
          $total += $value['harga'];$inwl = false;
          $idprod = $value['ID_produk'];
          $stmtwl = $conn->prepare("SELECT * FROM wishlist WHERE ID_user = ? AND ID_produk = ?");
          $stmtwl->bind_param("ii", $iduser, $idprod);
          $stmtwl->execute();
          $resultwl = $stmtwl->get_result();
          $inwl = $resultwl->num_rows > 0;
          echo "
          <div class='bg-white shadow-md rounded-md p-4 flex items-center gap-4 mb-4'>
            <input type='checkbox' class='itemCheckbox self-start'>
            <img src='./products/$value[foto]' alt='Jacket' class='w-28 h-28 rounded-md object-cover'>
            <div class='flex-1'>
              <h2 class='font-semibold'> $value[nama]</h2>
              <div class='flex items-center mt-2 gap-4 py-4'>
                <button class='bg-gray-200 rounded-full px-2 quantity-button decrease onclick='updateSubtotal();' data-action='decrease''>-</button>
                <span class='quantity'>1</span>
                <button class='bg-gray-200 rounded-full px-2 quantity-button increase onclick='updateSubtotal();' data-action='increase''>+</button>
              </div>
            </div>
            <div class='text-right flex items-center gap-4'>
              <button>
                <i class='fas fa-trash text-gray-600'></i>
              </button>
              <button id='loveButton$sr' data-product-id='$value[ID_produk]' onclick='toggleHeart($value[ID_produk])'>
  <i id='heartIcon$sr' class='". ($inwl ? 'fas' : 'far') ." fa-heart text-red-600  '></i>
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

    function toggleHeart(idprodd) {
      fetch('add_to_wishlist.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idprod: idprodd
          }),
        });
    }

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

    document.addEventListener("DOMContentLoaded", function () {
  const selectAllCheckbox = document.getElementById("selectAll");
  const itemCheckboxes = document.querySelectorAll(".itemCheckbox");
  const iprice = document.getElementsByClassName("iprice");
  const iquantity = document.getElementsByClassName("quantity");
  const subtotal = document.getElementById("totalPrice");

  // Function to calculate the subtotal for selected items
  function calculateSubtotal() {
    let total = 0;
    for (let i = 0; i < itemCheckboxes.length; i++) {
      if (itemCheckboxes[i].checked) {
        total += parseInt(iprice[i].value, 10) * parseInt(iquantity[i].innerText, 10);
      }
    }
    subtotal.innerText = `Rp. ${total.toLocaleString("id-ID")}`;
  }

  // Select All Checkbox functionality
  selectAllCheckbox.addEventListener("change", function () {
    const isChecked = selectAllCheckbox.checked;
    itemCheckboxes.forEach((checkbox) => {
      checkbox.checked = isChecked;
    });
    calculateSubtotal(); // Update subtotal
  });

  // Individual Item Checkbox functionality
  itemCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // If any checkbox is unchecked, uncheck "All Items"
      if (!checkbox.checked) {
        selectAllCheckbox.checked = false;
      }

      // If all checkboxes are checked, check "All Items"
      const allChecked = Array.from(itemCheckboxes).every((cb) => cb.checked);
      selectAllCheckbox.checked = allChecked;

      calculateSubtotal(); // Update subtotal
    });
  });

  // Increase and Decrease Quantity Buttons
  document.querySelectorAll(".quantity-button").forEach((button) => {
    button.addEventListener("click", function () {
      const action = this.getAttribute("data-action");
      const parentDiv = this.closest(".flex.items-center");
      const quantityElement = parentDiv.querySelector(".quantity");
      let quantity = parseInt(quantityElement.innerText, 10);

      if (action === "increase") {
        quantity += 1; // Increase by 1
      } else if (action === "decrease" && quantity > 1) {
        quantity -= 1; // Decrease by 1
      }

      quantityElement.innerText = quantity;
      calculateSubtotal(); // Update subtotal when quantity changes
    });
  });
});



  </script>
</body>

</html>