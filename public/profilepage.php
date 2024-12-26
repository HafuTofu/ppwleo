<?php
require ".../public/controller/sess.php";
$ID_user = $_SESSION['id'];
$querry = "SELECT * FROM userdata WHERE ID_user = '$ID_user'";
$result = mysqli_query($conn, $querry);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="icon" href="./photo/ciG.png">
    <link rel="stylesheet" href="./css/style8.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <div class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200">
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

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <a href="./cart.php"><img src="./photo/cart.png" class="w-12 cursor-pointer"></a>
            <div class="relative">
                <img src="./photouser/<?php echo $_SESSION['fotouser']; ?>"
                    class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="./profilepage.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <?php if ($_SESSION['login'] === 'trueadmin') { ?>
                        <a href=".../atmin/view/atmindashboard.html"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                            Admin Dashboard</a>
                        <a href=".../atmin/view/admindash.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Product
                            Managing Page</a>
                        <a href=".../atmin/view/discount.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Discount
                            Managing Page</a>
                        <a href=".../atmin/view/orderadmin.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                            Managing Page</a>
                        <a href=".../atmin/view/usercontroller.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">User
                            Managing Page</a>
                        <a href=".../atmin/view/admincat.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Category
                            Managing Page</a>
                    <?php } ?>
                    <a href=".../public/view/dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                        Dashboard</a>
                    <a href=".../public/view/wishlist.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href=".../public/view/orderlist.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Order
                        List</a>
                    <a href=".../public/controller/logout.php"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="p-6 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8">Profile</h2>
        <div id="profile-view" class="flex flex-col md:flex-row items-center bg-white shadow-lg rounded-lg p-4">
            <!-- Profile Picture Section -->
            <div class="flex flex-col items-center mb-6 md:mb-0 mr-6 ml-3">
                <img src="./photo/<?php echo $row['fotouser']; ?>" alt="Profile"
                    class="w-40 h-40 rounded-full object-cover mb-4 shadow-md">
                <p class="text-lg font-medium text-gray-700"><?php echo $row['Username']; ?></p>
            </div>
            <!-- Profile Details Section -->
            <div class="flex-grow bg-[#FAF3E0] p-6 rounded-lg">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Username</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['Username']; ?>" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['fullname'] != '' ? $row['fullname'] : 'required'; ?>" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Email</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['Email']; ?>" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Gender</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['gender']; ?>" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Cell Phone</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['phone'] != '' ? $row['phone'] : 'required'; ?>" readonly>
                    </div>
                    <div class="flex items-start">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Address</label>
                        <input type="text"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['address'] != '' ? $row['address'] : 'required'; ?>" readonly>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <button id="edit-btn"
                        class="px-6 py-2 bg-[#55A7A0] text-white font-medium rounded-md hover:bg-[#479288] focus:outline-none focus:ring-2 focus:ring-blue-500">Edit</button>
                </div>
            </div>
        </div>

        <!-- Editable Profile Design -->
        <div id="profile-edit" class="hidden flex flex-col md:flex-row items-center bg-white shadow-lg rounded-lg p-6">
            <!-- Profile Picture Section -->
            <div class="flex flex-col items-center mb-6 md:mb-0 ml-3 mr-6">
                <img src="./photo/<?php echo $row['fotouser']; ?>" alt="Profile"
                    class="w-40 h-40 rounded-full object-cover mb-4 shadow-md">
                <input type="file" id="fileInput" accept="image/*" class="hidden">
                <button id="changePicBtn" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-400">Change
                    Picture</button>
            </div>
            <!-- Editable Profile Details Section -->
            <div class="flex-grow bg-[#FAF3E0] p-6 rounded-lg">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['Username']; ?>" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="fullname"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['fullname']; ?>" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['Email']; ?>" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Gender</label>
                        <select
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            id="gender" name="gender" required>
                            <option value="Rather not say">Rather not say</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Cell Phone</label>
                        <input type="tel" id="phone"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['phone']; ?>" required>
                    </div>
                    <div class="flex items-start">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Address</label>
                        <input type="address" id="address"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?php echo $row['address']; ?>" required>
                    </div>
                    <div class="flex items-start">
                        <label class="w-1/4 text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" id="crnpass"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="">
                    </div>
                    <div class="flex items-start">
                        <label class="w-1/4 text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="newpass"
                            class="flex-grow px-4 py-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            value="">
                    </div>
                </div>
                <div class="mt-6 flex justify-center space-x-4">
                    <button id="cancel-btn"
                        class="px-6 py-2 bg-red-500 text-white font-medium rounded-md hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">Cancel</button>
                    <button id="confirm-btn"
                        class="px-6 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-500"
                        onclick="updateprofile()">Confirm</button>
                </div>
            </div>
        </div>
    </main>

    <script>

        // Profile Dropdown Menu
        document.addEventListener('DOMContentLoaded', function () {
            const profileIcon = document.getElementById('profileIcon');
            const dropdownMenu = document.getElementById('dropdownMenu');

            profileIcon.addEventListener('mouseenter', function () {
                dropdownMenu.classList.remove('hidden'); // Show dropdown
            });

            profileIcon.addEventListener('mouseleave', function () {
                setTimeout(() => {
                    if (!dropdownMenu.matches(':hover')) {
                        dropdownMenu.classList.add('hidden'); // Hide dropdown
                    }
                }, 100);
            });

            dropdownMenu.addEventListener('mouseleave', function () {
                dropdownMenu.classList.add('hidden');
            });

            // Select/Deselect All Functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.productCheckbox');

            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = selectAllCheckbox.checked;
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });
        });

        const editBtn = document.getElementById('edit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const confirmBtn = document.getElementById('confirm-btn');
        const profileView = document.getElementById('profile-view');
        const profileEdit = document.getElementById('profile-edit');

        editBtn.addEventListener('click', () => {
            profileView.classList.add('hidden');
            profileEdit.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            profileEdit.classList.add('hidden');
            profileView.classList.remove('hidden');
        });

        confirmBtn.addEventListener('click', () => {
            // Handle form submission or save logic here
            alert('Changes saved successfully!');
            profileEdit.classList.add('hidden');
            profileView.classList.remove('hidden');
        });

        const profilePic = document.getElementById('profilePic');
        const fileInput = document.getElementById('fileInput');
        const changePicBtn = document.getElementById('changePicBtn');

        // Open file input when the button is clicked
        changePicBtn.addEventListener('click', () => {
            fileInput.click();
        });

        // Preview the selected image
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePic.src = e.target.result; // Update the image preview
                };
                reader.readAsDataURL(file);
            }
        });

        function updateprofile() {
            const username = document.getElementById('username').value;
            const fullName = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const gender = document.getElementById('gender').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            const currentPassword = document.getElementById('crnpass').value;
            const newPassword = document.getElementById('newpass').value;

            const formData = {
                username: username,
                fullname: fullName,
                email: email,
                gender: gender,
                phone: phone,
                address: address,
                currentPassword: currentPassword ?? "<?php echo $row['Password']; ?>",
                newPassword: newPassword ?? "<?php echo $row['Password']; ?>"
            };

            fetch('.../public/controller/update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating profile: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    alert('An unexpected error occurred. Please try again later.');
                });

        }


    </script>
</body>

</html>