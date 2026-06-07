<?php
include("config.php");

$search = "";
$results = array();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $search = trim($_POST["buscar"] ?? "");

    if ($search === "") {
        $message = "Inserte Nombre";
    } else {
        $conn = db();
        $like = "%" . $search . "%";
        $stmt = $conn->prepare("SELECT nombre FROM upload WHERE nombre LIKE ? ORDER BY nombre ASC");
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $results[] = $row["nombre"];
        }

        if (count($results) === 0) {
            $message = "No se ha encontrado ningun subtitulo";
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
    <title><?php echo e($title); ?></title>
    <link href="css/material-icons.css" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection">
    <link rel="icon" href="img/favicon.png" sizes="32x32">
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script>
      $(document).ready(function(){
        $('.tooltipped').tooltip({delay: 50});
        <?php if ($message !== ""): ?>
        Materialize.toast(<?php echo js_value($message); ?>, 4000, 'rounded');
        <?php endif; ?>
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

      .search-panel {
        margin-top: 0;
      }

      .search-panel .row {
        margin-bottom: 0;
      }

      .search-panel .input-field {
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

      <div class="container search-panel">
        <div class="row">
          <div class="col s12 m8 offset-m2">
            <form action="<?php echo e($_SERVER["PHP_SELF"]); ?>" method="post" name="busqueda">
              <div class="input-field col s10">
                <input id="last_name" type="text" class="validate" name="buscar" value="<?php echo e($search); ?>">
                <label for="last_name" class="<?php echo $search !== "" ? "active" : ""; ?>">Inserte Nombre</label>
              </div>
              <div class="col s2">
                <button class="btn waves-effect waves-light blue" type="submit" name="action" style="margin-top: 15px">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="fixed-action-btn horizontal">
        <a href="<?php echo e(site_url("add.php")); ?>" class="tooltipped btn-floating btn-large red" data-position="left"
          data-delay="50" data-tooltip="Anadir Subtitulo"><i class="fa fa-plus-circle"></i></a>
      </div>

      <div class="container">
        <?php if (count($results) > 0): ?>
          <div class="collection">
            <?php foreach ($results as $subtitle): ?>
              <div class="collection-item">
                <div style="color: #808080">
                  <?php echo e($subtitle); ?>
                  <a href="<?php echo e(site_url("download.php?file=" . rawurlencode($subtitle))); ?>" class="secondary-content">
                    <i class="fa fa-cloud-download blue-text"></i>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </main>

    <?php include("footer.php"); ?>
  </body>
</html>
