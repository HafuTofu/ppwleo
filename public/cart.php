<?php
require '../public/sess.php';

if ($_SESSION['login'] === 'false') {
  header('Location: login.php');
}

$iduser = $_SESSION['id'];
$query = "SELECT * FROM (cart NATURAL JOIN ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori)) WHERE ID_user = ? AND checkorno = 'unchecked'";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $iduser);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart Page</title>
  <link rel="icon" href="./photo/ciG.png">
  <link rel="stylesheet" href="./css/style5.css">
  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }
  </style>
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
        header("Location: search.php?{$searchkey}");
      } ?>
    </div>

    <!-- User and Cart Icons -->
    <div class="flex items-center space-x-6 mr-6">
      <a href="cart.php">
        <img src="./photo/cart.png" class="w-12 cursor-pointer">
      </a>
      <div class="relative">
        <img src="./photo/<?php echo $_SESSION['fotouser']; ?>" class="w-12 h-12 rounded-full cursor-pointer"
          alt="User profile" id="profileIcon">
        <!-- Dropdown menu -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
          <?php if ($_SESSION['login'] === 'trueadmin') { ?>
            <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
              Admin Dashboard</a>
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
          <?php } ?>
          <a href="../public/profilepage.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
          <a href="../public/dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
            Dashboard</a>
          <a href="../public/wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
          <a href="../public/orderlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
            List</a>
          <a href="../public/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
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
            <button id="deleteSelected" class="text-gray-500">Delete..</button>
          </div>
        </div>


        <?php
        $total = 0;
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($items as $product => $value) {
          $sr = $product + 1;
          $total += $value['harga'];
          $inwl = false;
          $idprod = $value['ID_produk'];
          $idcart = $value['ID_cart'];
          $stmtwl = $conn->prepare("SELECT * FROM wishlist WHERE ID_user = ? AND ID_produk = ?");
          $stmtwl->bind_param("ii", $iduser, $idprod);
          $stmtwl->execute();
          $resultwl = $stmtwl->get_result();
          $inwl = $resultwl->num_rows > 0;
          $price = $value['ID_discount'] > 0 ? $value['discountprice'] : $value['harga'];
          echo "
          <div class='bg-white shadow-md rounded-md p-4 flex items-center gap-4 mb-4'>
            <input type='checkbox' class='itemCheckbox self-start' value='$idcart'>
            <input type='hidden' class='idprod' value='$idprod'>
            <input type='hidden' id='iduser' value='$iduser'>
            <img src='./products/$value[foto]' alt='Jacket' class='w-28 h-28 rounded-md object-cover cursor-pointer' onclick='window.location.href = `detailprod.php?idprod=$value[ID_produk]`'>
            <div class='flex-1'>
              <h2 class='font-semibold'> $value[nama]</h2>
              <div class='flex items-center mt-2 gap-4 py-4'>
                <button class='bg-gray-200 rounded-full px-2 quantity-button decrease onclick='updateSubtotal();' data-action='decrease''>-</button>
                <input type='number' value='$value[qty]' min=1 max=$value[stok] class='quantity w-16 text-center border border-gray-300 rounded-md no-arrows' oninput='inputQuantity()' >
                <button class='bg-gray-200 rounded-full px-2 quantity-button increase onclick='updateSubtotal();' data-action='increase''>+</button>
              </div>
            </div>
            <div class='text-right flex items-center gap-4'>
              <button id='trashButton$sr' data-product-id='$value[ID_produk]' onclick='deleteCart($value[ID_cart])'>
                <i id='trashIcon$sr' class='fas fa-trash text-gray-600'></i>
              </button>
              <button id='loveButton$sr' data-product-id='$value[ID_produk]' onclick='toggleHeart($value[ID_produk])'>
  <i id='heartIcon$sr' class='" . ($inwl ? 'fas' : 'far') . " fa-heart text-red-600  '></i>
</button>

              <p class='font-semibold price'>Rp. " . number_format($value['ID_discount'] > 0 ? $value['discountprice'] : $value['harga'], 0, ',', '.') . "
              <input type='hidden' class='iprice' id='iprice' value='$price'></p>
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
          <a>
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
      fetch('../public/add_to_wishlist.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          idprod: idprodd
        }),
      });
    }

    function deleteCart(cartID) {

      fetch('../public/delete_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          idcart: cartID
        }),
      });

      event.preventDefault();

      const popup = document.createElement('div');
      popup.innerHTML = 'Item removed from your cart!';
      popup.style.position = 'fixed';
      popup.style.bottom = '20px';
      popup.style.right = '20px';
      popup.style.background = '#AF4C4CFF';
      popup.style.color = 'white';
      popup.style.padding = '10px 20px';
      popup.style.borderRadius = '5px';
      popup.style.boxShadow = '0 2px 5px rgba(0,0,0,0.3)';
      popup.style.zIndex = '1000';

      document.body.appendChild(popup);

      setTimeout(() => {
        popup.remove();
        event.target.submit();
      }, 2000);

      location.reload();
    }

    document.addEventListener("DOMContentLoaded", function () {
      const deleteSelectedButton = document.getElementById("deleteSelected");
      const itemCheckboxes = document.querySelectorAll(".itemCheckbox");

      deleteSelectedButton.addEventListener("click", function () {
        const selectedItems = Array.from(itemCheckboxes)
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => parseInt(checkbox.value, 10));

        if (selectedItems.length > 0) {
          fetch("../public/delete_cart.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ selectedItems }),
          });
          location.reload();
        } else {
          alert("No items selected.");
        }
      });
    });




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

    document.addEventListener("DOMContentLoaded", function () {
      const selectAllCheckbox = document.getElementById("selectAll");
      const itemCheckboxes = document.querySelectorAll(".itemCheckbox");
      const iduser = document.getElementById("iduser").value;
      const idproduct = document.querySelectorAll(".idprod");
      const iprice = document.getElementsByClassName("iprice");
      const iquantity = document.getElementsByClassName("quantity");
      const subtotal = document.getElementById("totalPrice");

      function calculateSubtotal() {
        let total = 0;
        for (let i = 0; i < itemCheckboxes.length; i++) {
          if (itemCheckboxes[i].checked) {
            total += parseInt(iprice[i].value, 10) * parseInt(iquantity[i].value, 10);
            fetch("../public/update_qty.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                idprod: parseInt(idproduct[i].value, 10),
                iduser: iduser,
                newqty: parseInt(iquantity[i].value, 10),
                harga: parseInt(iprice[i].value, 10)
              }),
            });
          } else {
            fetch("../public/update_qty.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                idprod: parseInt(idproduct[i].value, 10),
                iduser: iduser,
                newqty: parseInt(iquantity[i].value, 10),
                harga: parseInt(iprice[i].value, 10)
              }),
            });
          }
        }
        subtotal.innerText = `Rp. ${total.toLocaleString("id-ID")}`;
      }

      selectAllCheckbox.addEventListener("change", function () {
        const isChecked = selectAllCheckbox.checked;
        itemCheckboxes.forEach((checkbox) => {
          checkbox.checked = isChecked;
        });
        calculateSubtotal();
      });

      itemCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
          if (!checkbox.checked) {
            selectAllCheckbox.checked = false;
          }

          const allChecked = Array.from(itemCheckboxes).every((cb) => cb.checked);
          selectAllCheckbox.checked = allChecked;

          calculateSubtotal();
        });
      });

      document.querySelectorAll(".quantity-button").forEach((button) => {
        button.addEventListener("click", function () {
          const action = this.getAttribute("data-action");
          const parentDiv = this.closest(".flex.items-center");
          const quantityElement = parentDiv.querySelector(".quantity");
          let quantity = parseInt(quantityElement.value, 10);
          let quantitymax = parseInt(quantityElement.max, 10);

          if (action === "increase" && quantity < quantitymax) {
            quantity += 1;
          } else if (action === "decrease" && quantity > 1) {
            quantity -= 1;
          }

          quantityElement.value = quantity;
          calculateSubtotal();
        });
      });

      document.querySelectorAll(".quantity").forEach((input) => {
        input.addEventListener("input", function () {
          const max = parseInt(input.getAttribute("max"), 10);
          const value = parseInt(input.value, 10);

          if (value < 1 || isNaN(value)) {
            input.value = 1;
          } else if (value > max) {
            input.value = max;
          }
          calculateSubtotal();
        });
      });
    });

    document.addEventListener("DOMContentLoaded", function () {
      const checkoutButton = document.querySelector("button.bg-red-600");

      checkoutButton.addEventListener("click", function (event) {
        event.preventDefault();

        const selectedItems = Array.from(document.querySelectorAll(".itemCheckbox"))
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => parseInt(checkbox.value, 10)); // Extract the `id_cart` value

        if (selectedItems.length === 0) {
          alert("Please select at least one item to checkout.");
          return;
        }

        const form = document.createElement("form");
        form.method = "POST";
        form.action = "checkout.php";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "selected_cart_ids";
        input.value = JSON.stringify(selectedItems);

        form.appendChild(input);
        document.body.appendChild(form);

        form.submit();
      });
    });


  </script>
</body>

</html>