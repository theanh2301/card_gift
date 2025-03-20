<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT email FROM accounts WHERE access_token = '$token'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
      
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = trim($_POST['new_password']);
            $error = [];
            if (empty($new_password)) {
                $error['new_password'] = "Please enter a new password!";
            } else {
                if (strlen($new_password) < 8 || strlen($new_password) > 20) {
                    $error['new_password'] = "Password must be between 8 and 20 characters long!";
                }
            }
            if (empty($error)) {
                $email = mysqli_fetch_assoc($result)['email'];
                $sql_update_password = "UPDATE accounts SET password = '$new_password', access_token = NULL WHERE email = '$email'";
                mysqli_query($conn, $sql_update_password);
                echo "Password has been updated!";
                header("Location: login.php");
                exit();
            } 
        }
    } else {
        echo "Invalid token!";
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./register.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="breadcrumb">
            <a href="#">Home</a> &gt; <span>Reset Password</span>
        </div>
        <h1>RESET PASSWORD</h1>
        <p>To ensure security, please set a password with at least 8 characters</p>
        <form method="POST" class="register-form">
            <div class="input-container">
                <label for="new-password">New Password *</label>
                <input type="password" id="new-password" name="new_password" class="input-password">
                <i id="togglePassword" class="fas fa-eye eye-icon"></i>
                <i id="togglePasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
            </div>
            <span class="error"><?php echo $error['new_password'] ?? '' ;?></span>
            <div class="form-actions">
                <button type="submit" name="reset">Reset Password</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordHidden = document.getElementById('togglePasswordHidden');
        const passwordInput = document.getElementById('new-password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.style.display = 'none';
            togglePasswordHidden.style.display = 'inline'; 
        });

        togglePasswordHidden.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.style.display = 'none'; 
            togglePassword.style.display = 'inline'; 
        });
    </script>
</body>

</html>