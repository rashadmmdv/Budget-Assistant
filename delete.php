<?php

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "mysql-rashad.alwaysdata.net";
    $username = "rashad_123123";
    $password = "resad123";
    $database = "rashad_hwp";

    $connection = new mysqli($servername, $username, $password, $database);

    $stmt = $connection->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("location: /hwproject/index.php");
exit;
?>
