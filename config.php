<?php
// Database connection.
$servername = getenv("SUB_REPOSITORY_DB_HOST") ?: "127.0.0.1";
$username = getenv("SUB_REPOSITORY_DB_USER") ?: "root";
$password = getenv("SUB_REPOSITORY_DB_PASS") ?: "";
$dbname = getenv("SUB_REPOSITORY_DB_NAME") ?: "subt";

// Site settings.
$urlsite = getenv("SUB_REPOSITORY_SITE_URL") ?: "http://127.0.0.1/Sub-Repository/";
$title = "Sub-Repository";
$desciption = "Repositorio de Subtitulos Audiovisuales.";
$version = "1.0.0";

define("SUBTITLE_DIR", __DIR__ . DIRECTORY_SEPARATOR . "srt");
define("SUBTITLE_PUBLIC_DIR", "srt");

mysqli_report(MYSQLI_REPORT_OFF);

function db()
{
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        die("Connection failed.");
    }

    $conn->set_charset("utf8mb4");
    return $conn;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function js_value($value)
{
    return json_encode((string) $value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}

function site_url($path = "")
{
    global $urlsite;
    return rtrim($urlsite, "/") . "/" . ltrim($path, "/");
}

function normalize_subtitle_name($name)
{
    $name = basename((string) $name);
    $name = preg_replace("/[^A-Za-z0-9._ -]/", "_", $name);
    $name = preg_replace("/\s+/", " ", trim($name));

    if ($name === "" || $name === "." || $name === "..") {
        return "";
    }

    if (!preg_match("/\.srt$/i", $name)) {
        return "";
    }

    return $name;
}

function subtitle_count()
{
    $files = glob(SUBTITLE_DIR . DIRECTORY_SEPARATOR . "*.srt");
    return is_array($files) ? count($files) : 0;
}

function download_count()
{
    $conn = db();
    $result = $conn->query("SELECT cont FROM cont WHERE id = 1");
    $count = 0;

    if ($result && $row = $result->fetch_assoc()) {
        $count = (int) $row["cont"];
    }

    $conn->close();
    return $count;
}

$total_imagenes = subtitle_count();
$c = download_count();
$a = 0;
?>
