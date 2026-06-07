<?php
include("config.php");

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
    <link rel="icon" href="img/favicon.png" sizes="32x32">
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script>
      $(document).ready(function(){
        $('.tooltipped').tooltip({delay: 50});
        <?php foreach ($messages as $message): ?>
        Materialize.toast(<?php echo js_value($message); ?>, 5000, 'rounded');
        <?php endforeach; ?>
      });
    </script>
    <style>
      #particles-js {
        position: relative;
        height: 300px;
        width: 100%;
        z-index: 0;
        background: url(img/bg.jpg);
      }

      .btn {
        border-radius: 35px;
      }

      html,
      body {
        min-height: 100vh;
      }

      body {
        display: flex;
        flex-direction: column;
        margin: 0;
      }

      .site-content {
        flex: 1 0 auto;
      }

      .upload-panel {
        margin-top: 0;
      }

      .upload-panel .row {
        margin-bottom: 0;
      }

      .upload-panel .input-field {
        margin-top: 0;
        margin-bottom: 0;
      }
    </style>
  </head>
  <body>
    <main class="site-content">
      <nav>
        <div class="nav-wrapper blue">
          <a style="padding-left: 30px; font-family: 'Roboto';" href=".">Home</a>
        </div>
      </nav>

      <div id="particles-js" class="center-align"></div>
      <?php include("nav.php"); ?>

      <div class="container upload-panel">
        <div class="row">
          <div class="col s12 m10 offset-m1">
            <form action="<?php echo e($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="subida">
              <div class="file-field input-field col s10">
                <div class="btn waves-effect waves-light blue">
                  examinar
                  <input type="file" name="archivo[]" multiple accept=".srt">
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" type="text">
                </div>
              </div>
              <button class="btn waves-effect waves-light blue" type="submit" name="action" style="margin-top: 15px">
                <i class="fa fa-cloud-upload"></i>
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="fixed-action-btn horizontal">
        <a href="index.php" class="tooltipped hoverable btn-floating btn-large waves-effect waves-light red"
          data-position="left" data-delay="50" data-tooltip="Buscar Subtitulo"><i class="fa fa-search"></i></a>
      </div>
    </main>

    <?php include("footer.php"); ?>
  </body>
</html>
