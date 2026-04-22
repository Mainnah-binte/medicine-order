<?php
include('../includes/config.php');
include('../includes/db.php');
include('../includes/admin_auth.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $medicine_id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT image FROM medicines WHERE medicine_id = $medicine_id");
    if (mysqli_num_rows($query) > 0) {
        $medicine = mysqli_fetch_assoc($query);
        $image_name = $medicine['image'];
        $delete = mysqli_query($conn, "DELETE FROM medicines WHERE medicine_id = $medicine_id");
        if ($delete) {
            if ($image_name != 'default.png' && file_exists("../uploads/" . $image_name)) {
                unlink("../uploads/" . $image_name);
            }
            header("Location: manage_medicines.php?msg=deleted");
        } else {
            header("Location: manage_medicines.php?msg=error");
        }
    } else {
        header("Location: manage_medicines.php?msg=notfound");
    }
} else {
    header("Location: manage_medicines.php");
}
exit();