<!-- total sales features -->
<?php
require '../motor-parts/backend/connection.php';

// Initialize total sales
$totalSales = 0;

// Check if the connection is successful
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT SUM(pos_data.quantity * items.price) AS total_sales
          FROM pos_data
          INNER JOIN items ON pos_data.item_id = items.item_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalSales = $row["total_sales"];
}
?>

<!-- total item features -->
<?php
require '../motor-parts/backend/connection.php';

// Initialize total items count
$totalItems = 0;

// Check if the connection is successful
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT COUNT(*) AS total_items FROM items";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalItems = $row["total_items"];
}
?>

<!-- total inventories features -->
<?php
require '../motor-parts/backend/connection.php';

// Initialize total inventory count
$totalInventory = 0;

// Check if the connection is successful
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT COUNT(id) AS total_inventory FROM inventories";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalInventory = $row["total_inventory"];
}
?>

<!-- chart features -->
<?php
// Include the connection.php file
require '../motor-parts/backend/connection.php';

// Get the database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT pos_data.created_at, pos_data.quantity, items.price 
        FROM pos_data 
        JOIN items ON pos_data.item_id = items.item_id";

$result = $conn->query($sql);

$chartData = array();
while ($row = $result->fetch_assoc()) {
    $chartData[] = array(
        'created_at' => $row['created_at'],
        'sales' => $row['quantity'] * $row['price']
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css" />
    <link rel="stylesheet" href="../motor-parts/src/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/cdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/9d1d9a82d2.js" crossorigin="anonymous"></script>
</head>
<style>
    .chart-container {
        width: 70%;
        height: 50%;
        margin: auto;
    }

    body {
        background-color: lightgrey;
    }
</style>

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

    <h1>Dashboard</h1>
    <div class="row mb-3 w-75 m-auto">
        <div class="col">
            <div class="card w-75 p-3 mb-2 bg-warning-subtle text-warning-emphasis">
                <div class="card-body">
                    <h5 class="card-title">TOTAL SALES</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">₱ <?php echo number_format($totalSales, 2); ?></h6>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-75 p-3 mb-2 bg-info-subtle text-info-emphasis">
                <div class="card-body">
                    <h5 class="card-title">ITEMS</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $totalItems; ?></h6>                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-75 p-3 mb-2 bg-danger-subtle text-danger-emphasis">
                <div class="card-body">
                    <h5 class="card-title">INVENTORY</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $totalInventory; ?></h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card chart-container">
        <h3>Sales Overview</h3>
        <canvas id="chart"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>

    <canvas id="chart"></canvas>
    <script>
        const data = <?php echo json_encode($chartData); ?>;
        const ctx = document.getElementById("chart").getContext('2d');
        const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: data.map(item => item.created_at),
        datasets: [{
            label: 'SALES',
            backgroundColor: 'rgba(161, 198, 247, 1)',
            borderColor: 'rgb(47, 128, 237)',
            data: data.map(item => item.sales),
            indexAxis: 'x' // Specify the index axis as 'x'
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day',
                    displayFormats: {
                        day: 'MMM D'
                    }
                }
            },
            y: {
                beginAtZero: true
            }
        }
    }
});
    </script>

</body>

</html>