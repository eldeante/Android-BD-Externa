<?php

include 'conexion.php';

$identificador_categoria=$_POST['identificador_subcategoria'];
$nombre_categoria=$_POST['nombre_categoria'];

//header("Location: index.php");

try
{
	$sql = "INSERT INTO categoria (id_cat, nombre) VALUES ('$identificador_categoria', '$nombre_categoria')";
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

mysqli_close($conexionCentral);
?>