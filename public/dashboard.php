<?php
    require "./sess.php";
    if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
        header('Location: login.php');
    }
    $query = "SELECT * FROM produk NATURAL JOIN kategori";
    $pallete = ['bg-orange-400','bg-teal-500','bg-yellow-400','bg-red-500'];

    if (!empty($_POST)) {
        $iduser = $_POST['iduser'];
        $idprod = $_POST['idprod'];
        $harga = $_POST['harga'];
        $total_harga = $_POST['total_harga'];

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("SELECT * FROM cart WHERE ID_produk = ? AND ID_user = ?");
            $stmt->bind_param("ii", $idprod, $iduser);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowsearch = $result->fetch_assoc();

            if ($rowsearch != null) {
                $curqty = $rowsearch['qty'] + 1;
                $newtotal = $curqty * $harga;

                $stmt = $conn->prepare("UPDATE cart SET qty = ?, total_harga = ? WHERE ID_produk = ? AND ID_user = ?");
                $stmt->bind_param("idii", $curqty, $newtotal, $idprod, $iduser);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO cart (ID_user, ID_produk, qty, total_harga) VALUES (?, ?, 1, ?)");
                $stmt->bind_param("iid", $iduser, $idprod, $total_harga);
                $stmt->execute();
            }

            $conn->commit();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style1.css">
    <link rel="icon" href="./photo/ciG.png">
    <style>
        /* Carousel styles */
        .carousel {
            position: relative;
            width: 80%;
            height: 300px;
            margin: 0 auto;
            margin-top: 40px; /* Prevent overlapping with navbar */
            overflow: hidden;
            border: 4px solid #ddd;
            border-radius: 12px;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            min-width: 100%;
            height: 300px;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Ensure navbar is above other elements */
        .sticky {
            z-index: 1000;
        }
    </style>
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="./dashboard.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>
        
        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
        <form action="" class="flex items-center w-full">
                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                    echo $_GET['search'];
                } ?>"
                    placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])){
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM produk NATURAL JOIN kategori WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
            } ?>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="./cart.php"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photouser/<?php echo $_SESSION['fotouser'];?>" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">            
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="carousel my-6">
        <div class="carousel-track">
            <div class="carousel-slide">
                <img src="./photo/FM1.png" alt="Slide 1">
            </div>
            <div class="carousel-slide">
                <img src="./photo/FM2.png" alt="Slide 2">
            </div>
            <div class="carousel-slide">
                <img src="./photo/FM3.png" alt="Slide 3">
            </div>
            <div class="carousel-slide">
                <img src="./photo/FM4.png" alt="Slide 4">
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex justify-center my-4 space-x-4">
        <button onclick="filterProducts('all')" class="px-6 py-2 text-white bg-gray-400 rounded-lg">All</button>
        <button onclick="filterProducts('Gaming')" class="px-6 py-2 text-white bg-orange-400 rounded-lg">Gaming</button>
        <button onclick="filterProducts('Food')" class="px-6 py-2 text-white bg-teal-500 rounded-lg">Food</button>
        <button onclick="filterProducts('Clothes')" class="px-6 py-2 text-white bg-red-500 rounded-lg">Clothes</button>
        <button onclick="filterProducts('Topup')" class="px-6 py-2 text-white bg-yellow-400 rounded-lg">Top-up</button>
    </div>

    <!-- Category Label -->
    <div id="category-label" class="my-4 text-xl font-bold text-center">Category: All</div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 px-10 py-8 md:grid-cols-2 lg:grid-cols-4">
    <?php $result = $conn->query($query); $row= $result->fetch_assoc();
        while($row != null) { 
            $palnum = ($row['ID_kategori'] - 1) % 4;?>
        <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer" data-category="<?php echo $row["nama_kategori"];?>" onclick="window.location.href = 'detailprod.php?idprod=<?php echo $row['ID_produk']; ?>';">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum];?> rounded-full"><?php echo $row["nama_kategori"];?></span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"];?></h1>
                    <p class="text-sm font-semibold text-gray-600">Rp. <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <form method="POST" >
                    <input type="hidden" name = "iduser" value = <?php echo $_SESSION['id'];?>>
                    <input type="hidden" name = "idprod" value = <?php echo $row['ID_produk'];?>>
                    <input type="hidden" name = "harga" value = <?php echo $row['harga'] ;?> >
                    <input type="hidden" name = "total_harga" value = <?php echo $row['harga'] ;?>>
                    <button type="submit" class="w-full py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</button>
                </form>
            </div>
        <?php $row = $result->fetch_assoc();} ?>

    <!-- Dropdown Menu -->
    <script>
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
        // Function to filter products by category
        function filterProducts(category) {
                const products = document.querySelectorAll('.product-card');
                const categoryLabel = document.getElementById('category-label');

                // Update the category label text
                const categoryMap = {
                    all: "All",
                    gaming: "Gaming",
                    food: "Food",
                    clothes: "Clothes",
                    topup: "Top-up"
                };
                categoryLabel.textContent = `Category: ${categoryMap[category.toLowerCase()] || "Unknown"}`;

                // Filter products by category
                products.forEach(product => {
                    if (category === 'all' || product.getAttribute('data-category') === category) {
                        product.style.display = 'flex'; // Show matching products
                    } else {
                        product.style.display = 'none'; // Hide non-matching products
                    }
                });
            }
    </script>
</body>
</html>