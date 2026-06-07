<?php
include("config.php");

$file = normalize_subtitle_name($_GET["file"] ?? "");
if ($file === "") {
    http_response_code(400);
    die("Invalid file.");
}

$conn = db();
$stmt = $conn->prepare("SELECT nombre FROM upload WHERE nombre = ? LIMIT 1");
$stmt->bind_param("s", $file);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    http_response_code(404);
    die("File not found.");
}

$stmt->close();

$path = SUBTITLE_DIR . DIRECTORY_SEPARATOR . $file;
if (!is_file($path)) {
    $conn->close();
    http_response_code(404);
    die("File not found.");
}

$conn->query("UPDATE cont SET cont = cont + 1 WHERE id = 1");
$conn->close();

header("Content-Type: application/x-subrip; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"" . addcslashes($file, "\\\"") . "\"");
header("Content-Length: " . filesize($path));
header("X-Content-Type-Options: nosniff");
readfile($path);
exit;
?>
