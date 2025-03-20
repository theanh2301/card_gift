<?php
include 'db.php';
include_once './PHPMailer/sendmail.php';
session_start();

function validateContactForm(){
    $check_email = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $check_phone = "/^\d{10}$/";
    $data = [
        'name'=>'',
        'email'=> '',
        'phone'=>'',
        'message'=>'',
    ];
    $error=[];
    
    if(empty($_POST['name'])){
        $error['name'] = "Please enter a Username!";
    } else {
        $data['name'] = htmlspecialchars(trim($_POST['name']));
        if(strlen($data['name']) < 3 || strlen($data['name']) > 20) {
            $error['name'] = "Username must be between 3 and 20 characters!";
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
    
    if(empty($_POST['phone'])){
        $error['phone'] = "Please enter a Phone number!";
    } else {
        $data['phone'] = trim($_POST['phone']);
        if(!preg_match($check_phone, $data['phone'])) {
            $error['phone'] = "Phone number must be 10 digits!";
        }
    }
    if(empty($_POST['message'])){
        $error['message'] = "Please enter a message!";
    } 
    return ['data'=>$data,'error'=>$error];
}
$contact_form = ['data' => [], 'error' => []];
$mess = [];
if (isset($_POST['contact'])) {
    $contact_form = validateContactForm();
    $data = $contact_form['data'];
    $error = $contact_form['error'];
    if (empty($error)) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $message = trim($_POST['message']);
    
    
        $addressMail = "manh.dt.02032003@gmail.com";
        $title = "New Contact Message from $name";
        $content = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
        $headers = "From: no-reply@yourwebsite.com";
    
        $mailer = new Mailer();
        $mailer->sendMail($title, $content, $addressMail);
        $_SESSION['success_message'] = "Your message has been sent successfully!";
        header("Location: intro_contact.php");
        exit();
    }
   
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./intro_contact.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="container">
        <div class="breadcrumb">
            <a href="#">Home</a> &gt; <span>Contact Craft & More Vietnam</span>
        </div>
        <div class="content">
            <h1>Papyrus Limited</h1>
            <p>Papyrus Limited, Shop for handmade birthday gifts, beautiful and meaningful valentine gifts, gifts for 20/10, 8/3, Christmas; Vintage decorative items for living rooms, shops, cafes.</p>
            <p>Greetings from Papyrus Limited,</p>
            <p>With decades of passion for making <span>handmade items</span> and selling <span>home decor</span>; especially fond of <span>vintage</span>, retro, and interesting nostalgic styles, Papyrus Limited is one of the favorite places whenever you need to buy a gift for occasions like <span>birthdays, November 20, October 20, March 8, Christmas, Valentine's Day</span>, New Year celebrations, or to collect home decor accessories for shops, restaurants, hotels, resorts, cafes, and homestays...</p>
            <p>Moreover, Papyrus Limited aims to bring a personal touch to each handmade gift, ensuring that the gifts are not only beautiful but also unique, with a highly customizable message at the best price.</p>
            <p>Papyrus Limited is also a place for those who share the same interests and passion for making handmade items, collecting, and creating retro and vintage-style home decor in <span>Ho Chi Minh City & Hanoi.</span></p>
            <p>With Love,</p>
            <p>Papyrus Limited</p>
        </div>
        <br>
        <h1>Send us a message</h1>
        <div class="form-container">
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Full Name*" value="<?= $data['name'] ?? '' ?>">
                <span class="error"><?php echo $error['name'] ?? '' ;?></span>
                <input type="email" name="email" placeholder="Email*"  value="<?= $data['email'] ?? '' ?>">
                <span class="error"><?php echo $error['email'] ?? '' ;?></span>
                <input type="tel" name="phone" placeholder="Phone*"  value="<?= $data['phone'] ?? '' ?>">
                <span class="error"><?php echo $error['phone'] ?? '' ;?></span>
                <textarea name="message" placeholder="Enter your message*"  value="<?= $data['message'] ?? '' ?>"></textarea>
                <span class="error"><?php echo $error['message'] ?? '' ;?></span><br>
                <button type="submit" name="contact">Send Contact</button>
            </form>
            <div class="contact-info">
                <div>
                    <i class="fas fa-map-marker-alt "></i>
                    <div>
                        <h2>Contact Address</h2>
                        <p>185 Doi Can Street, Doi Can, Ba Dinh, Hanoi</p>
                    </div>
                </div>
                <div>
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h2>Phone Number</h2>
                        <p>0903803556</p>
                        <p>Hanoi: 9am-7pm</p>
                    </div>
                </div>
                <div>
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h2>Email</h2>
                        <p>papyruslimited@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
        include "footer.php"
    ?>
</body>

</html>