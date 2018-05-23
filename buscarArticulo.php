<?php
include 'conexion.php';

$contador=0;
$nombre_articulo = $_POST['nombre_articulo'];

try
{
	$sql = "SELECT articulo.referencia, articulo.nombre, categoria.nombre, subcategoria.nombre, articulo.stock FROM `articulo` INNER JOIN subcategoria ON articulo.id_sub = subcategoria.id_sub INNER JOIN categoria ON subcategoria.id_cat = categoria.id_cat WHERE articulo.nombre LIKE '%".$nombre_articulo."%' AND articulo.activo=1";
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
<table class="table table-condensed" id="example2">
      <thead>
        <tr>
          <th width="10%">Identificador</th>
          <th>Referencia</th>
          <th>Articulo</th>
          <th>Categoria</th>
          <th>Subcategoria</th>
          <th>Stock</th>
          <th>Seleccionar</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($row = mysqli_fetch_array($result)) {
            $contador++;			  
            echo "<tr> ";
            echo "<td scope='row' id='idarticulo".$contador."'>$contador</td>" ;						
            echo "<td id='refarticulo".$contador."'>$row[0]</td>" ;
            echo "<td id='nombrearticulo".$contador."'>$row[1]</td>" ;
			echo "<td id='catarticulo".$contador."'>$row[2]</td>" ;
            echo "<td id='subarticulo".$contador."'>$row[3]</td>" ;
            echo "<td id='stockarticulo".$contador."'>$row[4]</td>" ;			
            echo "<td><button type='button' id='botonSeleccionar".$contador."' class='btn btn-default btn-lg' onclick='elegirArticulo(&#39$;row[0]&#39;,&#39;$row[1]&#39;,$row[4])'>Seleccionar <i class='glyphicon glyphicon-check'></i></button></td>" ;
            echo "</tr>";
          }
        ?>
      </tbody>
      <tfoot>
		 <th width="10%">Identificador</th>      
         <th>Referencia</th>
          <th>Articulo</th>
          <th>Categoria</th>
          <th>Subcategoria</th>
          <th>Stock</th>
          <th>Seleccionar</th>
      </tfoot>
    </table>
<script type="text/javascript">
      $(function () {
        $("#example2").dataTable({
	         "bSort": false,
           "bFilter": false
	       } );
      });
</script>
<?php
mysqli_close($conexionCentral);
?>