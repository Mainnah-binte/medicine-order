<?php
$conn = mysqli_connect("localhost", "root", "", "medibuddy");
if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>