<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM movies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movies</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container movies-container">
    <h1>Select Movie</h1>

    <div class="ad-banner">
    <div class="ad-text">
        <h3>🔥Upcoming Movie🔥</h3>
        <p><strong>Movie:</strong> Inception</p>
        <p><strong>Release Date:</strong> 1 May 2026</p>

    </div>
</div>

    <?php while($row = mysqli_fetch_assoc($result)){ ?>
        <a href="seats.php?id=<?php echo $row['id']; ?>" class="movie-item">
            🎬 <?php echo $row['title']; ?> |
            🕒 <?php echo $row['show_time']; ?> |
            🎭 <?php echo $row['genre']; ?> |
            ⏱️ <?php echo $row['duration']; ?> |
            💵 <?php echo "Tk ".$row['price']; ?>
        </a>
    <?php } ?>

    <div class="logout-container">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

</body>
</html>