<?php

include 'conexion.php';

$ref_articulo=$_POST['ref_articulo'];
$nombre_articulo=$_POST['nombre_articulo'];
$cat_articulo=$_POST['cat_articulo'];
$sub_articulo=$_POST['sub_articulo'];
$stock_articulo=$_POST['stock_articulo'];
$stockmin_articulo=$_POST['stockmin_articulo'];
$prov_articulo=$_POST['prov_articulo'];
$ob_articulo=$_POST['ob_articulo'];

//Categoría
try
{
	$sql1 = "SELECT id_cat FROM categoria WHERE nombre='".$cat_articulo."'";
	$result1 = mysqli_query($conexionCentral,$sql1);
	$row = mysqli_fetch_array($result1);
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

//Subcategoría
try
{
	$sql2 = "SELECT id_sub FROM subcategoria WHERE nombre='".$sub_articulo."'";
	$result2 = mysqli_query($conexionCentral,$sql2);
	$row2 = mysqli_fetch_array($result2);
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

//Proveedor
try
{
	$sql3 = "SELECT id_prov FROM proveedor WHERE nombre='".$prov_articulo."'";
	$result3 = mysqli_query($conexionCentral,$sql3);
	$row3 = mysqli_fetch_array($result3);
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

if(empty($ob_articulo))
{
	try
	{
		$sql = "UPDATE articulo SET id_cat=".$row[0].",id_sub=".$row2[0].",id_prov=".$row3[0].",nombre='".$nombre_articulo."',stock=".$stock_articulo.",stock_minimo=".$stockmin_articulo.",observacion='' WHERE referencia='".$ref_articulo."'";
		$result = mysqli_query($conexionCentral,$sql) or die(mysql_error($result));	
	}
	
	catch(Exception $e)
	{
		//Generamos un log
		$hoy = date("Y-m-d H:i");
		$file = fopen("logs/logs.txt","a");
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  	    fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$sql."\r\n");
		fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
		fwrite($file, "**********************************************************************************************************************"."\r\n");						
		fclose($file);
	}
}

else
{
	try
	{
		$sql = "UPDATE articulo SET id_cat=".$row[0].",id_sub=".$row2[0].",id_prov=".$row3[0].",nombre='".$nombre_articulo."',stock=".$stock_articulo.",stock_minimo=". $stockmin_articulo.",observacion='".$ob_articulo."' WHERE referencia='".$ref_articulo."'";
		$result = mysqli_query($conexionCentral,$sql) or die(mysql_error($result));	;
	}
	
	catch(Exception $e)
	{
		//Generamos un log
		$hoy = date("Y-m-d H:i");
		$file = fopen("logs/logs.txt","a");
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  	    fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$sql."\r\n");
		fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
		fwrite($file, "**********************************************************************************************************************"."\r\n");						
		fclose($file);
	}	
}
//header("Location: index.php");

mysqli_close($conexionCentral);
?>