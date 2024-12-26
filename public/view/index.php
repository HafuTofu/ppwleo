<?php
include '../connect.php';
if(isset($_SESSION['login']) && $_SESSION['login'] === 'trueguess'){
    header('Location: dashboard.php');        
}
$query = "SELECT * FROM produk NATURAL JOIN kategori WHERE statusproduk = 'available'";
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

        .sticky {
            z-index: 1000;
        }
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
                } ?>"
                    placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])){
                $searched = $_GET['search'];
                header("Location: search1.php?search={$searched}");
            } ?>
        </div>

        <!-- Login / Signup -->
        <div class="flex items-center space-x-4">
            <a href="login.php" class="font-semibold text-gray-700">LOGIN</a>
            <span class="text-gray-400">|</span>
            <a href="register.php" class="font-semibold text-gray-700">SIGN UP</a>
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

        <!-- New Arrivals Section -->
        <div class="px-10 my-6">
        <h2 class="text-4xl font-bold text-left mb-6">New Arrivals</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Product 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img src="./photo/JACKET.png" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-red-500 rounded-full">Clothes</span>
                    <h1 class="text-lg font-semibold">T1 Worlds Jacket 2024</h1>
                    <p class="text-sm font-semibold text-gray-600">Rp. 1.700.000</p>
                    <p class="text-sm text-gray-600">The T1 White Jacket White is not simply a form of clothing that one puts on; it is a proclamation of passion.</p>
                </div>
            <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>    

            <!-- Product 2 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img src="./photo/hoodie.jpg" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-red-500 rounded-full">Clothes</span>
                    <h1 class="text-lg font-semibold">McLaren Hoodie</h1>
                    <p class="text-sm font-semibold text-gray-600">Rp. 2.700.000</p>
                    <p class="text-sm text-gray-600">The McLaren Hoodie is not simply a form of clothing that one puts on; it is a proclamation of passion</p>
                </div>
            <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>

            <!-- Product 3 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img src="./photo/hoodie.jpg" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-red-500 rounded-full">Clothes</span>
                    <h1 class="text-lg font-semibold">McLaren Hoodie</h1>
                    <p class="text-sm font-semibold text-gray-600">Rp. 2.700.000</p>
                    <p class="text-sm text-gray-600">The McLaren Hoodie is not simply a form of clothing that one puts on; it is a proclamation of passion</p>
                </div>
                <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>

            <!-- Product 4 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img src="./photo/hoodie.jpg" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white bg-red-500 rounded-full">Clothes</span>
                    <h1 class="text-lg font-semibold">McLaren Hoodie</h1>
                    <p class="text-sm font-semibold text-gray-600">Rp. 2.700.000</p>
                    <p class="text-sm text-gray-600">The McLaren Hoodie is not simply a form of clothing that one puts on; it is a proclamation of passion</p>
                </div>
                <a href="#checkout" class="py-3 mt-auto font-semibold text-center text-white bg-black hover:opacity-75">Add to Cart</a>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex justify-center my-4 space-x-4" id="category-buttons">
        <button class="px-6 py-2 text-white bg-gray-400 rounded-lg category-button" data-category="all">All</button>
        <?php
        $categoriesResult = $conn->query("SELECT nama_kategori FROM kategori");
        $categories = [];
        $idx=0;
        while ($categoryRow = $categoriesResult->fetch_assoc()) {
            $categories[] = $categoryRow['nama_kategori'];
            $categoryName = $categoryRow['nama_kategori'];
            $categoryColor = $pallete[($idx % 4)];
            $idx ++;
            echo "<button class='px-6 py-2 text-white {$categoryColor} rounded-lg category-button' data-category='{$categoryName}'>{$categoryName}</button>";
        }
        ?>
    </div>
    <script>
        const categories = <?php echo json_encode($categories); ?>;
    </script>

    <!-- Category Label -->
    <div id="category-label" class="my-4 text-2xl font-bold text-center">Category: All</div>
    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 px-10 py-8 md:grid-cols-2 lg:grid-cols-4">

        <?php $result = $conn->query($query); $row = $result->fetch_assoc();
        while ($row != null) {
            $palnum = ($row['ID_kategori'] - 1) % 4; ?>
            <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer" onclick="window.location.href = 'detailprod1.php?idprod=<?php echo $row['ID_produk']; ?>';"
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
            const track = document.querySelector('.carousel-track');
            const slides = document.querySelectorAll('.carousel-slide');
            let index = 0;

            function moveCarousel() {
                index = (index + 1) % slides.length;
                track.style.transform = `translateX(-${index * 100}%)`;
            }

            setInterval(moveCarousel, 3000);
        });
        </script>

</body>

</html>