<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
if(isset($_GET['id'])){
    $cart_item_id = (int) $_GET['id'];
    $check = mysqli_query($conn,
        "SELECT ci.id 
         FROM cart_items ci
         JOIN cart c ON ci.cart_id = c.cart_id
         WHERE ci.id='$cart_item_id'
         AND c.user_id='$user_id'
         LIMIT 1"
    );
    if(mysqli_num_rows($check) == 1){
        mysqli_query($conn, "DELETE FROM cart_items WHERE id='$cart_item_id'");
    }
}
header("Location: cart.php");
exit();
?>