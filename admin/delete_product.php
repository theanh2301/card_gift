<?php
include 'db.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die("Product not found!");
}

// Delete related images
$delete_images = $conn->query("SELECT image_url FROM product_images WHERE product_id = $product_id");
while ($row = $delete_images->fetch_assoc()) {
    if (file_exists($row['image_url'])) {
        unlink($row['image_url']);
    }
}
$conn->query("DELETE FROM product_images WHERE product_id = $product_id");

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
if ($stmt->execute()) {
    header("Location: show.php");
    exit();
} else {
    echo "Error deleting product: " . $stmt->error;
}
$conn->close();
?>
