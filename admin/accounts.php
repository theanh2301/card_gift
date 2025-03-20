<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_account'])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);

        $stmt = $conn->prepare("INSERT INTO accounts (username, email, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $role);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_account'])) {
        $account_id = intval($_POST['account_id']); // Đúng biến được gửi từ form
        $stmt = $conn->prepare("DELETE FROM accounts WHERE user_id = ?"); // Đúng tên cột trong database
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->close();
    }
}

$accounts = $conn->query("SELECT * FROM accounts");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Accounts</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
<?php include "header.html"; ?>
    <h1>Account Management</h1>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="role" placeholder="Role" required>
        <button type="submit" name="add_account">Add Account</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $accounts->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_id']) ?></td> <!-- Đúng tên cột -->
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="account_id" value="<?= $row['user_id'] ?>"> <!-- Đúng biến -->
                        <button type="submit" name="delete_account" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
