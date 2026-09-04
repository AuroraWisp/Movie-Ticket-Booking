<?php
$conn = mysqli_connect("localhost","root","","movie_booking");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
