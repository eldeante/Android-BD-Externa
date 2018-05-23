<?php
include 'conexion.php';

$identificador_subcategoria=$_POST['identificador_subcategoria'];

try
{
	$sql = "SELECT id_cat, nombre FROM categoria WHERE activo=1";
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

echo "<select type='text'  class='form-control input-sm' id='catsubcatSelect".$identificador_subcategoria."'>";
echo "<option value='-1'> Categoria</option>";
while($row = mysqli_fetch_array($result)) {
  echo "<option value='$row[0]'> $row[1]</option>";
}
echo "</select>"; 
?>