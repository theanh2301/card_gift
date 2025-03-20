<header class="w-full h-[70px] flex items-center justify-center shadow-md fixed top-0 bg-white z-50 box-border">
    <div class="flex items-center justify-center">
        <!-- Logo -->
        <div>
            <a href="home.php"><img src="./img/logo.png" alt="Logo" class="max-h-[70px] w-auto border-r"></a>
        </div>

        <!-- Navigation -->
        <nav class="flex items-center">
            <a href="products.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Products</a>

            <!-- Categories Dropdown -->
            <div class="relative">
                <a id="category-btn" href="#" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white cursor-pointer">Categories</a>
                <nav id="category-dropdown" class="hidden absolute top-[70px] w-40 bg-white shadow-lg z-50">
                    <?php
                    $sql_query = "SELECT * FROM categories";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<a href='products.php?category=".$row['name']."' class='block p-2 text-black duration-200 hover:bg-[#ef4444] hover:text-white'>" . ucfirst($row['name']) . "</a>";
                        }
                    }
                    ?>
                </nav>
            </div>

            <!-- Occasions Dropdown -->
            <div class="relative">
                <a id="occasion-btn" href="#" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white cursor-pointer">Occasions</a>
                <nav id="occasion-dropdown" class="hidden absolute top-[70px] w-40 bg-white shadow-lg z-50">
                    <?php
                    $sql_query = "SELECT * FROM occasions";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<a href='products.php?occasion=".$row['name']."' class='block p-2 text-black duration-200 hover:bg-[#ef4444] hover:text-white'>" . ucfirst($row['name']) . "</a>";
                        }
                    }
                    ?>
                </nav>
            </div>

            <a href="products.php?blog" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Blogs</a>
        </nav>

        <!-- Search Form -->
        <form action="search.php" method="GET" class="relative z-50 group">
            <button class="border-x">
                <i class="fa-solid fa-magnifying-glass text-xl p-5 duration-200 hover:bg-[#ef4444] hover:text-white"></i>
            </button>
            <input type="text" name="search" id="search" placeholder="Search" required
                   class="focus:outline-none hidden border border-black p-3 absolute -bottom-12 bg-white z-50 group-hover:block">
        </form>

        <!-- Cart -->
        <a href="<?= isset($_SESSION['username']) ? 'cart.php' : 'login.php' ?>" class="px-5 py-5 duration-200 flex items-center justify-center hover:bg-[#ef4444] hover:text-white relative group">
            <i class="fa-solid fa-bag-shopping text-2xl mr-3"></i> Cart
            <?php
            if(isset($_SESSION['username'])){
                $username = $_SESSION['username'];
                $sql_query_user_id = "SELECT user_id from accounts where username = '$username'";
                $result = mysqli_query($conn, $sql_query_user_id);
                $user_id = mysqli_fetch_assoc($result)['user_id'];

                $sql_query_count = "SELECT COUNT(*) as count FROM carts where user_id = $user_id";
                $result = mysqli_query($conn, $sql_query_count);
                if ($result) {
                    $items = mysqli_fetch_assoc($result);
                    echo "<div class='bg-red-500 text-white rounded-full px-2 absolute top-3 left-7 group-hover:bg-red-600'>" . $items['count'] . "</div>";
                }
            } else {
                echo "<div class='bg-red-500 text-white rounded-full px-2 absolute top-3 left-7 group-hover:bg-red-600'>0</div>";
            }
            ?>
        </a>

        <!-- User Menu -->
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo '<div class="relative">
                    <div id="user-btn" class="px-5 py-5 duration-200 hover:bg-[#ef4444] hover:text-white cursor-pointer">
                        <i class="fa-solid fa-user text-2xl mr-2"></i>' . $username . '
                    </div>
                    <nav id="user-dropdown" class="hidden absolute top-[70px] w-40 bg-white shadow-lg z-50">
                        <a href="change_pass.php" class="block p-2 duration-200 hover:bg-[#ef4444] hover:text-white">Change Password</a>
                        <a href="logout.php" class="block p-2 duration-200 hover:bg-[#ef4444] hover:text-white">Logout</a>
                    </nav>
                </div>';
        } else {
            echo '<a href="login.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Login</a>';
        }
        ?>
    </div>
</header>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    function toggleDropdown(buttonId, dropdownId) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        button.addEventListener("click", function (event) {
            event.preventDefault();
            dropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", function (event) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    }

    toggleDropdown("category-btn", "category-dropdown");
    toggleDropdown("occasion-btn", "occasion-dropdown");
    toggleDropdown("user-btn", "user-dropdown");
});
</script>
