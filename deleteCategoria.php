<?php

include 'conexion.php';

$identificador_categoria=$_POST['identificador_categoria'];

try
{
	$sql = "UPDATE categoria SET activo=0 WHERE id_cat=$identificador_categoria";
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
?>
<table class="table table-condensed" id="tableCategorias">
      <thead>
        <tr>
          <th width="20%">Identificador</th>
          <th width="50%">Nombre</th>
          <th width="30%">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
		try
		{
			$query1 =  mysqli_query($conexionCentral, "SELECT * FROM categoria");
			while($row = mysqli_fetch_array($query1)) {
				if ($row[2] != 0) {
					echo "<tr id='categoria$row[0]'>";
					echo "<td scope='row' id='idcat$row[0]'>$row[0]</td>" ;
					echo "<td id='nomcat$row[0]'>$row[1]</td>" ;
					echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editCategoria($row[0])'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteCategoria($row[0])'>Deshabilitar</button></td>" ;
					echo "</tr>";
				}
			}	
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
        ?>
      </tbody>
      <tfoot>
          <th width="20%">Identificador</th>
          <th width="50%">Nombre</th>
          <th width="30%">Acciones</th>
      </tfoot>
    </table>
<script type="text/javascript">
      $(function () {
        $("#tableCategorias").dataTable();
      });
    </script>
<?php
mysqli_close($conexionCentral);
?>