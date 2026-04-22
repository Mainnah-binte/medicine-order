<?php

function getOrderTotal($conn, $order_id) {

    $stmt = $conn->prepare("
        SELECT SUM(quantity * price) AS total
        FROM order_items
        WHERE order_id = ?
    ");

    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'] ? $result['total'] : 0;
}

?>