<?php
require "../public/sess.php";

if (!isset($_SESSION['login'])) {
  header('Location: ../public/login.php');
}
$iduser = $_SESSION['id'];
$stmtuser = $conn->prepare('SELECT * FROM userdata WHERE ID_user = ?');
$stmtuser->bind_param('i', $iduser);
$stmtuser->execute();
$rowuser = $stmtuser->get_result()->fetch_assoc();
$queryst = "SELECT * FROM (transactions NATURAL JOIN transaction_details NATURAL JOIN cart NATURAL JOIN produk) WHERE ID_user = $iduser";


$shippingest = ["ekonomi" => "+5 days", "regular" => "+3 days", "express" => "+2 days", "priority" => "+1 days"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order List</title>
  <link rel="icon" href="./photo/ciG.png" />
  <link rel="stylesheet" href="./css/style10.css" />
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
    <a href="./dashboard.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

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
        $queryst = "SELECT * FROM (transactions NATURAL JOIN transaction_details NATURAL JOIN cart NATURAL JOIN produk) WHERE ID_user = $iduser";
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center mr-6 space-x-6">
      <div class="relative">
        <img src="./photouser/<?php echo $_SESSION['fotouser']; ?>" class="w-12 h-12 rounded-full cursor-pointer"
          alt="User profile" id="profileIcon">
        <!-- Dropdown menu -->
        <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
          <a href="./profilepage.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
          <a href="./cart.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Cart</a>
          <a href="./wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
          <a href="./orderlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order</a>
          <a href="./logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
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
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Need Rate">Need Rate</button>
      <button class="px-4 py-2 bg-gray-200 rounded-full" data-filter="Done">Done</button>
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
            <img src="./products/<?php echo $rowst['foto']; ?>" alt="Product" class="w-20 h-20 rounded-md object-cover">
            <div>
              <h3 class="text-lg font-bold"><?php echo $rowst['nama']; ?></h3>
              <p class="text-sm text-gray-600 mb-1">Qty: <?php echo $rowst['qty']; ?></p>
              <p class="text-sm">
                <span class="font-bold"><?php echo $rowuser['Username']; ?></span><br>
                <?php echo $rowuser['address']; ?>
              </p>
            </div>
          </div>

          <!-- Order Status -->
          <div class="w-1/4 flex flex-col items-center">
            <div class="flex items-center space-x-2">
              <i class="fa-solid fa-truck text-gray-600 text-xl"></i>
              <select class="p-2 border rounded-md text-sm w-40 text-center" name="status" disabled>
                <option><?php echo $rowst['order_status']; ?></option>
              </select>
            </div>
            <p class="text-red-500 font-semibold text-sm mt-2">
              <?php $eta = "ETA: $startDay - $endDay $month $year";
              echo $eta; ?>
            </p>
          </div>

          <!-- Total and Buttons -->
          <div class="text-right w-1/4">
            <p class="font-bold text-sm">Total</p>
            <p class="text-xl font-semibold text-gray-800">Rp.
              <?php echo number_format($rowst['total_harga'], 0, ',', '.'); ?>
            </p>
            <button
              class="bg-red-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-red-600 cancel-btn hidden"
              data-idtrans = <?php echo $rowst['ID_transaksi']; ?>>CANCEL</button>
            <button
              class="bg-green-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-green-600 rate-btn hidden"
              data-idtrans = <?php echo $rowst['ID_transaksi']; ?>>RATE</button>
          </div>
        </div>
        <!-- batas bawah -->
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


        });

        document.addEventListener('DOMContentLoaded', function () {
          const orderCards = document.querySelectorAll('.bg-white.shadow-md');

          orderCards.forEach((card) => {
            const statusDropdown = card.querySelector('select[name="status"]');
            const rateBtn = card.querySelector('.rate-btn');
            const cancelBtn = card.querySelector('.cancel-btn');
            const orderId = cancelBtn.getAttribute('data-idtrans'); // Assuming you add a `data-order-id` attribute to the card

            // Update button visibility based on status
            const updateButtons = () => {
              const status = statusDropdown.value.trim();
              rateBtn.classList.add('hidden');
              cancelBtn.classList.add('hidden');

              if (status === 'Need Rate') {
                rateBtn.classList.remove('hidden');
              }
              if (status === 'Confirmed' || status === 'Packing Process') {
                cancelBtn.classList.remove('hidden');
              }
            };

            // Initial call
            updateButtons();

            // Cancel Button Click
            cancelBtn.addEventListener('click', () => {
              if (confirm('Are you sure you want to cancel this order?')) {
                fetch('./cancel_order.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({ orderId }),
                })
                  .then((response) => response.json())
                  .then((data) => {
                    if (data.success) {
                      alert('Order has been canceled!');
                      statusDropdown.value = 'Canceled';
                      updateButtons();
                    } else {
                      alert('Failed to cancel order. Please try again.');
                    }
                  })
                  .catch((error) => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                  });
              }
              location.reload();
            });

            // Rate Button Click
            rateBtn.addEventListener('click', () => {
              const rating = prompt('Rate this order (1-5):');
              if (rating && rating >= 1 && rating <= 5) {
                fetch('./rate_order.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({ orderId, rating }),
                })
                  .then((response) => response.json())
                  .then((data) => {
                    if (data.success) {
                      alert('Thank you for rating!');
                      statusDropdown.value = 'Done';
                      updateButtons();
                    } else {
                      alert('Failed to submit rating. Please try again.');
                    }
                  })
                  .catch((error) => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                  });
              } else {
                alert('Invalid rating. Please enter a number between 1 and 5.');
              }
            });
          });
        });

      </script>
</body>

</html>