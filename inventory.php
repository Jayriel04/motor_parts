<!-- saving the data to inventories table -->
<?php
require '../motor-parts/backend/connection.php';
// $items = array();
// Retrieve user input
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $attributes = $_POST['attributes'];
    $price = $_POST['price'];
    $inventory = $_POST['inventory'];

    $sql = "INSERT INTO inventories (id, name, attributes, price, inventory) 
            VALUES ('null', '$name', '$attributes', '$price', '$inventory')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the items page
        header('location: inventory.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
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
            <button class="btn btn-outline-success" type="submit">Logout</button>
        </div>
    </nav>

    <div class="row">
        <div class="col-auto me-auto mx-3">
            <h1>Inventory</h1>
        </div>
        <div class="col-auto mx-4 mt-2"> <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Add
            </button>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Attributes</th>
            <th>Price</th>
            <th>Inventory</th>
            <th>Action</th>
        </tr>
        
        <tbody>
        <?php
            $sql_select = "SELECT * FROM inventories"; 
            $result = $conn->query($sql_select);
                // $items = array();
                if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    // array_push($items, $row);
                    echo "<tr>";
                    echo "<th scope='row'>" . $row["id"] . "</th>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["attributes"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["inventory"] . "</td>";
                    echo "<td>
                            <button type='button' class='btn btn-primary mr-2' data-bs-toggle='modal' data-bs-target='#editModal'onclick='test(". $row['id'] .")'>Edit</button>
                            <button type='button' class='btn btn-danger' onclick='deleteInventory(" . $row['id'] . ")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }                
            } else {
                echo "0 results";
            }
        ?>
    </table>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <form action="edit_inventory.php" method="post">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                            <label for="itemId" class="col-sm-2 col-form-label">Id:</label>
                            <div class="col-sm-10 mb-3">
                                <input type="number" class="form-control" id="itemId" name="id" readonly>
                            </div>
                            <label for="itemName" class="col-sm-2 col-form-label">Name:</label>
                            <div class="col-sm-10 mb-3">
                                <input type="text" class="form-control" id="itemName" name="name">
                            </div>
                            <label for="itemAttributes" class="col-sm-2 col-form-label">Attributes:</label>
                            <div class="col-sm-10 mb-3">
                                <input type="text" class="form-control" id="itemAttributes" name="attributes">
                            </div>
                            <label for="itemPrice" class="col-sm-2 col-form-label">Price:</label>
                            <div class="col-sm-10 mb-3">
                                <input type="number" class="form-control" id="itemPrice" name="price">
                            </div>
                            <label for="itemInventory" class="col-sm-2 col-form-label">Inventory:</label>
                            <div class="col-sm-10 mb-3">
                                <input type="number" class="form-control" id="itemInventory" name="inventory">
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="edit">Edit</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    

    <!-- Modal for adding item-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form action="" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Inventory</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Name:</label>
                        <div class="col-sm-10 mb-3">
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <label for="attributes" class="col-sm-2 col-form-label">Attributes:</label>
                        <div class="col-sm-10 mb-3">
                            <input type="text" class="form-control" id="attributes" name="attributes">
                        </div>
                        <label for="price" class="col-sm-2 col-form-label">Price:</label>
                        <div class="col-sm-10 mb-3">
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <label for="inventory" class="col-sm-2 col-form-label">Inventory:</label>
                        <div class="col-sm-10 mb-3">
                            <input type="number" class="form-control" id="inventory" name="inventory">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="add">Add</button>
                    </div>
                </div>
            </div>
        </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script>
            function test(id){
            console.log(document.getElementById('itemId'))
            document.getElementById('itemId').value = id;
            }

            function deleteInventory(id) {
                if (confirm("Are you sure you want to delete this user?")) {
                    // Send an AJAX request to delete the user
                    fetch('delete_inventory.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: id }),
                    })
                    .then(response => {
                        if (response.ok) {
                            // Reload the page after successful deletion
                            location.reload();
                        } else {
                            alert('Failed to delete inventory');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }

            // Open edit modal and populate fields with item data
            function editModal(itemId, itemName, itemAttributes, itemPrice, itemInventory) {
            document.getElementById('itemId').value = itemId;
            document.getElementById('itemName').value = itemName;
            document.getElementById('itemAttributes').value = itemAttributes;
            document.getElementById('itemPrice').value = itemPrice;
            document.getElementById('itemInventory').value = itemInventory;

            document.getElementById('editModal').style.display = 'block';

            // Close edit modal
            document.querySelector('.btn-close').addEventListener('click', function() {
                document.getElementById('editModal').style.display = 'none';
            });
        }
        </script>
</body>

</html>