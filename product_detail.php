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
    <title>Product Details</title>
</head>

<body class="m-0 p-0 scroll-smooth">
    <?php
    $username = 'hoang';
    include_once "./header.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
        if (isset($_SESSION['username'])) {
            $product_id = $_GET['product_id'];
            $username = $_SESSION['username'];
            $rating = $_POST['rating'];
            $content = $_POST['content'];
            $dateTime = (new DateTime())->format('Y-m-d H:i:s');
            $sql_query = "INSERT into feedbacks value ('',$user_id, $product_id,'$content', $rating, '$dateTime')";
            $result = mysqli_query($conn, $sql_query);

            if ($result) {
                echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Feedback sent successfully</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    <a href='product_detail.php?product_id=" . $product_id . "' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go back</a>
                                </div>
                            </div>";
            }
        } else {
            echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Login to feedback</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='login.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Login</a>
                                    <a href='" . $_SERVER['REQUEST_URI'] . "' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                </div>
                            </div>";
        }
    }
    ?>

    <div class="mt-28 px-32 flex justify-between w-full">
        <?php
        $product_id = $_GET['product_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $quantity = $_POST['quantity'];
                $sql_query_find_product = "SELECT * from carts where product_id = $product_id and user_id = $user_id";
                $result = mysqli_query($conn, $sql_query_find_product);
                if ($result) {
                    if (mysqli_num_rows($result) == 0) {
                        $sql_query_price = "SELECT price from products where product_id = $product_id";
                        $result = mysqli_query($conn, $sql_query_price);
                        $price = mysqli_fetch_assoc($result)['price'];
                        $sql_query = "INSERT into carts value ($user_id, $product_id, $quantity, $price)";
                        $result = mysqli_query($conn, $sql_query);
                        if ($result) {
                            echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                    <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                    <div class='mb-0 flex items-center justify-evenly'>
                                        <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                        <a href='cart.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go to cart</a>
                                    </div>
                                </div>";
                        }
                    } else {
                        $sql_query_price = "SELECT price from products where product_id = $product_id";
                        $result = mysqli_query($conn, $sql_query_price);
                        $price = mysqli_fetch_assoc($result)['price'];
                        $sql_query = "UPDATE carts set quantity = quantity + $quantity, price = price + $price*$quantity where product_id = $product_id and user_id = $user_id";
                        $result = mysqli_query($conn, $sql_query);
                        if ($result) {
                            echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                    <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                    <div class='mb-0 flex items-center justify-evenly'>
                                        <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
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
                                        <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    </div>
                                </div>";
            }
        }
        if (isset($_POST['prd_img'])) {
            $img_url = $_POST['prd_img'];
        }
        $sql_query_rating = "SELECT ROUND(AVG(rating)) as rating FROM feedbacks where product_id = $product_id";
        $result = mysqli_query($conn, $sql_query_rating);
        $rating = mysqli_fetch_assoc($result)['rating'];
        $sql_query = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price, p.description as description from product_images pi
            join products p on pi.product_id = p.product_id
            join categories c on p.catagory_id = c.category_id
            where p.product_id = $product_id";
        $result = mysqli_query($conn, $sql_query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "<div class='w-full grid grid-cols-3 gap-5'>
                        <div class='flex flex-col items-center'>";
            if (isset($img_url)) {
                echo "<img src='" . $img_url . "' alt='' class='max-w-[70%] h-[300px] object-center object-cover inline-block'>";
                $sql_query_images = "SELECT image_url from product_images where product_id = $product_id";
                $result_img = mysqli_query($conn, $sql_query_images);
                if ($result_img) {
                    echo "<div class='flex gap-2 mt-2 justify-center'>";
                    while ($row_img = mysqli_fetch_assoc($result_img)) {
                        echo "<form action='' method='POST'>
                                        <input type='hidden' name='prd_img' value='" . htmlspecialchars($row_img['image_url']) . "'></input >
                                        <button><img src='" . $row_img['image_url'] . "' class='h-16 w-16 object-center object-cover'></button>
                                    </form>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<img src='" . $row['img'] . "' alt='' class='max-w-[70%] h-[300px] object-center object-cover inline-block'>";
                $sql_query_images = "SELECT image_url from product_images where product_id = $product_id";
                $result_img = mysqli_query($conn, $sql_query_images);
                if ($result_img) {
                    echo "<div class='flex gap-2 mt-2 justify-center'>";
                    while ($row_img = mysqli_fetch_assoc($result_img)) {
                        echo "<form action='' method='POST'>
                                        <input type='hidden' name='prd_img' value='" . htmlspecialchars($row_img['image_url']) . "'></input >
                                        <button><img src='" . $row_img['image_url'] . "' class='h-16 w-16 object-center object-cover'></button>
                                    </form>";
                    }
                    echo "</div>";
                }
            }
            echo "</div>
                        <div class='text-left'>
                            <h1 class='text-xl font-semibold'>" . $row['name'] . "</h1>
                            <div class='flex gap-2 mt-2 items-center'>";
            for ($i = 0; $i < $rating; $i++) {
                echo "<i class='fa-solid fa-star text-yellow-400 text-xl'></i>";
            }
            ;
            for ($i = 0; $i < 5 - $rating; $i++) {
                echo "<i class='fa-regular fa-star text-yellow-400 text-xl'></i>";
            }
            ;
            echo "<p class='text-green-400 underline'><a href='#feedback'>Give your feedback</a></p>
                                </div>
                            <p class='text-2xl font-semibold mt-2 text-red-500'>" . $row['price'] . "đ</p>
                            <div class='mx-auto mt-3 w-full h-[1px] bg-gray-200'></div>
                            <p class='mt-3 text-gray-500'>" . $row['description'] . "</p>
                            <div class='mx-auto mt-3 w-full h-[1px] bg-gray-200'></div>
                            <form class='mt-5' action='' method='POST'>
                                <label for='quantity' class=' text-xl font-semibold'>Quantity:</label>
                                <input type='number' name='quantity' id='quantity' class='border border-gray-200 p-2 rounded-md w-20 ml-2' value='1' min='1'>
                                <button class='bg-red-500 text-white p-2 ml-2 w-[200px] hover:bg-red-600'>Add to cart</button>
                            </form>
                        </div>
                        <div class='ml-3 flex flex-col gap-2'>
                            <h1 class='text-xl font-semibold'>Related products</h1>";
            $sql_query_related = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price from product_images pi
                                            join products p on pi.product_id = p.product_id
                                            join product_occasions po on p.product_id = po.product_id
                                            join occasions o on po.occasion_id = o.occasions_id
                                            where stock > 0 group by p.product_id limit 5";
            $result_related = mysqli_query($conn, $sql_query_related);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result_related)) {
                    echo "<a href='product_detail.php?product_id=" . $row["id"] . "' class='flex gap-2'>
                                            <img src='" . $row['img'] . "' alt='' class='h-20 w-20 object-center object-cover'>
                                            <div>
                                                <p class='text-md'>" . $row['name'] . "</p>
                                                <p class='text-md text-red-500'>" . $row['price'] . "đ</p>
                                            </div>
                                        </a>
                                        <div class='mx-auto my-2 w-full h-[1px] bg-gray-200'></div>";
                }
            }
            echo "</div>
                    </div>";
        }
        ?>
    </div>
    <div class="mt-14 px-60">
        <h1 class="text-xl font-semibold">Feedbacks - Comments</h1>
        <div class='mx-auto my-2 w-full h-[1px] bg-gray-200'></div>
        <div class="border border-gray-200 p-3">
            <?php
            $sql_query_feedback = "SELECT a.username as username, f.content as content, f.rating as rating, f.date as date
            from accounts a join feedbacks f on a.user_id = f.user_id 
            where f.product_id = $product_id";
            $result = mysqli_query($conn, $sql_query_feedback);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='mb-5'>
                            <div class='flex gap-2 items-center'>
                            <h1 class='text-xl font-normal'>" . $row['username'] . "</h1>";
                    for ($i = 0; $i < $row['rating']; $i++) {
                        echo "<i class='fa-solid fa-star text-yellow-400'></i>";
                    }
                    echo "</div>
                            <p class='text-gray-500 text-xl'>" . $row['content'] . "</p>
                            <p class='text-gray-500 text-xs'>" . $row['date'] . "</p>
                        </div>";
                }
            }
            ?>
            <form action="" method="POST" id="feedback"
                class="mt-5 mx-auto w-fit border rounded-md shadow-xl p-3 flex flex-col gap-2 items-center scroll-m-24">
                <h1 class="text-2xl font-semibold">Give your feedback</h1>
                <div>
                    <i class='rating fa-regular fa-star text-yellow-400 text-xl cursor-pointer' id="1" value="1"></i>
                    <i class='rating fa-regular fa-star text-yellow-400 text-xl cursor-pointer' id="2" value="2"></i>
                    <i class='rating fa-regular fa-star text-yellow-400 text-xl cursor-pointer' id="3" value="3"></i>
                    <i class='rating fa-regular fa-star text-yellow-400 text-xl cursor-pointer' id="4" value="4"></i>
                    <i class='rating fa-regular fa-star text-yellow-400 text-xl cursor-pointer' id="5" value="5"></i>
                    <input type="text" name="rating" id="rating" class="hidden">
                </div>
                <textarea name="content" id="content" cols="60" rows="10" class="border border-gray-200 p-2 w-full mt-2"
                    placeholder="Write your feedback here"></textarea>
                <button class="bg-red-500 text-white mt-5 p-2 w-[200px] hover:bg-red-600">Send</button>
            </form>
        </div>
    </div>

    <?php
    include_once "./footer.php";
    ?>
    <script>
        let stars = document.querySelectorAll('.rating');
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                let value = parseInt(star.getAttribute('value'));
                document.getElementById('rating').value = value;
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    } else {
                        s.classList.remove('fa-solid');
                        s.classList.add('fa-regular');
                    }
                });
            });
        });
    </script>
</body>

</html>