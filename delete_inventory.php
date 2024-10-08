<?php
require '../motor-parts/backend/connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM inventories WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Inventory deleted successfully']);
    } else {
        echo json_encode(['error' => 'Error deleting Inventory']);
    }
}

$conn->close();
?>