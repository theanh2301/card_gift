<?php
include 'db.php';
$orders = $conn->query("SELECT * FROM orders ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
<?php
include "header.html";
?>
    <h1>Order Management</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['user_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['total_amount']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['shipping_address']) ?></td>
                <td>
                    <a style="background-color: #e57373;" href="order_details.php?order_id=<?= htmlspecialchars($row['order_id']) ?>">View Details</a> |
                    <a href="edit2.php?order_id=<?= htmlspecialchars($row['order_id']) ?>">
                        <button type="button">Change Detail</button>
                    </a>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
