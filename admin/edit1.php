<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $name = htmlspecialchars($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $material = htmlspecialchars($_POST['material']);

    $stmt = $conn->prepare("UPDATE products SET name=?, catagory_id=?, price=?, description=?, material=? WHERE product_id=?");
    $stmt->bind_param("sidssi", $name, $category_id, $price, $description, $material, $product_id);
    
    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
<?php
include "header.html";
?>
    <h1>Edit Product</h1>
    <form method="POST">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        <input type="number" name="category_id" value="<?= htmlspecialchars($product['catagory_id']) ?>" required>
        <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        <input type="text" name="description" value="<?= htmlspecialchars($product['description']) ?>">
        <input type="text" name="material" value="<?= htmlspecialchars($product['material']) ?>">
        <button type="submit">Update</button>
    </form>
</body>
</html>
