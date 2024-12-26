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
    $queryhapus = "DELETE FROM kategori WHERE ID_kategori = $catid";
    mysqli_query($conn, $queryhapus);
    echo "<meta http-equiv='refresh' content='1; url=admincat.php'>";
}

if (!empty($_POST['insert'])) {
    try {
        $nama = ucfirst($_POST['nama']);
        $query = "INSERT INTO kategori (nama_kategori) 
                VALUES ('$nama')";
        mysqli_query($conn, $query);
        header("Location: admincat.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else if (!empty($_POST['update'])) {
    try {
        $nama = ucfirst($_POST['nama']);
        $id_cat = $_POST['idcat'];
        $queryup = "UPDATE kategori SET nama_kategori = ? WHERE ID_kategori = ? ";
        $stmtup = $conn->prepare($queryup);
        $stmtup->bind_param("si", $nama, $id_cat);
        $stmtup->execute();
        header("Location: admincat.php");
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
            background-color: rgba(236, 218, 183, 1);
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
    <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="atmindashboard.php">
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
            <img src="../public/photouser/<?php echo $_SESSION['fotouser']; ?>"
                class="w-12 h-12 mr-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
            <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Admin
                    Dashboard</a>
                <a href="../atmin/admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
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
    </div>

    <!-- Title and Action Buttons -->
    <div class="flex items-center justify-between mx-16 mt-8 mb-4">
        <h1 class="text-2xl font-bold text-center">Kelola Kategori</h1>
        <button class="flex items-center px-6 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600"
            id="openAddCatModal">
            <i class="mr-2 fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <!-- add/edit cat -->
    <div class="modal" id="addCatModal">
        <div class="register-box">
            <h2 id="modalTitle">Add Category</h2>
            <form id="addCatForm" action="" method="POST">
                <input type="hidden" id="cat_id" name="idcat">
                <label for="cat_name">Category Name</label>
                <input type="text" id="cat_name" name="nama" placeholder="Enter Category Name" required>
                <input type="hidden" id="addorupdate">
                <button type="submit" id="btnpress">Tambahkan</button>
            </form>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="mx-16">
        <!-- Headers -->
        <div class="grid grid-cols-3 gap-4 mb-4 font-bold text-center text-gray-700">
            <div class="px-6 py-3 bg-gray-100 shadow-md">Kategori</div>
            <div class="px-6 py-3 bg-gray-100 shadow-md">Jumlah Barang</div>
            <div class="px-6 py-3 bg-gray-100 rounded-tr-lg rounded-br-lg shadow-md">Aksi</div>
        </div>

        <?php $result = $conn->query($query);
        $row = $result->fetch_assoc();
        while ($row != null) {
            ?>
            <!-- Product List -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="p-4 text-center bg-white rounded-lg shadow-md">
                    <p class="text-lg font-semibold"><?php echo $row['nama_kategori']; ?></p>
                </div>
                <div class="p-4 text-center bg-white rounded-lg shadow-md">
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
                <div class="p-4 text-center bg-white rounded-lg shadow-md">
                    <button type="button" class="px-4 py-1 text-white bg-blue-500 rounded-lg hover:bg-blue-600 edit-cat-btn"
                        data-cat-id="<?php echo $row['ID_kategori']; ?>"
                        data-cat-name="<?php echo $row['nama_kategori']; ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    <form action="" method="POST" class="table-column">
                        <input type="hidden" name="hapus" value="<?php echo $row['ID_kategori']; ?>">
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
        document.addEventListener('DOMContentLoaded', function () {
            const profileIcon = document.getElementById('profileIcon');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const addCatModal = document.getElementById('addCatModal');
            const openAddCatModal = document.getElementById('openAddCatModal');
            const addCatForm = document.getElementById('addCatForm');
            const addorupdate = document.getElementById('addorupdate');
            const catname = document.getElementById('cat_name');
            const modalTitle = document.getElementById('modalTitle');
            const modalBtn = document.getElementById('btnpress');
            const catIdField = document.getElementById('cat_id'); // Hidden input for ID

            // Dropdown menu behavior
            profileIcon.addEventListener('mouseenter', () => {
                dropdownMenu.classList.remove('hidden');
            });

            profileIcon.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!dropdownMenu.matches(':hover')) {
                        dropdownMenu.classList.add('hidden');
                    }
                }, 100);
            });

            dropdownMenu.addEventListener('mouseleave', () => {
                dropdownMenu.classList.add('hidden');
            });

            // Open Add Category Modal
            openAddCatModal.addEventListener('click', () => {
                modalTitle.innerText = 'Add Category';
                modalBtn.innerText = 'Add';
                addorupdate.name = 'insert'; // Ensure correct field name for form submission
                addorupdate.value = 'insert';
                catIdField.value = ''; // Clear ID field
                catname.value = ''; // Clear name field
                addCatModal.classList.add('show');
            });

            const editButtons = document.querySelectorAll('.edit-cat-btn');
            editButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const catName = button.getAttribute('data-cat-name'); // Get category name
                    const catId = button.getAttribute('data-cat-id'); // Get category name

                    modalTitle.innerText = 'Edit Category';
                    modalBtn.innerText = 'Save';
                    addorupdate.name = 'update'; // Ensure correct field name for form submission
                    addorupdate.value = 'update';
                    catIdField.value = catId;
                    catname.value = catName; // Set name in input field
                    addCatModal.classList.add('show');
                });
            });

            // Close Modal on outside click
            window.addEventListener('click', (e) => {
                if (e.target === addCatModal) {
                    addCatModal.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>