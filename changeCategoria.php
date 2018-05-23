<?php

include 'conexion.php';

$identificador_categoria=$_POST['identificador_categoria'];
$nombre_categoria=$_POST['nombre_categoria'];

try
{
	$sql = "UPDATE categoria SET nombre='$nombre_categoria' WHERE id_cat=$identificador_categoria";
	$result = mysqli_query($conexionCentral,$sql);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$sql."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//header("Location: index.php");

mysqli_close($conexionCentral);
?>