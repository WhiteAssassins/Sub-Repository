<?php
include("config.php");

$activePage = "subir";
$messages = array();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["archivo"])) {
    if (!is_dir(SUBTITLE_DIR) && !mkdir(SUBTITLE_DIR, 0755, true)) {
        $messages[] = "No se pudo crear la carpeta de subtitulos";
    } else {
        $conn = db();
        $stmt = $conn->prepare("INSERT IGNORE INTO upload (nombre, url) VALUES (?, ?)");

        foreach ($_FILES["archivo"]["name"] as $i => $originalName) {
            if ($_FILES["archivo"]["error"][$i] !== UPLOAD_ERR_OK) {
                $messages[] = "No se pudo subir " . basename($originalName);
                continue;
            }

            $safeName = normalize_subtitle_name($originalName);
            if ($safeName === "") {
                $messages[] = "Formato no soportado: " . basename($originalName);
                continue;
            }

            $tmpPath = $_FILES["archivo"]["tmp_name"][$i];
            $contents = file_get_contents($tmpPath);
            $sample = $contents === false ? false : substr($contents, 0, 4096);
            if ($sample === false || stripos($sample, "-->") === false) {
                $messages[] = "El archivo no parece ser un subtitulo SRT: " . $safeName;
                continue;
            }

            $destination = SUBTITLE_DIR . DIRECTORY_SEPARATOR . $safeName;
            if (file_exists($destination)) {
                $messages[] = "Ya existe: " . $safeName;
                continue;
            }

            if (!move_uploaded_file($tmpPath, $destination)) {
                $messages[] = "No se pudo guardar: " . $safeName;
                continue;
            }

            $publicPath = SUBTITLE_PUBLIC_DIR . "/" . $safeName;
            $stmt->bind_param("ss", $safeName, $publicPath);
            $stmt->execute();
            $messages[] = "Subtitulo subido correctamente: " . $safeName;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?> - Subir</title>
    <link href="css/material-icons.css" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="css/sub-repository.css" media="screen,projection">
    <link rel="icon" href="img/favicon.png" sizes="32x32">
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript" src="js/sub-repository.js"></script>
    <script>
      $(document).ready(function(){
        <?php foreach ($messages as $message): ?>
        Materialize.toast(<?php echo js_value($message); ?>, 5000, 'rounded');
        <?php endforeach; ?>
      });
    </script>
  </head>
  <body>
    <?php include("nav.php"); ?>

    <main class="site-content">
      <section class="sr-container hero">
        <div class="hero-content">
          <div class="hero-title-row">
            <span class="hero-mark"><i class="fa fa-upload"></i></span>
            <h1>Subir subtitulo</h1>
          </div>
          <p>Agrega archivos .srt al repositorio.</p>
          <div class="hero-actions">
            <a href="<?php echo e(site_url()); ?>" class="primary-button"><i class="fa fa-search"></i> Buscar subtitulos</a>
            <a href="#subir" class="outline-button"><i class="fa fa-upload"></i> Seleccionar archivo</a>
          </div>
        </div>
      </section>

      <section id="subir" class="upload-card">
        <form class="upload-form" action="<?php echo e($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="subida">
          <label class="file-input-wrap">
            <i class="fa fa-file-text-o"></i>
            <input type="file" name="archivo[]" multiple accept=".srt">
            <input class="file-path" type="text" placeholder="Selecciona uno o varios archivos .srt">
          </label>
          <button class="primary-button" type="submit"><i class="fa fa-cloud-upload"></i> Subir</button>
        </form>
      </section>
    </main>

    <button class="back-top" data-back-top aria-label="Volver arriba"><i class="fa fa-arrow-up"></i></button>

    <?php include("footer.php"); ?>
  </body>
</html>
