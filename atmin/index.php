<?php
require "../public/sess.php";

if (!isset($_SESSION['login'])) {
  session_destroy();
  header('Location: ../public/login.php');
}

if ($_SESSION['login'] === 'trueguess') {
  session_destroy();
  header('Location: ../public/dashboard.php');
}

if ($_SESSION['login'] === 'false') {
  session_destroy();
  header('Location: ../public/login.php');
}

$query = "SELECT * FROM produk NATURAL JOIN kategori";

if (isset($_POST['edit']) && !empty($_POST['edit'])) {
  $prodid = $_POST['edit'];
  header('Location: editproduk.php');
}

if (isset($_POST['hapus']) && !empty($_POST['hapus'])) {
  $prodid = $_POST['hapus'];
  $queryhapus = "DELETE FROM produk WHERE ID_produk = $prodid";
  mysqli_query($conn, $queryhapus);
  echo "<meta http-equiv='refresh' content='1; url='index.php'>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Produk</title>
  <link rel="icon" href="./photo/ciG.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../public/css/style7.css">
</head>

<body class="bg-yellow-50 font-sans">
  <!-- Navbar -->
  <div class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="adminew.php">
      <img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
    </a>

    <!-- Search Bar -->
    <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
      <form action="" class="flex items-center w-full">
        <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
          echo $_GET['search'];
        } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
        <button type="submit" class="p-2"><img src="../public/photo/search.png" width="20" height="20" alt="Search"></button>
      </form>
      <?php if (isset($_GET['search'])) {
        $filtervalues = $_GET['search'];
        $query = "SELECT * FROM produk NATURAL JOIN kategori WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
      } ?>
    </div>

    <!-- User and Dropdown Menu -->
    <div class="relative">
      <img src="../public/photo/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile"
        id="profileIcon">
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
        <a href="tambahproduk.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category Page</a>
        <a href="tambahkategori.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discounts Page</a>
        <a href="discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Add Discount Product</a>
        <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </div>

  <!-- Title and Action Buttons -->
  <div class="flex justify-between items-center mx-16 mt-8 mb-4">
    <h1 class="text-2xl font-bold text-center">Kelola Produk</h1>
    <button class="bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-600 flex items-center"
      onclick="window.location.href = 'tambahkategori.php';">
      <i class="fas fa-plus mr-2"></i> Tambah Kategori
    </button>
    <button class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 flex items-center"
      onclick="window.location.href = 'tambahproduk.php';">
      <i class="fas fa-plus mr-2"></i> Tambah Produk
    </button>
  </div>
  <!-- Content Wrapper -->
  <div class="mx-16">
    <!-- Headers -->
    <div class="grid grid-cols-8 gap-4 text-center font-bold text-gray-700 mb-4">
      <div class="bg-gray-100 py-3 px-6 rounded-tl-lg rounded-bl-lg shadow-md">
        Foto Barang
      </div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Nama Barang</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Stok</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Harga</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Kategori</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Status</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Deskripsi</div>
      <div class="bg-gray-100 py-3 px-6 rounded-tr-lg rounded-br-lg shadow-md">Aksi</div>
    </div>

    <?php $result = $conn->query($query);
    $row = $result->fetch_assoc();
    while ($row != null) {
      ?>
      <!-- Product List -->
      <div class="grid grid-cols-8 gap-4 mb-4">

        <!-- Product Card 1 -->
        <div class="flex items-center bg-white p-4 rounded-lg shadow-md">
          <img src="../public/products/<?php echo $row['foto']; ?>" alt="Product Image" class="w-16 h-16 rounded-lg mx-auto">
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="font-semibold text-lg"><?php echo $row['nama']; ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-lg font-semibold"><?php echo $row['stok']; ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-lg font-semibold">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-lg font-semibold"><?php echo $row['nama_kategori']; ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <!-- Toggle Switch -->
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-green-500"></div>
            <span
              class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></span>
          </label>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-sm"><?php echo $row['deskripsi']; ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <button type="submit" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600" onclick="window.location.href = 'editproduk.php?idprod=<?php echo $row['ID_produk']; ?>'">
            <i class="fas fa-edit"></i> Edit
          </button>
          <form action="" method="POST" class="table-column">
            <input type="hidden" name="hapus" value="<?php echo $row['ID_produk']; ?>">
            <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 mt-2">
              <i class="fas fa-trash"></i> Delete
            </button>
          </form>
        </div>
      </div>
      <?php $row = $result->fetch_assoc();
    } ?>
  </div>

  <!-- Dropdown Menu Script -->
  <script>
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


  </script>
</body>

</html>