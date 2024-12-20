<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout</title>
    <link rel="icon" href="./photo/ciG.png" />
    <link rel="stylesheet" href="./css/style9.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="font-sans bg-yellow-50">
    <!-- Navbar -->
    <header
      class="sticky top-0 flex items-center justify-between p-4 bg-yellow-200"
    >
      <a href="./dashboard.php">
        <img src="./photo/ciG.png" alt="ciGCentral" class="w-32 h-20 ml-10" />
      </a>
    </header>

    <!-- Checkout Container -->
    <div class="max-w-5xl mx-auto mt-10 grid grid-cols-3 gap-8">
      <!-- Left Section -->
      <div class="col-span-2 space-y-4">
        <!-- Shipping Address -->
        <div class="bg-white border rounded-lg p-4 shadow-md fixed">
          <h2 class="font-bold text-gray-800 mb-2">Shipping Address</h2>
          <p class="text-gray-600 text-sm mb-4" id="current-address">
            Jl. Johar Bahru IV A RT 04 RW 05, Johar Bahru, Jakarta Pusat, DKI
            Jakarta.
          </p>
          <!-- 'Change' button -->
          <label
            for="edit-modal"
            class="cursor-pointer border border-gray-400 px-4 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-200"
          >
            Change
          </label>

          <!-- Modal Trigger -->
          <input type="checkbox" id="edit-modal" class="peer hidden" />

          <!-- Modal -->
          <div
            class="absolute inset-0 bg-gray-700 bg-opacity-50 hidden peer-checked:flex items-center justify-center"
          >
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
              <h3 class="font-bold text-gray-800 mb-4">
                Edit Shipping Address
              </h3>
              <form>
                <textarea
                  id="edit-address"
                  rows="4"
                  class="w-full p-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                  placeholder="Enter new address"
                >
Jl. Johar Bahru IV A RT 04 RW 05, Johar Bahru, Jakarta Pusat, DKI Jakarta. 161616123</textarea
                >
                <div class="flex justify-end mt-4 space-x-2">
                  <!-- Close Modal -->
                  <label
                    for="edit-modal"
                    class="cursor-pointer px-4 py-1 rounded-lg border text-gray-700 hover:bg-gray-200"
                  >
                    Cancel
                  </label>
                  <!-- Save Button -->
                  <button
                    type="button"
                    class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    onclick="document.getElementById('current-address').innerText = document.getElementById('edit-address').value; document.getElementById('edit-modal').checked = false;"
                  >
                    Save
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Product Section -->
        <div class="bg-white border rounded-lg p-4 shadow-md">
          <!-- Product 1 -->
          <div class="flex mb-4">
            <img
              src="./photo/JACKET.png"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">T1 Worlds Jacket 2024</h3>
                <span class="font-bold text-gray-900">Rp. 1.700.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>

          <!-- Product 2 -->
          <div class="flex mb-4">
            <img
              src="./photo/superlight.jpeg"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Logitech Superlight 2</h3>
                <span class="font-bold text-gray-900">Rp. 2.000.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>

           <!-- Product 2 -->
           <div class="flex mb-4">
            <img
              src="./photo/superlight.jpeg"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Logitech Superlight 2</h3>
                <span class="font-bold text-gray-900">Rp. 2.000.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>

          <!-- Product 3 -->
          <div class="flex mb-4">
            <img
              src="./photo/Anggur.jpg"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Anggur Maharaja</h3>
                <span class="font-bold text-gray-900">Rp. 2.500.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>

          <!-- Product 3 -->
          <div class="flex mb-4">
            <img
              src="./photo/Anggur.jpg"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Anggur Maharaja</h3>
                <span class="font-bold text-gray-900">Rp. 2.500.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>

           <!-- Product 2 -->
           <div class="flex mb-4">
            <img
              src="./photo/superlight.jpeg"
              alt="Product Image"
              class="rounded-lg w-20 h-20"
            />
            <div class="ml-4 w-full">
              <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Logitech Superlight 2</h3>
                <span class="font-bold text-gray-900">Rp. 2.000.000</span>
              </div>
              <p class="text-gray-500 text-sm">QTY: 1</p>
            </div>
          </div>
        </div>

        <!-- Shipping Option -->
        <div class="bg-white border rounded-lg p-4 shadow-md mt-4">
          <h3 class="font-bold text-gray-800 mb-2">Shipping Option</h3>
          <select
            id="shipping"
            name="shippingOption"
            class="block w-full px-3 py-2 border rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
          >
            <option value="ekonomi">Economy (Rp. 16.500) - ETA 5 Days</option>
            <option value="regular">Regular (Rp. 25.000) - ETA 3 Days</option>
            <option value="express">Express (Rp. 40.000) - ETA 1 Day</option>
            <option value="priority">
              Priority (Rp. 60.000) - ETA 3 Hours (0-15KM)
            </option>
          </select>
        </div>
      </div>

      <!-- Right Section -->
      <div class="bg-white border rounded-lg p-4 shadow-md space-y-4 self-start" style="width: 300px">
        <!-- Payment Method -->
        <h2 class="font-bold text-gray-800">Payment Method</h2>
        <div class="space-y-2">
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment" class="w-4 h-4" required />
            <i class="fa-solid fa-handshake"></i>
            <span class="text-gray-700">COD</span>
          </label>
          <label class="flex items-center space-x-2">
            <input type="radio" name="payment" class="w-4 h-4" />
            <i class="fa-solid fa-building-columns"></i>
            <span class="text-gray-700">Virtual Account</span>
          </label>
        </div>

        <!-- Summary -->
        <div class="border-t border-gray-200 pt-4 summary-section">
          <h3 class="font-bold text-gray-700 mb-4">Summary</h3>
          <div class="space-y-2">
            <div class="flex justify-between text-gray-600">
              <span>Total (3 items)</span>
              <span class="font-semibold">Rp. 6.200.000</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Shipping Cost</span>
              <span class="font-semibold">Rp16.500</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Service Charge</span>
              <span class="font-semibold">Rp. 2.500</span>
            </div>
            <div
              class="border-t border-gray-300 mt-2 pt-2 flex justify-between text-gray-900"
            >
              <span class="font-bold">Grand Total</span>
              <span class="font-bold">Rp. 6.219.000</span>
            </div>
          </div>
        </div>

        <!-- Checkout Button -->
        <button
          onclick="window.location.href = 'dashboard.php'"
          class="w-full bg-red-500 text-white font-semibold py-2 rounded-lg hover:bg-red-600 focus:outline-none"
        >
          Checkout
        </button>
      </div>
    </div>
  </body>
</html>