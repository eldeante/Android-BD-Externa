<?php
include 'conexion.php';

session_start();

$user_trabajador=$_POST['user_trabajador'];
$pass_trabajador=$_POST['pass_trabajador'];

try
{
	$sql3 = "UPDATE trabajador SET sesion=0 WHERE usuario='".$user_trabajador."' AND password='".$pass_trabajador."'";
	$result3 = mysqli_query($conexionCentral,$sql3);
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

session_destroy();
			
mysqli_close($conexionCentral);
?>