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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Checkout</title>
</head>

<body class="m-0 p-0">
    <?php
    include_once "./header.php";
    ?>

    <div class="mt-20 px-40 flex w-full min-h-screen">
        <form action="" method="POST" class="mt-4 grid grid-cols-3 gap-4 w-full">
            <?php
            $sql_query = "SELECT * from accounts where username = '$username'";
            $result = mysqli_query($conn, $sql_query);
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
            $phone = $row['phone'];

            $sql_query_sum_count = "SELECT count(product_id) as count, sum(price) as total_amount from carts where user_id = '$user_id'";
            $result_sum_count = mysqli_query($conn, $sql_query_sum_count);
            $row_sum_count = mysqli_fetch_assoc($result_sum_count);
            $count = $row_sum_count['count'];
            $total_amount = $row_sum_count['total_amount'];

            $sql_query_cart = "SELECT * from carts where user_id = $user_id";
            $result_cart = mysqli_query($conn, $sql_query_cart);
            

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
                $dateTime = (new DateTime())->format('Y-m-d H:i:s');
                $shipping_address = $_POST['address'] . ", " . $_POST['ward'] . ", " . $_POST['district'] . ", " . $_POST['province'];

                $sql_query_orders = "INSERT into orders value ('', $user_id, '$dateTime', $total_amount, 'pending', '$shipping_address')";
                $result_orders = mysqli_query($conn, $sql_query_orders);

                if($result_orders){
                    $sql_query_order_id = "SELECT max(order_id) as order_id from orders where user_id = $user_id";
                    $result_order_id = mysqli_query($conn, $sql_query_order_id);
                    $order_id = mysqli_fetch_assoc($result_order_id)['order_id'];

                    while($row_cart = mysqli_fetch_assoc($result_cart)){
                        $product_id = $row_cart['product_id'];
                        $quantity = $row_cart['quantity'];
                        $total = $row_cart['price'];
                        $sql_query_order_detail = "INSERT into order_detail value ('', $order_id, $product_id, $quantity, $total)";
                        $result_order_detail = mysqli_query($conn, $sql_query_order_detail);

                        $sql_query_delete = "DELETE from carts where product_id = $product_id and user_id = $user_id";
                        $result_delete = mysqli_query($conn, $sql_query_delete);
                    }
                }
                echo "<script>window.location.href = 'successful.php';</script>";
            }

            ?>
            <div>
                <h1 class="text-2xl text-blue-400 font-semibold h-fit hover:text-blue-700 cursor-pointer"><a
                        href="home.php">Papyrus Limited Cart & Gift</a></h1>
                <h1 class="text-xl font-medium">Purchase information</h1>
                <div class="relative mt-4">
                    <input type="text" name="email" class="border border-gray-400 rounded-md p-2 w-full" required
                        value="<?php echo $email; ?>" readonly>
                    <label for="email" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">Email</label>
                </div>
                <div class="relative mt-4">
                    <input type="text" name="name"
                        class="border border-gray-400 rounded-md p-2 focus:outline-blue-400 w-full" required>
                    <label for="name" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">
                        Full Name</label>
                </div>
                <div class="relative mt-4">
                    <input type="text" name="phone" class="border border-gray-400 rounded-md p-2 w-full" required
                        value="<?php echo '0' . $phone; ?>" readonly>
                    <label for="phone" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">Phone number</label>
                </div>
                <div class="relative mt-4">
                    <input type="text" name="province" class="border border-gray-400 rounded-md p-2 w-full" required
                        value="Ha Noi" readonly>
                    <label for="province" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">Province</label>
                </div>
                <div class="relative mt-4">
                    <select name="district" id="district" class="border border-gray-400 rounded-md p-2 w-full" required>
                        <option value="">Select District</option>
                        <option value="HoanKiem">Hoan Kiem</option>
                        <option value="BaDinh">Ba Dinh</option>
                        <option value="DongDa">Dong Da</option>
                        <option value="HaiBaTrung">Hai Ba Trung</option>
                        <option value="CauGiay">Cau Giay</option>
                        <option value="ThanhXuan">Thanh Xuan</option>
                        <option value="TayHo">Tay Ho</option>
                        <option value="LongBien">Long Bien</option>
                        <option value="NamTuLiem">Nam Tu Liem</option>
                        <option value="BacTuLiem">Bac Tu Liem</option>
                        <option value="HoangMai">Hoang Mai</option>
                    </select>
                    <label for="district" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">District</label>
                </div>
                <div class="relative mt-4">
                    <select name="ward" id="ward" class="border border-gay-400 rounded-md p-2 w-full" required>
                        <option value="">Select Ward</option>
                    </select>
                    <label for="ward" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">Ward</label>
                </div>
                <div class="relative mt-4">
                    <input type="text" name="address"
                        class="border border-gray-400 rounded-md p-2 focus:outline-blue-400 w-full" required>
                    <label for="address" class="absolute -top-3 left-3 bg-white px-2 text-gray-400 rounded-full">
                        Specific address (house number, street)</label>
                </div>
                <div class="relative mt-4">
                    <textarea type="text" name="name"
                        class="border border-gray-400 rounded-md p-2 focus:outline-blue-400 w-full"></textarea>
                    <label for="name" class="absolute -top-3 left-3 bg-white px-2 text-gray-400">
                        Note (Option)</label>
                </div>
            </div>
            <div class="mt-8">
                <h1 class="text-xl font-medium">Transport</h1>
                <div class="p-3 border rounded-md border-gray-400 mt-4 flex item-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="transport" checked class="scale-150">
                        <label for="transport">Delivery to your home</label>
                    </div>
                    <p class="text-right text-gray-400">Free of charge</p>
                </div>
                <h1 class="text-xl font-medium mt-5">Pay</h1>
                <div class="p-3 border rounded-md border-gray-400 mt-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="pay" checked class="scale-150">
                            <label for="pay">Cash on Delivery (COD)</label>
                        </div>
                        <i class="fa-solid fa-money-bill-wave text-blue-600 scale-150 mt-1 mr-3"></i>
                    </div>
                    <p class=" text-gray-400 mt-3">You only pay when you receive the goods.</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 flex flex-col">
                <h1 class="text-2xl font-semibold">Orders (<?= $count ?> products)</h1>
                <div class="h-[1px] w-full bg-gray-400 mt-5"></div>
                <?php
                $sql_query_prd = "SELECT c.user_id as user_id, c.product_id as product_id, p.price as price, c.quantity as quantity, c.price as total, p.name as name, pi.image_url as img
                    from carts c join products p on c.product_id = p.product_id  
                    join product_images pi on p.product_id = pi.product_id 
                    where c.user_id = $user_id group by c.product_id";
                $result_prd = mysqli_query($conn, $sql_query_prd);
                if ($result_prd) {
                    while ($row = mysqli_fetch_assoc($result_prd)) {
                        echo "<div class='mt-5 flex justify-between relative gap-3'>
                            <div class='flex gap-5'>
                                <input type='hidden' name='product_id[" . $row['product_id'] . "]' value='" . $row['product_id'] . "'>
                                <img src='" . $row['img'] . "' alt='' class='w-16 h-16 object-center object-cover inline-block rounded-md'>
                                <h1 class='text-sm'>" . $row['name'] . "</h1>
                                <div class='bg-blue-500 w-fit text-white rounded-full px-2 absolute -top-2 left-12 group-hover:bg-red-600'>" . $row['quantity'] . "</div>
                            </div>
                            <p class='self-center text-gray-500'>" . $row['total'] . "đ</p>
                            <input type='hidden' name='total[" . $row['product_id'] . "]' value=" . $row['total'] . "đ'>
                            <input type='hidden' name='quantity[" . $row['product_id'] . "]' value=" . $row['quantity'] . "'>
                            </div>";
                    }
                }
                ?>
                <div class="h-[1px] w-full bg-gray-400 mt-5"></div>
                <div class="flex justify-between mt-5">
                    <h1>Provisional</h1>
                    <h1 class="text-gray-500"><?= $total_amount ?>đ</h1>
                </div>
                <div class="flex justify-between mt-5">
                    <h1>Shipping fee</h1>
                    <h1 class="text-gray-500">Free of charge</h1>
                </div>
                <div class="h-[1px] w-full bg-gray-400 mt-5"></div>
                <div class="flex justify-between mt-5">
                    <h1 class="text-2xl">Total</h1>
                    <h1 class="text-2xl text-blue-500"><?= $total_amount ?>đ</h1>
                </div>
                <button name="order"
                    class="rounded-md bg-blue-700 text-xl px-5 py-3 text-white mt-3 self-end hover:bg-blue-800">ORDER</button>
            </div>
        </form>
    </div>
    <script>
        const wards = {
            HoanKiem: ["Chuong Duong", "Cua Nam", "Dong Xuan", "Hang Bac", "Hang Bong", "Hang Buom", "Hang Dao", "Hang Gai", "Hang Ma", "Hang Trong", "Ly Thai To", "Phan Chu Trinh", "Phuc Tan", "Tran Hung Dao", "Trang Tien"],
            BaDinh: ["Cong Vi", "Dien Bien", "Doi Can", "Giang Vo", "Kim Ma", "Lieu Giai", "Ngoc Ha", "Ngoc Khanh", "Nguyen Trung Truc", "Phuc Xa", "Quan Thanh", "Thanh Cong", "Truc Bach", "Vinh Phuc"],
            DongDa: ["Cat Linh", "Hang Bot", "Kham Thien", "Khuong Thuong", "Lang Ha", "Lang Thuong", "Nam Dong", "O Cho Dua", "Phuong Lien", "Phuong Mai", "Quang Trung", "Quoc Tu Giam", "Thinh Quang", "Trung Liet", "Trung Phung", "Van Chuong", "Van Mieu"],
            HaiBaTrung: ["Bach Khoa", "Bach Mai", "Cau Den", "Dong Mac", "Dong Nhan", "Dong Tam", "Le Dai Hanh", "Minh Khai", "Ngu Hiep", "Nguyen Du", "Pham Dinh Ho", "Quynh Loi", "Quynh Mai", "Thanh Luong", "Thanh Nhan", "Truong Dinh", "Vinh Tuy"],
            CauGiay: ["Dich Vong", "Dich Vong Hau", "Mai Dich", "Nghia Do", "Nghia Tan", "Quan Hoa", "Trung Hoa", "Yen Hoa"],
            ThanhXuan: ["Ha Dinh", "Khuong Dinh", "Khuong Mai", "Khuong Trung", "Nhan Chinh", "Phuong Liet", "Thanh Xuan Bac", "Thanh Xuan Nam", "Thanh Xuan Trung", "Thuong Dinh"],
            TayHo: ["Buoi", "Nhat Tan", "Phu Thuong", "Quang An", "Thuy Khue", "Tu Lien", "Xuan La", "Yen Phu"],
            LongBien: ["Bat Khoi", "Bo De", "Cu Khoi", "Duc Giang", "Gia Thuy", "Giang Bien", "Long Bien", "Ngoc Lam", "Ngoc Thuy", "Phuc Dong", "Phuc Loi", "Sai Dong", "Thuong Thanh", "Viet Hung"],
            NamTuLiem: ["Cau Dien", "Dai Mo", "Mac Thai To", "Me Tri", "Phu Do", "Phuong Canh", "Tay Mo", "Trung Van", "Xuan Phuong"],
            BacTuLiem: ["Co Nhue 1", "Co Nhue 2", "Dong Ngac", "Duc Thang", "Lien Mac", "Minh Khai", "Phu Dien", "Tay Tuu", "Thuy Phuong", "Xuan Dinh", "Xuan Tao", "Thuong Cat"],
            HoangMai: ["Dai Kim", "Dinh Cong", "Giap Bat", "Hoang Liet", "Hoang Van Thu", "Linh Nam", "Mai Dong", "Tan Mai", "Thanh Tri", "Thinh Liet", "Tran Phu", "Tuong Mai", "Van Dien", "Yen So"]
        };

        const districtSelect = document.getElementById("district");
        const wardSelect = document.getElementById("ward");

        districtSelect.addEventListener("change", function () {
            const selectedDistrict = districtSelect.value;
            wardSelect.innerHTML = "<option value='' selected>Select Ward</option>";
            if (wards[selectedDistrict]) {
                wards[selectedDistrict].forEach((ward) => {
                    const option = document.createElement("option");
                    option.value = ward;
                    option.textContent = ward;
                    wardSelect.appendChild(option);
                });
            }
        });
    </script>
    <?php
        include_once('footer.php');
    ?>
</body>

</html>