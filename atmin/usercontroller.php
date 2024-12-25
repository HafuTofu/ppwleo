<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer List</title>
  <link rel="icon" href="../public/photo/ciG.png">
  <link rel="stylesheet" href="../public/css/style12.css">
  <!-- Font Awesome CDN -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  />
</head>

<body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 flex items-center justify-between p-4 bg-yellow-200">
        <a href="./admindash.php"><img src="../public/photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10"></a>

        <!-- Search Bar -->
        <div class="relative flex items-center w-3/4 max-w-xl p-2 mx-auto bg-gray-100 rounded-full">
            <form action="" class="flex items-center w-full">
                <input type="text" name="search" placeholder="Search" class="w-full text-lg text-center bg-transparent outline-none">
                <button type="submit" class="p-2"><img src="../public/photo/search.png" width="20" height="20" alt="Search"></button>
            </form>
        </div>

        <!-- User and Cart Icons -->
        <div class="flex items-center mr-6 space-x-6">
            <div class="relative">
                <img src="../public/photouser/pfp.png" class="w-12 h-12 rounded-full cursor-pointer" alt="User profile" id="profileIcon">
                <!-- Dropdown menu -->
                <div id="dropdownMenu" class="absolute right-0 hidden w-40 mt-2 bg-white rounded-md shadow-lg">
                    <a href="pfpadmin.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                    <a href="#wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Wishlist</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
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
                    <!-- Row 1 -->
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 font-bold text-center">Pham Hanni</td>
                        <td class="px-6 py-3 font-bold text-center">Jl. Agus Salim, RT 06</td>
                        <td class="px-6 py-3 font-bold text-center">PhamParapam@gmail.com</td>
                        <td class="px-6 py-3 font-bold text-center">0898172873485</td>
                        <td class="px-6 py-3 space-x-2 font-bold text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- Unban/Ban Button -->
                                <button onclick="toggleButton(this)" class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-teal-700 bg-teal-200 rounded-lg toggle-btn hover:bg-teal-300">
                                    <i class="fa-solid fa-unlock"></i> <span>Unban</span>
                                </button>

                                <!-- Edit Button -->
                                <button onclick="editRow(this)" class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                    <i class="fas fa-edit"></i> <span>Edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 font-bold text-center">Kim Minji</td>
                        <td class="px-6 py-3 font-bold text-center">Jl. Kemuning, RT 06</td>
                        <td class="px-6 py-3 font-bold text-center">minjiKim2004@gmail.com</td>
                        <td class="px-6 py-3 font-bold text-center">0898172424235</td>
                        <td class="px-6 py-3 space-x-2 font-bold text-center">
                            <div class="flex justify-center space-x-2 font-bold">
                                <!-- Unban/Ban Button -->
                                <button onclick="toggleButton(this)" class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-teal-700 bg-teal-200 rounded-lg toggle-btn hover:bg-teal-300">
                                    <i class="fa-solid fa-unlock"></i> <span>Unban</span>
                                </button>

                                <!-- Edit Button -->
                                <button onclick="editRow(this)" class="flex items-center justify-center w-32 h-10 space-x-2 text-xs font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                    <i class="fas fa-edit"></i> <span>Edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Ban/Unban Button
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

        // Edit Row Functionality
        function editRow(button) {
            const row = button.closest('tr'); // Get the closest table row
            const cells = row.querySelectorAll('td'); // Select all <td> cells in the row
            
            // Loop through each cell and make it editable
            cells.forEach((cell, index) => {
                if (index < 4) { // Only the first 4 columns are editable
                    const currentValue = cell.innerText;
                    cell.innerHTML = `<input type="text" value="${currentValue}" class="w-full p-1 border border-gray-300 rounded" />`;
                }
            });

            // Change the Edit button to "Save"
            button.innerHTML = `<i class="mr-2 fa-solid fa-floppy-disk"></i> Save`;
            button.classList.remove("bg-blue-500", "hover:bg-blue-600");
            button.classList.add("bg-green-500", "hover:bg-green-600");
            button.onclick = () => saveRow(button);
        }

        // Save Row Functionality
        function saveRow(button) {
            const row = button.closest('tr');
            const inputs = row.querySelectorAll('input'); // Find all input fields
            
            // Replace inputs with their values
            inputs.forEach(input => {
                const newValue = input.value;
                input.parentElement.innerText = newValue; // Set cell text to input value
            });

            // Change the Save button back to Edit
            button.innerHTML = `<i class="mr-2 fa-solid fa-pen"></i> Edit`;
            button.classList.remove("bg-green-500", "hover:bg-green-600");
            button.classList.add("bg-blue-500", "hover:bg-blue-600");
            button.onclick = () => editRow(button);
        }
    </script>
</body>
</html>