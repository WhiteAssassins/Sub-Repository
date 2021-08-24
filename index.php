  <?php
    //CONTADOR DE SUBTITULOS Y DESCARGAS
	include("config.php");
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);}
	$sql = "SELECT nombre, url FROM upload";
	$result = $conn->query($sql);
	$sql = "SELECT cont FROM cont WHERE id=1";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
	$c= $row ["cont"];
	}} else {
	echo "";
	}
	$conn->close();
	//CONTADOR DE SUBTITULOS Y DESCARGAS
  ?>
  
  <html>
    <head>
      <link href="css/material-icons.css" rel="stylesheet" type="text/css">
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	  <link rel="icon" href="img/favicon.png" sizes="32x32">
	  <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
      <script type="text/javascript" src="js/materialize.min.js"></script>
      <SCRIPT LANGUAGE="JavaScript">
var message = new Array();
// Set your messages below -- follow the pattern.
// To add more messages, just add more elements to the array.
message[0] = "";
message[1] = "";
message[2] = "";
message[3] = "";
message[4] = "";
message[5] = "";
message[6] = "";

// Set the number of repetitions (how many times the arrow
// cycle repeats with each message).
var reps = 2;
var speed = 100;  // Set the overall speed (larger number = slower action).

// DO NOT EDIT BELOW THIS LINE.
var p = message.length;
var T = "";
var C = 0;
var mC = 0;
var s = 0;
var sT = null;
if (reps < 1) reps = 1;
function doTheThing() {
T = message[mC];
A();
}
function A() {
s++;
if (s > 8) { s = 1;}
// you can fiddle with the patterns here...
if (s == 1) { document.title = '|<?php echo $title;?>====| '+T+''; }
if (s == 2) { document.title = '|=<?php echo $title;?>===| '+T+''; }
if (s == 3) { document.title = '|==<?php echo $title;?>==| '+T+''; }
if (s == 4) { document.title = '|===<?php echo $title;?>=| '+T+''; }
if (s == 5) { document.title = '|====<?php echo $title;?>| '+T+''; }
if (s == 6) { document.title = '|===<?php echo $title;?>=| '+T+''; }
if (s == 7) { document.title = '|==<?php echo $title;?>==| '+T+''; }
if (s == 8) { document.title = '|=<?php echo $title;?>===| '+T+''; }
if (C < (8 * reps)) {
sT = setTimeout("A()", speed);
C++;
}
else {
C = 0;
s = 0;
mC++;
if(mC > p - 1) mC = 0;
sT = null;
doTheThing();
   }
}
doTheThing();
//  End -->
</script>
	  <script>
	  $(document).ready(function(){
		$('.tooltipped').tooltip({delay: 50});
	  });
	  </script>
	  
	  <!--SCRIPT + CONTADOR DESCARGAS-->
	  <script language="javascript" type="text/javascript">
		var RequestObject = false;
		var Archivo = '<?php echo $urlsite?>upload.php';
		if (window.XMLHttpRequest) RequestObject = new XMLHttpRequest();
		if (window.ActiveXObject) RequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		function ReqChange() { 
		if (RequestObject.readyState==4) {
		if (RequestObject.responseText.indexOf('invalid') == -1) {
		} else { 
		}
		} 
		}
		function llamadaAjax() {
        RequestObject.open("GET", Archivo, true);
		RequestObject.onreadystatechange = ReqChange; 
		RequestObject.send(null);
		}
	  </script>
	  <!--/SCRIPT + CONTADOR DESCARGAS-->
<style>  
	  #particles-js {
    position: relative;
    height: 300px;
    width: 100%;
    z-index: 0;
	background: url(img/bg.jpg);
	}
	.btn{
		border-radius: 35px;
	}
	</style>
    </head>

    <body>    
   <nav>
    <div class="nav-wrapper blue">
    	<a style="padding-left: 30px; font-family: 'Roboto';" href=".">Home</a>
    </div>
  </nav>
    <div id="particles-js" class="center-align"></div>
    <?php include ('nav.php');?>
	  <div class="container">
	  <div class="row">
	  <div class="col s6 offset-s3">

	  <!-- FORM BUSCADOR -->

      <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" name="inscripcion">
		<div class="input-field col s10">
          <input id="last_name" type="text" class="validate" name="buscar">
          <label for="last_name">Inserte Nombre</label>
        </div>
		<div class="col s2">
		<button class="btn waves-effect waves-light blue" type="submit" name="action" style="margin-top: 15px">
		  <i class="fa fa-search"></i>
		</button>
		</div>
	  </form>
	
	  <!-- /FORM BUSCADOR -->
	
	  </div>
	  </div>
	  </div>
	  <div class="fixed-action-btn horizontal">
		<a href="<?php echo $urlsite;?>add.php" class="tooltipped btn-floating btn-large red" data-position="left" 
		data-delay="50" data-tooltip="Añadir Subtítulo"><i class="fa fa-plus-circle"></i></a>
	  </div>

      <div class="container">
  <br>
  <br>
  <br>
  <br>
  <br>
  <?php 
    //BUSCADOR
	  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = $_POST['buscar'];
      if (empty($name)) {
        echo "<script>Materialize.toast('Inserte Nombre', 4000)</script>";
      } else {
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);}
      $sql = "SELECT nombre FROM upload WHERE nombre LIKE '%$name%'"; 
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
      echo '<div class="collection">';
      while($row = $result->fetch_assoc()) {
      echo '<div class="collection-item"><div style="color: #808080">'.$row ["nombre"].'<a href="'.$urlsite.'srt/'
	  .$row ["nombre"].'" download="" class="secondary-content"><i class="fa fa-cloud-download blue-text" onclick="llamadaAjax()"></i></a></div>
	  </div>';}
      echo '</div>';
      } else {
      echo "<script>Materialize.toast('No se ha encontrado ningún subtítulo', 4000, 'rounded')</script>";}
	  $conn->close();}}
	//BUSCADOR
  ?> 
	  </div>

	  <?php include('footer.php'); ?>
	  </body>
	  
      </html>
        
