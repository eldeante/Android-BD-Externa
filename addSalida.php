<?php

include 'conexion.php';

$fecha_salida = $_POST['fecha_salida'];
$articulo_salida = $_POST['articulo_salida'];
$trabajador_salida = $_POST['trabajador_salida'];
$cantidad_salida = $_POST['cantidad_salida']; 
$gasolinera_salida = $_POST['gasolinera_salida']; 

//Obtenemos la referencia del artículo
try
{
	$sql4 = "SELECT referencia FROM articulo WHERE nombre='".$articulo_salida."'";
	$result4 = mysqli_query($conexionCentral,$sql4);
	$row2 = mysqli_fetch_array($result4);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$sql4."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//Seleccionamos el stock del producto y comprobamos si esta a 0 para mandar un correo de aviso
try
{
	$sql3 = "SELECT stock,stock_minimo FROM articulo WHERE referencia='".$row2[0]."'";
	$result3 = mysqli_query($conexionCentral,$sql3);
	$row = mysqli_fetch_array($result3);
}

catch(Exception $e)
{
	//Generamos un log
	$hoy = date("Y-m-d H:i");
	$file = fopen("logs/logs.txt","a");
	fwrite($file, "**********************************************************************************************************************"."\r\n");						  	fwrite($file, "Log generado el : " .$hoy."\r\n");  
	fwrite($file, "Se ha realizado con éxito la consulta: " .$sql3."\r\n");
	fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
	fwrite($file, "**********************************************************************************************************************"."\r\n");						
	fclose($file);
}

//Si el stock del articulo es 0 no te deja retirar más cantidad
if($row[0]<=0)
{
	echo"<script>alert('NO SE PUEDE RETIRAR MÁS CANTIDAD DE ESTE PRODUCTO PORQUE SU STOCK ES 0')</script>";
}

else
{	
	try
	{
		//Insertamos una nueva salida
		$sql1 = "INSERT INTO salida (cod_sal,fecha_salida,referencia,id_trab,cantidad,id_gas) VALUES (NULL,'".$fecha_salida."','".$row2[0]."',".$trabajador_salida.",".$cantidad_salida.",".$gasolinera_salida.")";
		$result = mysqli_query($conexionCentral,$sql1);
	}
	
	catch(Exception $e)
	{
		//Generamos un log
		$hoy = date("Y-m-d H:i");
		$file = fopen("logs/logs.txt","a");
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  	    fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$sql1."\r\n");
		fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
		fwrite($file, "**********************************************************************************************************************"."\r\n");						
		fclose($file);
	}
	
	try
	{
		//Actualizamos el stock
		$sql2 ="UPDATE articulo SET stock=stock-".$cantidad_salida." WHERE referencia='".$row2[0]."'";
		$result2 = mysqli_query($conexionCentral,$sql2);
	}
	
	catch(Exception $e)
	{
		//Generamos un log
		$hoy = date("Y-m-d H:i");
		$file = fopen("logs/logs.txt","a");
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  	    fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$sql2."\r\n");
		fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
		fwrite($file, "**********************************************************************************************************************"."\r\n");						
		fclose($file);
	}
	
	//header("Location: index.php");
}

mysqli_close($conexionCentral);
?>