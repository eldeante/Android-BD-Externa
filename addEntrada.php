<?php

include 'conexion.php';

$fecha_entrada = $_POST['fecha_entrada'];
$albaran_entrada = $_POST['albaran_entrada'];
$articulo_entrada = $_POST['articulo_entrada'];
$trabajador_entrada = $_POST['trabajador_entrada'];
$cantidad_entrada = $_POST['cantidad_entrada']; 

try
{
	//Obtengo la referencia del artículo
	$query = "SELECT referencia FROM articulo WHERE nombre='".$articulo_entrada."'";
	$result1 = mysqli_query($conexionCentral,$query);
	$row1 = mysqli_fetch_array($result1);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$query."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

try
{
	//Inserto una nueva entrada
	$sql1 = "INSERT INTO entrada (cod_ent,fecha_entrada,alabaran,referencia,id_trab,cantidad) VALUES (NULL,'".$fecha_entrada."','".$albaran_entrada."','".$row1[0]."',".$trabajador_entrada.",".$cantidad_entrada.")";
	$result = mysqli_query($conexionCentral,$sql1);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$sql1."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

try
{
	//Actualizo el stock del producto
	$sql2 ="UPDATE articulo SET stock=stock+".$cantidad_entrada." WHERE referencia='".$row1[0]."'";
	$result = mysqli_query($conexionCentral,$sql2);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$sql2."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//header("Location: index.php");

mysqli_close($conexionCentral);
?>