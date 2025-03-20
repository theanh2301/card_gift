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
    <title>Cart</title>
</head>

<body class="m-0 p-0">
    <?php
    include_once "./header.php";
    $sql_query_user_id = "SELECT user_id from accounts where username = '$username'";
    $result = mysqli_query($conn, $sql_query_user_id);
    $user_id = mysqli_fetch_assoc($result)['user_id'];
    $product_id = $_POST['product_id'];

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $product_id => $value) {
            $sql = "DELETE FROM carts WHERE product_id = $product_id AND user_id = $user_id";
            mysqli_query($conn, $sql);
        }
        
    }

    $sql_query_have_prd = "SELECT count(product_id) as count from carts where user_id = $user_id";
    $result_have_prd = mysqli_query($conn, $sql_query_have_prd);
    $count = mysqli_fetch_assoc($result_have_prd)['count'];

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['checkout'])) {
        if($count > 0 ){
            foreach ($_POST['product_id'] as $product_id => $value) {
                $quantity = $_POST['quantity'][$product_id];
                $price = $_POST['price'][$product_id] * $quantity;
                $sql_query_update = "UPDATE carts SET quantity = $quantity, price = $price WHERE user_id = $user_id AND product_id = $product_id";
                $result_update = mysqli_query($conn, $sql_query_update);
            }
            echo "<script>window.location.href = 'checkout.php';</script>";
        }
    }

    $sql_query = "SELECT c.user_id as user_id, c.product_id as product_id, p.price as price, c.quantity as quantity, c.price as total, p.name as name, pi.image_url as img
    from carts c join products p on c.product_id = p.product_id  
    join product_images pi on p.product_id = pi.product_id 
    where c.user_id = $user_id group by c.product_id";
    $result = mysqli_query($conn, $sql_query);

    if ($result) {
        echo "<div class='mt-28 px-48 flex flex-col justify-between w-full min-h-screen'>
                <form class='mt-5' action='' method='POST'>
                    <div class='grid grid-cols-6 place-items-center gap-y-4 border p-3'>
                        <h1 class='text-xl font-semibold mb-3'>Product</h1>
                        <h1 class='text-xl font-semibold mb-3'>Name</h1>
                        <h1 class='text-xl font-semibold mb-3'>Price</h1>
                        <h1 class='text-xl font-semibold mb-3'>Quantity</h1>
                        <h1 class='text-xl font-semibold mb-3'>Total</h1>
                        <h1 class='text-xl font-semibold mb-3'>Delete</h1>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<img src='" . $row['img'] . "' alt='' class='w-32 h-32 object-center object-cover inline-block mb-3'>
        <h1>" . $row['name'] . "</h1>
        <h1 class='font-medium price' data-price='" . $row["price"] . "'>" . $row['price'] . "đ</h1>
        <input type='number' name='quantity[" . $row['product_id'] . "]' 
               class='border border-gray-200 p-2 rounded-md w-20 ml-2 h-1/3 quantity' 
               value='" . $row['quantity'] . "' min='1'>
        <h1 class='font-medium total'>" . $row['total'] . "đ</h1>
        <input type='hidden' name='product_id[" . $row['product_id'] . "]' value='" . $row['product_id'] . "'>
        <input type='hidden' name='price[" . $row['product_id'] . "]' value='" . $row['price'] . "'>
        <button type='submit' name='delete[" . $row['product_id'] . "]' class='hover:text-red-500'><i class='fa-solid fa-trash text-2xl'></i></button>
            ";
        }
        echo "</div>
                <h1 class='text-xl font-semibold text-right mr-10 my-4'>Total Payment: <span class='totalPayment text-xl font-semibold text-red-500'></span></h1>
                <div class='flex justify-end gap-4'>
                    <button class='bg-gray-200 p-3 hover:bg-gray-300 w-40'><a href='home.php'>Continue shopping</a></button>
                    <button class='bg-red-500 text-white p-3 hover:bg-red-600 w-40' name='checkout'>Checkout</button>
                </div>
                </form>
                    </div>
                
                ";
    }
    ?>

    <?php
    include_once "./footer.php";
    ?>

    <script>
        var priceElements = document.querySelectorAll('.price');
        var quantityInputs = document.querySelectorAll('.quantity');
        var totalElements = document.querySelectorAll('.total');

        function updateTotalPayment() {
            let totalPayment = 0;
            for (let i = 0; i < priceElements.length; i++) {
                const price = parseFloat(priceElements[i].dataset.price);
                const quantity = parseInt(quantityInputs[i].value) || 0;
                const total = price * quantity;
                totalElements[i].innerHTML = total.toFixed(2);
                totalPayment += total;
            }
            document.querySelector('.totalPayment').innerHTML = totalPayment + ".00đ";
        }
        for (let i = 0; i < quantityInputs.length; i++) {
            quantityInputs[i].addEventListener('input', updateTotalPayment);
        }

        updateTotalPayment();
    </script>
</body>

</html>