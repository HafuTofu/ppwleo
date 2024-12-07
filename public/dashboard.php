<?php
    require "./sess.php";
    
    $query = "SELECT * FROM produk NATURAL JOIN kategori";
    $result = $conn->query($query);
    $qcount = "SELECT COUNT(*) AS sum FROM produk";
    $assoc = $conn->query($qcount);
    $assocc = $assoc->fetch_assoc();
    $sum = $assocc["sum"];
    $pallete = ['bg-orange-400','bg-teal-500','bg-yellow-400','bg-red-500'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style1.css">
    <link rel="icon" href="./photo/ciG.png">
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="./dashboard.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>
        
        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
            <form action="" class="flex items-center w-full">
                <input type="text" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20" alt="Search"></button>
            </form>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="#"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photouser/<?php echo $_SESSION['fotouser'];?>" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">            
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="#wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex justify-center my-4 space-x-4">
        <button onclick="filterProducts('all')" class="px-6 py-2 text-white bg-gray-400 rounded-lg">All</button>
        <button onclick="filterProducts('gaming')" class="px-6 py-2 text-white bg-orange-400 rounded-lg">Gaming</button>
        <button onclick="filterProducts('food')" class="px-6 py-2 text-white bg-teal-500 rounded-lg">Food</button>
        <button onclick="filterProducts('clothes')" class="px-6 py-2 text-white bg-red-500 rounded-lg">Clothes</button>
        <button onclick="filterProducts('topup')" class="px-6 py-2 text-white bg-yellow-400 rounded-lg">Top-up</button>
    </div>

    <!-- Category Label -->
    <div id="category-label" class="my-4 text-xl font-bold text-center">Category: All</div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 px-10 py-8 md:grid-cols-2 lg:grid-cols-4">
    <?php $row= $result->fetch_assoc();
        while($row != null) { 
            $palnum = $row['ID_kategori'] - 1;?>
        <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card" data-category="<?php echo $row["nama_kategori"];?>">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum];?> rounded-full"><?php echo $row["nama_kategori"];?></span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"];?></h1>
                    <p class="text-sm text-gray-600">Rp. <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>
        <?php $row = $result->fetch_assoc();}?>

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
            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.classList.remove('hidden');
                } else {
                    product.classList.add('hidden');
                }
            });
            categoryLabel.textContent = `Category: ${category.charAt(0).toUpperCase() + category.slice(1)}`;
        }
    </script>
</body>
</html>