<?php
    include '../connect.php';
    require_once 'sess.php';
    $iduser = $_SESSION['id'];
    $query = "SELECT * FROM wishlist NATURAL JOIN produk WHERE ID_user = ? ";
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
                } ?>"
                    placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])){
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM wishlist NATURAL JOIN kategori WHERE ID_user = ? AND CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
            } ?>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="#"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photo/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">            
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="#profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="#settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Settings</a>
                    <a href="#wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="#Help&Support" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Help & Support</a>
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
                                <input type="radio" name="category" value="all" class="text-indigo-500 category-filter focus:ring-indigo-400" checked />
                                <span>All Categories</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="gaming" class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Gaming</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="food" class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Food</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="clothes" class="text-indigo-500 category-filter focus:ring-indigo-400" />
                                <span>Clothes</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="topup" class="text-indigo-500 category-filter focus:ring-indigo-400" />
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
                
                <!-- Product Cards -->
                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="food">
                    <img src="./photo/Anggur.jpg" alt="Product 1" class="object-cover w-full h-48">
                    <div class="p-4">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-teal-500 rounded-full">Food</span>
                        <h1 class="text-lg font-semibold">Anggur Maharaja</h1>
                        <p class="text-sm text-gray-600">Rp. 17,000.00</p>
                        <p class="text-sm text-gray-600">Anggur bukan sembarangan untuk orang-orang, hanya orang terpilih yang dapat menikmatinya.</p>
                    </div>
                    <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
                </div>

                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="clothes">
                    <img src="./photo/JACKET.png" alt="Product 2" class="object-cover w-full h-48">
                    <a href="detailprod.html" class="flex-grow block transition hover:bg-gray-100">
                        <div class="h-full p-4">
                            <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-red-500 rounded-full">Clothes</span>
                            <h1 class="text-lg font-semibold">T1 Worlds Jacket 2024</h1>
                            <p class="text-sm text-gray-600">Rp. 1.700.000.00</p>
                            <p class="text-sm text-gray-600">The T1 White Jacket White is not simply a form of clothing that one puts on; it is a proclamation of passion.</p>
                        </div>
                    </a>
                    <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
                </div>

                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="gaming">
                    <img src="./photo/superlight.jpeg" alt="Product 3" class="object-cover w-full h-48">
                    <div class="p-4">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-orange-400 rounded-full">Gaming</span>
                        <h1 class="text-lg font-semibold">Logitech Superlight 2</h1>
                        <p class="text-sm text-gray-600">Rp. 2.220,000.00</p>
                        <p class="text-sm text-gray-600">Fast and Precise Wireless Gaming Mouse: A pro gaming icon—now faster and more precise; it is designed in collaboration with the world's leading esports pros and engineered to win</p>
                    </div>
                    <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
                </div>

                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="topup">
                    <img src="./photo/TOP UP.jpg" alt="Product 4" class="object-cover w-full h-48">
                    <div class="p-4">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-yellow-400 rounded-full">Top Up</span>
                        <h1 class="text-lg font-semibold">Clash Royale Top Up</h1>
                        <p class="text-sm text-gray-600">Rp. 20,000.00</p>
                        <p class="text-sm text-gray-600">Bermainlah bersama kamerad-kamerad anda, dengan semangat juang ksatria ganyang semuanya!</p>
                    </div>
                    <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
                </div>

                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="gaming">
                    <img src="./photo/monitor.jpg" alt="Product 5" class="object-cover w-full h-48">
                    <div class="p-4">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-orange-400 rounded-full">Gaming</span>
                        <h1 class="text-lg font-semibold">Acer Predator 360Hz Monitor</h1>
                        <p class="text-sm text-gray-600">Rp. 8,000,000.00</p>
                        <p class="text-sm text-gray-600">Play games like never before with Acer's best Predator series 360Hz monitor, optimized for competitive gamers.</p>
                    </div>
                    <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
                </div>

            </section>
        </div>
    </main>

    <script>
        // Dropdown Menu for Profile
        const profileIcon = document.getElementById('profileIcon');
        const dropdownMenu = document.getElementById('dropdownMenu');

        profileIcon.addEventListener('mouseover', function () {
            dropdownMenu.classList.remove('hidden');
        });

        profileIcon.addEventListener('mouseout', function () {
            dropdownMenu.classList.add('hidden');
        });

        // Category Filter Logic
        const categoryFilter = document.querySelectorAll('.category-filter');
        const productCards = document.querySelectorAll('.product-card');

        categoryFilter.forEach(filter => {
            filter.addEventListener('change', function () {
                const selectedCategory = this.value;
                productCards.forEach(card => {
                    if (selectedCategory === 'all' || card.getAttribute('data-category') === selectedCategory) {
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