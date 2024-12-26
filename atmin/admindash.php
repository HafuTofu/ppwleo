<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ppwleo/public/sess.php';


if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
  session_destroy();
  header("Location: { __DIR__ . '/../public/login.php'}");
}

if ($_SESSION['login'] === 'trueguess') {
  session_destroy();
  header("Location: {$_SERVER['DOCUMENT_ROOT']}dashboard.php");
}

$query = "SELECT * FROM (produk NATURAL JOIN kategori)";

$error = '';
$categories = [];
$result = $conn->query("SELECT nama_kategori FROM kategori");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['nama_kategori'];
}
//insert
if (!empty($_POST['insert'])) {
    try {
        $nama = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $stok = $_POST['stok'];
        $harga = $_POST['harga'];
        $deskripsi = "{$_POST['deskripsi']}";
        $filename = '';
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $queryidkat = "SELECT ID_kategori FROM kategori WHERE nama_kategori = '$kategori'";
        $resultidkat = mysqli_query($conn, $queryidkat);
        $rowidkat = mysqli_fetch_assoc($resultidkat);
        $idkat = $rowidkat['ID_kategori'];

        if (isset($_FILES['inputfoto']) && $_FILES['inputfoto']['error'] == 0) {
            if ($_FILES['inputfoto']['size'] <= 0) {
                throw new Exception('The uploaded file is empty.');
            }

            $tipe = $finfo->file($_FILES['inputfoto']['tmp_name']);
            if (!in_array($tipe, ['image/png', 'image/jpeg', 'image/jpg'])) {
                throw new Exception('Unsupported file format. Please upload a PNG or JPEG image.');
            }

            $filename = md5(random_bytes(1)) . '.' . pathinfo($_FILES['inputfoto']['name'], PATHINFO_EXTENSION);
            $filepath = '../public/products/' . $filename;
        }

        $query = "INSERT INTO produk (nama, deskripsi, harga, stok, terjual, ID_kategori, foto) VALUES ( '{$nama}' , '{$deskripsi}', '{$harga}', '{$stok}', 0, '{$idkat}', '{$filename}')";
        mysqli_query($conn, $query);
        move_uploaded_file($_FILES['inputfoto']['tmp_name'], $filepath);
        header("Location: admindash.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} 
//update
else if (!empty($_POST["update"])) {
  try {
    $idprod = $_POST['idprod'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $oldfoto = $_POST['oldfoto'];
    $filename = '';
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    $queryidkat = "SELECT ID_kategori FROM kategori WHERE nama_kategori = '$kategori'";
    $resultidkat = mysqli_query($conn, $queryidkat);
    $rowidkat = mysqli_fetch_assoc($resultidkat);
    $idkat = $rowidkat['ID_kategori'];

    if (isset($_FILES['inputfoto']) && $_FILES['inputfoto']['error'] == 0) {
        if ($_FILES['inputfoto']['size'] <= 0) {
            throw new Exception('The uploaded file is empty.');
        }

        $tipe = $finfo->file($_FILES['inputfoto']['tmp_name']);
        if (!in_array($tipe, ['image/png', 'image/jpeg', 'image/jpg'])) {
            throw new Exception('Unsupported file format. Please upload a PNG or JPEG image.');
        }

        $filename = md5(random_bytes(1)) . '.' . pathinfo($_FILES['inputfoto']['name'], PATHINFO_EXTENSION);
        $filepath = '../public/products/' . $filename;
        move_uploaded_file($_FILES['inputfoto']['tmp_name'], $filepath);
    }else{
        $filename = $oldfoto; 
    }
    $queryup = "UPDATE produk SET nama = ? , deskripsi = ? , harga = ? , stok = ? , terjual = 0, ID_kategori = ? , foto = ? 
                WHERE ID_produk = ? ";
    $stmtup = $conn->prepare($queryup);
    $stmtup->bind_param("ssiiisi", $nama, $deskripsi,$harga,$stok,$idkat,$filename,$idprod);
    $stmtup->execute();
    header("Location: admindash.php");
    exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}

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
    <link rel="icon" href="../public/photo/ciG.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../public/css/style7.css">
    <style>
        .product-box {
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

        .product-box form {
            display: flex;
            flex-direction: column;
        }

        .product-box h2 {
            font-size: 1.5rem;
            color: black;
            margin-bottom: 1rem;
        }

        .product-box label {
            display: block;
            font-size: 14.4px;
            color: black;
            text-align: left;
            margin-bottom: 0.5rem;
        }

        .product-box input[type="text"],
        .product-box input[type="number"],
        .product-box select,
        .product-box textarea{
            margin-bottom: 1rem;
            width: 100%;
            padding: 10px;
            border: 1px solid #a94b4b;
            border-radius: 5px;
            background-color: rgba(80, 7, 18, 0.5);
            font-size: 14.4px;
            color: white;
        }

        .product-box button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #a94b4b;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .product-box button:hover {
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

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="atmindashboard.php">
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
        $query = "SELECT * FROM (produk NATURAL JOIN kategori) WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
      } ?>
        </div>

        <!-- User and Dropdown Menu -->
        <div class="relative">
            <img src="../public/photo/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile"
                id="profileIcon">
            <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Admin
                    Dashboard</a>
                <a href="../atmin/admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
                    Managing Page</a>
                <a href="../atmin/discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
                    Managing Page</a>
                <a href="../atmin/orderadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
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
    </header>

    <!-- Title and Action Buttons -->
    <div class="flex items-center justify-between mx-16 mt-8 mb-4">
        <h1 class="text-2xl font-bold text-center">Kelola Produk</h1>
        <button class="flex items-center px-6 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600"
        id="openAddCatModal">
            <i class="mr-2 fas fa-plus"></i> Add New Product
        </button>
    </div>

    <!-- add/edit product -->
    <div class="modal" id="addCatModal">
        <div class="product-box">
            <h2 id="modalTitle">Add Product</h2>
            <form id="addCatForm" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="prod_id" name="idprod">
                <label for="prod_name">Product Name</label>
                <input type="text" id="prod_name" name="nama" placeholder="Enter Product Name" required>
                <label for="inputfoto">Insert Foto:</label>
                <input style="margin-bottom: 1rem;" type="file" name="inputfoto" id ="inputfoto"required
                    accept="image/png,image/jpeg,image/jpg">
                <label for="kategori">Categories:</label>
                <select id="kategori" name="kategori" required>
                    <option value="" disabled selected>Choose Category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php } ?>
                </select>
                <label for="stok">Stock Quantity:</label>
                <input type="number" id="stok" name="stok" min=1 required
                    value="<?php echo $_POST['inputnumber'] ?? 1 ?>">
                <label for="harga">Price:</label>
                <input type="number" id="harga" name="harga" min='1000' required
                    value="<?php echo $_POST['harga'] ?? '1000' ?>">    
                <label for="deskripsi">Deskripsi:</label>
                <textarea style="max-width:100% !important; min-width:100%;" id="deskripsi" name="deskripsi" required
                    value="<?php echo $_POST['deskripsi'] ?? '' ?>"></textarea>
                <input type="hidden" id="addorupdate">
                <input type="hidden" id="oldfoto" name="oldfoto">
                <button type="submit" id="btnpress">Add</button>
            </form>
        </div>
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
                <label class="fixed inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="cekbok_<?php echo $row['ID_produk']; ?>" class="sr-only peer"
                        <?php echo ($row['statusproduk'] == 'available') ? 'checked' : ''; ?>>
                    <div class="h-6 px-3 bg-red-500 rounded-full peer-checked:bg-green-500 w-full font-semibold"><?php echo ($row['statusproduk'] == 'available') ? 'AVAILABLE' : 'UNAVAILABLE'; ?></div>
                </label>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <p class="text-sm"><?php echo $row['deskripsi']; ?></p>
            </div>
            <div class="p-4 text-center bg-white rounded-lg shadow-md">
                <button type="button" class="px-4 py-1 text-white bg-blue-500 rounded-lg hover:bg-blue-600 edit-cat-btn"
                data-prod-id="<?php echo $row['ID_produk']; ?>"
                data-prod-name="<?php echo $row['nama']; ?>"
                data-stock-qty="<?php echo $row['stok']; ?>"
                data-price="<?php echo $row['harga']; ?>"
                data-desc = "<?php echo $row['deskripsi']; ?>"
                data-oldfoto = "<?php echo $row['foto']; ?>">
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
    document.addEventListener('DOMContentLoaded', function() {
        const profileIcon = document.getElementById('profileIcon');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const addCatModal = document.getElementById('addCatModal');
        const openAddCatModal = document.getElementById('openAddCatModal');
        const addCatForm = document.getElementById('addCatForm');
        const addorupdate = document.getElementById('addorupdate');
        const modalTitle = document.getElementById('modalTitle');
        const modalBtn = document.getElementById('btnpress');
        const prodIdField = document.getElementById('prod_id');
        const prodname = document.getElementById('prod_name');
        const prodstock = document.getElementById('stok');
        const prodprice = document.getElementById('harga');
        const proddesc = document.getElementById('deskripsi');
        const cekbok = document.getElementById('cekbok');
        const filefoto = document.getElementById('filefoto');

        prodstock.addEventListener('input', function() {
          const inputValue = parseInt(prodstock.value,10);
            if (prodstock.value < 1 || isNaN(inputValue)) {
                prodstock.value = 1;
            }
        });

        prodprice.addEventListener('input', function() {
          const inputValue = parseInt(prodprice.value,10);
            if (prodprice.value < 1000 || isNaN(inputValue)) {
                prodprice.value = 1000;
            }
        });

        openAddCatModal.addEventListener('click', () => {
          modalTitle.innerText = 'Add Product';
          modalBtn.innerText = 'Add';
          addorupdate.name = 'insert';
          addorupdate.value = 'insert';
          prodIdField.value = ''; 
          prodname.value = '';
          prodstock.value = 1;
          prodprice.value = 1000;
          proddesc.value = '';
          addCatModal.classList.add('show');
        });

        window.addEventListener('click', (e) => {
          if (e.target === addCatModal) {
              addCatModal.classList.remove('show');
          }
        });

        const editButtons = document.querySelectorAll('.edit-cat-btn');
        editButtons.forEach((button) => {
          button.addEventListener('click', () => {
            const prodof = document.getElementById('oldfoto');
            const inputfoto = document.getElementById('inputfoto');
            const prodId = button.getAttribute('data-prod-id'); 
            const prodName = button.getAttribute('data-prod-name'); 
            const prodStock = button.getAttribute('data-stock-qty'); 
            const prodPrice = button.getAttribute('data-price'); 
            const prodDesc = button.getAttribute('data-desc');
            const prodFoto = button.getAttribute('data-oldfoto');
            modalTitle.innerText = 'Edit Product';
            modalBtn.innerText = 'Save';
            addorupdate.name = 'update';
            addorupdate.value = 'update';
            prodIdField.value = prodId;
            prodname.value = prodName;
            prodstock.value = prodStock;
            prodprice.value = prodPrice;
            proddesc.value = prodDesc;
            prodof.value = prodFoto;
            inputfoto.required = false;
            addCatModal.classList.add('show');
          });
        });

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.id.split('_')[1];
                const status = this.checked ? 'available' : 'unavailable';
                const buttonText = this.nextElementSibling;

                if (this.checked) {
                    buttonText.textContent = 'AVAILABLE';
                } else {
                    buttonText.textContent = 'UNAVAILABLE';
                }

                fetch('../atmin/update_status.php', {
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

        profileIcon.addEventListener('mouseenter', function() {
            dropdownMenu.classList.remove('hidden');
        });

        profileIcon.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!dropdownMenu.matches(':hover')) {
                    dropdownMenu.classList.add('hidden');
                }
            }, 100);
        });

        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownMenu.classList.add('hidden');
        });
    });
    </script>
</body>

</html>