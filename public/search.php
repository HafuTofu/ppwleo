<?php
require "./sess.php";
if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
    header('Location: login.php');
}
$query = "SELECT * FROM (produk NATURAL JOIN kategori) WHERE statusproduk = 'available'";
$pallete = ['bg-orange-400', 'bg-teal-500', 'bg-yellow-400', 'bg-red-500'];

if (!empty($_POST)) {
    $iduser = $_POST['iduser'];
    $idprod = $_POST['idprod'];
    $harga = $_POST['harga'];
    $total_harga = $_POST['total_harga'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT * FROM cart WHERE (ID_produk = ? AND ID_user = ? AND checkorno = 'unchecked')");
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
                } ?>" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="./photo/search.png" width="20" height="20"
                        alt="Search"></button>
            </form>
            <?php if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM (produk NATURAL JOIN kategori) WHERE CONCAT(nama, nama_kategori, deskripsi) LIKE '%$filtervalues%' AND statusproduk = 'available'";
            } ?>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="./cart.php"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photouser/<?php echo $_SESSION['fotouser']; ?>"
                    class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="wishlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
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
            $palnum = ($row['ID_kategori'] - 1) % 4; ?>
            <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer"
                data-category="<?php echo $row["nama_kategori"]; ?>"
                onclick="window.location.href = 'detailprod.php?idprod=<?php echo $row['ID_produk']; ?>';">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48">
                <div class="p-4">
                    <span
                        class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum]; ?> rounded-full"><?php echo $row["nama_kategori"]; ?></span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"]; ?></h1>
                    <p class="text-sm font-semibold text-gray-600">Rp.
                        <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                    </p>
                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <form class="w-full mt-auto font-semibold text-center text-white bg-black hover:opacity-75 formatc"
                    method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="iduser" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="idprod" value="<?php echo $row['ID_produk']; ?>">
                    <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                    <input type="hidden" name="total_harga" value="<?php echo $row['harga']; ?>">
                    <button class="w-full py-3" type="submit">Add to Cart</button>
                </form>
            </div>
            <?php $row = $result->fetch_assoc();
        } ?>

        <!-- Dropdown Menu -->
        <script>
            function showPopup(message) {
                const popup = document.createElement('div');
                popup.innerHTML = message;
                popup.style.position = 'fixed';
                popup.style.bottom = '20px';
                popup.style.right = '20px';
                popup.style.background = '#4caf50';
                popup.style.color = 'white';
                popup.style.padding = '10px 20px';
                popup.style.borderRadius = '5px';
                popup.style.boxShadow = '0 2px 5px rgba(0,0,0,0.3)';
                popup.style.zIndex = '1000';

                document.body.appendChild(popup);

                setTimeout(() => {
                    popup.remove();
                }, 2000);
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Handle form submission with AJAX
                const forms = document.querySelectorAll('formatc');

                forms.forEach(form => {
                    form.addEventListener('submit', async function (event) {
                        event.preventDefault(); // Prevent default form submission

                        // Collect form data
                        const formData = new FormData(this);

                        try {
                            // Send data to server using fetch
                            const response = await fetch(this.action, {
                                method: 'POST',
                                body: formData,
                            });

                            if (response.ok) {
                                showPopup('Item added to cart!');
                            } else {
                                showPopup('Error adding item to cart.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showPopup('An error occurred.');
                        }
                    });
                });
            });

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

            // Carousel Script
            document.addEventListener('DOMContentLoaded', function () {
                const track = document.querySelector('.carousel-track');
                const slides = document.querySelectorAll('.carousel-slide');
                let index = 0;

                function moveCarousel() {
                    index = (index + 1) % slides.length;
                    track.style.transform = `translateX(-${index * 100}%)`;
                }

                setInterval(moveCarousel, 4000);
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