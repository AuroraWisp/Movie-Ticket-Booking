<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['movie_id']) || !isset($_GET['seats'])){
    die("Invalid request. Please select seats first.");
}

$movie_id = intval($_GET['movie_id']);
$selected_seats = explode(',', $_GET['seats']);

$movie_res = mysqli_query($conn, "SELECT * FROM movies WHERE id=$movie_id");
if(!$movie_res) {
    die("Database query failed: " . mysqli_error($conn));
}

$movie = mysqli_fetch_assoc($movie_res);
if(!$movie){
    die("Movie not found. Please go back and select a valid movie.");
}

$seat_price = isset($movie['price']) ? floatval($movie['price']) : 200;
$total_seats = count($selected_seats);
$total_price = $seat_price * $total_seats;

$discount = 0;
if($total_seats >= 3){
    $discount = $total_price * 0.10;
}
$final_price = $total_price - $discount;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $card_number = $_POST['card_number'] ?? '';
    $card_number = preg_replace('/\D/', '', $card_number); 

    if(strlen($card_number) < 12 || strlen($card_number) > 19){
        $error = "Invalid card number. Please enter a valid card number.";
    } else {
        $user_email = mysqli_real_escape_string($conn, $_SESSION['user']);
        $user_res = mysqli_query($conn,"SELECT id FROM users WHERE email='$user_email'");
        $user = mysqli_fetch_assoc($user_res);
        $user_id = $user['id'];

        foreach($selected_seats as $seat_no){
            $seat_safe = mysqli_real_escape_string($conn, $seat_no);

            mysqli_query($conn,"UPDATE seats SET status='booked' WHERE movie_id=$movie_id AND seat_no='$seat_safe'");

            mysqli_query($conn,"INSERT INTO bookings(user_id, movie_id, seat_no, payment_status)
                VALUES($user_id, $movie_id, '$seat_safe', 'Paid')");
        }

        header("Location: success.php?movie_id=$movie_id&seats=".urlencode(implode(',', $selected_seats)));
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - <?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error { color: red; margin-bottom: 10px; }
        input[type="text"] { padding: 8px; width: 250px; margin-bottom: 10px; }
        button { padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <h2>Payment for "<?php echo htmlspecialchars($movie['title']); ?>"</h2>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <p>Selected Seats: <b><?php echo htmlspecialchars(implode(', ', $selected_seats)); ?></b></p>
    <p>Price per seat: <b><?php echo number_format($seat_price, 2); ?>/=</b></p>
    <p>Total seats: <b><?php echo $total_seats; ?></b></p>
    <p>Total price: <b><?php echo number_format($total_price, 2); ?>/=</b></p>
    <?php if($discount > 0): ?>
        <p>Discount (10%): <?php echo number_format($discount, 2); ?>/=</p>
    <?php endif; ?>
    <h3>Amount to Pay: <?php echo number_format($final_price, 2); ?>/=</h3>

    <form method="post" action="">
        <label for="card_number">Enter Card Number:</label><br>
        <input type="text" id="card_number" name="card_number" placeholder=" " required><br>
        <button type="submit">Confirm Payment</button>
    </form>
</div>
</body>
</html>