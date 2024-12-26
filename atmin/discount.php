<?php
include "../connect.php";
$qd = "SELECT * FROM (produk NATURAL JOIN discounts NATURAL JOIN kategori)";
$qp = "SELECT * FROM produk WHERE ID_discount = 0 ";
$productsname = [];
$productsid = [];
$result = $conn->query($qp);
while ($row = $result->fetch_assoc()) {
  $productsname[] = $row['nama'];
  $productsid[] = $row['ID_produk'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discounted Products</title>
  <link rel="icon" href="../public/photo/ciG.png">
  <link rel="stylesheet" href="../public/css/style6.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .discount-box {
      position: relative;
      width: 90%;
      max-width: 400px;
      padding: 2rem;
      background-color: rgba(236, 218, 183, 1);
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 2;
      text-align: center;
    }

    .discount-box form {
      display: flex;
      flex-direction: column;
    }

    .discount-box h2 {
      font-size: 1.5rem;
      color: black;
      margin-bottom: 1rem;
    }

    .discount-box label {
      display: block;
      font-size: 14.4px;
      color: black;
      text-align: left;
      margin-bottom: 0.5rem;
    }

    .discount-box input[type="text"],
    .discount-box input[type="number"],
    .discount-box select,
    .discount-box textarea {
      margin-bottom: 1rem;
      width: 100%;
      padding: 10px;
      border: 1px solid #a94b4b;
      border-radius: 5px;
      background-color: rgba(80, 7, 18, 0.5);
      font-size: 14.4px;
      color: white;
    }

    .discount-box button {
      width: 100%;
      padding: 10px;
      border: none;
      background-color: #a94b4b;
      color: #fff;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .discount-box button:hover {
      background-color: rgba(80, 7, 18, 0.5);
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal.show {
      display: flex;
    }
  </style>
</head>

<body class="bg-yellow-50 font-sans">
  <!-- Navbar -->
  <header class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="atmindashboard.html">
      <img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
    </a>

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
        $qd = "SELECT * FROM (discounts NATURAL JOIN produk NATURAL JOIN kategori) WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
      } ?>
    </div>

    <!-- User and Dropdown Menu -->
    <div class="relative">
      <img src="../public/photo/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile"
        id="profileIcon">
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
        <a href="../public/profilepage.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
        <a href="../public/dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User Dashboard</a>
        <a href="atmindashboard.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Admin Dashboard</a>
        <a href="admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Add Product Page</a>
        <a href="admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category Page</a>
        <a href="orderadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order Page</a>
        <a href="usercontroller.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User Page</a>
        <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </header>

  <!-- Title and Action Buttons (Add Discount and Edit) -->
  <div class="flex justify-between items-center mx-16 mt-8 mb-4">
    <!-- Title -->
    <h1 class="text-2xl font-bold text-center">Discounted Products</h1>

    <!-- Action Buttons -->
    <div class="flex space-x-4">
      <button
        class="addDiscountButton bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 flex items-center">
        <i class="fas fa-plus mr-2"></i> Add Discount
      </button>
    </div>
  </div>

  <!-- add/edit product -->
  <div class="modal" id="addCatModal">
    <div class="discount-box">
      <h2 id="modalTitle">Add Discount</h2>
      <form id="addCatForm" action="process_discount.php" method="POST">
        <select id="idproduk" name="idproduk" required>
          <option value="" disabled selected>Choose Products Name</option>
          <?php for ($i = 0; $i < count($productsname); $i++) { ?>
            <option value="<?php echo $productsid[$i]; ?>"><?php echo $productsname[$i]; ?></option>
          <?php } ?>
        </select>
        <label for="disc">Discount Amount:</label>
        <input type="number" id="disc" name="disc" min="1" max="99" required>
        <button type="submit" id="modalBtn">Add</button>
      </form>

    </div>
  </div>

  <div class="modal" id="editCatModal">
    <div class="discount-box">
      <h2 id="modalTitle">Edit Discount</h2>
      <form id="addCatForm" action="process_discount.php" method="POST">
        <select id="idproduk" name="idproduk" required>
          <option value="" id="autoselect" selected>Choose Products Name</option>
        </select>
        <label for="disc">Discount Amount:</label>
        <input type="number" id="disc" name="disc" min="1" max="99" required>
        <input type="hidden" id="iddisc" name="iddisc">
        <button type="submit" id="modalBtn">Save</button>
      </form>

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

      <?php $resqd = $conn->query($qd);
      while ($rqd = $resqd->fetch_assoc()) {
        ?>
        <!-- Product Card 1 -->
        <div class="flex items-center bg-white p-4 rounded-lg shadow-md">
          <img src="../public/products/<?php echo $rqd['foto']; ?>" alt="Product Image" class="w-16 h-16 rounded-lg mr-4">
          <p class="font-semibold"><?php echo $rqd['nama']; ?></p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-red-600 text-xl font-bold">Rp. <?php echo number_format($rqd['discountprice'], 0, ',', '.'); ?>
          </p>
          <p class="line-through text-gray-500">Rp. <?php echo number_format($rqd['harga'], 0, ',', '.'); ?></p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-red-600 text-xl font-bold"><?php echo $rqd['amount']; ?>%</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-lg font-semibold"><?php echo $rqd['stok']; ?></p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <button class="edit-cat-btn bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600"
            data-disc-amount="<?php echo $rqd['amount']; ?>" data-prod-id="<?php echo $rqd['ID_produk']; ?>"
            data-disc-id="<?php echo $rqd['ID_discount']; ?>" data-prod-name="<?php echo $rqd['nama']; ?>">
            <i class="fas fa-edit mr-2"></i> Edit
          </button>
          <form class="bg-red-500 text-white rounded-lg hover:bg-red-600 mt-2" method="POST" action="process_discount.php">
            <input type="hidden" name="hapus" value="<?php echo $rqd['ID_discount']; ?>">
            <input type="hidden" name="idproduk" value="<?php echo $rqd['ID_produk']; ?>">
            <button type="submit" class="py-1 px-4 rounded-lg">
              <i class="fas fa-trash"></i> Delete
            </button>
          </form>
        </div>
        <?php
      } ?>
    </div>
  </div>

  <script>
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

    });

    document.addEventListener('DOMContentLoaded', function () {
      const addDiscountButton = document.querySelector('.addDiscountButton');
      const addCatModal = document.getElementById('addCatModal');
      const editCatModal = document.getElementById('editCatModal');
      const closeModalElements = document.querySelectorAll('#addCatModal button[type="submit"], #addCatModal');
      const closeEditModalElements = document.querySelectorAll('#editCatModal button[type="submit"], #editCatModal');
      const modalBtn = document.getElementById('modalBtn');

      const openModal = () => {
        addCatModal.classList.add('show');
      };

      const closeModal = (event) => {
        if (event.target === addCatModal || event.target.closest('button[type="submit"]')) {
          addCatModal.classList.remove('show');
        }
      };

      addDiscountButton.addEventListener('click', openModal);
      closeModalElements.forEach((element) => {
        element.addEventListener('click', closeModal);
      });

      const modalContent = document.querySelector('.discount-box');
      modalContent.addEventListener('click', (e) => {
        e.stopPropagation();
      });

      const editButtons = document.querySelectorAll('.edit-cat-btn');
      editButtons.forEach((button) => {
        button.addEventListener('click', () => {
          const prodIdField = document.getElementById('autoselect');
          const discAmount = document.getElementById('disc');
          const idDisc = document.getElementById('iddisc');
          const prodId = button.getAttribute('data-prod-id');
          const prodName = button.getAttribute('data-prod-name');
          const discamount = button.getAttribute('data-disc-amount');
          const discid = button.getAttribute('data-disc-id');
          prodIdField.value = prodId;
          prodIdField.innerText = prodName;
          discAmount.value = discamount;
          idDisc.value = discid;
          editCatModal.classList.add('show');
        });
      });

      const closeEditModal = (event) => {
        if (event.target === editCatModal || event.target.closest('button[type="submit"]')) {
          editCatModal.classList.remove('show');
        }
      };

      closeEditModalElements.forEach((element) => {
        element.addEventListener('click', closeEditModal);
      });

      const modalEditContent = document.querySelector('.discount-box');
      modalContent.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    });
  </script>
</body>

</html>