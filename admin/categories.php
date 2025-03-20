<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = htmlspecialchars($_POST['name']);
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_category'])) {
        $category_id = intval($_POST['category_id']);
        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $stmt->close();
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
<?php include "header.html"; ?>
    <h1>Category Management</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Category Name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['category_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
                        <button type="submit" name="delete_category">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
