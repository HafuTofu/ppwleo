<?php
require "../public/sess.php";
if (!isset($_SESSION['login']) || $_SESSION['login'] === 'false') {
    header('Location: login.php');
}
$query = "SELECT * FROM ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori) WHERE statusproduk = 'available'";
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
    <link rel="stylesheet" href="./css/dashtry.css">
    <link rel="icon" href="./photo/ciG.png">
    <style>
        .carousel {
            position: relative;
            width: 80%;
            height: 300px;
            margin: 0 auto;
            margin-top: 40px;
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
                $searched = urlencode($_GET['search']);
                header("Location: search.php?search={$searched}");
                exit();
            } ?>
        </div>

        <div class="flex items-center mr-6 space-x-6">
            <a href="./cart.php"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photouser/<?php echo $_SESSION['fotouser']; ?>"
                    class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <?php if ($_SESSION['login'] === 'trueadmin') { ?>
                        <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                            Admin Dashboard</a>
                        <a href="../atmin/admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
                            Managing Page</a>
                        <a href="../atmin/discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
                            Managing Page</a>
                        <a href="../atmin/orderadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                            Managing Page</a>
                        <a href="../atmin/usercontroller.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User
                            Managing Page</a>
                        <a href="../atmin/admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
                            Managing Page</a>
                    <?php } ?>
                    <a href="../public/profilepage.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="../public/wishlist.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="../public/orderlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                        List</a>
                    <a href="../public/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="carousel my-6">
        <div class="carousel-track">
            <div class="carousel-slide">
                <img src="./photo/FM4.png" alt="Duplicate Last Slide"> <!-- Duplicate of last -->
            </div>
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
            <div class="carousel-slide">
                <img src="./photo/FM1.png" alt="Duplicate First Slide"> <!-- Duplicate of first -->
            </div>
        </div>
    </div>

    <!-- New Arrivals Section -->
    <div class="px-10 my-6">
        <h2 class="text-4xl font-bold text-left mb-6">New Arrivals</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <?php $queryNew = "SELECT * FROM ((produk LEFT JOIN discounts ON produk.ID_discount = discounts.ID_discount) NATURAL JOIN kategori) WHERE statusproduk = 'available' ORDER BY ID_produk DESC LIMIT 4";
            $stmtNew = $conn->query($queryNew);
            while ($rowNew = $stmtNew->fetch_assoc()) {
                $palnum = ($rowNew['ID_kategori'] - 1) % 4; ?>
                <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer"
                    data-category="<?php echo $rowNew['nama_kategori']; ?>">
                    <img src="./products/<?php echo $rowNew["foto"]; ?>" alt="Product" class="object-cover w-full h-48"
                        onclick="window.location.href = 'detailprod.php?idprod=<?php echo $rowNew['ID_produk']; ?>';">
                    <div class="p-4">
                        <span
                            class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum]; ?> rounded-full">
                            <?php echo $rowNew["nama_kategori"]; ?>
                        </span>
                        <h1 class="text-lg font-semibold"><?php echo $rowNew["nama"]; ?></h1>
                        <?php if ($rowNew['ID_discount'] == 0) { ?>
                            <p class="text-sm font-semibold text-gray-600">Rp.
                                <?php echo number_format($rowNew['harga'], 0, ',', '.'); ?>
                            </p>
                        <?php } else { ?>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-semibold text-gray-600 line-through">Rp.
                                    <?php echo number_format($rowNew['harga'], 0, ',', '.'); ?>
                                </p>
                                <p class="text-sm font-semibold text-red-500">Rp.
                                    <?php echo number_format($rowNew['discountprice'], 0, ',', '.'); ?>
                                </p>
                            </div>
                        <?php } ?>

                        <p class="text-sm text-gray-600"><?php echo $rowNew['deskripsi']; ?></p>
                    </div>
                    <form class="w-full mt-auto font-semibold text-center text-white bg-black hover:opacity-75 formatc"
                        method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="iduser" value="<?php echo $_SESSION['id']; ?>">
                        <input type="hidden" name="idprod" value="<?php echo $rowNew['ID_produk']; ?>">
                        <input type="hidden" name="harga" value="<?php echo $rowNew['harga']; ?>">
                        <input type="hidden" name="total_harga" value="<?php echo $rowNew['harga']; ?>">
                        <button class="w-full py-3" type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex justify-center my-4 space-x-4" id="category-buttons">
        <button class="px-6 py-2 text-white bg-gray-400 rounded-lg category-button" data-category="All">All</button>
        <?php
        $categoriesResult = $conn->query("SELECT * FROM kategori");
        $categories = [];
        while ($categoryRow = $categoriesResult->fetch_assoc()) {
            $categories[] = $categoryRow['nama_kategori'];
            $categoryName = $categoryRow['nama_kategori'];
            $categoryColor = $pallete[(($categoryRow['ID_kategori'] - 1) % 4)];
            echo "<button class='px-6 py-2 text-white {$categoryColor} rounded-lg category-button' data-category='{$categoryName}'>{$categoryName}</button>";
        }
        ?>
    </div>

    <!-- Category Label -->
    <div id="category-label" class="my-4 text-2xl font-bold text-center">Category: All</div>
    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 px-10 py-8 md:grid-cols-2 lg:grid-cols-4">

        <?php $result = $conn->query($query);
        $row = $result->fetch_assoc();
        while ($row != null) {
            $palnum = ($row['ID_kategori'] - 1) % 4; ?>
            <!-- Product Card 1 -->
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md product-card cursor-pointer"
                data-category="<?php echo $row["nama_kategori"]; ?>">
                <img src="./products/<?php echo $row["foto"]; ?>" alt="Product" class="object-cover w-full h-48"
                    onclick="window.location.href = 'detailprod.php?idprod=<?php echo $row['ID_produk']; ?>';">
                <div class="p-4">
                    <span
                        class="inline-block px-3 py-1 mb-2 text-xs font-semibold text-white <?php echo $pallete[$palnum]; ?> rounded-full">
                        <?php echo $row["nama_kategori"]; ?>
                    </span>
                    <h1 class="text-lg font-semibold"><?php echo $row["nama"]; ?></h1>
                    <?php if ($row['ID_discount'] == 0) { ?>
                        <p class="text-sm font-semibold text-gray-600">Rp.
                            <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                        </p>
                    <?php } else { ?>
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-semibold text-gray-600 line-through">Rp.
                                <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </p>
                            <p class="text-sm font-semibold text-red-500">Rp.
                                <?php echo number_format($row['discountprice'], 0, ',', '.'); ?>
                            </p>
                        </div>
                    <?php } ?>

                    <p class="text-sm text-gray-600"><?php echo $row['deskripsi']; ?></p>
                </div>
                <form class="w-full mt-auto font-semibold text-center text-white bg-black hover:opacity-75 formatc"
                    method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="iduser" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="idprod" value="<?php echo $row['ID_produk']; ?>">
                    <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                    <input type="hidden" name="total_harga" value="<?php echo $row['harga']; ?>">
                    <button class="w-full py-3 mt-auto" type="submit">Add to Cart</button>
                </form>

            </div>
            <?php $row = $result->fetch_assoc();
        } ?>

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
                const forms = document.querySelectorAll('.formatc');

                forms.forEach(form => {
                    form.addEventListener('submit', async function (event) {
                        event.preventDefault();

                        const formData = new FormData(this);

                        try {
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

                const categoryLabel = document.getElementById('category-label');
                const products = document.querySelectorAll('.product-card');
                const categoryButtons = document.querySelectorAll('.category-button');

                const categoryMap = <?php echo json_encode($categories); ?>;

                categoryButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const selectedCategory = button.getAttribute('data-category');
                        categoryLabel.textContent = `Category: ${selectedCategory}`;

                        products.forEach(product => {
                            const productCategory = product.getAttribute('data-category');
                            product.style.display = (selectedCategory === 'All' || productCategory === selectedCategory) ? 'block' : 'none';
                        });
                    });
                });

                const track = document.querySelector('.carousel-track');
                const slides = document.querySelectorAll('.carousel-slide');
                let index = 1;
                const slideWidth = slides[0].clientWidth;

                function moveCarousel() {
                    index++;

                    track.style.transition = 'transform 0.5s ease-in-out';
                    track.style.transform = `translateX(-${index * slideWidth}px)`;

                    setTimeout(() => {
                        if (index >= slides.length - 1) {
                            index = 1;
                            track.style.transition = 'none';
                            track.style.transform = `translateX(-${index * slideWidth}px)`;
                        }
                    }, 500);
                }

                setInterval(moveCarousel, 4000);

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

                    setTimeout(() => popup.remove(), 2000);
                }
            });

        </script>
</body>

</html>