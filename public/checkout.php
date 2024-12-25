<?php
require 'sess.php';

// Check if the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
  header('Location: login.php');
  exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['selected_cart_ids'])) {
    header('Location: cart.php');
    exit;
  }

  $selectedCartIds = json_decode($_POST['selected_cart_ids'], true);

  if (empty($selectedCartIds)) {
    header('Location: cart.php');
    exit;
  }

  // Fetch user data
  $iduser = $_SESSION['id'];
  $stmtuser = $conn->prepare('SELECT * FROM userdata WHERE ID_user = ?');
  $stmtuser->bind_param('i', $iduser);
  $stmtuser->execute();
  $rowuser = $stmtuser->get_result()->fetch_assoc();

  // Process payment and transaction details
  if (isset($_POST['payment_method'])) {
    $totalItems = $_POST['total_items'];
    $totalPrice = $_POST['total_price'];
    $shippingCost = $_POST['shipping_cost'];
    $serviceCharge = $_POST['service_charge'];
    $grandTotal = $_POST['grand_total'];
    $shippingOption = $_POST['shipping_option'];
    $paymentMethod = $_POST['payment_method'];

    // Insert transaction
    $stmttransaction = $conn->prepare(
      'INSERT INTO transactions (ID_user, timestamp, total_pembelian, pengiriman, hargaongkir, payment) VALUES (?, NOW(), ?, ?, ?, ?)'
    );
    $stmttransaction->bind_param('iisis', $iduser, $totalPrice, $shippingOption, $shippingCost, $paymentMethod);

    if ($stmttransaction->execute()) {
      // Fetch last transaction ID
      $transactionId = $conn->insert_id;

      // Insert transaction details
      foreach ($selectedCartIds as $cartId) {
        $stmttd = $conn->prepare('INSERT INTO transaction_details (ID_transaksi, ID_cart) VALUES (?, ?)');
        $stmttd->bind_param('ii', $transactionId, $cartId);
        $stmttd->execute();

        // Update cart and stock
        $stmtcart = $conn->prepare('SELECT * FROM cart NATURAL JOIN produk WHERE ID_cart = ?');
        $stmtcart->bind_param('i', $cartId);
        $stmtcart->execute();
        $rowc = $stmtcart->get_result()->fetch_assoc();

        $stmtUpdateCart = $conn->prepare("UPDATE cart SET checkorno = 'checked' WHERE ID_cart = ?");
        $stmtUpdateCart->bind_param('i', $cartId);
        $stmtUpdateCart->execute();

        $stmtUpdateStock = $conn->prepare('UPDATE produk SET stok = stok - ? WHERE ID_produk = ?');
        $stmtUpdateStock->bind_param('ii', $rowc['qty'], $rowc['ID_produk']);
        $stmtUpdateStock->execute();
      }

      header('Location: orderlist.html');
      exit;
    } else {
      die('Transaction error: ' . $stmttransaction->error);
    }
  }
} else {
  header('Location: cart.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout</title>
  <link rel="icon" href="./photo/ciG.png" />
  <link rel="stylesheet" href="./css/style9.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="font-sans bg-yellow-50">
  <!-- Navbar -->
  <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
    <a href="./dashboard.php">
      <img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10" />
    </a>
  </header>

  <!-- Checkout Container -->
  <div class="max-w-5xl mx-auto mt-10 grid grid-cols-3 gap-8">
    <!-- Left Section -->
    <div class="col-span-2 space-y-4">
      <!-- Shipping Address -->
      <div class="bg-white border rounded-lg p-4 shadow-md fixed">
        <h2 class="font-bold text-gray-800 mb-2">Shipping Address</h2>
        <p class="text-gray-600 text-sm mb-4" id="current-address">
          <?php echo $rowuser['address']; ?>
        </p>
        <!-- 'Change' button -->
        <label for="edit-modal"
          class="cursor-pointer border border-gray-400 px-4 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-200">
          Change
        </label>

        <!-- Modal Trigger -->
        <input type="checkbox" id="edit-modal" class="peer hidden" />

        <!-- Modal -->
        <div class="absolute inset-0 bg-gray-700 bg-opacity-50 hidden peer-checked:flex items-center justify-center">
          <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="font-bold text-gray-800 mb-4">
              Edit Shipping Address
            </h3>
            <form>
              <textarea id="edit-address" rows="4"
                class="w-full p-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                placeholder="Enter new address"><?php echo $rowuser['address']; ?></textarea>
              <div class="flex justify-end mt-4 space-x-2">
                <!-- Close Modal -->
                <label for="edit-modal"
                  class="cursor-pointer px-4 py-1 rounded-lg border text-gray-700 hover:bg-gray-200">
                  Cancel
                </label>
                <!-- Save Button -->
                <button type="button" class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                  onclick="updateAddress()">
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Product Section -->
      <div class="bg-white border rounded-lg p-4 shadow-md">
        <?php $totalItems = 0;
        $totalPrice = 0;
        foreach ($selectedCartIds as $cartId) {
          $querycart = "SELECT * FROM cart NATURAL JOIN produk WHERE ID_cart = ?";
          $stmtcart = $conn->prepare($querycart);
          $stmtcart->bind_param("i", $cartId);
          $stmtcart->execute();
          $rowcart = $stmtcart->get_result()->fetch_assoc();
          $totalItems += $rowcart['qty'];
          $totalPrice += $rowcart['harga'] * $rowcart['qty'];
          ?>
          <!-- Product 1 -->
          <div class="flex mb-4">
            <img src="./products/<?php echo $rowcart['foto']; ?>" alt="Product Image" class="rounded-lg w-20 h-20" />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700"><?php echo $rowcart['nama'] ?></h3>
                <span class="font-bold text-gray-900">Rp.
                  <?php echo number_format($rowcart['harga'], 0, ',', '.'); ?></span>
              </div>
              <p class="text-gray-500 text-sm">QTY: <?php echo $rowcart['qty']; ?></p>
            </div>
          </div>
          <!-- batas bawah -->
        <?php } ?>
      </div>

      <!-- Shipping Option -->
      <div class="bg-white border rounded-lg p-4 shadow-md mt-4">
        <h3 class="font-bold text-gray-800 mb-2">Shipping Option</h3>
        <select id="shipping" name="shippingOption"
          class="block w-full px-3 py-2 border rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
          <option value="ekonomi">Economy (Rp. 16.500) - ETA 5 Days</option>
          <option value="regular">Regular (Rp. 25.000) - ETA 3 Days</option>
          <option value="express">Express (Rp. 40.000) - ETA 1 Day</option>
          <option value="priority">
            Priority (Rp. 60.000) - ETA 3 Hours (0-15KM)
          </option>
        </select>
      </div>
    </div>

    <!-- Right Section -->
    <form id="checkout-form" action="" method="POST">
      <input type="hidden" name="selected_cart_ids" id="selected_cart_ids_input">
      <input type="hidden" name="total_items" id="total_items_input">
      <input type="hidden" name="total_price" id="total_price_input">
      <input type="hidden" name="shipping_cost" id="shipping_cost_input">
      <input type="hidden" name="service_charge" id="service_charge_input">
      <input type="hidden" name="grand_total" id="grand_total_input">
      <input type="hidden" name="shipping_option" id="shipping_option_input">
      <input type="hidden" name="payment_method" id="payment_method_input">

      <div class="bg-white border rounded-lg p-4 shadow-md space-y-4 self-start" style="width: 300px">
        <!-- Payment Method -->
        <h2 class="font-bold text-gray-800">Payment Method</h2>
        <div class="space-y-2">
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment_method_choice" id="payment_cod" class="w-4 h-4" value="COD" checked required>
            <i class="fa-solid fa-handshake"></i>
            <span class="text-gray-700">COD</span>
          </label>
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment_method_choice" id="payment_va" class="w-4 h-4" value="Virtual Account">
            <i class="fa-solid fa-building-columns"></i>
            <span class="text-gray-700">Virtual Account</span>
          </label>
        </div>

        <!-- Summary -->
        <div class="border-t border-gray-200 pt-4 summary-section">
          <h3 class="font-bold text-gray-700 mb-4">Summary</h3>
          <div class="space-y-2">
            <div class="flex justify-between text-gray-600">
              <span id="total-items">Total (<?php echo $totalItems; ?> items)</span>
              <span id="total-price" class="font-semibold">Rp.
                <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Shipping Cost</span>
              <span id="shipping-cost" class="font-semibold">Rp. 0</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Service Charge</span>
              <span id="service-charge" class="font-semibold">Rp. 2500</span>
            </div>
            <div class="border-t border-gray-300 mt-2 pt-2 flex justify-between text-gray-900">
              <span class="font-bold">Grand Total</span>
              <span id="grand-total" class="font-bold">Rp. 0</span>
            </div>
          </div>
        </div>

        <!-- Checkout Button -->
        <button type="submit"
          class="w-full bg-red-500 text-white font-semibold py-2 rounded-lg hover:bg-red-600 focus:outline-none">
          Checkout
        </button>
      </div>
    </form>
  </div>

  <script>
    function updateAddress() {
      const iduser = <?php echo $iduser; ?>;
      const username = "<?php echo htmlspecialchars($rowuser['Username']); ?>";
      const fullname = "<?php echo htmlspecialchars($rowuser['fullname']); ?>";
      const email = "<?php echo htmlspecialchars($rowuser['Email']); ?>";
      const gender = "<?php echo htmlspecialchars($rowuser['gender']); ?>";
      const phone = "<?php echo htmlspecialchars($rowuser['phone']); ?>";
      const address = document.getElementById('edit-address').value;
      const password = "<?php echo $rowuser['Password']; ?>";
      document.getElementById('current-address').innerText = document.getElementById('edit-address').value;
      document.getElementById('edit-modal').checked = false;

      const formData = {
        username: username,
        fullname: fullname,
        email: email,
        gender: gender,
        phone: phone,
        address: address,
        currentPassword: password,
        newPassword: password
      };

      fetch('update_profile.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            alert('Profile updated successfully!');
            location.reload();
          } else {
            alert('Error updating profile: ' + data.message);
          }
        })
        .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
          alert('An unexpected error occurred. Please try again later.');
        });
    }

    const shippingOptions = {
      ekonomi: 16500,
      regular: 25000,
      express: 40000,
      priority: 60000
    };

    function updateSummary() {
      const shippingCost = shippingOptions[document.getElementById('shipping').value];
      const totalPrice = <?php echo $totalPrice; ?>;
      const serviceCharge = 2500;
      const grandTotal = totalPrice + shippingCost + serviceCharge;

      document.getElementById('shipping-cost').innerText = `Rp. ${shippingCost.toLocaleString('id-ID')}`;
      document.getElementById('service-charge').innerText = `Rp. ${serviceCharge.toLocaleString('id-ID')}`;
      document.getElementById('grand-total').innerText = `Rp. ${grandTotal.toLocaleString('id-ID')}`;
    }

    document.getElementById('shipping').addEventListener('change', updateSummary);

    window.onload = updateSummary;

    document.getElementById('checkout-form').addEventListener('submit', function (event) {
      event.preventDefault();

      const selectedCartIds = <?php echo json_encode($selectedCartIds); ?>;
      const shippingOption = document.getElementById('shipping').value;
      const totalItems = <?php echo $totalItems; ?>;
      const totalPrice = <?php echo $totalPrice; ?>;
      const shippingCost = shippingOptions[shippingOption];
      const serviceCharge = 2500;
      const grandTotal = totalPrice + shippingCost + serviceCharge;
      const paymentMethod = document.querySelector('input[name="payment_method_choice"]:checked').value;

      document.getElementById('selected_cart_ids_input').value = JSON.stringify(selectedCartIds);
      document.getElementById('total_items_input').value = totalItems;
      document.getElementById('total_price_input').value = totalPrice;
      document.getElementById('shipping_cost_input').value = shippingCost;
      document.getElementById('service_charge_input').value = serviceCharge;
      document.getElementById('grand_total_input').value = grandTotal;
      document.getElementById('shipping_option_input').value = shippingOption;
      document.getElementById('payment_method_input').value = paymentMethod;

      this.submit();
    });



  </script>
</body>

</html>