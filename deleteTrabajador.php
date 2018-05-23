<?php

include 'conexion.php';

$identificador_trabajador=$_POST['identificador_trabajador'];

try
{
	$sql = "UPDATE trabajador SET activo=0 WHERE id_trab=$identificador_trabajador";
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
<table class="table table-condensed" id="tableTrabajadores">
      <thead>
        <tr>
          <th width="10%">Identificador</th>
          <th width="30%">Nombre</th>
          <th width="30%">Apellidos</th>
          <th width="20%">Departamento</th>
          <th width="10%">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php	
		try
		{
			$query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador");
			while($row = mysqli_fetch_array($query1)) {
				if ($row[4] != 0) {
					echo "<tr id='trabajador$row[0]'>";
					echo "<td scope='row' id='idtrab$row[0]'>$row[0]</td>" ;
					echo "<td id='nomtrab$row[0]'>$row[1]</td>" ;
					echo "<td id='apetrab$row[0]'>$row[2]</td>" ;
					echo "<td id='deptrab$row[0]'>$row[3]</td>" ;
					echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editTrabajador($row[0])'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteTrabajador($row[0])'>Deshabilitar</button></td>" ;
					  echo "</tr>";
					}
			}
		}
		
		catch(Exception $e)
		{
			//Generamos un log
			$hoy = date("Y-m-d H:i");
			$file = fopen("logs/logs.txt","a");
			fwrite($file, "**********************************************************************************************************************"."\r\n");						  	        fwrite($file, "Log generado el : " .$hoy."\r\n");  
			fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			fwrite($file, "**********************************************************************************************************************"."\r\n");						
			fclose($file);
		}
        ?>
      </tbody>
      <tfoot>
          <th width="15%">Identificador</th>
          <th width="25%">Nombre</th>
          <th width="25%">Apellidos</th>
          <th width="15%">Departamento</th>
          <th width="20%">Acciones</th>
      </tfoot>
    </table>
<script type="text/javascript">
      $(function () {
        $("#tableTrabajadores").dataTable();
      });
    </script>
<?php
mysqli_close($conexionCentral);
?>