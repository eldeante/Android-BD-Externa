<?php

include 'conexion.php';

$identificador_trabajador=$_POST['identificador_trabajador'];
$nombre_trabajador=$_POST['nombre_trabajador'];
$apellidos_trabajador=$_POST['apellidos_trabajador']; 
$departamento_trabajador=$_POST['departamento_trabajador']; 
$usuario_trabajador=$_POST['usuario_trabajador']; 
$password_trabajador=$_POST['password_trabajador']; 
$rol_trabajador=$_POST['rol_trabajador']; 

try
{
	$sql = "UPDATE trabajador SET nombre='".$nombre_trabajador."', apellidos='".$apellidos_trabajador."', departamento='".$departamento_trabajador."', usuario='".$usuario_trabajador."',  password='".$password_trabajador."', rol='".$rol_trabajador."' WHERE id_trab=$identificador_trabajador";
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