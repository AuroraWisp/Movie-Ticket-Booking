<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['movie_id']) || !isset($_GET['seats'])){
    die("Invalid request. Please complete payment first.");
}

$movie_id = intval($_GET['movie_id']);
$seats = explode(',', $_GET['seats']);

$user_email = mysqli_real_escape_string($conn, $_SESSION['user']);
$res = mysqli_query($conn,"SELECT id FROM users WHERE email='$user_email'");
$user = mysqli_fetch_assoc($res);
if(!$user){
    die("User not found.");
}
$user_id = $user['id'];

$movieRes = mysqli_query($conn,"SELECT price, title FROM movies WHERE id=$movie_id");
$movie = mysqli_fetch_assoc($movieRes);
$price_per_seat = isset($movie['price']) ? floatval($movie['price']) : 200;

$total_seats = count($seats);
$total_price = $total_seats * $price_per_seat;
$discount = 0;
if($total_seats >= 3){
    $discount = $total_price * 0.10;
}
$final_price = $total_price - $discount;
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Success</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>🎉 Booking Successful</h2>

    <p>Movie: <b><?php echo htmlspecialchars($movie['title']); ?></b></p>
    <p>Your seats: <b><?php echo htmlspecialchars(implode(', ', $seats)); ?></b></p>
    <p>Paid: <b><?php echo number_format($final_price, 2); ?>/=</b></p>

    <a href="movies.php" class="btn">Book Another Ticket</a>
    <a href="logout.php" class="btn">Logout</a>
</div>

</body>
</html>