<?php

include 'conexion.php';

$identificador_proveedor = $_POST['identificador_proveedor'];
$nombre_proveedor = $_POST['nombre_proveedor'];
$email_proveedor = $_POST['email_proveedor'];
$contacto_proveedor = $_POST['contacto_proveedor'];
$telefono_proveedor = $_POST['telefono_proveedor'];

try
{
	$sql = "INSERT INTO proveedor (id_prov, nombre, email, contacto, telefono) VALUES ('$identificador_proveedor', '$nombre_proveedor', '$email_proveedor', '$contacto_proveedor', '$telefono_proveedor')";
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