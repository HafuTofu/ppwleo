<?php
include '../connect.php';
$query = "SELECT * FROM produk NATURAL JOIN kategori";
$pallete = ['bg-orange-400', 'bg-teal-500', 'bg-yellow-400', 'bg-red-500'];
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
        <a href="dashboard1.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

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

        <!-- Login / Signup -->
        <div class="flex items-center space-x-4">
            <a href="login.php" class="font-semibold text-gray-700">LOGIN</a>
            <span class="text-gray-400">|</span>
            <a href="register.php" class="font-semibold text-gray-700">SIGN UP</a>
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

        <?php $result = $conn->query($query); $row = $result->fetch_assoc();
        while ($row != null) {
            $palnum = $row['ID_kategori'] - 1 % 4; ?>
            <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card"
                data-category="<?php echo $row["nama_kategori"]; ?>">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span
                        class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum]; ?> rounded-full"><?php echo $row["nama_kategori"]; ?></span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"]; ?></h1>
                    <p class="text-sm font-semibold text-gray-600">Rp.
                        <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <a href="login.php" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>
            <?php $row = $result->fetch_assoc();
        } ?>


        <!-- Filtering Products by Category -->
        <script>
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
                    if (category === 'all' || product.getAttribute('data-category') === category.toLowerCase()) {
                        product.style.display = 'flex'; // Show matching products
                    } else {
                        product.style.display = 'none'; // Hide non-matching products
                    }
                });
            }
        </script>

</body>

</html>