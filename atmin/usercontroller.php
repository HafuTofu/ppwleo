<?php
require '../public/sess.php';
$error = '';
$userQ = "SELECT * FROM userdata WHERE Username != 'admin'";
$resU = $conn->query($userQ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="icon" href="../public/photo/ciG.png">
    <link rel="stylesheet" href="../public/css/style12.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 flex items-center justify-between p-4 bg-yellow-200">
        <a href="./admindash.php"><img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

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
                $query = "SELECT * FROM userdata WHERE CONCAT(Username,Email,Password,gender,address,fullname,phone) LIKE '%$filtervalues%' ";
            } ?>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <div class="relative">
                <img src="../public/photouser/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile"
                    id="profileIcon">
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="../atmin/atmindashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Admin
                        Dashboard</a>
                    <a href="../atmin/admincat.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
                        Managing Page</a>
                    <a href="../atmin/admindash.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
                        Managing Page</a>
                    <a href="../atmin/discount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
                        Managing Page</a>
                    <a href="../atmin/orderadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                        Managing Page</a>
                    <a href="../public/profilepage.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="../public/dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                        User Dashboard</a>
                    <a href="../public/wishlist.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="../public/orderlist.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                        List</a>
                    <a href="../public/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Customer List -->
    <div class="container px-6 py-6 mx-auto">
        <h2 class="mb-6 text-3xl font-bold text-black">Customer List</h2>

        <!-- Table Container -->
        <div class="overflow-hidden rounded-lg">
            <table class="min-w-full bg-white" id="customerTable">
                <!-- Table Header -->
                <thead class="text-sm leading-normal text-black uppercase bg-yellow-400">
                    <tr>
                        <th class="px-6 py-3 text-center">Name</th>
                        <th class="px-6 py-3 text-center">Address</th>
                        <th class="px-6 py-3 text-center">Email</th>
                        <th class="px-6 py-3 text-center">Phone</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <!-- Table Rows -->
                <tbody class="text-sm font-light text-gray-600">
                    <?php
                    while ($rowU = $resU->fetch_assoc()) {
                        ?>
                        <!-- Row 1 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3 font-bold text-center"><?php echo $rowU['Username'] ;?></td>
                            <td class="px-6 py-3 font-bold text-center"><?php echo $rowU['address'] ;?></td>
                            <td class="px-6 py-3 font-bold text-center"><?php echo htmlspecialchars($rowU['Email']) ;?></td>
                            <td class="px-6 py-3 font-bold text-center"><?php echo htmlspecialchars($rowU['phone']) ;?></td>
                            <td class="px-6 py-3 space-x-2 font-bold text-center">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="toggleButton(this)"
                                        class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-teal-700 bg-teal-200 rounded-lg toggle-btn hover:bg-teal-300">
                                        <i class="fa-solid fa-unlock"></i> <span>Unban</span>
                                    </button>

                                    <button onclick="editRow(this)"
                                        class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                        <i class="fas fa-edit"></i> <span>Edit</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- batas bawah -->
                    <?php } ?>
                    
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleButton(button) {
            if (button.innerText.includes("Unban")) {
                button.innerHTML = `<i class="mr-2 fa-solid fa-lock"></i> Ban`;
                button.classList.remove("text-teal-700", "bg-teal-200", "hover:bg-teal-300");
                button.classList.add("text-white", "bg-red-500", "hover:bg-red-600");
            } else {
                button.innerHTML = `<i class="mr-2 fa-solid fa-unlock"></i> Unban`;
                button.classList.remove("text-white", "bg-red-500", "hover:bg-red-600");
                button.classList.add("text-teal-700", "bg-teal-200", "hover:bg-teal-300");
            }
        }

        function editRow(button) {
            const row = button.closest('tr'); 
            const cells = row.querySelectorAll('td'); 

            cells.forEach((cell, index) => {
                if (index < 4) {
                    const currentValue = cell.innerText;
                    cell.innerHTML = `<input type="text" value="${currentValue}" class="w-full p-1 border border-gray-300 rounded" />`;
                }
            });

            button.innerHTML = `<i class="mr-2 fa-solid fa-floppy-disk"></i> Save`;
            button.classList.remove("bg-blue-500", "hover:bg-blue-600");
            button.classList.add("bg-green-500", "hover:bg-green-600");
            button.onclick = () => saveRow(button);
        }

        function saveRow(button) {
            const row = button.closest('tr');
            const inputs = row.querySelectorAll('input'); 

            inputs.forEach(input => {
                const newValue = input.value;
                input.parentElement.innerText = newValue;
            });

            button.innerHTML = `<i class="mr-2 fa-solid fa-pen"></i> Edit`;
            button.classList.remove("bg-green-500", "hover:bg-green-600");
            button.classList.add("bg-blue-500", "hover:bg-blue-600");
            button.onclick = () => editRow(button);
        }
    </script>
</body>

</html>