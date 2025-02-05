<?php
$servername = "mysql-rashad.alwaysdata.net";
$username = "rashad_123123";
$password = "resad123";
$database = "rashad_hwp";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT c.category_name, a.accounting_coefficient, SUM(t.amount) as total_amount
        FROM categories c
        INNER JOIN accounting a ON c.accounting_id = a.accounting_id
        LEFT JOIN transactions t ON t.category_id = c.category_id
        GROUP BY c.category_name, a.accounting_coefficient";
$result = $connection->query($sql);

$labels = array();
$data = array();

while ($row = $result->fetch_assoc()) {
    $categoryName = $row['category_name'];
    $accountingCoefficient = $row['accounting_coefficient'];
    $totalAmount = $row['total_amount'];

    $labels[] = $categoryName;
    $data[] = $accountingCoefficient * $totalAmount; 
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>Categories Chart</title>
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
            padding: 40px;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <center>
        <h2 style="padding:20px">Categories Chart</h2>
        <button style="background-color: #6c7ae0; border-radius: 4px; padding: 10px; font-size: 16px;  color: white;"><a href="/hwproject/index.php" class="buttonn" role="button" style="color:white;text-decoration:none;">Main Page</a></button>
    </center>
    <div class = 'wrapper' style="width: 80%; margin: auto;">
        <canvas id="incomeExpenseChart" width="600" height="400"></canvas>
    </div>

    <script>
        var labels = <?php echo json_encode($labels); ?>;
        var data = <?php echo json_encode($data); ?>;

        var ctx = document.getElementById('incomeExpenseChart').getContext('2d');
        var incomeExpenseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'All Categories',
                    data: data,
                    backgroundColor: data.map(value => value > 0 ? 'rgba(75, 192, 192, 0.7)' : 'rgba(255, 99, 132, 0.7)'),
                    borderColor: data.map(value => value > 0 ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)'),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
