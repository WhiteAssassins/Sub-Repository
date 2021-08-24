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
	  <div class="col s10 offset-s1">
	
	  <!-- FORM UPLOAD SRT-->
      <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" name="inscripcion">
      <div class="file-field input-field col s10">
      <div class="btn waves-effect waves-light blue">
        examinar
        <input type="file" name="archivo[]" multiple="multiple">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
      </div>
      <button class="btn waves-effect waves-light blue" type="submit" name="action" style="margin-top: 15px">
		  <i class="fa fa-cloud-upload"></i>
      </button>
	  </form>
	  <!-- /FORM UPLOAD SRT-->
	
	  </div>
	  </div>
	  </div>
	  <div class="fixed-action-btn horizontal" style="">
		<a href="index.php" class="tooltipped hoverable btn-floating btn-large waves-effect waves-light red" 
		data-position="left" data-delay="50" data-tooltip="Buscar Subtítulo"><i class="fa fa-search"></i></a>
	  </div>

	 <br>
	 <br>
	 <br>
	 <br>
	 <br>
  <?php
	//SCRIPT SUBIR SRT
	$carpetaDestino="srt/";
	if($_FILES["archivo"]["name"][0])
	{
	for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
	{
	if($_FILES["archivo"]["type"][$i]=="application/x-subrip" || $_FILES["archivo"]["type"][$i]=="application/octet-stream")
	{
	if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
	{
	$origen=$_FILES["archivo"]["tmp_name"][$i];
	$destino=$carpetaDestino.$_FILES["archivo"]["name"][$i];
	$nombre=$_FILES["archivo"]["name"][$i];
	if(@move_uploaded_file($origen, $destino))
	{
	echo "<script>Materialize.toast('Subtítulo Subido Correctamente!', 4000, 'rounded')</script>";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);}
	$sql = "SELECT nombre FROM upload";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
       if($row["nombre"]==$nombre){
       $a = 1;
	$conn->close();}
	else{}
	}} else {
    echo "0 results";
	}
	$conn->close();
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);}
	if($a == 0){
	$sql = "INSERT INTO upload (nombre)
	VALUES ('$nombre')";
	}
	if ($conn->query($sql) === TRUE) {
    echo "";
	} else {
    }
	$conn->close();
	
	}else{
		echo "<script>Materialize.toast('No se Pudo Subir el Subtítulo', 4000, 'rounded')</script>";}
    }else{
        echo "<script>Materialize.toast('No se Pudo Crear la Carpeta', 4000, 'rounded')</script>";}
	}else{
		echo "<script>Materialize.toast('Formato no Soportado', 4000, 'rounded')</script>";}

    }
	}else{
		echo "";
	}
  ?>

	  <?php include('footer.php'); ?>
  
  </body>
  
  </html>