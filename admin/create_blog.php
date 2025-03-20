<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date("Y-m-d H:i:s");

    $image_url = "";
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }

    $sql = "INSERT INTO blogs (title, content, date, image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $content, $date, $image_url);

    if ($stmt->execute()) {
        header("Location: blog_list.php");
        exit();
    } else {
        echo "Error publishing blog: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .blog-img {
            width: 100%;
            height: auto;
            max-width: 400px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
include "header.html";
?>
<div class="container">
    <h2 class="text-center">Create New Blog Post</h2>

    <form action="create_blog.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content:</label>
            <textarea name="content" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            <img id="image-preview" class="blog-img d-none">
        </div>

        <button type="submit" class="btn btn-primary w-100">Publish Blog</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function previewImage(event) {
        var imagePreview = $("#image-preview");
        var reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.attr("src", e.target.result);
            imagePreview.removeClass("d-none");
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
