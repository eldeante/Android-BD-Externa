<?php
include 'conexion.php';

?>
   <h2>Trabajadores</h2>
          <hr>
          <table class="table table-condensed" id="example1">
            <thead>
        <tr>
          <th>Identificador</th>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Departamento</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
		
		try
		{
			$query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador");
			while($row = mysqli_fetch_array($query1)) {
				echo "<tr> ";
				echo "<td scope='row'>$row[0]</td>" ;
				echo "<td>$row[1]</td>" ;
				echo "<td>$row[2]</td>" ;
				echo "<td>$row[3]</td>" ;
				echo "<td></td>" ;
				echo "</tr>";
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
          <th>Identificador</th>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Departamento</th>
          <th>Acciones</th>
      </tfoot>
    </table>

        
<script type="text/javascript">
      $(function () {
        $("#example1").dataTable();
      });
</script>

<?php mysqli_close($conexionCentral);?>