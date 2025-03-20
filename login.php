<?php
session_start();

if (isset($_SESSION['username'])) {
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    header("Location: login.php");
    exit();
}

include 'db.php';
include_once './PHPMailer/sendmail.php';


function validate_login()
{
    $error = [];
    $data = [
        'email' => '',
        'password' => '',
    ];
    $check_email = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if (empty($_POST['email'])) {
        $error['email'] = "Please enter an email";
    } else {
        if (!preg_match($check_email, trim($_POST['email']))) {
            $error['email'] = "Email is not in the correct format!";
        } else {
            $data['email'] = trim($_POST['email']);
        }
    }
    if (empty($_POST['password'])) {
        $error['password'] = "Please enter a password!";
    } else {
        $data['password'] = trim($_POST['password']);
    }

    return ['data' => $data, 'error' => $error];
}
$login_form = ['data' => [], 'error' => []];
$mess = "";
$mess_send='';  
$result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_form =  validate_login();
    $data =  $login_form['data'];
    $error = $login_form['error'];
    if (empty($error)) {
        $email = mysqli_real_escape_string($conn, $data['email']);
        $password = $data['password'];
        $query = "SELECT username, email, password,role FROM accounts WHERE email = '$email'";
        echo "Query: " . $query;
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if ($password === $row['password']) {
                session_start();
                $_SESSION['username'] = $row['username'];
                if ($row['role'] == 0) {
                    header("Location: home.php");
                    exit();
                } else {
                    header("Location: ./admin/products.php");
                    exit();
                }
            } else {
                $mess = "Incorrect password!";
            }
        } else {
            $mess = "Incorrect email!";
        }
    }
}

if (isset($_POST['reset_pass'])) {    
    $email = trim($_POST['forgot_email']);
    // bảo vệ giá trị email khỏi các tấn công SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT email FROM accounts WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $access_token = md5($email.time()); 
        $sql_update_token = "UPDATE accounts SET access_token ='$access_token' WHERE email ='$email'";
        $result_token = mysqli_query($conn,$sql_update_token) or die(mysqli_error($conn));
        if($result_token){
          
            $addressMail = $email;
            $title = "Reset Password";
            $content = "Click the following link to reset your password: http://cardgift.ct.ws/nhom7/src/reset_pass.php?token=$access_token";
            $headers = "From: no-reply@yourwebsite.com";

            $mailer = new Mailer();
            $mailer->sendMail($title, $content, $addressMail);
            header("Location: check_email.php");
            exit();
        } else {
            $mess_send = "Error updating token!";
        }
    } else {
        $mess_send = "Email does not exist!";
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./login.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="main">
        <div class="breadcrumb">
            <a href="#">Home</a> &gt; <span>Login</span>
        </div>
        <h1 class="title">LOGIN</h1>
        <div class="content">
            <div class="left">
                <p>If you already have an account, log in here.</p>
                <form method="POST">
                    <span class="error"><?php echo $mess; ?></span>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" value="<?= $data['email'] ?? '' ?>">
                        <span class="error"><?php echo $error['email'] ?? ''; ?></span>
                    </div>
                    <div class="input-container">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <i id="togglePassword" class="fas fa-eye eye-icon"></i>
                        <i id="togglePasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
                    </div>
                    <span class="error"><?php echo $error['password'] ?? ''; ?></span>
                    <div>
                        <button type="submit" id="log_in" name="login">Log In</button>
                        <a href="register.php" id="register">Register</a>
                    </div>
                </form>
            </div>
            <div class="right">
                <p>Forgot your password? Enter your email address to retrieve your password via email.</p>
                <form method="POST">
                    <label for="forgot-email">Email:</label>
                    <input type="email" id="forgot-email" name="forgot_email" placeholder="Email" required>
                    <button type="submit" name="reset_pass">Retrieve Password</button>
                </form>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordHidden = document.getElementById('togglePasswordHidden');
        const passwordInput = document.getElementById('password');

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