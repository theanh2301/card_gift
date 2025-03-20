<?php
include_once "./db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Product</title>
</head>

<body class="m-0 p-0">
    <?php
    include_once "./header.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $product_id = $_POST['product_id'];
            $sql_query_find_product = "SELECT * from carts where product_id = $product_id and user_id = $user_id";
            $result = mysqli_query($conn, $sql_query_find_product);
            if ($result) {
                if (!mysqli_fetch_assoc($result)['product_id']) {
                    $sql_query_price = "SELECT price from products where product_id = $product_id";
                    $result = mysqli_query($conn, $sql_query_price);
                    $price = mysqli_fetch_assoc($result)['price'];
                    $sql_query = "INSERT into carts value ($user_id, $product_id, 1, $price)";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='products.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    <a href='cart.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go to cart</a>
                                </div>
                            </div>";
                    }
                } else {
                    $sql_query_price = "SELECT price from products where product_id = $product_id";
                    $result = mysqli_query($conn, $sql_query_price);
                    $price = mysqli_fetch_assoc($result)['price'];
                    $sql_query = "UPDATE carts set quantity = quantity + 1, price = price + $price where product_id = $product_id and user_id = $user_id";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='products.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    <a href='cart.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go to cart</a>
                                </div>
                            </div>";
                    }
                }
            }
        } else {
            echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Login to buy</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='login.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Login</a>
                                    <a href='products.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                </div>
                            </div>";
        }
    }
    ?>

    <div class="mt-28 px-32 flex justify-between w-full min-h-screen">
        <div class="min-w-[15%]">
            <h1 class="font-semibold text-xl">Filter</h1>
            <h1 class="hover:text-red-500 ml-5"><a href="products.php">All</a></h1>
            <h1 id="categories" class="cursor-pointer hover:text-red-500"><i
                    class="fa-solid fa-plus text-sm mr-2 mt-3"></i>Categories</h1>
            <div class="gap-y-2 ml-2 hidden" id="categories_list">
                <?php
                $sql_query = "SELECT * FROM categories";
                $result = mysqli_query($conn, $sql_query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<h1 class='hover:text-red-500 ml-3 mt-1'>
                                                <a href='products.php?category=" . $row['name'] . "'><i class='fa-solid fa-circle mr-2 scale-50'></i>" . ucfirst($row['name']) . "</a>
                                            </h1>";
                    }
                }
                ?>
            </div>
            <h1 id="occasions" class="cursor-pointer hover:text-red-500"><i
                    class="fa-solid fa-plus text-sm mr-2 mt-3"></i>Occasions</h1>
            <div class="gap-y-2 ml-2 hidden" id="occasions_list">
                <?php
                $sql_query = "SELECT * FROM occasions";
                $result = mysqli_query($conn, $sql_query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<h1 class='hover:text-red-500 ml-3 mt-1'>
                                                <a href='products.php?occasion=" . $row['name'] . "'><i class='fa-solid fa-circle mr-2 scale-50'></i>" . ucfirst($row['name']) . "</a>
                                            </h1>";
                    }
                }
                ?>
            </div>
            <div>
                <h1 id="price" class="cursor-pointer hover:text-red-500"><i
                        class="fa-solid fa-plus text-sm mr-2 mt-3"></i>Price</h1>
                <div class="gap-y-2 ml-4 hidden" id="price_list">
                    <h1 class='hover:text-red-500 mt-1'>
                        <a href="<?php
                        $url = $_SERVER['REQUEST_URI'];
                        if (!isset($_GET['from']) && !str_contains($url, '?')) {
                            echo $url . '?from=0&to=100000';
                        }
                        if (!isset($_GET['from']) && str_contains($url, '?')) {
                            echo $url . '&from=0&to=100000';
                        }
                        if (str_contains($url, '?from')) {
                            echo substr($url, 0, strpos($url, '?from')) . '?from=0&to=100000';
                        }
                        if (str_contains($url, '&from')) {
                            echo substr($url, 0, strpos($url, '&from')) . '&from=0&to=100000';
                        }
                        ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>0-100.000đ
                        </a>
                    </h1>
                    <h1 class='hover:text-red-500 mt-1'>
                        <a href="<?php
                        $url = $_SERVER['REQUEST_URI'];
                        if (!isset($_GET['from']) && !str_contains($url, '?')) {
                            echo $url . '?from=100000&to=300000';
                        }
                        if (!isset($_GET['from']) && str_contains($url, '?')) {
                            echo $url . '&from=100000&to=300000';
                        }
                        if (str_contains($url, '?from')) {
                            echo substr($url, 0, strpos($url, '?from')) . '?from=100000&to=300000';
                        }
                        if (str_contains($url, '&from')) {
                            echo substr($url, 0, strpos($url, '&from')) . '&from=100000&to=300000';
                        }
                        ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>100.000-300.000đ
                        </a>
                    </h1>
                    <h1 class='hover:text-red-500 mt-1'>
                        <a href="<?php
                        $url = $_SERVER['REQUEST_URI'];
                        if (!isset($_GET['from']) && !str_contains($url, '?')) {
                            echo $url . '?from=300000&to=500000';
                        }
                        if (!isset($_GET['from']) && str_contains($url, '?')) {
                            echo $url . '&from=300000&to=500000';
                        }
                        if (str_contains($url, '?from')) {
                            echo substr($url, 0, strpos($url, '?from')) . '?from=300000&to=500000';
                        }
                        if (str_contains($url, '&from')) {
                            echo substr($url, 0, strpos($url, '&from')) . '&from=300000&to=500000';
                        }
                        ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>300.000-500.000đ
                        </a>
                    </h1>
                    <h1 class='hover:text-red-500 mt-1'>
                        <a href="<?php
                        $url = $_SERVER['REQUEST_URI'];
                        if (!isset($_GET['from']) && !str_contains($url, '?')) {
                            echo $url . '?from=500000&to=700000';
                        }
                        if (!isset($_GET['from']) && str_contains($url, '?')) {
                            echo $url . '&from=500000&to=700000';
                        }
                        if (str_contains($url, '?from')) {
                            echo substr($url, 0, strpos($url, '?from')) . '?from=500000&to=700000';
                        }
                        if (str_contains($url, '&from')) {
                            echo substr($url, 0, strpos($url, '&from')) . '&from=500000&to=700000';
                        }
                        ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>500.000-700.000đ
                        </a>
                    </h1>
                    <h1 class='hover:text-red-500 mt-1'>
                        <a href="<?php
                        $url = $_SERVER['REQUEST_URI'];
                        if (!isset($_GET['from']) && !str_contains($url, '?')) {
                            echo $url . '?from=700000&to=1000000';
                        }
                        if (!isset($_GET['from']) && str_contains($url, '?')) {
                            echo $url . '&from=700000&to=1000000';
                        }
                        if (str_contains($url, '?from')) {
                            echo substr($url, 0, strpos($url, '?from')) . '?from=700000&to=1000000';
                        }
                        if (str_contains($url, '&from')) {
                            echo substr($url, 0, strpos($url, '&from')) . '&from=700000&to=1000000';
                        }
                        ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>700.000-1.000.000đ
                        </a>
                    </h1>
                </div>
            </div>
            <h1 id="sort" class="cursor-pointer hover:text-red-500 mt-3"><i class="fa-solid fa-plus text-sm mr-2"></i>Sort by
            </h1>
            <div class="gap-y-2 ml-4 hidden" id="sort_list">
                <h1 class='hover:text-red-500 mt-1'>
                    <a href="<?php
                    $url = $_SERVER['REQUEST_URI'];
                    if (!isset($_GET['sort']) && !str_contains($url, '?')) {
                        echo $url . '?sort=asc';
                    }
                    if (!isset($_GET['sort']) && str_contains($url, '?')) {
                        echo $url . '&sort=asc';
                    }
                    if (str_contains($url, '?sort')) {
                        echo substr($url, 0, strpos($url, '?sort')) . '?sort=asc';
                    }
                    if (str_contains($url, '&sort')) {
                        echo substr($url, 0, strpos($url, '&sort')) . '&sort=asc';
                    }
                    ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>Price increasing
                    </a>
                </h1>
                <h1 class='hover:text-red-500 mt-1'>
                    <a href="<?php
                    $url = $_SERVER['REQUEST_URI'];
                    if (!isset($_GET['sort']) && !str_contains($url, '?')) {
                        echo $url . '?sort=desc';
                    }
                    if (!isset($_GET['sort']) && str_contains($url, '?')) {
                        echo $url . '&sort=desc';
                    }
                    if (str_contains($url, '?sort')) {
                        echo substr($url, 0, strpos($url, '?sort')) . '?sort=desc';
                    }
                    if (str_contains($url, '&sort')) {
                        echo substr($url, 0, strpos($url, '&sort')) . '&sort=desc';
                    }
                    ?>"><i class='fa-solid fa-circle mr-2 scale-50'></i>Price decreasing
                    </a>
                </h1>
            </div>
            <h1 class="hover:text-red-500 ml-5 mt-3"><a href="products.php?blog">Blogs</a></h1>
        </div>

        <div class=" grid grid-cols-4 gap-5 text-center min-w-[85%]">
            <?php
            $blog = isset($_GET['blog']);
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
            $from = isset($_GET['from']) ? $_GET['from'] : 0;
            $to = isset($_GET['to']) ? $_GET['to'] : 1000000;
            $sql_query = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price from product_images pi
            join products p on pi.product_id = p.product_id
            join categories c on p.catagory_id = c.category_id
            where stock > 0 and price between $from and $to group by p.product_id order by price $sort";
            if (isset($_GET['category'])) {
                $category = $_GET['category'];
                $sql_query = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price from product_images pi
                                    join products p on pi.product_id = p.product_id
                                    join categories c on p.catagory_id = c.category_id
                                    where stock > 0 and c.name = '$category' and price between $from and $to group by p.product_id order by price $sort";
            }
            if (isset($_GET['occasion'])) {
                $occasion = $_GET['occasion'];
                $sql_query = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price from product_images pi
                            join products p on pi.product_id = p.product_id
                            join product_occasions po on p.product_id = po.product_id
                            join occasions o on po.occasion_id = o.occasions_id
                            where o.name = '$occasion' and stock > 0 and price between $from and $to group by p.product_id order by price $sort";
            }
            $result = mysqli_query($conn, $sql_query);
            if(isset($_GET['blog'])){
                $sql_query = "SELECT * from blogs";
                $result = mysqli_query($conn, $sql_query);
                if($result){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class=" border-2 flex justify-center p-3 relative h-72 w-60 group">
                                     <a href="blogs_detail.php?blog_id=' . $row["blog_id"] . '">
                                         <img src="' . $row['image_url'] . '" alt="" class="w-40 h-40 object-center object-cover inline-block mb-3">
                                         <p class="duration-200 group-hover:text-red-500 font-medium">' . $row['title'] . '</p>
                                     </a>
                                </div>';
                    }
                }
            }else{
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class=" border-2 flex justify-center p-3 relative h-80 w-60 group">
                                         <a href="product_detail.php?product_id=' . $row["id"] . '">
                                             <img src="' . $row['img'] . '" alt="" class="w-40 h-40 object-center object-cover inline-block mb-3">
                                             <p>' . $row['name'] . '</p>
                                             <p class="absolute bottom-2 left-1/2 -translate-x-[50%] font-medium text-xl text-red-500">' . $row['price'] . '<u class="text-base">đ</u></p>
                                         </a>
                                         <div class="absolute shadow-lg flex bg-white top-1/2 left-1/2 -translate-x-[50%] -translate-y-[20%]  opacity-0 duration-500 ease-in-out group-hover:opacity-100 group-hover:-translate-y-[50%]">
                                             <a href="product_detail.php?product_id=' . $row["id"] . '" class="h-10 w-10 flex items-center justify-center border-r hover:text-red-500"><i class="fa-solid fa-eye"></i></a>
                                             <form method="POST" action="">
                                                 <input type="hidden" name="product_id" value="' . $row["id"] . '">
                                                 <button class="add_to_cart h-10 w-10  hover:text-red-500"><i class="fa-solid fa-bag-shopping"></i></button>
                                             </form>
                                         </div>
                                    </div>';
                    }
                }
            }
            ?>
        </div>
    </div>

    <?php
    include_once "./footer.php";
    ?>
    <script>
        document.getElementById('categories').addEventListener('click', function () {
            document.getElementById('categories').classList.toggle('text-red-500');
            document.getElementById('categories_list').classList.toggle('hidden');
        });
        document.getElementById('occasions').addEventListener('click', function () {
            document.getElementById('occasions').classList.toggle('text-red-500');
            document.getElementById('occasions_list').classList.toggle('hidden');
        });
        document.getElementById('price').addEventListener('click', function () {
            document.getElementById('price').classList.toggle('text-red-500');
            document.getElementById('price_list').classList.toggle('hidden');
        });
        document.getElementById('sort').addEventListener('click', function () {
            document.getElementById('sort').classList.toggle('text-red-500');
            document.getElementById('sort_list').classList.toggle('hidden');
        });
    </script>
</body>

</html>