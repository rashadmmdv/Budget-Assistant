<?php

$servername = "mysql-rashadmammadov.alwaysdata.net";
$username = "398037_rashad";
$password = "Rashad@31";
$database = "rashadmammadov_141414";

$connection = new mysqli($servername, $username, $password, $database);

$amount = "";
$date = "";
$category = "";
$payment_method = "";

$errorMessage = "";
$successMessage = "";

function getCategories($connection)
{
    $categories = array();
    $result = $connection->query("SELECT * FROM categories");
    
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = $row['category_name'];
    }

    return $categories;
}

function getPaymentMethods($connection)
{
    $paymentMethods = array();
    $result = $connection->query("SELECT * FROM payment_methods");
    
    while ($row = $result->fetch_assoc()) {
        $paymentMethods[$row['payment_method_id']] = $row['method_name'];
    }

    return $paymentMethods;
}

$categories = getCategories($connection);
$paymentMethods = getPaymentMethods($connection);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST["amount"];
    $date = date("Y-m-d", strtotime($_POST["date"]));
    $category = $_POST["category"];
    $payment_method = $_POST["payment_method"];

    try {
        if (empty($amount) || empty($date) || empty($category) || empty($payment_method)) {
            throw new Exception("All the fields are required");
        }

        $stmt = $connection->prepare("INSERT INTO transactions (amount, transaction_date, category_id, payment_method_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dsii", $amount, $date, $category, $payment_method);
        $stmt->execute();

        if ($stmt->affected_rows === -1) {
            throw new Exception("Invalid query: " . $connection->error);
        }

        $stmt->close();

        $amount = "";
        $date = "";
        $category = "";
        $payment_method = "";

        header('Location: ./index.php');

    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Transaction</title>
    <link rel="stylesheet" href="css/transaction.css">
    <style>
        @media screen and (max-width: 820px) {
            .box {
                width: 90%;
            }

            .box form {
                padding: 20px;
            }

        }
    </style>
</head>
<body>
    <div class="box">
        <span class="borderLine"></span>
        <form method="post" class="form-inline">
            <h2>New transaction</h2>
            <?php if (!empty($errorMessage)) : ?>
                <div style="color: red;"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <?php if (!empty($successMessage)) : ?>
                <div style="color: green;"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <div class="inputBox">
                <p>Amount</p>
                <input type="number" name="amount" required value="<?php echo $amount; ?>">
                <i></i>
            </div>
            <div class="inputBox">
                <p>Date</p>
                <input type='date' required name="date" value="<?php echo $date; ?>">
                <i></i>
            </div>
            <div class="inputBox">
                <p>Category</p>
                <select name="category">
                    <?php foreach ($categories as $categoryId => $categoryName) : ?>
                        <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                    <?php endforeach; ?>
                </select>
                <i></i>
            </div>
            <div class="inputBox">
                <p>Payment Method</p>
                <select name="payment_method">
                    <?php foreach ($paymentMethods as $methodId => $methodName) : ?>
                        <option value="<?php echo $methodId; ?>"><?php echo $methodName; ?></option>
                    <?php endforeach; ?>
                </select>
                <i></i>
            </div>
            <input type="submit" value="Submit">
            <a href="./index.php" class="button" role="button">View Transactions</a>
        </form>
    </div>
</body>
</html>
