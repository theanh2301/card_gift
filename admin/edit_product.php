<?php
include 'db.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die("Product not found!");
}

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

$categories = $conn->query("SELECT category_id, name FROM categories");

$images = $conn->query("SELECT * FROM product_images WHERE product_id = $product_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

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
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin: 5px;
        }
    </style>
</head>
<body>
<?php
include "header.html";
?>
<div class="container">
    <h2 class="text-center">Edit Product</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Product Name:</label>
            <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Category:</label>
            <select name="category_id" class="form-select" disabled>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <option value="<?= $row['category_id']; ?>" <?= $row['category_id'] == $product['catagory_id'] ? 'selected' : '' ?>>
                        <?= $row['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock:</label>
            <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Price:</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Material:</label>
            <input type="text" name="material" class="form-control" value="<?= $product['material'] ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea name="description" class="form-control" readonly><?= $product['description'] ?></textarea>
        </div>
    </form>

    <h3>Product Images</h3>
    <form method="post">
        <div class="d-flex flex-wrap">
            <?php while ($row = $images->fetch_assoc()): ?>
                <div class="d-flex flex-column align-items-center">
                    <input type="checkbox" name="delete_images[]" value="<?= $row['image_id']; ?>">
                    <img src="<?= $row['image_url']; ?>" class="product-img">
                </div>
            <?php endwhile; ?>
        </div>
        <br>
        <button type="submit" class="btn btn-danger">Delete Selected Images</button>
    </form>

    <h3>Add New Images</h3>
    <form method="post" action="upload_image.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product_id; ?>">
        <div class="mb-3">
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
        </div>

        <div id="preview-container" class="d-flex flex-wrap"></div>

        <button type="submit" class="btn btn-primary mt-3">Upload Images</button>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function previewImages(event) {
        var previewContainer = $("#preview-container");
        previewContainer.html("");

        var files = event.target.files;
        if (files.length > 3) {
            alert("Only up to 3 images can be uploaded!");
            event.target.value = "";
            return;
        }

        $.each(files, function(index, file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = $("<img>").attr("src", e.target.result).addClass("product-img");
                previewContainer.append(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>

</body>
</html>
