<?php

include 'conexion.php';

$identificador_categoria=$_POST['identificador_categoria'];
$identificador_articulo=$_POST['identificador_articulo'];

//Subcategoria
try
{
	$sql = "SELECT id_sub,nombre FROM subcategoria WHERE id_cat=".$identificador_categoria." AND activo=1";
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
//$row1 = mysqli_fetch_array($result1);

//Combobox Subcategorías
echo "<select type='text' class='form-control input-sm' id='subcategoriaSelect".$identificador_articulo."'>";
while ($fila = mysqli_fetch_array($result)) {
  echo "<option value='$fila[0]'>$fila[1]</option>";
}
echo "</select>"; 

mysqli_free_result($result);
mysqli_close($conexionCentral);
?>