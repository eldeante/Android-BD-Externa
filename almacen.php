<?php 
include 'conexion.php';

session_start();

if (!isset($_SESSION["id_trabAlmacen"])) 
{
header("Location: login.php");
}

else
{
	$fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 

    //comparamos el tiempo transcurrido 
    if($tiempo_transcurrido >= 1200)
	{
		try
		{
		  $sql3 = "UPDATE trabajador SET sesion=0 WHERE usuario='".$_SESSION["user_trab"]."' AND password='".$_SESSION["pass_trab"]."'";
		  $result3 = mysqli_query($conexionCentral,$sql3);
		}
			
		catch(Exception $e)
		{
			//Generamos un log
			$hoy = date("Y-m-d H:i");
			$file = fopen("logs/logs.txt","a");
			fwrite($file, "**********************************************************************************************************************"."\r\n");						  	        fwrite($file, "Log generado el : " .$hoy."\r\n");  
			fwrite($file, "Se ha realizado con éxito la consulta: " .$sql3."\r\n");
			fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			fwrite($file, "**********************************************************************************************************************"."\r\n");						
			fclose($file);
		}																		
		     	
	  //si pasaron 20 minutos o más 
      session_destroy(); // destruyo la sesión 
      header("Location: login.php"); //envío al usuario a la pag. de autenticación 
    }
	
	else
	{ 
	    //sino, actualizo la fecha de la sesión 
    	$_SESSION["ultimoAcceso"] = $ahora; 
   	} 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>StockPETRONICS</title>
    
    <!-- jQuery (required) & jQuery UI + theme (optional) -->
	 <link href="Keyboard-master/docs/css/jquery-ui.min.css" rel="stylesheet"> 

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
     <!-- DATA TABLES -->
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    
    <!-- keyboard widget css & script (required) -->
	<link href="Keyboard-master/css/keyboard.css" rel="stylesheet">

	<link type="text/css" rel="stylesheet" href="css/autocomplete.css"></link>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><strong>Stock</strong>PETRONICS</a>
        </div>
     <!--   <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
        <li><a href="#">Almacen <span class="sr-only">(current)</span></a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeTrabajador">Trabajadores</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeCategoria">Categorias</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeSubcategoria">Subcategorias</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeProveedor">Proveedores</a></li>
      </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form> -->
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" id="jumboTrab" style="display: block">
      <div class="container">
        <?php
		
			 echo '<div class="row">';
             echo '<div class="col-md-3" style="margin-top: 20px">';
             echo '<button id="trabajador'.$_SESSION["id_trabAlmacen"].'" type="button" class="btn btn-default btn-lg btn-block" onclick="elegirTrabajador('.$_SESSION["id_trabAlmacen"].',&#39'.$_SESSION["nombre_trab"].' '.$_SESSION["apellidos_trab"].'&#39)">'.$_SESSION["nombre_trab"].' '.$_SESSION["apellidos_trab"].' <i class="glyphicon glyphicon-user"></i></button>';
			 
			 //Añadir entradas
			 if(($_SESSION["user_trab"]=="Florentino") || ($_SESSION["user_trab"]=="Sergio"))
			 {
             echo '<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addEntrada">A&ntilde;adir entrada <i class="glyphicon glyphicon-log-in"></i></button>';
			 }
			 
             echo '</div>';
             echo '</div>';
        ?>
      </div>
    </div>

      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <h2>Almacen Petronics</h2>
        </div>
        <div class="col-md-6 col-md-offset-1" id="trabajador" style="display:none">
          <label  class="form-control input-lg" id="nombreTrabajador" style="margin-bottom: 10px;">Trabajador</label>
        </div>
        <div class="col-md-2" id="botonCancelar" style="display:none">
          <button type="button" id="botonCancelacion" class="btn btn-danger btn-lg" onClick="logout( '<?=$_SESSION['user_trab']?>', '<?= $_SESSION['pass_trab'] ?>')">Cerrar Sesión <i class="glyphicon glyphicon-remove-sign"></i></button>
        </div>
        <div class="col-md-8 col-md-offset-1" id="buscador" style="display:none">          
          <input  class="form-control input-lg" id="ipad" style="margin-bottom: 10px;" placeholder="Articulo...">
        </div>
        <div class="col-md-8 col-md-offset-1" id="BuscadorCategorias" style="display:none">          

            <select type="text"  class="form-control" id="catSelect">
            <option value="-1"> Categoria</option>
              <?php
                  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM categoria WHERE activo=1");
                  while($row = mysqli_fetch_array($query1)) {
                    echo "<option value='".$row[1]."'>".$row[1]."</option>" ;
                  }
              ?>
          </select>       
       
        </div>
        <div class="col-md-2" id="botonBuscador" style="display:none">
          <button type="button" id="botonBuscar" class="btn btn-default btn-lg">Buscar <i class="glyphicon glyphicon-search"></i></button>
        </div>
        <div class="col-md-10 col-md-offset-1" id="tablaArticulos" style="display:none">
        </div>
        <div class="alert alert-danger col-md-10 col-md-offset-1" id="alerta" role="alert" style="display:none">
          <strong>Error!</strong> Has superado el stock disponible.  
        </div>
        <div class="alert alert-danger col-md-10 col-md-offset-1" id="sinCantidad" role="alert" style="display:none">
          <strong>Atención!</strong> Introduzca la cantidad que desee retirar.  
        </div>                
        <div class="col-md-6 col-md-offset-1" id="articulo" style="display:none">
          <label  class="form-control input-lg" id="nombreArticulo" style="margin-bottom: 10px;">hola</label>
        </div>
         <div class="col-md-2" id="cantidad" style="display:none">
          <input  class="form-control input-lg" id="numerico" style="margin-bottom: 10px;" placeholder="Cantidad" type="text">
        </div>
        <div class="col-md-2" id="botonEnviar" style="display:none">
          <button type="button" class="btn btn-primary btn-lg" id="enviar">Enviar <i class="glyphicon glyphicon-send"></i></button>
        </div>        
        <div class="col-md-6 col-md-offset-1" id="ListaGasolineras" style="display:none">
        	<select type="text"  class="form-control" id="gasSelect">
                  <?php
                      
					  try
					  {
						  $query2 =  mysqli_query($conexionCentral, "SELECT * FROM gasolinera WHERE activo=1 ORDER BY nombre");
						  while($row2 = mysqli_fetch_array($query2)) {
							echo "<option value='".$row2[0]."'>".$row2[1]."</option>" ;
						  }						  
					  }
					  				  
					  catch(Exception $e)
					  {
						  //Generamos un log
						  $hoy = date("Y-m-d H:i");
						  $file = fopen("logs/logs.txt","a");
						  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			              fwrite($file, "Log generado el : " .$hoy."\r\n");  
						  fwrite($file, "Se ha realizado con éxito la consulta: " .$query2."\r\n");
						  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						  fwrite($file, "**********************************************************************************************************************"."\r\n");						
						  fclose($file);			  
					  }

                  ?>
        	</select>       
        </div>
      </div>                               

      <hr>

      <footer>
        <p>&copy; Petronics Tecnologia SL <?= date("Y") ?></p>
      </footer>
    </div> <!-- /container -->
    <input type="hidden" id="inputTrabajador" value="0">
    <input type="hidden" id="inputArticulo" value="">
    <input type="hidden" id="inputStock" value="0">
    
    <!-- Modal ENTRADA-->
    <div class="modal fade" id="addEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Entrada <i class="glyphicon glyphicon-log-in"></i></h4>
          </div>
          <form class="form-horizontal">
            <div class="modal-body">
          
      <div class="form-group">
        <label for="inputFecha" class="col-sm-2 control-label">Fecha</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="fecha_entrada" value="<?= date('Y-n-j H:i') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="inputAlbaran" class="col-sm-2 control-label">Albaran</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="albaran_entrada" placeholder="Albaran">
        </div>
      </div>
      <div class="form-group autocomplete">
        <label for="inputBuscarArticulo" class="col-sm-2 control-label">Artículo</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" value="" id="articulo_entrada" placeholder="Buscar Artículo" data-source="search.php?search=" />
        </div>
      </div>  
      <div class="form-group">
        <label for="inputTrabajador" class="col-sm-2 control-label">Trabajador</label>
        <div class="col-sm-10">
          <select type="text"  class="form-control" id="trabajador_entrada">
          <?php
    
              try
              {
                  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador WHERE activo=1 AND usuario='".$_SESSION['user_trab']."'" );
                  
                  while($row = mysqli_fetch_array($query1))
                  {
                    echo "<option value='".$row[0]."'>".$row[1]."</option>" ;
                  }
              }
          
              catch(Exception $e)
              {
				  //Generamos un log
				  $hoy = date("Y-m-d H:i");
				  $file = fopen("logs/logs.txt","a");
				  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			      fwrite($file, "Log generado el : " .$hoy."\r\n");  
				  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
				  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
				  fwrite($file, "**********************************************************************************************************************"."\r\n");						
				  fclose($file);			  
              }
          
            ?>
          </select>
        </div>
      </div>
          <div class="form-group">
        <label for="inputCantidad" class="col-sm-2 control-label">Cantidad</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="cantidad_entrada" placeholder="Cantidad">
        </div>
      </div>
          </div>
          <div class="modal-footer"> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insertEntrada" onClick="addEntrada()">Guardar Entrada</button>
          </div>
          </form>
        </div><!-- /modal-contebt -->
      </div><!-- /modal-dialog -->
    </div><!-- /modal -->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- DATA TABES SCRIPT -->
    <script src="plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="Keyboard-master/js/jquery.keyboard.js"></script>

    <!-- AUTOCOMPLETE -->    
    <!-- <script src="js/jquery-1.4.2.min.js"></script> -->
    <script src="js/autocomplete.jquery.js"></script> 

	<script type="text/javascript">   
		$(document).ready(function() {
        	
			$('.autocomplete').autocomplete();

			$('#ipad').keyup(function(){
            //Obtenemos el value del input
            var nombre = $(this).val();        
            var dataString = nombre;
			
			var parametros = {
            "nombre_articulo"   : dataString                                                     
          	};
			
            //Le pasamos el valor del input al ajax
            $.ajax({
                type: "post",
                url: "buscarArticulo.php",
                data: parametros,
                success: function(response) {
					$("#tablaArticulos").html(response).show();
              		$('#tablaArticulos').css('display','block');
                }
            });
        });              
    });    
    </script>

    <script type="text/javascript">
        /*$('#ipad').keyboard({
  usePreview: false,
	display: {
		'bksp'   :  "\u2190",
		'accept' : 'aceptar',
		'default': 'ABC',
		'meta1'  : '.?123',
		'meta2'  : '#+='
	},

	layout: 'custom',
	customLayout: {
		'default': [
			'q w e r t y u i o p {bksp}',
			'a s d f g h j k l {enter}',
			'{s} z x c v b n m , . {s}',
			'{meta1} {space} {meta1} {accept}'
		],
		'shift': [
			'Q W E R T Y U I O P {bksp}',
			'A S D F G H J K L {enter}',
			'{s} Z X C V B N M ! ? {s}',
			'{meta1} {space} {meta1} {accept}'
		],
		'meta1': [
			'1 2 3 4 5 6 7 8 9 0 {bksp}',
			'- / : ; ( ) \u20ac & @ {enter}',
			'{meta2} . , ? ! \' " {meta2}',
			'{default} {space} {default} {accept}'
		],
		'meta2': [
			'[ ] { } # % ^ * + = {bksp}',
			'_ \\ | ~ &lt; &gt; $ \u00a3 \u00a5 {enter}',
			'{meta1} . , ? ! \' " {meta1}',
			'{default} {space} {default} {accept}'
		]
	}

});
$('#numerico').keyboard({
  usePreview: false,
  display: {
    'bksp'   :  "\u2190",
		'accept' : 'OK'
 	},
	layout: 'custom',
  customLayout: {
		'default': [
			'7 8 9 ',
			'4 5 6',
			'1 2 3',
			'{accept} 0 {bksp}'
		]
	}

});*/

  $('#botonBuscar').on( "click", function() {
    //var articulo = $('#ipad').val()
    var categoria=$('#catSelect option:selected').val();
	
	var parametros = {
            "categoria_articulo"   : categoria                                                     
          };
                               
     $.ajax({
     	data:  parametros,
        url:   'BuscarArticuloCategoria.php',
        type:  'post',
            
     	success:  function (response) {
        	$("#tablaArticulos").html(response).show();
            $('#tablaArticulos').css('display','block');
            //document.location.href = 'index.php';
          }
		  
        });
});


</script>
<script type="text/javascript">
function elegirTrabajador(t,n) {
  $('#trabajador'+t).attr("class","btn btn-default btn-lg btn-block active");
  $('#inputTrabajador').val(t);
  $('#buscador').css('display','block');
  $('#BuscadorCategorias').css('display','block');
  $('#botonBuscador').css('display','block');
  $('#contenido').css('margin-top','100px');
  $('#jumboTrab').css('display','none');
  $('#trabajador').css('display','block');
  $('#nombreTrabajador').text(n);
  $('#botonCancelar').css('display','block');
}
</script>
<script type="text/javascript">
function elegirArticulo(articulo,nombre,stock) {
  $('#inputArticulo').val(articulo);
  $('#inputStock').val(stock);
  $('#numerico').attr('placeholder','Cant.: Max. '+stock);
  $('#buscador').css('display','none');
  $('#botonBuscador').css('display','none');
  $('#tablaArticulos').css('display','none');
  $('#BuscadorCategorias').css('display','none');  
  $('#nombreArticulo').text(nombre);
  $('#articulo').css('display','block');
  $('#cantidad').css('display','block');
  $('#botonEnviar').css('display','block');
  $('#ListaGasolineras').css('display','block');
}
</script>
<script type="text/javascript">
$('#enviar').on( "click", function() {
        var referencia = $('#nombreArticulo').text();
        var stock = $('#inputStock').val();
        var cantidad = $('#numerico').val();
		var gasolinera = $('#gasSelect').val();
			
		var cantIntroducida=parseInt(cantidad);
		var stockArticulo=parseInt(stock);
        
		if ( (cantIntroducida > stockArticulo) || (stockArticulo==0) || (cantIntroducida==0) ) {
          $('#alerta').css('display','block');
        } 
		
		else
		{
			if(Number.isNaN(cantIntroducida))
			{
				$('#sinCantidad').css('display','block');
			}
        
			else
			{
				var parametros = {
				"referencia_salida"   : referencia,
				"cantidad_salida"     : cantidad,
				"gasolinera_salida"   : gasolinera			
				};
								   
			  $.ajax({
				data:  parametros,
				url:   'updateArticulo.php',
				type:  'post',
				
				success:  function (response) {
				 document.location.href = 'almacen.php';
					}		  
				});				
			}			
        }		
});    
</script>
<script type="text/javascript">
function logout(user,pass) {

  var parametros = {
            "user_trabajador"   : user,
            "pass_trabajador"     : pass,
          };
		  
	$.ajax({
            data:  parametros,
            url:   'cerrarSesion.php',
            type:  'post',
            
            success:  function (response) {
			document.location.href = 'login.php';				
          }
          });    
};
</script>
<script type="text/javascript">
function addEntrada(){
		
		  //Comprobamos que los campos no estén vaciós
		  if( ($('#albaran_entrada').val()=="") || ($('#articulo_entrada').val()=="") || ($('#trabajador_entrada').val()==-1) || ($('#cantidad_entrada').val()=="") )
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }
	
		  else
		  {
			  var parametros = {
				"fecha_entrada"       : $('#fecha_entrada').val(),
				"albaran_entrada"     : $('#albaran_entrada').val(),
				"articulo_entrada"    : $('#articulo_entrada').val(),
				"trabajador_entrada"  : $('#trabajador_entrada').val(),
				"cantidad_entrada"    : $('#cantidad_entrada').val()                                                         
			  };
								   
			  $.ajax({
				data:  parametros,
				url:   'addEntrada.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'almacen.php';
			  }
			  });			  
		  }
        }
</script>    
  </body>
</html>