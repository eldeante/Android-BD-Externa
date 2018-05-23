<?php

include 'conexion.php';

session_start();

$referencia_salida=$_POST['referencia_salida'];
$cantidad_salida=$_POST['cantidad_salida'];
$gasolinera_salida=$_POST['gasolinera_salida'];
$fecha_salida=date("Y-m-d H:i");

//Obtengo el id del trabajdor
try
{
	$query2 = "SELECT id_trab FROM trabajador WHERE nombre='".$_SESSION["nombre_trab"]."'";
	$result2 = mysqli_query($conexionCentral,$query2);
	$row3 = mysqli_fetch_array($result2);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$query2."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//Obtengo el id del articulo
try
{
	$query3 = "SELECT referencia FROM articulo WHERE nombre='".$referencia_salida."'";
	$result3 = mysqli_query($conexionCentral,$query3);
	$row4 = mysqli_fetch_array($result3);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$query3."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//Inserto la nueva salida
try
{
	$sql1 = "INSERT INTO salida (cod_sal,fecha_salida,referencia,id_trab,cantidad,id_gas) VALUES (NULL,'".$fecha_salida."','".$row4[0]."',".$row3[0].",".$cantidad_salida.",".$gasolinera_salida.")";
	$result4 = mysqli_query($conexionCentral,$sql1);
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
	
//Actualizo el stock
try
{
	$sql = "UPDATE articulo SET stock=stock-".$cantidad_salida." WHERE referencia='".$row4[0]."'";
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