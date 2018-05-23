<?php

include 'conexion.php';

$identificador_proveedor=$_POST['identificador_proveedor'];

try
{
	$sql = "UPDATE proveedor SET activo=0 WHERE id_prov=$identificador_proveedor";
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
 <table class="table table-condensed" id="tableProveedores">
<thead>
        <tr>
          <th width="10%">Identificador</th>
          <th>Nombre</th>   
          <th>Email</th>
          <th>Contacto</th>
          <th>Telefono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
		
		try
		{
          $query1 =  mysqli_query($conexionCentral, "SELECT * FROM proveedor");
          while($row = mysqli_fetch_array($query1)) {
            if ($row[5] != 0) {
              echo "<tr id='proveedor$row[0]'>";
              echo "<td scope='row' id='idprov$row[0]'>$row[0]</td>" ;
              echo "<td id='nomprov$row[0]'>$row[1]</td>" ;
              echo "<td id='mailprov$row[0]'>$row[2]</td>" ;
              echo "<td id='conprov$row[0]'>$row[3]</td>" ;
              echo "<td id='telprov$row[0]'>$row[4]</td>" ;
              echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editProveedor($row[0])'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteProveedor($row[0])'>Deshabilitar</button></td>" ;
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
			fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			fwrite($file, "**********************************************************************************************************************"."\r\n");						
			fclose($file);
		}
		
        ?>
      </tbody>
      <tfoot>
          <th width="10%">Identificador</th>
          <th>Nombre</th>   
          <th>Email</th>
          <th>Contacto</th>
          <th>Telefono</th>
          <th>Acciones</th>
      </tfoot>
</table>
<script type="text/javascript">
      $(function () {
        $("#tableProveedores").dataTable();
        
      });
    </script>      
<?php 
mysqli_close($conexionCentral);
?>