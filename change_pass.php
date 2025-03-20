<?php
session_start();
include 'db.php';



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change'])) {
    $old_password = trim($_POST['old-password']);
    $new_password = trim($_POST['new-password']);
    $confirm_password = trim($_POST['confirm-password']);
    $error = [];


    $username = $_SESSION['username'];
    $query = "SELECT password FROM accounts WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($old_password)) {
        $error['old-password'] = "Please enter a old password!";
    } else if (strlen($old_password) < 8 || strlen($old_password) > 20) {
        $error['old-password'] = "Password must be between 8 and 20 characters long!";
    }
    if ($row && $row['password'] !== $old_password) {
        $error['old-password'] = "Old password is incorrect!";
    }
    if (empty($new_password)) {
        $error['new-password'] = "Please enter a new password!";
    } else if (strlen($new_password) < 8 || strlen($new_password) > 20) {
        $error['new-password'] = "Password must be between 8 and 20 characters long!";
    }
    if (empty($confirm_password)) {
        $error['confirm-password'] = "Please enter a confirm password!";
    } else if ($new_password !== $confirm_password) {
        $error['confirm-password'] = "New password and confirm password do not match!";
    }
    if (empty($error)) {
        $sql_update_password = "UPDATE accounts SET password = '$new_password' WHERE username = '$username'";
        if (mysqli_query($conn, $sql_update_password)) {
            header("Location: home.php");
            exit();
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./register.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="breadcrumb">
            <a href="#">Home</a> &gt; <span>Change Password</span>
        </div>
        <h1>Change Password</h1>
        <p>To ensure security, please set a password with at least 8 characters</p>
        <form method="POST" class="register-form">
            <div class="input-container">
                <label for="old-password">Old Password *</label>
                <input type="password" id="old-password" name="old-password" class="input-password">
                <i id="toggleOldPassword" class="fas fa-eye eye-icon"></i>
                <i id="toggleOldPasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
            </div>
            <span class="error"><?php echo $error['old-password'] ?? '' ;?></span>
            <div class="input-container">
                <label for="pass">New Password *</label>
                <input type="password" id="new-password" name="new-password" class="input-password">
                <i id="toggleNewPassword" class="fas fa-eye eye-icon"></i>
                <i id="toggleNewPasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
            </div>
            <span class="error"><?php echo $error['new-password'] ?? '' ;?></span>
            <div class="input-container">
                <label for="confirm-password">Confirm Password *</label>
                <input type="password" id="confirm-password" name="confirm-password" class="input-password">
                <i id="toggleConfirmPassword" class="fas fa-eye eye-icon"></i>
                <i id="toggleConfirmPasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
            </div>
            <span class="error"><?php echo $error['confirm-password'] ?? '' ;?></span>
            <div class="form-actions">
            <button type="submit" name="change">Change Password</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        const toggleOldPassword = document.getElementById('toggleOldPassword');
        const toggleOldPasswordHidden = document.getElementById('toggleOldPasswordHidden');
        const oldPasswordInput = document.getElementById('old-password');

        toggleOldPassword.addEventListener('click', function() {
            const type = oldPasswordInput.type === 'password' ? 'text' : 'password';
            oldPasswordInput.type = type;
            this.style.display = 'none';
            toggleOldPasswordHidden.style.display = 'inline';
        });

        toggleOldPasswordHidden.addEventListener('click', function() {
            const type = oldPasswordInput.type === 'password' ? 'text' : 'password';
            oldPasswordInput.type = type;
            this.style.display = 'none';
            toggleOldPassword.style.display = 'inline';
        });

        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const toggleNewPasswordHidden = document.getElementById('toggleNewPasswordHidden');
        const newPasswordInput = document.getElementById('new-password');

        toggleNewPassword.addEventListener('click', function() {
            const type = newPasswordInput.type === 'password' ? 'text' : 'password';
            newPasswordInput.type = type;
            this.style.display = 'none';
            toggleNewPasswordHidden.style.display = 'inline';
        });

        toggleNewPasswordHidden.addEventListener('click', function() {
            const type = newPasswordInput.type === 'password' ? 'text' : 'password';
            newPasswordInput.type = type;
            this.style.display = 'none';
            toggleNewPassword.style.display = 'inline';
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const toggleConfirmPasswordHidden = document.getElementById('toggleConfirmPasswordHidden');
        const confirmPasswordInput = document.getElementById('confirm-password');

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            this.style.display = 'none';
            toggleConfirmPasswordHidden.style.display = 'inline';
        });

        toggleConfirmPasswordHidden.addEventListener('click', function() {
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            this.style.display = 'none';
            toggleConfirmPassword.style.display = 'inline';
        });
    </script>
</body>

</html>