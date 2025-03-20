<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $category_id = intval($_POST['category_id']);
        $price = floatval($_POST['price']);
        $description = htmlspecialchars(trim($_POST['description']));
        $material = htmlspecialchars(trim($_POST['material']));

        if (!empty($name) && $category_id > 0 && $price > 0) {
            $stmt = $conn->prepare("INSERT INTO products (name, catagory_id, price, description, material) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sidss", $name, $category_id, $price, $description, $material);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: " . $_SERVER['PHP_SELF']); // Chuyển hướng để tránh submit lại
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }
        }
    }

    if (isset($_POST['delete_product'])) {
        $product_id = intval($_POST['product_id']);
        if ($product_id > 0) {
            $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $product_id);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: " . $_SERVER['PHP_SELF']); // Chuyển hướng để tránh submit lại
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }
        }
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

$products = $conn->query("SELECT p.product_id, p.name, c.name AS category_name, p.price, p.description, p.material 
                          FROM products p
                          JOIN categories c ON p.catagory_id = c.category_id
                          ORDER BY p.product_id ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="products.css">
    <style>
        select {
            width: 100%;
            padding: 8px;
            border: 2px solid #ccc;
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function deleteProduct(productId) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'delete_product=1&product_id=' + encodeURIComponent(productId)
                })
                    .then(response => response.text())
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => console.error('Lỗi:', error));
            }
        }
    </script>
</head>
<body>
<?php include "header.html"; ?>
<h1>Quản lý sản phẩm</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Tên sản phẩm" required>
    <select name="category_id" required>
        <option value="">Chọn danh mục</option>
        <?php while ($category = $categories->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($category['category_id']) ?>">
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="number" name="price" placeholder="Giá" step="0.01" required>
    <input type="text" name="description" placeholder="Mô tả">
    <input type="text" name="material" placeholder="Chất liệu">
    <button type="submit" name="add_product">Thêm sản phẩm</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Danh mục</th>
        <th>Giá</th>
        <th>Mô tả</th>
        <th>Chất liệu</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $products->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= number_format($row['price'], 2) ?> VNĐ</td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['material']) ?></td>
            <td>
                <button onclick="deleteProduct(<?= htmlspecialchars($row['product_id']) ?>)">Xóa</button>
                <a href="edit1.php?product_id=<?= htmlspecialchars($row['product_id']) ?>">
                    <button type="button">Sửa</button>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>