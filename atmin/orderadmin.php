<?php
require "../public/sess.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] === 'trueguess' || $_SESSION['login'] === 'false') {
  header('Location: ../public/login.php');
}

$iduser = $_SESSION['id'];
$queryst = "SELECT * FROM (transactions NATURAL JOIN transaction_details NATURAL JOIN cart NATURAL JOIN produk NATURAL JOIN userdata)";

$shippingest = ["ekonomi" => "+5 days", "regular" => "+3 days", "express" => "+2 days", "priority" => "+1 days"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order List</title>
  <link rel="icon" href="../public/photo/ciG.png" />
  <link rel="stylesheet" href="../public/css/style10.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    .status-filter button:hover {
      background-color: gray;
      color: white;
      cursor: pointer;
    }
  </style>
</head>

<body class="font-sans bg-yellow-50">
  <!-- Navbar -->
  <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200 z-50">
    <a href="./atmindashboard.php"><img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
      <form action="" class="flex items-center w-full">
        <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
          echo $_GET['search'];
        } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
        <button type="submit" class="p-2"><img src="../public/photo/search.png" width="20" height="20"
            alt="Search"></button>
      </form>
      <?php if (isset($_GET['search'])) {
        $filtervalues = $_GET['search'];
        $queryst = "SELECT * FROM (transactions NATURAL JOIN transaction_details NATURAL JOIN cart NATURAL JOIN produk NATURAL JOIN userdata NATURAL JOIN kategori) WHERE CONCAT(nama, nama_kategori, deskripsi, Username, address) LIKE '%$filtervalues%' ";
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center mr-6 space-x-6">
      <div class="relative">
        <img src="../public/photouser/<?php echo $_SESSION['fotouser']; ?>"
          class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
        <!-- Dropdown menu -->
        <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
        <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Admin
                    Dashboard</a>
                <a href="../atmin/admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
                    Managing Page</a>
                <a href="../atmin/admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
                    Managing Page</a>
                <a href="../atmin/discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
                    Managing Page</a>
                <a href="../atmin/usercontroller.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User
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
  </header>

  <!-- Main Content -->
  <div class="p-4">
    <!-- Order List Title -->
    <h2 class="text-2xl font-bold mb-2 text-center">Order List</h2>

    <!-- Status Filter -->
    <div class="flex flex-wrap justify-center space-x-2 mb-4 status-filter">
      <button class="px-4 py-2 bg-gray-200 rounded-full active" data-filter="All">All</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Confirmed">Confirmed</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Packing Process">Packing Process</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Delivering">Delivering</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Delivered">Delivered</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Done">Done</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Need Rate">Need Rate</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Canceled">Canceled</button>
    </div>

    <!-- Status Message -->
    <div id="statusMessage" class="status-message hidden font-bold text-center mb-4"></div>


    <?php $result = $conn->query($queryst);
    $rowst = $result->fetch_assoc();
    while ($rowst != null) {
      $startDate = new DateTime($rowst['timestamp']);
      $startDay = $startDate->format('d');
      $month = $startDate->format('F');
      $year = $startDate->format('Y');
      $endDate = $startDate;
      $endDate->modify($shippingest[$rowst['pengiriman']]);
      $endDay = $endDate->format('d');
      ?>
      <!-- Order Cards 1 -->
      <div class="space-y-6">
        <!-- Order Card -->
        <div class="bg-white shadow-md rounded-md p-4 flex items-center space-x-4 max-w-4xl mx-auto">
          <!-- Product Details -->
          <div class="flex items-center space-x-4 flex-1">
            <img src="../public/products/<?php echo $rowst['foto']; ?>" alt="Product"
              class="w-20 h-20 rounded-md object-cover">
            <div>
              <h3 class="text-lg font-bold"><?php echo $rowst['nama']; ?></h3>
              <p class="text-sm text-gray-600 mb-1">Qty: <?php echo $rowst['qty']; ?></p>
              <p class="text-sm">
                <span class="font-bold">Username</span><br>
                <?php echo $rowst['address']; ?>
              </p>
            </div>
          </div>

          <!-- Order Status -->
          <div class="w-1/4 flex flex-col items-center">
            <div class="flex items-center space-x-2">
              <i class="fa-solid fa-truck text-gray-600 text-xl"></i>
              <select class="p-2 border rounded-md text-sm w-40 text-center" name="status" disabled>
                <option value="Confirmed" <?php echo $rowst['order_status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed
                </option>
                <option value="Packing Process" <?php echo $rowst['order_status'] == 'Packing Process' ? 'selected' : ''; ?>>
                  Packing Process</option>
                <option value="Delivering" <?php echo $rowst['order_status'] == 'Delivering' ? 'selected' : ''; ?>>
                  Delivering
                </option>
                <option value="Delivered" <?php echo $rowst['order_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered
                </option>
                <option value="Need Rate" <?php echo $rowst['order_status'] == 'Need Rate' ? 'selected' : ''; ?>>Need Rate
                </option>
                <option value="Done" <?php echo $rowst['order_status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                <option value="Canceled" <?php echo $rowst['order_status'] == 'Canceled' ? 'selected' : ''; ?>>Canceled
                </option>
              </select>
            </div>
            <p class="text-red-500 font-semibold text-sm mt-2"><?php $eta = "ETA: $startDay - $endDay $month $year";
            echo $eta; ?></p>
            </p>
          </div>

          <!-- Total and Buttons -->
          <div class="text-right w-1/4">
            <p class="font-bold text-sm">Total</p>
            <p class="text-xl font-semibold text-gray-800">Rp.
              <?php echo number_format($rowst['total_harga'], 0, ',', '.'); ?>
            </p>
            <button class="bg-green-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-green-600">UPDATE</button>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-blue-600 hidden" data-idtrans=<?php echo $rowst['ID_transaksi']; ?>>SAVE</button>
          </div>
        </div>
        <?php $rowst = $result->fetch_assoc();
    } ?>

      <!-- JavaScript -->
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const profileIcon = document.getElementById('profileIcon');
          const dropdownMenu = document.getElementById('dropdownMenu');

          // Dropdown menu logic
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

          // Status Filter Logic
          const filterButtons = document.querySelectorAll('.status-filter button');
          const allCards = document.querySelectorAll('.bg-white.shadow-md');
          const statusMessage = document.getElementById('statusMessage');

          // Set default filter to 'All'
          filterButtons[0].classList.add('active');
          statusMessage.textContent = "Order Status: All"; // Set "All" as default message
          statusMessage.classList.remove('hidden'); // Show the message for "All"

          filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
              const selectedStatus = button.textContent.trim(); // Get the button text (e.g., "Confirmed")

              // Show all orders if "All" is selected
              if (selectedStatus === "All") {
                allCards.forEach((card) => {
                  card.classList.remove('hidden'); // Show all cards
                });
                statusMessage.textContent = "Order Status: All"; // Show "All" status message
                statusMessage.classList.remove('hidden'); // Show the message for "All"
              } else {
                statusMessage.textContent = `Order Status: ${selectedStatus}`; // Show the specific status message
                statusMessage.classList.remove('hidden'); // Show the message

                // Loop through all cards and filter by status
                allCards.forEach((card) => {
                  const statusDropdown = card.querySelector('select[name="status"]');
                  const currentStatus = statusDropdown.value.trim(); // Get current status value

                  if (currentStatus === selectedStatus) {
                    card.classList.remove('hidden'); // Show card if status matches
                  } else {
                    card.classList.add('hidden'); // Hide card if status does not match
                  }
                });
              }

              // Update active button style
              filterButtons.forEach((btn) => btn.classList.remove('active'));
              button.classList.add('active');
            });
          });

          // Update and Save Button Logic
          const updateButtons = document.querySelectorAll('.bg-green-500'); // Select all 'Update' buttons
          const saveButtons = document.querySelectorAll('.bg-blue-500'); // Select all 'Save' buttons

          updateButtons.forEach((updateButton) => {
            updateButton.addEventListener('click', () => {
              const card = updateButton.closest('.bg-white'); // Find the closest card
              const statusDropdown = card.querySelector('select[name="status"]'); // Find the status dropdown
              const saveButton = card.querySelector('.bg-blue-500'); // Find the corresponding 'Save' button

              // Enable the dropdown and show the Save button
              statusDropdown.disabled = false;
              saveButton.classList.remove('hidden');
              updateButton.classList.add('hidden');
            });
          });

          saveButtons.forEach((saveButton) => {
            saveButton.addEventListener('click', () => {
              const card = saveButton.closest('.bg-white'); // Find the closest card
              const statusDropdown = card.querySelector('select[name="status"]'); // Find the status dropdown
              const updateButton = card.querySelector('.bg-green-500'); // Find the corresponding 'Update' button

              // Save the selected value (optional: add an alert or make an API call to save the data)
              const newStatus = statusDropdown.value;
              alert(`Order status updated to: ${newStatus}`);

              // Disable the dropdown and hide the Save button
              statusDropdown.disabled = true;
              saveButton.classList.add('hidden');
              updateButton.classList.remove('hidden');
            });
          });

          saveButtons.forEach((saveButton) => {
            saveButton.addEventListener('click', () => {
              const card = saveButton.closest('.bg-white'); // Find the closest card
              const statusDropdown = card.querySelector('select[name="status"]'); // Find the status dropdown
              const updateButton = card.querySelector('.bg-green-500'); // Find the corresponding 'Update' button

              const orderId = saveButton.getAttribute('data-idtrans'); // Get the order ID
              const newStatus = statusDropdown.value;

              // Send AJAX request to update status
              fetch('../atmin/update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
              })
                .then(response => {
                  if (!response.ok) {
                    throw new Error('Network response was not ok.');
                  }
                  return response.json();
                })
                .then(data => {
                  if (data.success) {
                    alert('Order status updated successfully.');

                    // Disable the dropdown and toggle buttons
                    statusDropdown.disabled = true;
                    saveButton.classList.add('hidden');
                    updateButton.classList.remove('hidden');
                  } else {
                    alert('Error: ' + data.message);
                  }
                })
            });
          });
        });



      </script>
</body>

</html>