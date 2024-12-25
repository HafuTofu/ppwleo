<?php
require "../public/sess.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
  session_destroy();
  header('Location: ../public/login.php');
}

if ($_SESSION['login'] === 'trueguess') {
  session_destroy();
  header('Location: dashboard.php');
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
  echo "<meta http-equiv='refresh' content='1; url=adminew.php'>";
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

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="adminew.php">
            <img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10">
        </a>

        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
            <form action="" class="flex items-center w-full">
                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                  echo $_GET['search'];
                                                } ?>" placeholder="Search"
                    class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="../public/photo/search.png" width="20" height="20"
                        alt="Search"></button>
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
            <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                <a href="admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category Page</a>
                <a href="discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount Page</a>
                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
            </div>
        </div>
    </div>

    <!-- Title and Action Buttons -->
    <div class="flex items-center justify-between mx-16 mt-8 mb-4">
        <h1 class="text-2xl font-bold text-center">Kelola Produk</h1>
        <button class="flex items-center px-6 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600"
            onclick="window.location.href = 'tambahproduk.php';">
            <i class="mr-2 fas fa-plus"></i> Tambah Produk
        </button>
    </div>
    <!-- Content Wrapper -->
    <div class="mx-16">
        <!-- Headers -->
        <div class="grid grid-cols-8 gap-4 mb-4 font-bold text-center text-gray-700">
            <div class="px-6 py-3 bg-gray-100 rounded-tl-lg rounded-bl-lg shadow-md">
                Foto Barang
            </div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Nama Barang</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Stok</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Harga</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Kategori</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Status</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Deskripsi</div>
            <div class="px-6 py-3 bg-gray-100 rounded-tr-lg rounded-br-lg shadow-md">Aksi</div>
        </div>

        <?php $result = $conn->query($query);
    $row = $result->fetch_assoc();
    while ($row != null) {
    ?>
        <!-- Product List -->
        <div class="grid grid-cols-8 gap-4 mb-4">

            <!-- Product Card 1 -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
                <img src="../public/products/<?php echo $row['foto']; ?>" alt="Product Image"
                    class="w-16 h-16 mx-auto rounded-lg">
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-lg font-semibold"><?php echo $row['nama']; ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-lg font-semibold"><?php echo $row['stok']; ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-lg font-semibold">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-lg font-semibold"><?php echo $row['nama_kategori']; ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <!-- Toggle Switch -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="cekbok_<?php echo $row['ID_produk']; ?>" class="sr-only peer"
                        <?php echo ($row['statusproduk'] == 'available') ? 'checked' : ''; ?>>
                    <div class="h-6 bg-gray-200 rounded-full w-11 peer-checked:bg-green-500"></div>
                    <span
                        class="absolute w-4 h-4 transition-transform bg-white rounded-full top-1 left-1 peer-checked:translate-x-5"></span>
                </label>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-sm"><?php echo $row['deskripsi']; ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <button type="submit" class="px-4 py-1 text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                    onclick="window.location.href = 'editproduk.php?idprod=<?php echo $row['ID_produk']; ?>'">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form action="" method="POST" class="table-column">
                    <input type="hidden" name="hapus" value="<?php echo $row['ID_produk']; ?>">
                    <button type="submit" class="px-4 py-1 mt-2 text-white bg-red-500 rounded-lg hover:bg-red-600">
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
    document.addEventListener('DOMContentLoaded', function() {
        const profileIcon = document.getElementById('profileIcon');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const cekbok = document.getElementById('cekbok');

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.id.split('_')[1];
                const status = this.checked ? 'available' : 'unavailable';

                // Use AJAX to update the database
                fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: productId,
                            status: status
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`Product ${productId} status updated to ${status}`);
                        } else {
                            console.error('Failed to update status');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Toggle dropdown visibility when mouse enters the profile icon
        profileIcon.addEventListener('mouseenter', function() {
            dropdownMenu.classList.remove('hidden'); // Show dropdown
        });

        // Hide dropdown when mouse leaves the profile icon or the dropdown menu
        profileIcon.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!dropdownMenu.matches(':hover')) {
                    dropdownMenu.classList.add('hidden'); // Hide dropdown
                }
            }, 100); // Small delay to allow mouse to hover over dropdown menu
        });

        // Hide dropdown when mouse leaves the dropdown menu
        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownMenu.classList.add('hidden'); // Hide dropdown
        });
    });
    </script>
</body>

</html>