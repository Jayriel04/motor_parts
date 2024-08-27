<?php
require '../motor-parts/backend/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Insert the item_id and quantity into the database table
    $insertQuery = "INSERT INTO pos_data (item_id, quantity) VALUES ('$item_id', '$quantity')";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
?>