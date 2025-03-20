<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $user_id = intval($_POST['user_id']);
    $date = htmlspecialchars($_POST['date']);
    $total_amount = floatval($_POST['total_amount']);
    $status = htmlspecialchars($_POST['status']);
    $shipping_address = htmlspecialchars($_POST['shipping_address']);

    $stmt = $conn->prepare("UPDATE orders SET user_id=?, date=?, total_amount=?, status=?, shipping_address=? WHERE order_id=?");
    $stmt->bind_param("isdssi", $user_id, $date, $total_amount, $status, $shipping_address, $order_id);
    
    if ($stmt->execute()) {
        header("Location: orders.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
<?php
include "header.html";
?>
    <h1>Edit Order</h1>
    <form method="POST">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
        <label>User ID:</label>
        <input type="number" name="user_id" value="<?= htmlspecialchars($order['user_id']) ?>" required>
        <label>Date:</label>
        <input type="text" name="date" value="<?= htmlspecialchars($order['date']) ?>" required>
        <label>Total Amount:</label>
        <input type="number" name="total_amount" step="0.01" value="<?= htmlspecialchars($order['total_amount']) ?>" required>
        <label>Status:</label>
        <input type="text" name="status" value="<?= htmlspecialchars($order['status']) ?>" required>
        <label>Shipping Address:</label>
        <input type="text" name="shipping_address" value="<?= htmlspecialchars($order['shipping_address']) ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
