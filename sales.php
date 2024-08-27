<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <link rel="stylesheet" href="../motor-parts/src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css" />
    <link rel="stylesheet" href="../motor-parts/src/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-primary-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">SALES AND INVENTORY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">Account Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="items.php">Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory.php">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pos.php">POS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sales.php">Sales</a>
                    </li>
                </ul>
            </div>
            <button class="btn btn-outline-success" type="submit"><a class="logout" href="login.php">Logout</a></button>
        </div>
    </nav>

    <div class="row">
        <div class="col-auto me-auto mx-3">
            <h1>Sales</h1>
        </div>
    </div>



    <table class="table table-bordered text-center">
        <tr>
            <th>SALES ID</th>
            <th>Date/Time</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>

        </tr>
        <tbody>
            <!-- fetching the data from database -->
        <?php
        require '../motor-parts/backend/connection.php';

        $query_pos_data = "SELECT pos_id, created_at, quantity, item_id FROM pos_data";
        $query_items = "SELECT item_id, name, price FROM items";
        $result_pos_data = $conn->query($query_pos_data);

        if ($result_pos_data->num_rows > 0) {
            while ($row = $result_pos_data->fetch_assoc()) {
                echo "<tr id='code_" . $row["pos_id"] . "'>";
                echo "<th scope='row'>" . $row["pos_id"] . "</th>";
                echo "<td id='date_" . $row["pos_id"] . "'>" . $row["created_at"] . "</td>";
                echo "<td id='quantity_" . $row["pos_id"] . "' >" . $row["quantity"] . "</td>";

                // Fetch the corresponding item data based on item_id
                $query_item = "SELECT name, price FROM items WHERE item_id = " . $row["item_id"];
                $result_item = $conn->query($query_item);

                if ($result_item->num_rows > 0) {
                    $item_row = $result_item->fetch_assoc();

                    echo "<td id='name_" . $row["pos_id"] . "' style='display: none;'>" . $item_row["name"] . "</td>";
                    echo "<td id='price_" . $row["pos_id"] . "' style='display: none;'>" . $item_row["price"] . "</td>";

                    echo "<td id='total_" . $row["pos_id"] . "'>" . ($row["quantity"] * $item_row["price"]) . "</td>";
                    echo "<td>
                        <button type='button' class='btn btn-primary mr-2' onclick='selectRow(" . $row["pos_id"] . ", " . $row["quantity"] . ", \"" . $item_row["name"] . "\", " . $item_row["price"] . ", \"" . $row["created_at"] . "\", " . $row["pos_id"] . ")' data-bs-toggle='modal' data-bs-target='#receiptModal'>Receipt</button>
                        <button type='button' class='btn btn-danger' onclick='deleteItem(" . $row['pos_id'] . ")'>Delete</button>
                        </td>";
                    echo "</tr>";
                } else {
                    echo "Item not found for item_id: " . $row["item_id"];
                }
            }
        } else {
            // echo "0 results";
        }
        ?>
        </tbody>
    </table>

    <!-- Modal for reciept -->
    <form action="sales_data.php" method="post">
    <input type="hidden" id="item_id" name="item_id">
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Transaction's Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Date: 
                        <span id="sales_date"></span>
                        <input type="hidden" id="sales_dates" name="sales_dates">
                    </p>
                    <p>
                        Sales ID: 
                        <span id="sales_id"></span>
                    </p>
                    <!-- Receipt content goes here -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Quantity</th>
                                <th>Product</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <p>
                                        <span id="quantity"></span>
                                        <input type="hidden" id="sales_quantity" name="sales_quantity">
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <span id="item_name"></span>
                                        <input type="hidden" id="sales_name" name="sales_name">
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <span id="price"></span>
                                        <input type="hidden" id="sales_price" name="sales_price">
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p id="total_row">
                    Total: 0
                    <input type="hidden" id="sales_total" name="sales_total">
                </p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="record" id="recordBtn" onclick="showAlert()">Record</button>
                </div>
            </div>
        </div>
    </div>
</form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function selectRow(itemId, itemQuantity, itemName, itemPrice, salesDate, salesId) {
            document.getElementById('quantity').innerText = itemQuantity;
            document.getElementById('item_name').innerText = itemName;
            document.getElementById('price').innerText = itemPrice;
            document.getElementById('sales_date').innerText = salesDate;
            document.getElementById('sales_id').innerText = salesId;
            document.getElementById('total_row').innerText = 'Total: ' + (itemQuantity * itemPrice);

            // Set the values of the hidden input fields
            document.getElementById('item_id').value = itemId;
            document.getElementById('sales_dates').value = salesDate;
            document.getElementById('sales_quantity').value = itemQuantity;
            document.getElementById('sales_name').value = itemName;
            document.getElementById('sales_price').value = itemPrice;
        }

    
        //for deletion of data
        function deleteItem(itemId) {
            if (confirm("Are you sure you want to delete this item?")) {
                // Send an AJAX request to delete the item
                fetch('delete_POS.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        itemId: itemId
                    }),
                })
                    .then(response => {
                        if (response.ok) {
                            // Reload the page after successful deletion
                            location.reload();
                        } else {
                            alert('Failed to delete item');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        }

        function showAlert() {
            alert('Record Succesfuly');
        }

    </script>
</body>

</html>