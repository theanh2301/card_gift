<?php
include 'db.php';
session_start();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($row['title']) ? $row['title'] : 'Blog Detail'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./blogs_detail.css">
</head>

<body class="bg-gray-100 p-0 m-0">
    <?php
    include "header.php";
    if (isset($_GET['blog_id'])){
        $blog_id = $_GET['blog_id'];
        $sql_blog_detail = "SELECT title, content, image_url FROM blogs WHERE blog_id = '$blog_id'";
        $result = mysqli_query($conn, $sql_blog_detail);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            $row = null;
        }
    }
    ?>

    <div class="max-w-7xl min-h-screen mx-auto mt-[80px] bg-white p-6 rounded-lg shadow-lg">
        <img src="<?php echo isset($row['image_url']) ? $row['image_url'] : 'default_image.jpg'; ?>" 
             alt="Blog Image" 
             class="w-full h-[500px] object-cover rounded-lg mb-6">

        <h2 class="text-3xl font-bold text-gray-800 mb-4">
            <?php echo isset($row['title']) ? $row['title'] : 'No Title'; ?>
        </h2>

        <p class="text-lg text-gray-600 leading-relaxed">
            <?php echo isset($row['content']) ? nl2br($row['content']) : 'No Content Available'; ?>
        </p>
    </div>
    
    <?php
        include "footer.php";
    ?>
</body>


</html>