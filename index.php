<?php

$servername = "mysql-rashadmammadov.alwaysdata.net";
$username = "398037_rashad";
$password = "Rashad@31";
$database = "rashadmammadov_141414";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error){
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $category_filter = isset($_POST['category']) ? $_POST['category'] : '';
    $payment_method_filter = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

    $sql = "SELECT transactions.*, categories.category_name, payment_methods.method_name 
            FROM transactions 
            JOIN categories ON transactions.category_id = categories.category_id
            JOIN payment_methods ON transactions.payment_method_id = payment_methods.payment_method_id 
            WHERE 1";

    if (!empty($start_date) && !empty($end_date)) {
        $sql .= " AND transaction_date BETWEEN '$start_date' AND '$end_date'";
    }

    if (!empty($category_filter)) {
        $sql .= " AND transactions.category_id = '$category_filter'";
    }

    if (!empty($payment_method_filter)) {
        $sql .= " AND transactions.payment_method_id = '$payment_method_filter'";
    }

    $result = $connection->query($sql);

    if (!$result) {
        die("Invalid query: " . $connection->error);
    }
} else {
    $sql = "SELECT transactions.*, categories.category_name, payment_methods.method_name 
            FROM transactions 
            JOIN categories ON transactions.category_id = categories.category_id
            JOIN payment_methods ON transactions.payment_method_id = payment_methods.payment_method_id";
    $result = $connection->query($sql);

    if (!$result) {
        die("Invalid query: " . $connection->error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <style>
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            width: 20%;
        }
        input,select,button {
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 3px;
            box-sizing: border-box;
            width: 20%;
        }
        @media screen and (max-width: 767px) {
            label {
                width: 50%;
            }
            input, select,button{
                width: 50%;
            }


        }


        @media screen and (min-width: 768px) and (max-width: 991px) {
            label {
                width: 30%; 
            }
            input, select,button {
                width: 30%;
            }
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="limiter">
        <div class="elements">
            <center>
                <h2 style="padding:40px">All Transactions</h2>
                <button style="background-color: #6c7ae0; border-radius: 4px; padding: 10px; font-size: 16px;  color: white; margin-right:10px;"><a href="/hwproject/accountingChart.php" class="buttonn" role="button" style="color:white;text-decoration:none;">Accounting Chart</a></button><button style="background-color: #6c7ae0; border-radius: 4px; padding: 10px; font-size: 16px;margin-right:10px;  color: white;"><a href="/hwproject/new_transaction.php" class="buttonn" role="button" style="color:white;text-decoration:none;">New Transaction</a></button><button style="background-color: #6c7ae0; border-radius: 4px; padding: 10px; font-size: 16px;  color: white;"><a href="/hwproject/categoriesChart.php" class="buttonn" role="button" style="color:white;text-decoration:none;">Categories Chart</a></button>
            </center>
        </div>
        <center>
        <br>
        <form method="post" action="">
            <div class="form-row mb-3">
                <div class="col-md-12">
                    <label for="start_date">Start Date :</label>
                    <input type="date" name="start_date" id="start_date" value="<?= isset($_POST['start_date']) ? $_POST['start_date'] : '' ?>">
                </div>
                <div class="col-md-12">
                    <label for="end_date">End Date :</label>
                    <input type="date" name="end_date" id="end_date" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : '' ?>">
                </div>
                <div class="col-md-12">
                <label for="start_date">Categories :</label>

                    <select name="category" id="category">
                        <option value="">All Categories</option>
                        <?php
                        $selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
                        $sqlCategories = "SELECT * FROM categories";
                        $resultCategories = $connection->query($sqlCategories);

                        while ($rowCategory = $resultCategories->fetch_assoc()) {
                            $categoryId = $rowCategory['category_id'];
                            $categoryName = $rowCategory['category_name'];
                            $selected = ($selectedCategory == $categoryId) ? 'selected' : '';
                            echo "<option value='$categoryId' $selected>$categoryName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-12">
                <label for="start_date">Payment Methods :</label>

                    <select name="payment_method" id="payment_method"">
                        <option value="">All Payment Methods</option>
                        <?php
                        $selectedPaymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
                        $sqlPaymentMethods = "SELECT * FROM payment_methods";
                        $resultPaymentMethods = $connection->query($sqlPaymentMethods);

                        while ($rowPaymentMethod = $resultPaymentMethods->fetch_assoc()) {
                            $paymentMethodId = $rowPaymentMethod['payment_method_id'];
                            $paymentMethodName = $rowPaymentMethod['method_name'];
                            $selected = ($selectedPaymentMethod == $paymentMethodId) ? 'selected' : '';
                            echo "<option value='$paymentMethodId' $selected>$paymentMethodName</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br>
            <input type="submit" style="background-color: #6c7ae0; border-radius: 4px; padding: 10px; font-size: 15px;  color: white;" value="Filter">
        </form>
            </center>
        <div class="container-table100">
            <div class="wrap-table100">
                <div class="table">
                    <div class="row header">
                        <div class="cell" data-title="Id">Id</div>
                        <div class="cell" data-title="Date">Date</div>
                        <div class="cell" data-title="Amount">Amount</div>
                        <div class="cell" >Category</div>
                        <div class="cell" data-title="Payment">Payment Method</div>
                        <div class="cell" data-title="Actions">Actions</div>
                    </div>
                    <?php
                    while($row = $result->fetch_assoc()) {
                        $className = "cell";
                        echo "
                        <div class='row'>
                            <div  data-title='Id' class='" . $className . "'>$row[transaction_id]</div>
                            <div  data-title='Date' class='" . $className . "'>$row[transaction_date]</div>
                            <div  data-title='Amount' class='" . $className . "'>$row[amount] $</div>
                            <div  data-title='Category' class='" . $className . "'>$row[category_name]</div>
                            <div  data-title='Payment Method' class='" . $className . "'>$row[method_name]</div>
                            <div  data-title='Actions' class='" . $className . "'>
                                <a class='btn btn-secondary btn-sm' href='/hwproject/edit.php?id=$row[transaction_id]'>Edit</a>
                                <a class='btn btn-secondary btn-sm' href='/hwproject/delete.php?id=$row[transaction_id]'>Delete</a>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
