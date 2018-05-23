<?php

	include 'conexion.php';

 	$codigoPost = $_POST['codigo'];

	 $res=mysqli_query($conexionCentral, "SELECT * FROM articulo where codigo='".$codigoPost."'");

//$res=mysql_query("select * from grupo_artista where codigo='".$_POST[cod_banda]."'",$conexionCentral);

?>

<?php while($fila=mysqli_fetch_array($res)){ ?>

 

 

 <div class="control-group">
                        <label class="control-label"  style="width:50px;"><b>Fecha:</b></label>
                        <input type="text"  class="input-small" name="fecha_entrada" placeholder="2013-11-03" value= "<?php echo $fila[1];?>">
                        <label class="control-label" style="width:60px;"><b>Código</b></label>
                        <input type="text"  class="input" name="nombre_entrada" style="width:175px;" value= "<?php echo $fila[0];?>">
                       
                       
                        
                    </div>
                     <div class="control-group">
                        
                       
                        
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" style="width:55px;"><b>Stock:</b></label>
                        <input type="text"  style="width:49px;" name="stock_entrada" placeholder="5" value= "<?php echo $fila[4];?>">
                        <label class="control-label" style="width:105px;"><b>Stock Mínimo:</b></label>
                        <input type="text"  style="width:49px;" name="stock_minimo_entrada" placeholder="10" value= "<?php echo $fila[5];?>">
                        
                    </div> 
                    <div class="control-group">
                        <label class="control-label"  style="width:80px;"><b>Proveedor</b></label>
                        <input type="text"  style="width:315px;" name="proveedor_entrada" placeholder="Amazon" value= "<?php echo $fila[2];?>">
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="width:80px;"><b>Detalle</b></label>
                        <textarea style="width:314px;" rows="3" name="detalle_entrada"><?php echo $fila[6];?></textarea>
                        
                    </div>

                    
                    <div class="control-group" align="right">
                           <input type="Submit"  class="btn btn-success" name="enviar" value="Añadir / Modificar">
                            <button class="btn">Ver Entradas </button>
                                
                    </div>  


<?php } ?>


<?php mysqli_close($conexionCentral); ?>
