<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$pass'");

    if(mysqli_num_rows($q) > 0){
        $_SESSION['user'] = $email;
        header("Location: movies.php");
        exit();
    } else {
        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>User Login</h2>

<?php if(isset($error)){ echo "<p style='color:red;'>$error</p>"; } ?>

<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>

<div class="footer">
New user ? <a href="register.php">Register here</a>
</div>
</div>

</body>
</html>