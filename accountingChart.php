<?php
$servername = "mysql-rashad.alwaysdata.net";
$username = "rashad_123123";
$password = "resad123";
$database = "rashad_hwp";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT c.accounting_id, SUM(t.amount) as total_amount
        FROM transactions t
        INNER JOIN categories c ON t.category_id = c.category_id
        GROUP BY c.accounting_id";
        
$result = $connection->query($sql);

$labels = ['Expense', 'Income', 'Resulting Income'];
$data = [];

while ($row = $result->fetch_assoc()) {
    $accountingID = $row['accounting_id'];
    $totalAmount = $row['total_amount'];

    if ($accountingID == 1) {
        $data['Expense'] = -$totalAmount;
    } elseif ($accountingID == 2) {
        $data['Income'] = $totalAmount;
    }
}

$data['Resulting Income'] = $data['Income'] + $data['Expense'];

$connection->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounting Chart</title>
    <style>
        body {
            background-color: #c4d3f6;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .wrapper {
            width: 80%;
            margin: auto;
            padding: 20px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .wrapper {
                width: 100%;
            }
            canvas{
                max-width: 100%;
            }
        }

        h2 {
            text-align: center;
            padding: 20px;
        }

        button {
            background-color: #6c7ae0;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            color: white;
            display: block;
            margin: auto;
            text-decoration: none;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <center>
        <h2>Accounting Chart</h2>
        <button><a href="/hwproject/index.php" class="buttonn" role="button" style="color:white;text-decoration:none;">Main Page</a></button>
    </center>
    <div class="wrapper">
        <canvas id="myColumnChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myColumnChart').getContext('2d');
        var myColumnChart = new Chart(ctx, {
            type: 'bar',
            data: { 
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($data)); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });

    </script>
</body>
</html>