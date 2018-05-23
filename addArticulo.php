<?php

include 'conexion.php';

$referencia_articulo = $_POST['referencia_articulo'];
$categoria_articulo = $_POST['categoria_articulo'];
$subcategoria_articulo = $_POST['subcategoria_articulo'];
$proveedor_articulo = $_POST['proveedor_articulo'];
$nombre_articulo = $_POST['nombre_articulo'];
$stock_articulo = $_POST['stock_articulo'];
$stockminimo_articulo = $_POST['stockminimo_articulo'];
$observacion_articulo = $_POST['observacion_articulo'];

try
{
	$sql = "INSERT INTO articulo (referencia, id_cat, id_sub, id_prov, nombre, stock, stock_minimo, observacion) VALUES ('".$referencia_articulo."','".$categoria_articulo."','".$subcategoria_articulo."','".$proveedor_articulo."','".$nombre_articulo."','".$stock_articulo."','".$stockminimo_articulo."','".$observacion_articulo."')";
	
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