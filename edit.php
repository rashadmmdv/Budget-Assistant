<?php 
$id = "";
$amount = "";
$date = "";
$category = "";
$payment_method = "";


$errorMessage = "";
$successMessage = "";

$servername = "mysql-rashadmammadov.alwaysdata.net";
$username = "398037_rashad";
$password = "Rashad@31";
$database = "rashadmammadov_141414";

$connection = new mysqli($servername, $username, $password, $database);

if( $_SERVER['REQUEST_METHOD']== 'GET'){

    if(!isset($_GET["id"])){
        header("location: /hwproject/index.php");
        exit;
    }

    $id = $_GET["id"];

    $connection = new mysqli($servername, $username, $password, $database);


    $sql = "SELECT * FROM transactions WHERE transaction_id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if(!$row){
        header("location: /hwproject/index.php");
        exit;
    }

    $amount = $row["amount"];
    $date = $row["transaction_date"];
    $category = $row["category_id"];
    $payment_method = $row["payment_method_id"];
}
else {

    $id = $_POST["id"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];
    $category = $_POST["category"];
    $payment_method = $_POST["payment_method"];

    do {
        if(empty($amount) || empty($date) || empty($category) || empty($payment_method)){
            $errorMessage = "All the fields are required";
            break;
        }
        $sql = "UPDATE transactions " .
                "SET amount = '$amount', transaction_date = '$date', category_id = '$category', payment_method_id = '$payment_method' " .
                "WHERE transaction_id = $id";

        $result = $connection->query($sql);

        if(!$result){
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Transaction update successfully";

        header("location: /hwproject/index.php");
        exit;

    }while(false);  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Manager</title>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="box">

        <span class="borderLine"></span>
        <form method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
            <h2>Update transaction</h2>
            <div class="inputBox">
                <p>Amount</p>
                <input type="number" name="amount" value="<?php echo $amount; ?>">
                <i></i>
            </div>
            <div class="inputBox">
                <p>Date</p>
                <input type='date' name="date" value="<?php echo $date; ?>">
                <i></i>
            </div>
            <div class="inputBox">
                <p>Category</p>
                <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="category">
                    <?php echo getCategoryOptions($category); ?>
                </select>
                <i></i>
            </div>
            <div class="inputBox">
                <p>Payment Method</p>
                <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="payment_method">
                    <?php echo getPaymentMethodOptions($payment_method); ?>
                </select>
                <i></i>
            </div>

            <input type="submit" value="Update">
            <a href="./index.php" class="button" role="button" style="width:100px;">Cancel</a>

        </form>

    </div>
</body>
</html>

<?php
function getCategoryOptions($selectedCategory) {
    global $connection;
    $sql = "SELECT * FROM categories";
    $result = $connection->query($sql);
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $categoryId = $row['category_id'];
        $categoryName = $row['category_name'];
        $selected = ($selectedCategory == $categoryId) ? 'selected' : '';
        $options .= "<option value=\"$categoryId\" $selected>$categoryName</option>";
    }
    return $options;
}

function getPaymentMethodOptions($selectedPaymentMethod) {
    global $connection;
    $sql = "SELECT * FROM payment_methods";
    $result = $connection->query($sql);
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $paymentMethodId = $row['payment_method_id'];
        $paymentMethodName = $row['method_name'];
        $selected = ($selectedPaymentMethod == $paymentMethodId) ? 'selected' : '';
        $options .= "<option value=\"$paymentMethodId\" $selected>$paymentMethodName</option>";
    }
    return $options;
}
?>