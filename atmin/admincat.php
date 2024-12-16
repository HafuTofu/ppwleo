<?php
require "../public/sess.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
  session_destroy();
  header('Location: ../public/login.php');
}

if ($_SESSION['login'] === 'trueguess') {
  session_destroy();
  header('Location: ../public/dashboard.php');
}

$query = "SELECT * FROM kategori";

if (isset($_POST['hapus']) && !empty($_POST['hapus'])) {
  $catid = $_POST['hapus'];
  $members = $_POST['members'];
  if($members > 0) {
    echo "<script>alert('Can't delete category because this category has member(s) in it');</script>";
    echo "<meta http-equiv='refresh' content='0; url=admincat.php'>";
  }else{
  $queryhapus = "DELETE FROM kategori WHERE ID_kategori = $catid";
  mysqli_query($conn, $queryhapus);
  echo "<meta http-equiv='refresh' content='0; url=admincat.php'>";
  }
}

if (!empty($_POST['postype']) && $_POST['postype'] === 'insert') {
  try {
    $nama = ucfirst(strtolower($_POST['nama']));
    $query = "INSERT INTO kategori (nama_kategori) 
                VALUES ('$nama')";
    mysqli_query($conn, $query);
    echo "<meta http-equiv='refresh' content='0; url=admincat.php'>";
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
} else if (!empty($_POST['postype']) && $_POST['postype'] === 'update') {
  try {
    $nama = ucfirst(strtolower($_POST['nama']));
    $idcat = $_POST['idcat'];
    $queryup = "UPDATE kategori SET nama_kategori = ? WHERE ID_kategori = ? ";
    $stmtup = $conn->prepare($queryup);
    $stmtup->bind_param("si", $nama, $idcat);
    $stmtup->execute();
    echo "<meta http-equiv='refresh' content='0; url=admincat.php'>";
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Kategori</title>
  <link rel="icon" href="../public/photo/ciG.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../public/css/style7.css">
  <style>
    .register-box {
      position: relative;
      width: 90%;
      max-width: 400px;
      padding: 2rem;
      background-color: rgba(236, 218, 183, 0.85);
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 2;
      text-align: center;
    }

    .register-box form {
      display: flex;
      flex-direction: column;
    }

    .register-box h2 {
      font-size: 1.5rem;
      color: black;
      margin-bottom: 1rem;
    }

    .register-box label {
      display: block;
      font-size: 14.4px;
      color: black;
      text-align: left;
      margin-bottom: 0.5rem;
    }

    .register-box input[type="text"],
    .register-box input[type="number"],
    .register-box select {
      margin-bottom: 1rem;
      width: 100%;
      padding: 10px;
      border: 1px solid #a94b4b;
      border-radius: 5px;
      background-color: rgba(80, 7, 18, 0.5);
      font-size: 14.4px;
      color: white;
    }

    .register-box button {
      width: 100%;
      padding: 10px;
      border: none;
      background-color: #a94b4b;
      color: #fff;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .register-box button:hover {
      background-color: rgba(80, 7, 18, 0.5);
    }

    /* Modal Styles */
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
  <div class="bg-yellow-200 sticky top-0 flex justify-between items-center p-4">
    <a href="admindash.php">
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
        $query = "SELECT * FROM kategori WHERE CONCAT(nama_kategori) LIKE '%$filtervalues%' ";
      } ?>
    </div>

    <!-- User and Dropdown Menu -->
    <div class="relative">
      <img src="../public/photo/pfp.png" class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile"
        id="profileIcon">
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg">
        <a href="admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product Page</a>
        <a href="discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount Page</a>
        <a href="../public/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </div>

  <!-- Title and Action Buttons -->
  <div class="flex justify-between items-center mx-16 mt-8 mb-4">
    <h1 class="text-2xl font-bold text-center">Kelola Kategori</h1>
    <button class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 flex items-center" id="openAddModal">
      <i class="fas fa-plus mr-2"></i> Tambah Kategori
    </button>
  </div>

  <!-- Modal -->
  <div class="modal" id="categoryModal">
    <div class="register-box">
      <h2 id="modalTitle">Tambah Kategori</h2>
      <form id="categoryForm" action="" method="POST">
        <input type="hidden" name="postype" id="postype">
        <input type="hidden" name="idcat" id="cat_id">
        <label for="cat_name">Nama Kategori</label>
        <input type="text" id="cat_name" name="nama" placeholder="Masukkan nama produk" required>
        <button type="submit" id="buttonTitle">Simpan</button>
      </form>
    </div>
  </div>


  <!-- Content Wrapper -->
  <div class="mx-16">
    <!-- Headers -->
    <div class="grid grid-cols-3 gap-4 text-center font-bold text-gray-700 mb-4">
      <div class="bg-gray-100 py-3 px-6 shadow-md">Kategori</div>
      <div class="bg-gray-100 py-3 px-6 shadow-md">Jumlah Barang</div>
      <div class="bg-gray-100 py-3 px-6 rounded-tr-lg rounded-br-lg shadow-md">Aksi</div>
    </div>

    <?php $result = $conn->query($query);
    $row = $result->fetch_assoc();
    while ($row != null) {
      ?>
      <!-- Product List -->
      <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-lg font-semibold"><?php echo $row['nama_kategori']; ?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <p class="text-sm">
            <?php
            $idcat = $row['ID_kategori'];
            $countq = "SELECT COUNT(*) AS totalcategory FROM (SELECT * FROM produk WHERE ID_kategori = ?) AS sub;";
            $stmtcountcat = $conn->prepare($countq);
            $stmtcountcat->bind_param("i", $idcat);
            $stmtcountcat->execute();
            $rescat = $stmtcountcat->get_result();
            $totalcat = $rescat->fetch_assoc();
            echo $totalcat['totalcategory'];
            ?>
          </p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md text-center">
          <button type="submit" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 edit-btn" 
          data-catid = <?php echo $row['ID_kategori']; ?>
          data-catname = <?php echo $row['nama_kategori']; ?>
          >
            <i class="fas fa-edit"></i> Edit
          </button>
          <form action="" method="POST" class="table-column">
            <input type="hidden" name="hapus" value="<?php echo $row['ID_kategori']; ?>">
            <input type="hidden" name="members" value="<?php echo $totalcat['totalcategory']; ?>">
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

    const categoryModal = document.getElementById('categoryModal');
    const openAddModal = document.getElementById('openAddModal');
    const categoryForm = document.getElementById('categoryForm');
    const catId = document.getElementById('cat_id');
    const catName = document.getElementById('cat_name');
    const modalTitle = document.getElementById('modalTitle');
    const buttonTitle = document.getElementById('buttonTitle');
    const postype = document.getElementById('postype');

    openAddModal.addEventListener('click', () => {
      modalTitle.innerText = 'Tambah Kategori';
      buttonTitle.innerText = 'TambahGan';
      categoryForm.reset();
      catId.value = '';
      postype.value = 'insert';
      categoryModal.style.display = 'flex';
    });
    
    window.addEventListener('click', (e) => {
      if (e.target === categoryModal) {
        categoryModal.style.display = 'none';
      }
    });
    
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        modalTitle.innerText = 'Edit Kategori';
        buttonTitle.innerText = 'Simpan';
        catId.value = button.dataset.catid;
        catName.value = button.dataset.catname;
        postype.value = 'update';
        categoryModal.style.display = 'flex';
      });
    });
  </script>
</body>

</html>