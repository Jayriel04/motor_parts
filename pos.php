<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css" />
    <link rel="stylesheet" href="../motor-parts/src/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-primary-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">SALES AND INVENTORY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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

    <div class="px-3">
    <h3>POS</h3>

    <div class="row">
        <div class="col px-3">
            <h6>Items</h6>
            <select id="item-select" class="form-select" aria-label="Default select example">
                <option value="" disabled selected>Please select an item</option>
                <!-- // Generate options for the select dropdown based on database query results -->
                <?php
                // Establish a connection to your database
                require '../motor-parts/backend/connection.php';
                // Query to fetch items from the database
                $sql = "SELECT item_id, name, price FROM items";
                $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['item_id'] . "' data-price='" . $row['price'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="col px-3">
            <h6>Quantity</h6>
            <div class="input-group input-group">
                <input id="quantity-input" type="number" class="form-control text-center" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
        </div>
        <div class="col">
        <button id="add-item-btn" type="button" class="btn btn-light mt-4" 
        style="background-color: #f8f9fa; color: #000; transition: background-color 0.3s, color 0.3s;"
        onmouseover="this.style.backgroundColor='blue'; this.style.color='white';"
        onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.color='#000';"
        onclick="window.location.reload();">Add Item</button>
    </div>
    
    
<script>

        //adding item
        // Keep track of selected items and their quantities
        let selectedItems = [];

        document.getElementById('add-item-btn').addEventListener('click', function() {
                const itemSelect = document.getElementById('item-select');
                const quantityInput = document.getElementById('quantity-input');

                const selectedItem = itemSelect.options[itemSelect.selectedIndex];
                const itemId = selectedItem.value;
                const quantity = quantityInput.value;

                // Add the selected item and quantity to the array
                selectedItems.push({ itemId, quantity });

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'insert_pos_data.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`item_id=${itemId}&quantity=${quantity}`);
            });

        //for deletion
        function deleteItem(itemId) {
                if (confirm("Are you sure you want to delete this item?")) {
                    // Send an AJAX request to delete the item
                    fetch('delete_POS.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ itemId: itemId }),
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
</script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>