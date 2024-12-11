<?php
    require './sess.php';
    $iduser = $_SESSION['id'];
    $query = "SELECT * FROM wishlist NATURAL JOIN produk NATURAL JOIN kategori WHERE ID_user = ? ";
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="icon" href="./photo/ciG.png">
    <link rel="stylesheet" href="./css/style4.css">
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="dashboard.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
            <form action="" class="flex items-center w-full">
                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                    echo $_GET['search'];
                } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])){
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM wishlist NATURAL JOIN produk NATURAL JOIN kategori WHERE ID_user = ? AND CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
            } ?>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="#"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photo/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile"
                    id="profileIcon">
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="px-8 py-8">
        <!-- Title -->
        <h1 class="mb-6 text-2xl font-bold">All Wishlist</h1>

        <!-- Layout -->
        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar Filters -->
            <aside class="col-span-3 bg-white rounded-lg shadow p-4 h-[380px] overflow-y-auto">
                <!-- Category Filter -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold">Category</h2>
                    <ul class="mt-3 space-y-3">
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="all"
                                    class="text-indigo-500 category-filter focus:ring-indigo-400" checked />
                                <span>All Categories</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="gaming"
                                    class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Gaming</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="food"
                                    class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Food</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="clothes"
                                    class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Clothes</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="topup"
                                    class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Top-Up</span>
                            </label>
                        </li>
                    </ul>

                    <!-- Stock Filter -->
                    <div>
                        <h2 class="pt-4 text-lg font-semibold">Stock</h2>
                        <ul class="mt-3 space-y-3">
                            <li>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="stock" class="text-indigo-500 focus:ring-indigo-400" />
                                    <span>Available</span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="stock" class="text-indigo-500 focus:ring-indigo-400" />
                                    <span>No Available</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Wishlist Grid -->
            <section class="grid grid-cols-3 col-span-9 gap-6" id="productGrid">

                <?php $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $iduser);
            $stmt->execute();
            $result = $stmt->get_result(); $row = $result->fetch_assoc();
                while ($row != null) {
                    $palnum = $row['ID_kategori'] - 1 % 4; ?>
                <!-- Product Cards -->
                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card"
                    data-category="<?php echo $row["nama_kategori"];?>"
                    onclick="window.location.href = 'detailprod.php?idprod=<?php echo $row['ID_produk']; ?>';">
                    <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                    <div class="p-4">
                        <span
                            class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum];?> rounded-full"><?php echo $row["nama_kategori"];?></span>
                        <h1 class="text-lg font-semibold"><?php echo $row["nama"];?></h1>
                        <p class="text-sm font-semibold text-gray-600">Rp.
                            <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                        <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="iduser" value=<?php echo $_SESSION['id'];?>>
                        <input type="hidden" name="idprod" value=<?php echo $row['ID_produk'];?>>
                        <input type="hidden" name="harga" value=<?php echo $row['harga'] ;?>>
                        <input type="hidden" name="total_harga" value=<?php echo $row['harga'] ;?>>
                        <button type="submit"
                            class="w-full py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add
                            to Cart</button>
                    </form>
                </div>
                <?php $row = $result->fetch_assoc();
            } ?>
            </section>
        </div>
    </main>

    <script>
    // Dropdown Menu for Profile
    const profileIcon = document.getElementById('profileIcon');
    const dropdownMenu = document.getElementById('dropdownMenu');

    profileIcon.addEventListener('mouseover', function() {
        dropdownMenu.classList.remove('hidden');
    });

    profileIcon.addEventListener('mouseout', function() {
        dropdownMenu.classList.add('hidden');
    });

    // Category Filter Logic
    const categoryFilter = document.querySelectorAll('.category-filter');
    const productCards = document.querySelectorAll('.product-card');

    categoryFilter.forEach(filter => {
        filter.addEventListener('change', function() {
            const selectedCategory = this.value;
            productCards.forEach(card => {
                if (selectedCategory === 'all' || card.getAttribute('data-category').toLowerCase() ===
                    selectedCategory) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
    </script>
</body>

</html>