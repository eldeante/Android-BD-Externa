<?php

include 'conexion.php';

$identificador_articulo=$_POST['identificador_articulo'];
$cat_articulo=$_POST['cat_articulo'];
$sub_articulo=$_POST['sub_articulo'];
$stock_articulo=$_POST['stock_articulo'];
$stockmin_articulo=$_POST['stockmin_articulo'];
$prov_articulo=$_POST['prov_articulo'];

//header("Location: index.php");

//Categoria
try
{
	$sql = "SELECT id_cat,nombre FROM categoria WHERE activo=1";
	$result = mysqli_query($conexionCentral,$sql);
	$row = mysqli_fetch_array($result);
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
	
//Subcategoria
try
{
	$sql2 = "SELECT id_cat FROM subcategoria WHERE nombre='".$sub_articulo."' AND activo=1";
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

try
{
	$sql1 = "SELECT id_sub,nombre FROM subcategoria WHERE id_cat=".$row2[0]." AND activo=1";
	$result1 = mysqli_query($conexionCentral,$sql1);
	$row1 = mysqli_fetch_array($result1);
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

//Proveedor
try
{
	$sql3 = "SELECT id_prov,nombre FROM proveedor WHERE activo=1";
	$result3 = mysqli_query($conexionCentral,$sql3);
	$row3 = mysqli_fetch_array($result3);
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

mysqli_close($conexionCentral);

//Combobox Categorías
echo "<td id='catarticulo".$identificador_articulo."'><select type='text' class='form-control input-sm' id='categoriaSelect".$identificador_articulo."' onchange='myFunction(this.value,".$identificador_articulo.")'>";
echo "<option value='-1'>".$cat_articulo."</option>";
while($row = mysqli_fetch_array($result)) {
  echo "<option value='$row[0]'>$row[1]</option>";
}
echo "</select></td>";

//Combobox Subcategorías
echo "<td id='subarticulo".$identificador_articulo."'><select type='text' class='form-control input-sm' id='subcategoriaSelect".$identificador_articulo."'>";
echo "<option value='-1'>".$sub_articulo."</option>";
while($row1 = mysqli_fetch_array($result1)) {
  echo "<option value='$row1[0]'>$row1[1]</option>";
}
echo "</select></td>"; 
echo "<td id='stockarticulo".$identificador_articulo."'><input type='text' class='form-control input-sm' id='stockarticuloInput".$identificador_articulo."' value='".$stock_articulo."'></td>";
echo "<td id='stockminarticulo".$identificador_articulo."'><input type='text' class='form-control input-sm' id='stockminarticuloInput".$identificador_articulo."' value='".$stockmin_articulo."'></td>";
echo "<td id='provarticulo".$identificador_articulo."'><select type='text'  class='form-control input-sm' id='proveedorSelect".$identificador_articulo."'>";

//Combobox Proveedor
echo "<option value='-1'>".$prov_articulo."</option>";
while($row3 = mysqli_fetch_array($result3)) {
  echo "<option value='$row3[0]'>$row3[1]</option>";
}
echo "</select></td>"; 
?>