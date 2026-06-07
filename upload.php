<?php
include("config.php");

header("Content-Type: text/plain; charset=UTF-8");

$conn = db();
if ($conn->query("UPDATE cont SET cont = cont + 1 WHERE id = 1")) {
    echo "ok";
} else {
    http_response_code(500);
    echo "error";
}
$conn->close();
?>
