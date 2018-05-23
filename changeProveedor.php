<?php

include 'conexion.php';

$identificador_proveedor=$_POST['identificador_proveedor'];
$nombre_proveedor=$_POST['nombre_proveedor'];
$mail_proveedor=$_POST['mail_proveedor'];
$contacto_proveedor=$_POST['contacto_proveedor'];
$telefono_proveedor=$_POST['telefono_proveedor'];

try
{
	$sql = "UPDATE proveedor SET nombre='$nombre_proveedor', email='$mail_proveedor', contacto='$contacto_proveedor', telefono=$telefono_proveedor WHERE id_prov=$identificador_proveedor";
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