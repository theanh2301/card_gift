<?php
session_start();
include 'db.php';

function validateRegister(){
    global $conn;
    $check_email = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $check_phone = "/^\d{10}$/";
    $data = [
        'username'=>'',
        'email'=> '',
        'password'=>'',
        'phone'=>''
    ];
    $error=[];
    
    if(empty($_POST['username'])){
        $error['username'] = "Please enter a Username!";
    } else {
        $data['username'] = htmlspecialchars(trim($_POST['username']));
        $sql_query_username = "SELECT * from accounts where username = '".$data['username']."'";
        $result_query_username = mysqli_query($conn, $sql_query_username);
        $is_have_username = mysqli_num_rows($result_query_username);
        if(strlen($data['username']) < 3 || strlen($data['username']) > 20) {
            $error['username'] = "Username must be between 3 and 20 characters!";
        }elseif($is_have_username > 0){
            $error['username'] = "Username already exists, please give another username!";
        }
    }
    
    if(empty($_POST['email'])){
        $error['email'] = "Please enter an email";
    } else {
        if(!preg_match($check_email, trim($_POST['email']))){
            $error['email'] = "Email is not in the correct format!";
        } else {
            $data['email'] = trim($_POST['email']);
        }
    }
    
    if(empty($_POST['password'])){
        $error['password'] = "Please enter a password!";
    } else {
        $data['password'] = trim($_POST['password']);
        if(strlen($data['password']) < 8 || strlen($data['password']) > 20) {
            $error['password'] = "Password must be between 8 and 20 characters long!";
        }
    }
    
    if(empty($_POST['phone'])){
        $error['phone'] = "Please enter a Phone number!";
    } else {
        $data['phone'] = trim($_POST['phone']);
        if(!preg_match($check_phone, $data['phone'])) {
            $error['phone'] = "Phone number must be 10 digits!";
        }
    }
    
    return ['data'=>$data,'error'=>$error];
}
$register_form = ['data' => [], 'error' => []];
$mess = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $register_form = validateRegister();
    $data = $register_form['data'];
    $error = $register_form['error'];
    if (empty($error)) {
        $username = mysqli_real_escape_string($conn, $data['username']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $password = mysqli_real_escape_string($conn, $data['password']);
        $phone = mysqli_real_escape_string($conn, $data['phone']);

        $query = "INSERT INTO accounts (username,password,email,phone) VALUES ('$username', '$password', '$email','$phone')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['user'] = ['username' => $username,
                                 'password'=> $password];
            header("Location: login.php");
            exit();
        } else {
            $error['general'] = "Error: Unable to add new user. " . mysqli_error($conn);
        }
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./register.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="breadcrumb">
            <a href="#">Home</a> &gt; <span>Register Account</span>
        </div>
        <h1>REGISTER ACCOUNT</h1>
        <p>If you do not have an account, please register here</p>
        <form method="POST" class="register-form">
            <div>
                <label for="last">Username:</label>
                <input type="text" id="username" name="username" value="<?= $data['username'] ?? '' ?>">
            </div>
            <span class="error"><?php echo $error['username'] ?? '' ;?></span>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $data['email'] ?? '' ?>">
            </div>
            <span class="error"><?php echo $error['email'] ?? '' ;?></span>
            <div class="input-container">
                <label for="pass">Password:</label>
                <input type="password" id="password" name="password" class="input-password">
                <i id="togglePassword" class="fas fa-eye eye-icon"></i>
                <i id="togglePasswordHidden" class="fas fa-eye-slash eye-icon" style="display: none;"></i>
            </div>
            <span class="error"><?php echo $error['password'] ?? '' ;?></span>
            <div>
                  <label for="phone">Phonenumber:</label>
                  <input type="tel" id="phone" name="phone"  value="<?= $data['phone'] ?? '' ?>">
            </div>
            <span class="error"><?php echo $error['phone'] ?? '' ;?></span>
            <div class="form-actions">
                <button type="submit" name="register">Register</button>
                <a href="login.php">Login</a>
            </div>
        </form>
    </div>
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