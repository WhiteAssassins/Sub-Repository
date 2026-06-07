<?php
include("config.php");

$activePage = "inicio";
$search = trim($_GET["buscar"] ?? $_POST["buscar"] ?? "");
$category = trim($_GET["categoria"] ?? "");
$results = array();
$latestSubtitles = array();
$latestRequests = array();
$message = "";

$categoryFilters = array(
    "peliculas" => array("label" => "Peliculas", "icon" => "fa-film", "class" => "movies", "terms" => array("movie", "film", "dvdrip", "brrip", "bluray", "web-dl")),
    "series" => array("label" => "Series", "icon" => "fa-television", "class" => "series", "terms" => array("s01", "s02", "e01", "episode", "season", "serie")),
    "documentales" => array("label" => "Documentales", "icon" => "fa-file-text-o", "class" => "docs", "terms" => array("doc", "documentary", "documental")),
    "anime" => array("label" => "Anime", "icon" => "fa-puzzle-piece", "class" => "anime", "terms" => array("anime", "ova", "naruto", "one piece")),
    "otros" => array("label" => "Otros", "icon" => "fa-star", "class" => "other", "terms" => array())
);

function subtitle_category_key($name, $categoryFilters)
{
    $lower = strtolower($name);
    foreach ($categoryFilters as $key => $meta) {
        if ($key === "otros") {
            continue;
        }
        foreach ($meta["terms"] as $term) {
            if (strpos($lower, $term) !== false) {
                return $key;
            }
        }
    }
    return "otros";
}

$conn = db();
$conn->query("CREATE TABLE IF NOT EXISTS subtitle_requests (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(190) NOT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "request_subtitle") {
    $requestTitle = trim($_POST["request_title"] ?? "");
    if ($requestTitle === "") {
        $message = "Escribe una solicitud primero";
    } else {
        $stmt = $conn->prepare("INSERT INTO subtitle_requests (title) VALUES (?)");
        $stmt->bind_param("s", $requestTitle);
        if ($stmt->execute()) {
            $message = "Solicitud creada";
        } else {
            $message = "No se pudo crear la solicitud";
        }
        $stmt->close();
    }
}

$all = $conn->query("SELECT nombre FROM upload ORDER BY created_at DESC, nombre ASC");
$allNames = array();
while ($all && $row = $all->fetch_assoc()) {
    $allNames[] = $row["nombre"];
}

$latestSubtitles = array_slice($allNames, 0, 6);
$requestResult = $conn->query("SELECT title, created_at FROM subtitle_requests ORDER BY created_at DESC LIMIT 6");
while ($requestResult && $row = $requestResult->fetch_assoc()) {
    $latestRequests[] = $row;
}
$categoryCounts = array_fill_keys(array_keys($categoryFilters), 0);
foreach ($allNames as $name) {
    $categoryCounts[subtitle_category_key($name, $categoryFilters)]++;
}

