<?php

include 'conexion.php';

$identificador_subcategoria=$_POST['identificador_subcategoria'];

try
{
	$sql = "UPDATE subcategoria SET activo=0 WHERE id_sub=$identificador_subcategoria";
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
<table class="table table-condensed" id="tableSubcategorias">
      <thead>
        <tr>
          <th width="20%">Identificador</th>
          <th width="25%">Categoria</th>
          <th width="25%">Nombre</th>
          <th width="30%">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php

		try
		{
			$query1 =  mysqli_query($conexionCentral, "SELECT * FROM subcategoria");
			while($row = mysqli_fetch_array($query1)) {
				if ($row[3] != 0) {
					$query2 =  mysqli_query($conexionCentral, "SELECT id_cat, nombre FROM categoria WHERE id_cat=$row[1]");
					$row2 = mysqli_fetch_array($query2); 
					echo "<tr id='subcategoria$row[0]'>";
					echo "<td scope='row' id='idsubcat$row[0]'>$row[0]</td>" ;
					echo "<td id='catsubcat$row[0]'>$row2[1]<input type='hidden' id='catsubcatId$row[0]' value='$row2[0]'></td>" ; 
					echo "<td id='nomsubcat$row[0]'>$row[2]</td>" ;
					echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editSubcategoria($row[0])'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteSubcategoria($row[0])'>Deshabilitar</button></td>" ;
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
          <th width="20%">Identificador</th>
          <th width="25%">Categoria</th>
          <th width="25%">Nombre</th>
          <th width="30%">Acciones</th>
      </tfoot>
    </table>
<script type="text/javascript">
      $(function () {
        $("#tableSubcategorias").dataTable();
      });
    </script>
<?php
mysqli_close($conexionCentral);
?>