<?php
include 'db.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
} else {
    die('Order ID not provided.');
}

$order_details = $conn->prepare("SELECT * FROM order_details WHERE order_id = ?");
if (!$order_details) {
    die('Error preparing the query: ' . $conn->error);
}

$order_details->bind_param("i", $order_id);
$order_details->execute();
$result = $order_details->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="details.css">
</head>
<body>
<?php
include "header.html";
?>
    <h1>Order Details #<?= htmlspecialchars($order_id) ?></h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No data available for this order.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
