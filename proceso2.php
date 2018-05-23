<?php

include 'conexion.php';

 	$codigoPost = $_POST['codigo'];

	 $res=mysqli_query($conexionCentral, "SELECT * FROM articulo where codigo='".$codigoPost."'");

//$res=mysql_query("select * from grupo_artista where codigo='".$_POST[cod_banda]."'",$conexionCentral);

?>

<?php while($fila=mysqli_fetch_array($res)){ ?>

 

 

   <div class="control-group">
                        <label class="control-label" for="inputEmail" style="width:85px;"><b>Código:</b></label>
                        
                        <input type="text"  class="input" name="nombre_salida" style="width:273px;" value= "<?php echo $fila[0];?>">
                        
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputEmail" style="width:85px;"><b>Trabajador:</b></label>
                        <?php 
                          $queryCodigo =  mysqli_query($conexionCentral, "SELECT * FROM trabajador");
                        ?>
                        <select type="text"  class="input" name="trabajador_salida" style="width:288px;">
                        <?php
                        while($lista=mysqli_fetch_array($queryCodigo))
                          echo "<option  value='".$lista[0]."'>".$lista[1]." ". $lista[2]."</option>"; 
                        ?>

                        </select>
                        
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputPassword" style="width:85px;"><b>Fecha:</b></label>       
                        <input type="text" name="fecha_salida" class="input-small" placeholder="2013-11-27">
                        <label class="control-label" for="inputPassword" style="width:85px;"><b>Cantidad:</b></label>
                        <input type="text"class="input-small" name="cantidad_salida" class="input-mini">
                        
                    </div> 
                    <div class="control-group">
                      <label class="control-label" for="inputPassword" style="width:85px;"><b>Gasolinera:</b></label>
                        <select name= "gasolinera_salida" style="width:95px;margin-right:88px;">
                              <option value="Interno">Interno</option>
                              <option value="Martos">Martos</option>
                              <option value="Jaén">Jaén</option>
                              <option value="Castellar">Castellar</option>
                              <option value="Alcala">Alcala</option>
                              <option value="Cordoba">Córdoba</option>
                        </select>
                           
                                
                    </div> 
                     <div class="control-group" align="right">
                            <input type="Submit"  class="btn btn-success" name="enviar" value="Añadir / Modificar">
                            <a href= "#seeSalidas" role ="button"  data-toggle="modal" class="btn btn">Ver Salidas</a>
                                
                    </div>   
                    
            </form>


<?php } ?>


<?php mysqli_close($conexionCentral); ?>
