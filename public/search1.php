<?php
include '../connect.php';
$query = "SELECT * FROM ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori) WHERE statusproduk = 'available'";
$pallete = ['bg-orange-400', 'bg-teal-500', 'bg-yellow-400', 'bg-red-500'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/dashtry.css">
    <link rel="icon" href="./photo/ciG.png">
    </style>
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
        <a href="dashboard1.php"><img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
            <form action="" class="flex items-center w-full">
                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                    echo $_GET['search'];
                } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori) WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' ";
            } ?>
        </div>

        <!-- Login / Signup -->
        <div class="flex items-center space-x-4">
            <a href="login.php" class="font-semibold text-gray-700">LOGIN</a>
            <span class="text-gray-400">|</span>
            <a href="register.php" class="font-semibold text-gray-700">SIGN UP</a>
        </div>
    </header>

    <!-- Categories -->
    <div class="flex justify-center my-4 space-x-4" id="category-buttons">
        <button class="px-6 py-2 text-white bg-gray-400 rounded-lg category-button" data-category="all">All</button>
        <?php
        $categoriesResult = $conn->query("SELECT nama_kategori FROM kategori");
        $categories = [];
        $idx = 0;
        while ($categoryRow = $categoriesResult->fetch_assoc()) {
            $categories[] = $categoryRow['nama_kategori'];
            $categoryName = $categoryRow['nama_kategori'];
            $categoryColor = $pallete[($idx % 4)];
            $idx++;
            echo "<button class='px-6 py-2 text-white {$categoryColor} rounded-lg category-button' data-category='{$categoryName}'>{$categoryName}</button>";
        }
        ?>
        <div>
            <select id="sortDropdown" class="px-6 py-2 text-white bg-blue-500 rounded-lg cursor-pointer">
                <option value="most_relevant">Most Relevant</option>
                <option value="highest_price">Highest Price</option>
                <option value="lowest_price">Lowest Price</option>
                <option value="newest">Newest</option>
            </select>
        </div>
    </div>
    <script>
        const categories = <?php echo json_encode($categories); ?>;
    </script>

    <!-- Category Label -->
    <div id="category-label" class="my-4 text-xl font-bold text-center">Category: All</div>
    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 px-10 py-8 md:grid-cols-2 lg:grid-cols-4">

        <?php $result = $conn->query($query);
        $row = $result->fetch_assoc();
        while ($row != null) {
            $palnum = $row['ID_kategori'] - 1 % 4; ?>
            <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer"
                onclick="window.location.href = 'detailprod1.php?idprod=<?php echo $row['ID_produk']; ?>';"
                data-category="<?php echo $row["nama_kategori"]; ?>">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span
                        class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum]; ?> rounded-full"><?php echo $row["nama_kategori"]; ?></span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"]; ?></h1>
                    <p class="text-sm font-semibold text-gray-600">Rp.
                        <?php echo number_format($row['harga'], 2, ',', '.'); ?>
                    </p>
                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <a href="login.php" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add
                    to Cart</a>
            </div>
            <?php $row = $result->fetch_assoc();
        } ?>


        <!-- Filtering Products by Category -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const categoryLabel = document.getElementById('category-label');
                const products = document.querySelectorAll('.product-card');

                const categoryMap = categories.reduce((map, category) => {
                    map[category.toLowerCase()] = category;
                    return map;
                }, { all: 'All' });

                // Function to filter products
                function filterProducts(category) {
                    const categoryName = categoryMap[category.toLowerCase()] || 'Unknown';
                    categoryLabel.textContent = `Category: ${categoryName}`;

                    products.forEach(product => {
                        if (category === 'all' || product.getAttribute('data-category').toLowerCase() === category.toLowerCase()) {
                            product.style.display = 'flex';
                        } else {
                            product.style.display = 'none';
                        }
                    });

                    if (category !== 'all' && [...products].every(product => product.style.display === 'none')) {
                        categoryLabel.textContent += ' (No products available)';
                    }
                }

                const categoryButtons = document.querySelectorAll('.category-button');
                categoryButtons.forEach(button => {
                    button.addEventListener('click', () => filterProducts(button.getAttribute('data-category')));
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const productsContainer = document.querySelector('.grid'); // Product grid container
                const products = Array.from(document.querySelectorAll('.product-card')); // Convert NodeList to Array
                const sortDropdown = document.getElementById('sortDropdown');

                sortDropdown.addEventListener('change', function () {
                    const selectedOption = this.value; // Get selected sort option

                    // Sorting logic
                    let sortedProducts = [...products]; // Clone products array

                    switch (selectedOption) {
                        case 'highest_price':
                            sortedProducts.sort((a, b) => {
                                const priceA = parseFloat(a.querySelector('p.text-sm.font-semibold').textContent.replace(/[^\d]/g, ''));
                                const priceB = parseFloat(b.querySelector('p.text-sm.font-semibold').textContent.replace(/[^\d]/g, ''));
                                return priceB - priceA; // Descending order
                            });
                            break;
                        case 'lowest_price':
                            sortedProducts.sort((a, b) => {
                                const priceA = parseFloat(a.querySelector('p.text-sm.font-semibold').textContent.replace(/[^\d]/g, ''));
                                const priceB = parseFloat(b.querySelector('p.text-sm.font-semibold').textContent.replace(/[^\d]/g, ''));
                                return priceA - priceB; // Ascending order
                            });
                            break;
                        case 'newest':
                            sortedProducts.sort((a, b) => {
                                const idA = parseInt(a.querySelector('input[name="idprod"]').value, 10);
                                const idB = parseInt(b.querySelector('input[name="idprod"]').value, 10);
                                return idB - idA; // Newest first
                            });
                            break;
                        default: // Default: Most Relevant (original order)
                            sortedProducts = [...products]; // Reset to original order
                            break;
                    }

                    // Clear the container and append sorted products
                    productsContainer.innerHTML = '';
                    sortedProducts.forEach(product => {
                        productsContainer.appendChild(product);
                    });
                });
            });
        </script>

</body>

</html>