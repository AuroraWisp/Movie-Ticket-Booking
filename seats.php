<?php
session_start();
include "db.php";
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$movie_id = $_GET['id'];
$rows = ['A','B','C','D','E'];

$error = ""; 
$selected_seats = []; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!isset($_POST['seat']) || empty($_POST['seat'])){
        $error = "Select seats to proceed";
    } else {
        $selected_seats = $_POST['seat'];
        header("Location: payment.php?movie_id=".$movie_id."&seats=".urlencode(implode(',', $selected_seats)));
        exit();
    }
} elseif(isset($_POST['seat'])) {
    $selected_seats = $_POST['seat']; 
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Select Seats</title>
<link rel="stylesheet" href="style.css">
<script>
function toggleSeat(checkbox) {
    if(checkbox.checked) {
        checkbox.parentNode.classList.add('selected');
    } else {
        checkbox.parentNode.classList.remove('selected');
    }
}
</script>
</head>
<body>

<div class="container">
<h2>Select Your Seats</h2>
<div class="discount-ad">
    🎉 Buy <b>3 or more seats</b> & get <b>10% discount</b> on total price!
</div>

<?php if($error != ""): ?>
    <div class="error-message" style="color:red; margin-bottom:10px;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="cinema-screen">SCREEN</div>

<form method="post" action="">

<?php
foreach($rows as $r){
    echo '<div class="seat-row">';
    $seats_row = mysqli_query($conn,"SELECT * FROM seats WHERE movie_id=$movie_id AND seat_no LIKE '$r%' ORDER BY seat_no ASC");
    while($s=mysqli_fetch_assoc($seats_row)){
        if($s['status']=='free'){

            $checked = in_array($s['seat_no'], $selected_seats) ? "checked" : "";
            $selected_class = $checked ? "selected" : "";
            echo '<label class="seat free '.$selected_class.'">';
            echo '<input type="checkbox" name="seat[]" value="'.$s['seat_no'].'" onclick="toggleSeat(this)" '.$checked.'>';
            echo $s['seat_no'];
            echo '</label>';
        } else {
            echo '<span class="seat booked">'.$s['seat_no'].'</span>';
        }
    }
    echo '</div>';
}
?>

<input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
<button type="submit">Proceed to Payment</button>
</form>

</div>
</body>
</html>