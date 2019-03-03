<?php
try {
    $conn = new mysqli("az1-ls7.a2hosting.com", "chinese6_zjm", "jzm2019ok", "chinese6_companion");
    if ($conn != null) {
        $query_result = $conn->query("select * from message");
        while ($row = $query_result->fetch_row()) {
            echo json_encode($row);
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
