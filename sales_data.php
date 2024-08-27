<?php 
if(isset($_POST['record'])) {
    // Retrieve the form data
    $item_id = $_POST['item_id'];
    $sales_date = $_POST['sales_dates'];
    $quantity = $_POST['sales_quantity'];
    $item_name = $_POST['sales_name'];
    $price = $_POST['sales_price'];
    
    // Calculate the total
    $total = $quantity * $price;

    // Connect to your database
    require '../motor-parts/backend/connection.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the form data into the sales table
    $sql = "INSERT INTO sales (sales_id, date, quantity, name, price, total) 
            VALUES ('$item_id', '$sales_date', '$quantity', '$item_name', '$price', '$total')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    // Redirect back to the page after processing
    header('Location: sales.php');
    exit();
}
?>