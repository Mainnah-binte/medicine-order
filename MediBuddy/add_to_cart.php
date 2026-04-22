<?php
include('includes/config.php');
include('includes/db.php');
include('includes/auth.php');
$user_id = $_SESSION['user_id'];
if(isset($_POST['medicine_id'], $_POST['qty'])){
    $medicine_id = (int) $_POST['medicine_id'];
    $qty = (int) $_POST['qty'];
    if($qty < 1){
        $qty = 1;
    }
    $cartQuery = mysqli_query($conn, 
        "SELECT cart_id FROM cart WHERE user_id='$user_id' LIMIT 1"
    );
    if(mysqli_num_rows($cartQuery) > 0){
        $cart = mysqli_fetch_assoc($cartQuery);
        $cart_id = $cart['cart_id'];
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id) VALUES ('$user_id')");
        $cart_id = mysqli_insert_id($conn);
    }
    $checkItem = mysqli_query($conn,
        "SELECT id FROM cart_items 
         WHERE cart_id='$cart_id' 
         AND medicine_id='$medicine_id'"
    );
    if(mysqli_num_rows($checkItem) > 0){
        mysqli_query($conn,
            "UPDATE cart_items 
             SET quantity = quantity + $qty 
             WHERE cart_id='$cart_id' 
             AND medicine_id='$medicine_id'"
        );
    } else {
        mysqli_query($conn,
            "INSERT INTO cart_items (cart_id, medicine_id, quantity)
             VALUES ('$cart_id', '$medicine_id', '$qty')"
        );
    }
    header("Location: cart.php");
    exit();

} else {
    header("Location: medicines.php");
    exit();
}
?>