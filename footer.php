
<footer class="mt-10 bg-gray-950 text-white p-5 grid grid-cols-3 place-items-center px-40 pb-20 gap-20">
    <div class="flex flex-col  gap-y-3">
        <h1 class="mb-3 text-3xl font-semibold text-white">Address</h1>
        <p class="text-white">Papyrus Limited, Shop for handmade birthday gifts, beautiful and meaningful valentine gifts, gifts for
            20/10, 8/3, Christmas; Vintage decorative items for living rooms, shops, cafes.</p>
        <p class="text-white"><i class="fa-solid fa-location-dot mr-2"></i>185 Doi Can Street, Doi Can, Ba Dinh, Hanoi</p>
        <p class="text-white"><i class="fa-solid fa-phone mr-2"></i>0327081826</p>
        <p class="text-white"><i class="fa-solid fa-envelope mr-2"></i>papyruslimited@gmail.com</p>
    </div>
    <div class="flex flex-col gap-y-3">
        <h1 class="mb-3 text-3xl font-semibold">Support</h1>
        <a href='products.php?blog' class="duration-300 hover:text-red-500 "><i class='fa-solid fa-circle mr-2 scale-50'></i>Blogs</a>
        <a href='#' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Buying Instructions</a>
        <a href='#' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Payment Instructions</a>
        <a href='#' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Delivery instructions</a>
        <a href='#' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Terms of Service</a>
    </div>
    <div class="flex flex-col gap-y-3">
        <h1 class="mb-3 text-3xl font-semibold">More</h1>
        <a href='search.php?search=+' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Search</a>
        <a href='login.php' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Login</a>
        <a href='register.php' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Register</a>
        <a href='<?= isset($_SESSION['username']) ? "cart.php" : "login.php" ?>' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Cart</a>
        <a href='intro_contact.php' class="duration-300 hover:text-red-500"><i class='fa-solid fa-circle mr-2 scale-50'></i>Introduct & Contact</a>
    </div>
</footer>