<?php
	// CONEXION A LA DB
	
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "subt";
	
	//INICIALIZACION DE VARIABLES
	
	$urlsite="http://127.0.0.1/subs/";
	$title="FreeSubs";
	$desciption="Repositorio de Subtitulos Audiovisuales.";
	$b=0;
	$a=0;

	$total_imagenes = count(glob('srt/{*.srt}',GLOB_BRACE));
?>