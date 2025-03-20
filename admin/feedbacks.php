<?php
include 'db.php'; // Kết nối đến database

// Xử lý yêu cầu xóa feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback'])) {
    $feedback_id = intval($_POST['feedback_id']);
    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE feedback_id = ?");
    $stmt->bind_param("i", $feedback_id);
    if ($stmt->execute()) {
        echo "<script>alert('Feedback deleted successfully!'); window.location.href='feedbacks.php';</script>";
    } else {
        echo "<script>alert('Failed to delete feedback!');</script>";
    }
    $stmt->close();
}

// Truy vấn danh sách feedbacks
$sql = "SELECT f.feedback_id, a.username, p.name AS product_name, f.content, f.rating, f.date 
        FROM feedbacks f
        JOIN accounts a ON f.user_id = a.user_id  -- Sửa thành user_id thay vì account_id
        JOIN products p ON f.product_id = p.product_id
        ORDER BY f.date DESC";

$feedbacks = $conn->query($sql);

// Kiểm tra truy vấn có lỗi không
if (!$feedbacks) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <?php include "header.html"; ?>
    
    <h1>Feedback Management</h1>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Content</th>
            <th>Rating</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $feedbacks->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['feedback_id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['content']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="feedback_id" value="<?= $row['feedback_id'] ?>">
                        <button type="submit" name="delete_feedback" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