if ($category !== "" && isset($categoryFilters[$category])) {
    foreach ($allNames as $name) {
        if (subtitle_category_key($name, $categoryFilters) === $category) {
            $results[] = $name;
        }
    }
    if (count($results) === 0) {
        $message = "No hay subtitulos en esta categoria";
    }
} elseif ($search !== "") {
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
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <link href="css/material-icons.css" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="css/sub-repository.css" media="screen,projection">
    <link rel="icon" href="img/favicon.png" sizes="32x32">
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript" src="js/sub-repository.js"></script>
    <script>
      $(document).ready(function(){
        <?php if ($message !== ""): ?>
        Materialize.toast(<?php echo js_value($message); ?>, 4000, 'rounded');
        <?php endif; ?>
      });
    </script>
  </head>
  <body>
    <?php include("nav.php"); ?>

    <main class="site-content">
      <section id="inicio" class="sr-container hero">
        <div class="hero-content">
          <div class="hero-title-row">
            <span class="hero-mark"><i class="fa fa-code"></i></span>
            <h1><?php echo e($title); ?></h1>
          </div>
          <p><?php echo e($desciption); ?></p>
          <div class="hero-actions">
            <a href="#buscar" class="primary-button"><i class="fa fa-search"></i> Buscar subtitulos</a>
            <a href="<?php echo e(site_url("add.php")); ?>" class="outline-button"><i class="fa fa-upload"></i> Subir subtitulo</a>
          </div>
        </div>
      </section>

      <section id="buscar" class="search-card">
        <form class="search-form" action="index.php#resultados" method="get">
          <label class="search-input-wrap" for="buscar-subtitulos">
            <i class="fa fa-search"></i>
            <input id="buscar-subtitulos" type="text" name="buscar" value="<?php echo e($search); ?>" placeholder="Buscar subtitulos por nombre, pelicula, serie, etc...">
          </label>
          <button class="primary-button" type="submit">Buscar</button>
        </form>
      </section>

      <section id="categorias" class="sr-container">
        <div class="section-header">
          <div class="section-title">Categorias populares</div>
          <a class="section-link" href="index.php#categorias">Ver todas <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="category-grid">
          <?php foreach ($categoryFilters as $key => $meta): ?>
            <a class="category-card <?php echo e($meta["class"]); ?>" href="index.php?categoria=<?php echo e($key); ?>#resultados">
              <span class="category-icon"><i class="fa <?php echo e($meta["icon"]); ?>"></i></span>
              <span>
                <strong><?php echo e($meta["label"]); ?></strong>
                <span><b><?php echo (int) $categoryCounts[$key]; ?></b> subtitulos</span>
              </span>
              <i class="fa fa-angle-right"></i>
            </a>
          <?php endforeach; ?>
        </div>
      </section>

      <?php if ($search !== "" || $category !== ""): ?>
        <section id="resultados" class="sr-container results-section">
        <div class="section-header">
            <div class="section-title">
              <?php echo $category !== "" && isset($categoryFilters[$category]) ? "Categoria: " . e($categoryFilters[$category]["label"]) : "Resultados"; ?>
            </div>
            <a class="section-link" href="index.php">Limpiar busqueda</a>
          </div>
          <div class="results-list">
            <?php if (count($results) > 0): ?>
              <?php foreach ($results as $subtitle): ?>
                <div class="result-row">
                  <span class="result-name"><i class="fa fa-file-text-o blue-text"></i><?php echo e($subtitle); ?></span>
                  <a class="download-link" href="<?php echo e(site_url("download.php?file=" . rawurlencode($subtitle))); ?>">
                    <i class="fa fa-cloud-download"></i> Descargar
                  </a>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="result-row">
                <span class="result-name"><i class="fa fa-info-circle blue-text"></i>No hay resultados para mostrar.</span>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>

      <section id="explorar" class="sr-container explore-section">
        <div class="section-header">
          <div class="section-title">Explorar</div>
        </div>
        <div class="explore-panel">
          <?php if (count($latestSubtitles) > 0): ?>
            <p>Ultimos subtitulos disponibles en el repositorio.</p>
            <div class="results-list">
              <?php foreach ($latestSubtitles as $subtitle): ?>
                <div class="result-row">
                  <span class="result-name"><i class="fa fa-file-text-o blue-text"></i><?php echo e($subtitle); ?></span>
                  <a class="download-link" href="<?php echo e(site_url("download.php?file=" . rawurlencode($subtitle))); ?>">
                    <i class="fa fa-cloud-download"></i> Descargar
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p>Todavia no hay subtitulos. Usa el boton de subir para cargar el primero.</p>
          <?php endif; ?>
        </div>
      </section>

      <section id="solicitudes" class="sr-container requests-section">
        <div class="section-header">
          <div class="section-title">Solicitudes</div>
        </div>
        <div class="request-panel">
          <p>Crea solicitudes publicas de subtitulos. No requiere login.</p>
          <form method="post" action="index.php#solicitudes">
            <input type="hidden" name="action" value="request_subtitle">
            <input type="text" name="request_title" maxlength="190" placeholder="Ej: Subtitulo para pelicula o episodio...">
            <button class="primary-button" type="submit"><i class="fa fa-envelope-o"></i> Crear solicitud</button>
          </form>
          <?php if (count($latestRequests) > 0): ?>
            <div class="request-list">
              <?php foreach ($latestRequests as $request): ?>
                <div class="request-row">
                  <span><i class="fa fa-envelope-open-o blue-text"></i><?php echo e($request["title"]); ?></span>
                  <small><?php echo e(date("Y-m-d", strtotime($request["created_at"]))); ?></small>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <button class="floating-info tooltipped" data-position="left" data-delay="50" data-tooltip="Subs Totales: <?php echo (int) $total_imagenes; ?> | Descargas: <?php echo (int) $c; ?>">
      <i class="fa fa-info"></i>
    </button>
    <button class="back-top" data-back-top aria-label="Volver arriba"><i class="fa fa-arrow-up"></i></button>

    <?php include("footer.php"); ?>
  </body>
</html>
