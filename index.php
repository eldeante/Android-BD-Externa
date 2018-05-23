<?php 

include 'conexion.php';

session_start();

if (!isset($_SESSION["id_trab"])) 
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
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  		fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
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

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
     <!-- DATA TABLES -->
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" href="css/autocomplete.css"></link>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- *********************************************FUNCIONES PARA AÑADIR, EDITAR Y ELIMINAR******************************************************************** -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- DATA TABES SCRIPT -->
    <script src="plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

    <!-- AUTOCOMPLETE -->    
    <!-- <script src="js/jquery-1.4.2.min.js"></script> -->
    <script src="js/autocomplete.jquery.js"></script> 
    
	<script type="text/javascript">
      $(function () {
        $("#example1").dataTable();
        $("#tableTrabajadores").dataTable();
        $("#tableCategorias").dataTable();
        $("#tableSubcategorias").dataTable();
        $("#tableProveedores").dataTable();
        $("#tableEntrada").dataTable();
        $("#tableSalida").dataTable();
        $("#tableArticulos").dataTable();
        $("#tableGasolinera").dataTable();		
        $("#tablePedido").dataTable();						        
      });
	  	  
    </script>

    <script type="text/javascript">
      
	  $(document).ready(function() {
        
		$('.autocomplete').autocomplete();

		//Buscador	
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
				success: function(response)
				{
					$("#tablaArticulos").html(response).show();
					$('#tablaArticulos').css('display','block');
				}			
            });
        });              
      });    
	    
	  //Cerrar Sesión
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

	  <!-- TRABAJADOR -->
	  
	  function addTrabajador(){
          
		  //Comprobamos que los campos no estén vacios
		  if( ($('#nombre_trabajador').val()=="") || ($('#apellidos_trabajador').val()=="") || ($('#usuario_trabajador').val()=="") || ($('#password_trabajador').val()=="") )
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }	

		  else
		  {
				var parametros = {
				"identificador_trabajador"  : $('#identificador_trabajador').val(),
				"departamento_trabajador"   : $('#departamento_trabajador').val(),
				"nombre_trabajador"         : $('#nombre_trabajador').val(),
				"apellidos_trabajador"      : $('#apellidos_trabajador').val(),
				"usuario_trabajador"      : $('#usuario_trabajador').val(),
				"password_trabajador"      : $('#password_trabajador').val(),
				"rol_trabajador"      : $('#rol_trabajador').val(),			                                                              
			  };
																
			  $.ajax({
				data:  parametros,
				url:   'addTrabajador.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });  
		  }
        }
		
		function editTrabajador(t){
        var fila = '#trabajador'+t;
        var idtrab = $('#idtrab'+t).html();
        var nomtrab = $('#nomtrab'+t).html();
        var apetrab = $('#apetrab'+t).html();
        var deptrab = $('#deptrab'+t).html();
        var usertrab = $('#usertrab'+t).html();
        var passtrab = $('#passtrab'+t).html();
        var roltrab = $('#roltrab'+t).html();
        
        var result = "<td scope='row' id='idtrab"+t+"'><input size='10' type='text' class='form-control input-sm' id='idtrabInput"+t+"' value='"+t+"' width='50' disabled></td>" 
            + "<td id='nomtrab"+t+"'><input type='text' class='form-control input-sm' id='nomtrabInput"+t+"' value='"+nomtrab+"'></td>"
            + "<td id='apetrab"+t+"'><input type='text' class='form-control input-sm' id='apetrabInput"+t+"' value='"+apetrab+"'></td>"
            + "<td id='deptrab"+t+"'><select type='text'  class='form-control input-sm' id='deptrabSelect"+t+"'>"
            + "<option value='informatica'> Informática</option>"
            + "<option value='ingenieria'> Ingeniería</option>"
            + "<option value='administracion'> Administración</option>"
            + "<option value='taller'> Taller</option>"
            + "<option value='gerencia'> Gerencia</option>"
            + "<option value='electricidad'> Electricidad</option>"			
            + "</select></td>"
            + "<td id='usertrab"+t+"'><input type='text' class='form-control input-sm' id='usertrabInput"+t+"' value='"+usertrab+"'></td>"
            + "<td id='passtrab"+t+"'><input type='text' class='form-control input-sm' id='passtrabInput"+t+"' value='"+passtrab+"'></td>"
			+ "<td id='roltrab"+t+"'><select type='text'  class='form-control input-sm' id='roltrabSelect"+t+"'>"
            + "<option value='Trabajador'>Trabajador</option>"
            + "<option value='Administrador'>Administrador</option>"
            + "</select></td>"			            
			+ "<td><button type='button' class='btn btn-default btn-xs' onclick='changeTrabajador("+t+")'>Guardar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteTrabajador("+t+")'>Deshabilitar</button></td>" ;
        $('#deptrabSelect'+t).val(deptrab);
        $('#roltrabSelect'+t).val(roltrab);		
        $(fila).html(result).show();
      }
	  
	    function changeTrabajador(t){
        var fila = '#trabajador'+t;
        var idtrab = $('#idtrabInput'+t).val();
        var nomtrab = $('#nomtrabInput'+t).val();
        var apetrab = $('#apetrabInput'+t).val();
        var deptrab = $('#deptrabSelect'+t+' option:selected').val();
        var usertrab = $('#usertrabInput'+t).val();
        var passtrab = $('#passtrabInput'+t).val();
        var roltrab = $('#roltrabSelect'+t+' option:selected').val();

        var parametros = {
            "identificador_trabajador"   : idtrab,
            "nombre_trabajador" : nomtrab,
            "apellidos_trabajador"       : apetrab,
            "departamento_trabajador"        : deptrab,
            "usuario_trabajador"        : usertrab,
            "password_trabajador"        : passtrab,
            "rol_trabajador"        : roltrab						                                                         
          };
                               
          $.ajax({
            data:  parametros,
            url:   'changeTrabajador.php',
            type:  'post',
            
            success:  function (response) {
              var result = "<td scope='row' id='idtrab"+t+"'>"+idtrab+"</td>" 
            + "<td id='nomtrab"+t+"'>"+nomtrab+"</td>"
            + "<td id='apetrab"+t+"'>"+apetrab+"</td>"
            + "<td id='deptrab"+t+"'>"+deptrab+"</td>"
            + "<td id='usertrab"+t+"'>"+usertrab+"</td>"
            + "<td id='passtrab"+t+"'>"+passtrab+"</td>"
            + "<td id='roltrab"+t+"'>"+roltrab+"</td>"						
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='editTrabajador("+t+")'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteTrabajador("+t+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
          }
          });    
      }
	  
	  function deleteTrabajador(t){
        var fila = '#trabajador'+t;
        
        var parametros = {
            "identificador_trabajador"   : t,                                                     
          };
                               
          $.ajax({
            data:  parametros,
            url:   'deleteTrabajador.php',
            type:  'post',
            
            success:  function (response) {
              $('#bodyTrabajadores').html(response).show();
          }
          });    
      }
	  
	  <!-- CATEGORÍA -->
	  
	  function addCategoria(){
          
		  //Comprobamos que los campos no estén vaciós
		  if($('#nombre_categoria').val()=="")
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }
		  
		  else
		  {
				var parametros = {
				"identificador_categoria" : $('#identificador_categoria').val(),
				"nombre_categoria"        : $('#nombre_categoria').val()                                                         
				};

			    $.ajax({
				data:  parametros,
				url:   'addCategoria.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });
		  }                              
        }
		
		function editCategoria(c){
        var fila = '#categoria'+c;
        var idcat = $('#idcat'+c).html();
        var nomcat = $('#nomcat'+c).html();
        
        var result = "<td scope='row' id='idcat"+c+"'><input size='10' type='text' class='form-control input-sm' id='idcatInput"+c+"' value='"+c+"' width='50' disabled></td>" 
            + "<td id='nomcat"+c+"'><input type='text' class='form-control input-sm' id='nomcatInput"+c+"' value='"+nomcat+"'></td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='changeCategoria("+c+")'>Guardar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteCategoria("+c+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
      }

      function changeCategoria(c){
        var fila = '#categoria'+c;
        var idcat = $('#idcatInput'+c).val();
        var nomcat = $('#nomcatInput'+c).val();
        
        var parametros = {
            "identificador_categoria"   : idcat,
            "nombre_categoria" : nomcat                                                      
          };
                               
          $.ajax({
            data:  parametros,
            url:   'changeCategoria.php',
            type:  'post',
            
            success:  function (response) {
              var result = "<td scope='row' id='idcat"+c+"'>"+idcat+"</td>" 
            + "<td id='nomcat"+c+"'>"+nomcat+"</td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='editCategoria("+c+")'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteCategoria("+c+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
          }
          });    
      }
	  
	  function deleteCategoria(c){
        var fila = '#categoria'+c;
        
        var parametros = {
            "identificador_categoria"   : c,                                                     
          };
                               
          $.ajax({
            data:  parametros,
            url:   'deleteCategoria.php',
            type:  'post',
            
            success:  function (response) {
              $('#bodyCategorias').html(response).show();
          }
          });    
      }
	  
	   <!-- SUBCATEGORÍA -->
	   
	   function addSubcategoria(){
		                  
		  //Comprobamos que los campos no estén vaciós
		  if($('#nombre_subcategoria').val()=="")
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }

		  else
		  {
			    var parametros = {
				"identificador_subcategoria"  : $('#identificador_subcategoria').val(),
				"categoria_subcategoria"      : $('#categoria_subcategoria').val(),
				"nombre_subcategoria"         : $('#nombre_subcategoria').val()                                                         
			  };
											
			  $.ajax({
				data:  parametros,
				url:   'addSubcategoria.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });
		  }
        }
		
		function editSubcategoria(s){
        var fila = '#subcategoria'+s;
        var catsubcatId = $('#catsubcatId'+s).val();
        var idsubcat = $('#idsubcat'+s).html();
        var nomsubcat = $('#nomsubcat'+s).html();
        
        var parametros = {
            "identificador_subcategoria"   : s                                                     
          };
        
        $.ajax({
            data:  parametros,
            url:   'getCategorias.php',
            type:  'post',
            
            success:  function (response) {
              var result = "<td scope='row' id='idsubcat"+s+"'><input size='10' type='text' class='form-control input-sm' id='idsubcatInput"+s+"' value='"+s+"' disabled></td>" 
            + "<td id='catsubcat"+s+"'>"+response+"</td>"
            + "<td id='nomsubcat"+s+"'><input type='text' class='form-control input-sm' id='nomsubcatInput"+s+"' value='"+nomsubcat+"'></td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='changeSubcategoria("+s+")'>Guardar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteSubcategoria("+s+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
        $('#catsubcatSelect'+s).val(catsubcatId);      
          }
          });
      }
    
      function changeSubcategoria(s){
        var fila = '#subcategoria'+s;
        var idsubcat = $('#idsubcatInput'+s).val();
        var catsubcat = $('#catsubcatSelect'+s+' option:selected').text();
        var catsubcatVal = $('#catsubcatSelect'+s).val();
        var nomsubcat = $('#nomsubcatInput'+s).val();
        
        var parametros = {
            "identificador_subcategoria"   : idsubcat,
            "categoria_subcategoria" :catsubcatVal,
            "nombre_subcategoria" : nomsubcat                                                      
          };
                               
          $.ajax({
            data:  parametros,
            url:   'changeSubcategoria.php',
            type:  'post',
            
            success:  function (response) {
              var result = "<td scope='row' id='idsubcat"+s+"'>"+idsubcat+"</td>"
            + "<td id='catsubcat"+s+"'>"+catsubcat+"<input type='hidden' id='catsubcatId"+s+"'value='"+catsubcatVal+"'></td>"   
            + "<td id='nomsubcat"+s+"'>"+nomsubcat+"</td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='editSubcategoria("+s+")'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteSubcategoria("+s+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
          }
          });    
      }
	  
	  function deleteSubcategoria(s){
        var fila = '#subcategoria'+s;
        
        var parametros = {
            "identificador_subcategoria"   : s,                                                     
          };
                               
          $.ajax({
            data:  parametros,
            url:   'deleteSubcategoria.php',
            type:  'post',
            
            success:  function (response) {
              
              $('#bodySubcategorias').html(response).show();
          }
          });    
      }
	  
	   <!-- PROVEEDORES -->
	   
	   function addProveedor(){
          
		  //Comprobamos que los campos no estén vaciós
		  if($('#nombre_proveedor').val()=="")
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }

		  else
		  {
			  var parametros = {
				"identificador_proveedor" : $('#identificador_proveedor').val(),
				"nombre_proveedor"        : $('#nombre_proveedor').val(),
				"email_proveedor"         : $('#email_proveedor').val(),
				"contacto_proveedor"      : $('#contacto_proveedor').val(),
				"telefono_proveedor"      : $('#telefono_proveedor').val()                                                         
			  };
											
			  $.ajax({
				data:  parametros,
				url:   'addProveedor.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });			  
		  }
        }
		
		function editProveedor(p){
        var fila = '#proveedor'+p;
        var idprov = $('#idprov'+p).html();
        var nomprov = $('#nomprov'+p).html();
        var mailprov = $('#mailprov'+p).html();
        var conprov = $('#conprov'+p).html();
        var telprov = $('#telprov'+p).html();
        
        var result = "<td scope='row' id='idtrab"+p+"'><input size='10' type='text' class='form-control input-sm' id='idprovInput"+p+"' value='"+p+"' disabled></td>" 
            + "<td id='nomprov"+p+"'><input type='text' class='form-control input-sm' id='nomprovInput"+p+"' value='"+nomprov+"'></td>"
            + "<td id='mailprov"+p+"'><input type='text' class='form-control input-sm' id='mailprovInput"+p+"' value='"+mailprov+"'></td>"
            + "<td id='conprov"+p+"'><input type='text' class='form-control input-sm' id='conprovInput"+p+"' value='"+conprov+"'></td>"
            + "<td id='telprov"+p+"'><input type='text' class='form-control input-sm' id='telprovInput"+p+"' value='"+telprov+"'></td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='changeProveedor("+p+")'>Guardar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteProveedor("+p+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
      }
   
      function changeProveedor(p){
        var fila = '#proveedor'+p;
        var idprov = $('#idprovInput'+p).val();
        var nomprov = $('#nomprovInput'+p).val();
        var mailprov = $('#mailprovInput'+p).val();
        var conprov = $('#conprovInput'+p).val();
        var telprov = $('#telprovInput'+p).val();
        
        var parametros = {
            "identificador_proveedor"   : idprov,
            "nombre_proveedor" : nomprov,
            "mail_proveedor"        : mailprov,
            "contacto_proveedor"        : conprov,
            "telefono_proveedor"        : telprov                                                         
          };
                               
          $.ajax({
            data:  parametros,
            url:   'changeProveedor.php',
            type:  'post',
            
            success:  function (response) {
              var result = "<td scope='row' id='idprov"+p+"'>"+idprov+"</td>" 
            + "<td id='nomprov"+p+"'>"+nomprov+"</td>"
            + "<td id='mailprov"+p+"'>"+mailprov+"</td>"
            + "<td id='conprov"+p+"'>"+conprov+"</td>"
            + "<td id='telprov"+p+"'>"+telprov+"</td>"
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='editProveedor("+p+")'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteProveedor("+p+")'>Deshabilitar</button></td>" ;
        $(fila).html(result).show();
          }
          });    
      }
	  
	  function deleteProveedor(p){
        var fila = '#proveedor'+p;
        
        var parametros = {
            "identificador_proveedor"   : p                                                     
          };
                               
          $.ajax({
            data:  parametros,
            url:   'deleteProveedor.php',
            type:  'post',
            
            success:  function (response) {
              $("#bodyProveedores").html(response).show();
          }
          });    
      }
	  
	  <!-- ARTÍCULO -->
	  
	  function addArticulo(){
		  
		  //Comprobamos que los campos no estén vaciós
		  if( ($('#referencia_articulo').val()=="") || ($('#categoria_articulo').val()==-1) || ($('#subcategoria_articulo').val()==-1) || ($('#proveedor_articulo').val()==-1) || ($('#nombre_articulo').val()=="") || ($('#referencia_articulo').val()=="") )
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }
		  
		  else
		  {
			    var parametros = {
				"referencia_articulo"   : $('#referencia_articulo').val(),
				"categoria_articulo"   : $('#categoria_articulo').val(),            
				"subcategoria_articulo" : $('#subcategoria_articulo').val(),
				"proveedor_articulo"    : $('#proveedor_articulo').val(),
				"nombre_articulo"       : $('#nombre_articulo').val(),
				"stock_articulo"        : $('#stock_articulo').val(),
				"stockminimo_articulo"  : $('#stockminimo_articulo').val(),
				"observacion_articulo"  : $('#observacion_articulo').val()                                                         
			  };
											
			  $.ajax({
				data:  parametros,
				url:   'addArticulo.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });
		  }
        }
		
		function editAlmacen(p)
		{
		var fila = '#articulo'+p;
		var idarticulo = $('#idarticulo'+p).html();
        var refarticulo = $('#refarticulo'+p).html();
        var nombrearticulo = $('#nombrearticulo'+p).html();		
        var catarticulo = $('#catarticulo'+p).html();
        var subarticulo = $('#subarticulo'+p).html();
        var stockarticulo = $('#stockarticulo'+p).html();		
        var stockminarticulo = $('#stockminarticulo'+p).html();
        var provarticulo = $('#provarticulo'+p).html();
        var obarticulo = $('#obarticulo'+p).html();		
              
		var parametros = {
			"identificador_articulo"   : idarticulo,
            "cat_articulo"        : catarticulo,
			"sub_articulo"        : subarticulo,
			"stock_articulo"        : stockarticulo,
			"stockmin_articulo"        : stockminarticulo,
			"prov_articulo"        : provarticulo,								
          };

			$.ajax({
            data:  parametros,
            url:   'editAlmacen.php',
            type:  'post',
            
            success:  function (response) {
			  var result ="<td scope='row' id='idarticulo"+p+"'><input type='text' class='form-control input-sm' id='idarticuloInput"+p+"' value='"+p+"' disabled></td>"+"<td scope='row' id='refarticulo"+idarticulo+"'><input type='text' class='form-control input-sm' id='refarticuloInput"+idarticulo+"' value='"+refarticulo+"' disabled></td>"+"<td id='nombrearticulo"+idarticulo+"'><input type='text' class='form-control input-sm' id='nombrearticuloInput"+idarticulo+"' value='"+nombrearticulo+"'></td>"+response+"<td id='obarticulo"+idarticulo+"'><input type='text' class='form-control input-sm' id='obarticuloInput"+idarticulo+"' value='"+obarticulo+"'></td>"+"<td><button type='button' class='btn btn-default btn-xs' onclick='changeAlmacen("+p+")'>Guardar</button> </td>";
			  $(fila).html(result);
			  $(fila).show();				  
          }
          });		  			        			
		}
		
		//Función Onchange de select de categoría
		function myFunction(id_cat,p)
		{			
			var fila = '#articulo'+p;
			var idarticulo = $('#idarticulo'+p).html();
		
			var parametros = {
			"identificador_articulo"   : p,					  
			"identificador_categoria"   : id_cat,			
			};
				  
			$.ajax({
				data:  parametros,
				url:   'getSubcategoriasModal.php',
				type:  'post',

				success:  function (response) {
			  	console.log(response);
					
					var result1=response;												
					
					$("#subcategoriaSelect"+p).replaceWith(result1);					
					}
				});
		}

		function changeAlmacen(p)
		{
        var fila = '#articulo'+p;
		var idarticulo = $('#idarticuloInput'+p).val();
        var refarticulo = $('#refarticuloInput'+p).val();		
        var nombrearticulo = $('#nombrearticuloInput'+p).val();		
        var catarticulo = $('#categoriaSelect'+p+' option:selected').text();
        var subarticulo = $('#subcategoriaSelect'+p+' option:selected').text();
        var stockarticulo = $('#stockarticuloInput'+p).val();		
        var stockminarticulo = $('#stockminarticuloInput'+p).val();
        var provarticulo = $('#proveedorSelect'+p+' option:selected').text();
        var obarticulo = $('#obarticuloInput'+p).val();	
		
        var parametros = {
            "ref_articulo"   : refarticulo,
            "nombre_articulo" : nombrearticulo,
            "cat_articulo"        : catarticulo,
            "sub_articulo"        : subarticulo,
            "stock_articulo"        : stockarticulo,
            "stockmin_articulo"        : stockminarticulo,
            "prov_articulo"        : provarticulo,
            "ob_articulo"        : obarticulo			                                                         
          };
                               
          $.ajax({
            data:  parametros,
            url:   'changeAlmacen.php',
            type:  'post',
            
            success:  function (response) {
				
              var result = "<td scope='row' id='idarticulo"+p+"'>"+idarticulo+"</td>" 
            + "<td id='refarticulo"+p+"'>"+refarticulo+"</td>"
            + "<td id='nombrearticulo"+p+"'>"+nombrearticulo+"</td>"
            + "<td id='catarticulo"+p+"'>"+catarticulo+"</td>"
            + "<td id='subarticulo"+p+"'>"+subarticulo+"</td>"
            + "<td id='stockarticulo"+p+"'>"+stockarticulo+"</td>"
            + "<td id='stockminarticulo"+p+"'>"+stockminarticulo+"</td>"
            + "<td id='provarticulo"+p+"'>"+provarticulo+"</td>"
            + "<td id='obarticulo"+p+"'>"+obarticulo+"</td>"						
            + "<td><button type='button' class='btn btn-default btn-xs' onclick='editAlmacen("+p+")'>Editar</button>"
			+"<button type='button' class='btn btn-default btn-xs'  onclick='deleteAlmacen("+p+","+refarticulo+")'>Deshabilitar</button>"
			+"<button type='button' class='btn btn-default btn-xs' onclick='deleteStock("+p+","+refarticulo+")'>Eliminar Stock</button></td>" ;
        $(fila).html(result).show();
		document.location.href = 'index.php';
          }
          });    		
		}
		
		//Obtener las categorías para el modal del botón Añadir Artículo
		function getSubCategoriasAnadirArticulo(id_cat)
		{
			var parametros = {
			"identificador_categoria"   : id_cat,			
			};
			
			$.ajax({
            data:  parametros,
            url:   'getSubcategorias.php',
            type:  'post',
            
            success:  function (response) {
       		  $("#subcategoria_articulo").replaceWith(response);
          }
          });
		}
		
		function deleteAlmacen(p,ref){
        var fila = '#articulo'+p;
        
        var parametros = {
            "identificador_articulo"   : p,
            "referencia_articulo"   : ref			                                                     
          };

          $.ajax({
            data:  parametros,
            url:   'deleteAlmacen.php',
            type:  'post',
            
            success:  function (response)
			{
				document.location.href = 'index.php';
          	}
          });    
		}

		function deleteStock(p,referencia){
        var fila = '#articulo'+p;
        
        var parametros = {
            "identificador_articulo"   : p,			
            "referencia_articulo"   : referencia,
          };

          $.ajax({
            data:  parametros,
            url:   'deleteStock.php',
            type:  'post',
            
            success:  function (response)
			{
				document.location.href = 'index.php';
          	}
          });    
		}
				
		<!-- ENTRADA -->
		
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
				  document.location.href = 'index.php';
			  }
			  });			  
		  }
        }
		
		<!-- SALIDA -->
		
		function addSalida(){
		
		  //Comprobamos que los campos no estén vaciós
		  if( ($('#articulo_salida').val()=="") || ($('#trabajador_salida').val()==-1) || ($('#cantidad_salida').val()=="") )
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }
	
          else
		  {
			  var parametros = {
				"fecha_salida"      : $('#fecha_salida').val(),
				"articulo_salida"   : $('#articulo_salida').val(),
				"trabajador_salida" : $('#trabajador_salida').val(),
				"cantidad_salida"   : $('#cantidad_salida').val(), 
				"gasolinera_salida"   : $('#gasolinera_salida').val() 			                                                        
			  };
								   
			  $.ajax({
				data:  parametros,
				url:   'addSalida.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });			  
		  }
        }		

		<!-- GASOLINERA -->

	   function addGasolinera(){
		                  
		  //Comprobamos que los campos no estén vaciós
		  if($('#nombre_gasolinera').val()=="")
		  {
			  alert ("NO PUEDE HABER CAMPOS VACÍOS");
		  }

		  else
		  {
				var parametros = {
				"nombre_gasolinera"        : $('#nombre_gasolinera').val()                                                         
				};

			    $.ajax({
				data:  parametros,
				url:   'addGasolinera.php',
				type:  'post',
				
				success:  function (response) {
				  document.location.href = 'index.php';
			  }
			  });
		  }
        }
	
		//Funciones para los botones
		$(function () {
        $('#seeTrabajador').on('hide.bs.modal', function (e) {
        window.location.reload("true");});
      	$('#seeCategoria').on('hide.bs.modal', function (e) {
        window.location.reload("true");});
      	$('#seeSubcategoria').on('hide.bs.modal', function (e) {
        window.location.reload("true");});
      	$('#seeProveedor').on('hide.bs.modal', function (e) {
        window.location.reload("true");});      			
		$('#seeEntrada').on('hide.bs.modal', function (e) {
        window.location.reload("true");});
      	$('#seeSalida').on('hide.bs.modal', function (e) {
        window.location.reload("true");});
      	$('#seeGasolinera').on('hide.bs.modal', function (e) {
        window.location.reload("true");});		
      	$('#seePedido').on('hide.bs.modal', function (e) {
        window.location.reload("true");});			  
		});
		  
    </script> 
    
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
          <a class="navbar-brand" href="#"><strong>Stock</strong>EJEMPLO</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">                 
        <li><a href="#" data-toggle="modal" data-target="#seeTrabajador">Trabajadores</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeCategoria">Categorias</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeSubcategoria">Subcategorias</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeProveedor">Proveedores</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeEntrada">Entradas</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeSalida">Salidas</a></li>
        <li><a href="#" data-toggle="modal" data-target="#seeGasolinera">Gasolineras</a></li>        
        <li><a href="#" data-toggle="modal" data-target="#seePedido">Hacer Pedido</a></li>                
      </ul>
       <!--   <form class="navbar-form navbar-right">
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
    <div class="jumbotron">
      <div class="container">
        <div class="row">
         <div class="col-md-3" style="margin-top: 40px">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addTrabajador">A&ntilde;adir trabajador <i class="glyphicon glyphicon-user"></i></button>
         </div>
         <div class="col-md-3" style="margin-top: 40px">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addCategoria">A&ntilde;adir categoria <i class="glyphicon glyphicon-tag"></i></button>
         </div>
         <div class="col-md-3" style="margin-top: 40px">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addSubcategoria">A&ntilde;adir subcategoria <i class="glyphicon glyphicon-tags"></i></button>
         </div>
         <div class="col-md-3" style="margin-top: 40px">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addArticulo">A&ntilde;adir articulo <i class="glyphicon glyphicon-bookmark"></i></button>
         </div>
        </div>
        <div class="row" style="margin-top: 10px">
         <div class="col-md-3">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addProveedor">A&ntilde;adir proveedor <i class="glyphicon glyphicon-th-list"></i></button>
         </div>
         <div class="col-md-3">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addEntrada">A&ntilde;adir entrada <i class="glyphicon glyphicon-log-in"></i></button>
         </div>
         <div class="col-md-3">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addSalida">A&ntilde;adir salida <i class="glyphicon glyphicon-log-out"></i></button>
         </div>
         <div class="col-md-3">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addGasolinera">A&ntilde;adir gasolinera <i class="glyphicon glyphicon-log-out"></i></button>
         </div>         
         <div class="col-md-3">
          <button type="button" id="botonCancelacion" class="btn btn-danger btn-lg btn-block" onClick="logout( '<?=$_SESSION['user_trab']?>', '<?= $_SESSION['pass_trab'] ?>')">Cerrar Sesión <i class="glyphicon glyphicon-remove-sign"></i></button>
         </div>         
        </div>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-10 col-md-offset-1" id="#contenido">
          <h2>Almacen</h2>
          <table class="table table-condensed" id="example1">
      <thead>
        <tr>
          <th width="10%">Identificador</th>
          <th>Referencia</th>
          <th>Articulo</th>
          <th>Categoria</th>
          <th>Subcategoria</th>
          <th>Stock</th>
          <th>Stock minimo</th>
          <th>Proveedor</th>
          <th>Observacion</th>
		  <th>Acciones</th>          
        </tr>
      </thead>
      <tbody>
        <?php
		  $contador=0;
          
		  try
		  {
		  	$query1 =  mysqli_query($conexionCentral, "SELECT articulo.referencia, articulo.nombre, categoria.nombre, subcategoria.nombre, articulo.stock, articulo.stock_minimo, proveedor.nombre, articulo.observacion FROM `articulo` INNER JOIN subcategoria ON articulo.id_sub = subcategoria.id_sub INNER JOIN categoria ON subcategoria.id_cat = categoria.id_cat INNER JOIN proveedor ON articulo.id_prov = proveedor.id_prov WHERE articulo.activo=1");
			  
			  while($row = mysqli_fetch_array($query1))
			  {
				$contador++;
				if ($row[4]<=$row[5])
				{
				  echo "<tr class='danger' id='articulo$contador'> ";
				} 
				
				else
				{
				  echo "<tr id='articulo$contador'> ";
				}
				
				echo "<td scope='row' id='idarticulo".$contador."'>$contador</td>" ;			
				echo "<td id='refarticulo".$contador."'>$row[0]</td>" ;
				echo "<td id='nombrearticulo".$contador."'>$row[1]</td>" ;
				echo "<td id='catarticulo".$contador."'>$row[2]</td>" ;
				echo "<td id='subarticulo".$contador."'>$row[3]</td>" ;
				echo "<td id='stockarticulo".$contador."'>$row[4]</td>" ;
				echo "<td id='stockminarticulo".$contador."'>$row[5]</td>" ;
				echo "<td id='provarticulo".$contador."'>$row[6]</td>" ;
				echo "<td id='obarticulo".$contador."'>$row[7]</td>" ;
				echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editAlmacen(".$contador.")'>Editar</button><button type='button' class='btn btn-default btn-xs' onclick='deleteAlmacen(".$contador.",&#39;$row[0]&#39;)'>Deshabilitar</button><button type='button' class='btn btn-default btn-xs'  onclick='deleteStock(".$contador.",&#39;$row[0]&#39;)'>Eliminar Stock</button></td>";
				echo "</tr>";			
			  }
		  }
		  
		  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
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
          <th>Stock minimo</th>
          <th>Proveedor</th>
          <th>Observacion</th>
		  <th>Acciones</th> 
      </tfoot>
    </table>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Petronics Tecnologia SL <?= date("Y") ?></p>
      </footer>
    </div> <!-- /container -->

<!-- ***********************************************MODAL PARA LOS BOTONES************************************************************************************** -->
    
<!-- Modal TRABAJADOR-->
<div class="modal fade" id="addTrabajador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Trabajador <i class="glyphicon glyphicon-user"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
     
  <div class="form-group">
    <label for="inputDepartamento" class="col-sm-2 control-label">Departamento</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="departamento_trabajador">
        <option value="Informatica"> Informatica</option>
        <option value="Ingenieria"> Ingenieria</option>
        <option value="Administracion"> Administracion</option>
        <option value="Taller"> Taller</option>
        <option value="Gerencia"> Gerencia</option>
        <option value="Compras"> Compras</option>
        <option value="Electricidad"> Electricidad</option>
        
      </select>
    </div>
  </div>
      <div class="form-group">
    <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nombre_trabajador"placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="apellidos_trabajador" placeholder="Apellidos">
    </div>
  </div>
  <div class="form-group">
    <label for="inputUsuario" class="col-sm-2 control-label">Usuario</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="usuario_trabajador" placeholder="Usuario">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">Contraseña</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="password_trabajador" placeholder="Contraseña">
    </div>
  </div>  
   <div class="form-group">
    <label for="inputRol" class="col-sm-2 control-label">Rol</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="rol_trabajador">
        <option value="Administrador"> Administrador</option>
        <option value="Trabajador"> Trabajador</option>
        
      </select>
    </div>
  </div>  
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addTrabajador()">Guardar Trabajador</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal CATEGORIA-->
<div class="modal fade" id="addCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Categoria <i class="glyphicon glyphicon-tag"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
     
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nombre_categoria" placeholder="Nombre">
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addCategoria()">Guardar Categoría</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal SUBCATEGORIA-->
<div class="modal fade" id="addSubcategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Subcategoria <i class="glyphicon glyphicon-tags"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
      
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Categoria</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="categoria_subcategoria">
      <?php
          
		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM categoria WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
    <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="nombre_subcategoria" placeholder="Nombre">
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addSubcategoria()">Guardar Subcategoría</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal PROVEEDOR-->
<div class="modal fade" id="addProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Proveedor <i class="glyphicon glyphicon-th-list"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
      
      <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="nombre_proveedor"placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Mail</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="email_proveedor" placeholder="Mail">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Contacto</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="contacto_proveedor" placeholder="Contacto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Tlfno.</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="telefono_proveedor" placeholder="Telefono">
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addProveedor()">Guardar Proveedor</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal ARTICULO-->
<div class="modal fade" id="addArticulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Articulo <i class="glyphicon glyphicon-bookmark"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
      <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Referencia</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="referencia_articulo" placeholder="Referencia">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Categoria</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="categoria_articulo" onChange="getSubCategoriasAnadirArticulo(this.value)">
      <option value="-1"> Categoría</option>
	  <?php
		  
		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM categoria WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
    <label for="inputPassword4" class="col-sm-2 control-label">Subcategoria</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="subcategoria_articulo">
      <option value="-1"> Subcategoría</option>
        
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword5" class="col-sm-2 control-label">Proveedor</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="proveedor_articulo">
        <option value="-1"> Proveedor</option>
      <?php

		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM proveedor WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
    <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nombre_articulo" placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Stock</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="stock_articulo" placeholder="Stock">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Stock Min.</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="stockminimo_articulo" placeholder="Stock Minimo">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Observ.</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="observacion_articulo" placeholder="Observacion">
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addArticulo()">Guardar Artículo</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

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
        <option value="-1"> Trabajador</option>
      <?php

		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insertEntrada">Guardar Entrada</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal SALIDA-->
<div class="modal fade" id="addSalida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Salida <i class="glyphicon glyphicon-log-out"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
      
  <div class="form-group">
    <label for="inputFecha" class="col-sm-2 control-label">Fecha</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="fecha_salida" value="<?= date('Y-n-j H:i') ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputTrabajador" class="col-sm-2 control-label">Trabajador</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="trabajador_salida">
        <option value="-1"> Trabajador</option>
      <?php

		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }
	  
        ?>
      </select>
    </div>
  </div>
  <div class="form-group autocomplete">
    <label for="inputBuscarArticuloSalida" class="col-sm-2 control-label">Artículo</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" value="" id="articulo_salida" placeholder="Buscar Artículo" data-source="search.php?search=" />
    </div>
  </div>  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Cantidad</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="cantidad_salida" placeholder="Cantidad">
    </div>
  </div>
    <div class="form-group">
    <label for="inputTrabajador" class="col-sm-2 control-label">Gasolinera</label>
    <div class="col-sm-10">
      <select type="text"  class="form-control" id="gasolinera_salida">
      <?php
	  
		  try
		  {		  
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM gasolinera WHERE activo=1");
			  
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }
	  
        ?>
      </select>
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insertSalida">Guardar Salida</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal GASOLINERA-->
<div class="modal fade" id="addGasolinera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">A&ntilde;adir Gasolinera <i class="glyphicon glyphicon-map-marker"></i></h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
     
  <div class="form-group">
    <label for="inputNombreGasolinera" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nombre_gasolinera" placeholder="Nombre">
    </div>
  </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" type="submit" onclick="addGasolinera()">Guardar Gasolinera</button>
      </div>
      </form>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- ***********************************************MODAL PARA LOS MENU************************************************************************************** -->

<!-- Modal TRABAJADOR-->
<div class="modal fade" id="seeTrabajador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Trabajadores <i class="glyphicon glyphicon-user"></i></h4>
      </div>
      <div class="modal-body" id="bodyTrabajadores">
      <table class="table table-condensed" id="tableTrabajadores">
      <thead>
        <tr>
          <th>Identificador</th>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Departamento</th>
          <th>Usuario</th>
          <th>Contraseña</th>
          <th>Rol</th>                    
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
		
		  try
		  {		  
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM trabajador WHERE activo=1");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {				
				if ($row[8] != 0)
				{
				  echo "<tr id='trabajador$row[0]'>";
				  echo "<td scope='row' id='idtrab$row[0]'>$row[0]</td>" ;
				  echo "<td id='nomtrab$row[0]'>$row[1]</td>" ;
				  echo "<td id='apetrab$row[0]'>$row[2]</td>" ;
				  echo "<td id='deptrab$row[0]'>$row[3]</td>" ;
				  echo "<td id='usertrab$row[0]'>$row[4]</td>" ;
				  echo "<td id='passtrab$row[0]'>$row[5]</td>" ;			  			  
				  echo "<td id='roltrab$row[0]'>$row[6]</td>" ;
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
          <th>Apellidos</th>
          <th>Departamento</th>
          <th>Usuario</th>
          <th>Contraseña</th>
          <th>Rol</th>                    
          <th>Acciones</th>
      </tfoot>
    </table>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal CATEGORIA-->
<div class="modal fade" id="seeCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Categorias <i class="glyphicon glyphicon-tag"></i></h4>
      </div>
      <div class="modal-body" id="bodyCategorias">
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
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM categoria WHERE activo=1");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {
				if ($row[2] != 0) 
				{
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
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
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal SUBCATEGORIA-->
<div class="modal fade" id="seeSubcategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Subcategorias <i class="glyphicon glyphicon-tags"></i></h4>
      </div>
      <div class="modal-body" id="bodySubcategorias">
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
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM subcategoria WHERE activo=1");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {
				if ($row[3] != 0) 
				{					
				  try
				  {
					  $query2 =  mysqli_query($conexionCentral, "SELECT id_cat, nombre FROM categoria WHERE id_cat=$row[1] AND activo=1");
					  $row2 = mysqli_fetch_array($query2); 
					  echo "<tr id='subcategoria$row[0]'>";
					  echo "<td scope='row' id='idsubcat$row[0]'>$row[0]</td>" ;
					  echo "<td id='catsubcat$row[0]'>$row2[1]<input type='hidden' id='catsubcatId$row[0]' value='$row2[0]'></td>" ; 
					  echo "<td id='nomsubcat$row[0]'>$row[2]</td>" ;
					  echo "<td><button type='button' class='btn btn-default btn-xs' onclick='editSubcategoria($row[0])'>Editar</button> <button type='button' class='btn btn-default btn-xs' onclick='deleteSubcategoria($row[0])'>Deshabilitar</button></td>" ;
					  echo "</tr>";					  
				  }
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			          fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query2."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }
				}
			  }		  	  
		  }
	  
	  	  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal PROVEEDOR-->
<div class="modal fade" id="seeProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Proveedores <i class="glyphicon glyphicon-th-list"></i></h4>
      </div>
      <div class="modal-body">
      <div class="table-responsive" id="bodyProveedores">
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
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM proveedor WHERE activo=1");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {				
				if ($row[5] != 0) 
				{
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
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
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
    </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal ENTRADA -->
<div class="modal fade" id="seeEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Entrada <i class="glyphicon glyphicon-th-list"></i></h4>
      </div>
      <div class="modal-body">
      <div class="table-responsive" id="bodyEntrada">
      <table class="table table-condensed" id="tableEntrada">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Albaran</th>
          <th>Artículo</th>
          <th>Trabajador</th>
          <th>Cantidad</th>                    
        </tr>
      </thead>
      <tbody>
        <?php
		
		  try
		  {	
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM entrada");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {				
				if ($row[5] != 0) 
				{
				  echo "<tr id='entrada$row[0]'>";
				  $date = date_create($row[1]);
				  echo "<td id='fechaentrada$row[0]'>".date_format($date, 'Y-m-d H:i')."</td>" ;
				  echo "<td id='albaranentrada$row[0]'>$row[2]</td>" ;
				  
				  //Referencia (Artículo)				  
				  try
				  {
					  $query2 =  mysqli_query($conexionCentral, "SELECT nombre FROM articulo WHERE referencia='".$row[3]."'");
					  $row2 = mysqli_fetch_array($query2);
					  echo "<td id='referenciaentrada$row[0]'>$row2[0]</td>" ;					  
				  }
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			          fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query2."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }
				  
				  //Trabajador
				  try
				  {
					  $query3 =  mysqli_query($conexionCentral, "SELECT nombre FROM trabajador WHERE id_trab='".$row[4]."'");
					  $row3 = mysqli_fetch_array($query3);			  
					  echo "<td id='trabajadorentrada$row[0]'>$row3[0]</td>" ;
					  echo "<td id='cantidadentrada$row[0]'>$row[5]</td>" ;			  
					  echo "</tr>";
				  }
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						        			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query3."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }				  
				}
			  }
		  }
	  
	  	  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }
		
        ?>        
      </tbody>
      <tfoot>
          <th>Fecha</th>
          <th>Albaran</th>
          <th>Artículo</th>
          <th>Trabajador</th>          
          <th>Cantidad</th>                    
      </tfoot>
    </table>
    </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal SALIDA -->
<div class="modal fade" id="seeSalida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Salida <i class="glyphicon glyphicon-th-list"></i></h4>
      </div>
      <div class="modal-body">
      <div class="table-responsive" id="bodySalida">
      <table class="table table-condensed" id="tableSalida">
      <thead>
        <tr>
          <th>Cod</th>        
          <th>Fecha</th>
          <th>Artículo</th>
          <th>Trabajador</th>          
          <th>Cantidad</th>
          <th>Gasolinera</th>          
        </tr>
      </thead>
      <tbody>
        <?php
		
		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM salida ORDER BY cod_sal DESC");
				  
			  while($row = mysqli_fetch_array($query1)) 
			  {	
				if ($row[5] != 0) 
				{
				  echo "<tr id='salida$row[0]'>";
				  echo "<td id='fechasalida$row[0]'>$row[0]</td>" ;				  
				  $date = date_create($row[1]);
				  echo "<td id='fechasalida$row[0]'>".date_format($date, 'Y-m-d H:i')."</td>" ;
				  
				  //Referencia (Artículo)
				  try
				  {
					  $query2 =  mysqli_query($conexionCentral, "SELECT nombre FROM articulo WHERE referencia='".$row[2]."'");
					  $row2 = mysqli_fetch_array($query2);
					  echo "<td id='referenciasalida$row[0]'>$row2[0]</td>" ;
				  }				  
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			          fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query2."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }

				  //Trabajador
				  try
				  {
					  $query3 =  mysqli_query($conexionCentral, "SELECT nombre FROM trabajador WHERE id_trab='".$row[3]."'");
					  $row3 = mysqli_fetch_array($query3);			  
					  echo "<td id='trabajadorsalida$row[0]'>$row3[0]</td>" ;
					  echo "<td id='cantidadsalida$row[0]'>$row[4]</td>" ;
				  }				  
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			          fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query3."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }
				  
				  //Gasolinera
				  try
				  {
					  $query4 =  mysqli_query($conexionCentral, "SELECT nombre FROM gasolinera WHERE id_gas='".$row[5]."'");
					  $row4 = mysqli_fetch_array($query4);			  			  
					  echo "<td id='gasolinerasalida$row[0]'>$row4[0]</td>" ;			  
					  echo "</tr>";
				  }				  
				  
				  catch(Exception $e)
				  {
					  //Generamos un log
					  $hoy = date("Y-m-d H:i");
					  $file = fopen("logs/logs.txt","a");
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			          fwrite($file, "Log generado el : " .$hoy."\r\n");  
					  fwrite($file, "Se ha realizado con éxito la consulta: " .$query4."\r\n");
					  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
					  fwrite($file, "**********************************************************************************************************************"."\r\n");						
					  fclose($file);			  
				  }				  
				}
			  }			  	
		  }
	  
	  	  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }
		
        ?>

      </tbody>
      <tfoot>
          <th>Cod</th>              
          <th>Fecha</th>
          <th>Artículo</th>
          <th>Trabajador</th>          
          <th>Cantidad</th>
          <th>Gasolinera</th>                    
      </tfoot>
    </table>
    </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal GASOLINERA-->
<div class="modal fade" id="seeGasolinera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Gasolineras <i class="glyphicon glyphicon-map-marker"></i></h4>
      </div>
      <div class="modal-body" id="bodyGasolineras">
      <table class="table table-condensed" id="tableGasolinera">
      <thead>
        <tr>
          <th width="20%">Identificador</th>
          <th width="50%">Nombre</th>
        </tr>
      </thead>
      <tbody>
        <?php

		  try
		  {		  
			  $query1 =  mysqli_query($conexionCentral, "SELECT * FROM gasolinera WHERE activo=1");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {
				if ($row[2] != 0) 
				{
				  echo "<tr id='gasolinera$row[0]'>";
				  echo "<td scope='row' id='idgasolinera$row[0]'>$row[0]</td>" ;
				  echo "<td id='nomgasolinera$row[0]'>$row[1]</td>" ;
				  echo "</tr>";
				}
			  }		  
		  }
	  
	  	  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }
		  
        ?>
      </tbody>
      <tfoot>
          <th width="20%">Identificador</th>
          <th width="50%">Nombre</th>
      </tfoot>
    </table>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Modal PEDIDO -->
<div class="modal fade" id="seePedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Hacer Pedido <i class="glyphicon glyphicon-th-list"></i></h4>
      </div>
      <div class="modal-body">
      <div class="table-responsive" id="bodyPedido">
      <table class="table table-condensed" id="tablePedido">
      <thead>
        <tr>
          <th>Referencia</th>
          <th>Articulo</th>
          <th>Categoria</th>
          <th>Subcategoria</th>
          <th>Stock</th>
          <th>Stock minimo</th>
          <th>Proveedor</th>
        </tr>
      </thead>
      <tbody>
        <?php
		  $contador=0;
		  
		  try
		  {
			  $query1 =  mysqli_query($conexionCentral, "SELECT articulo.referencia, articulo.nombre, categoria.nombre, subcategoria.nombre, articulo.stock, articulo.stock_minimo, proveedor.nombre, articulo.observacion FROM `articulo` INNER JOIN subcategoria ON articulo.id_sub = subcategoria.id_sub INNER JOIN categoria ON subcategoria.id_cat = categoria.id_cat INNER JOIN proveedor ON articulo.id_prov = proveedor.id_prov WHERE articulo.stock<articulo.stock_minimo");
			  
			  while($row = mysqli_fetch_array($query1)) 
			  {
				$contador++;
				
				if ($row[4]<=$row[5])
				{
				  echo "<tr class='danger' id='articulo$contador'> ";
				} 
				
				else 
				{
				  echo "<tr id='articulo$contador'> ";
				}
				
				echo "<td id='refarticulo".$contador."'>$row[0]</td>" ;
				echo "<td id='nombrearticulo".$contador."'>$row[1]</td>" ;
				echo "<td id='catarticulo".$contador."'>$row[2]</td>" ;
				echo "<td id='subarticulo".$contador."'>$row[3]</td>" ;
				echo "<td id='stockarticulo".$contador."'>$row[4]</td>" ;
				echo "<td id='stockminarticulo".$contador."'>$row[5]</td>" ;
				echo "<td id='provarticulo".$contador."'>$row[6]</td>" ;
			  }			  
		  }
	  
	  	  catch(Exception $e)
		  {
			  //Generamos un log
			  $hoy = date("Y-m-d H:i");
			  $file = fopen("logs/logs.txt","a");
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						  			  fwrite($file, "Log generado el : " .$hoy."\r\n");  
			  fwrite($file, "Se ha realizado con éxito la consulta: " .$query1."\r\n");
			  fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
			  fwrite($file, "**********************************************************************************************************************"."\r\n");						
			  fclose($file);			  
		  }

        ?>

      </tbody>
      <tfoot>
          <th>Referencia</th>
          <th>Articulo</th>
          <th>Categoria</th>
          <th>Subcategoria</th>
          <th>Stock</th>
          <th>Stock minimo</th>
          <th>Proveedor</th>
      </tfoot>
    </table>
    </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- ***********************************************MODAL ALERT************************************************************************************** -->

<!-- Insertar ENTRADA-->
<div class="modal fade" id="insertEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmar Operación <i class="glyphicon glyphicon-tag"></i></h4>
      </div>
      
    <div class="modal-body">

        <div class="alert alert-info">
          <p>¿ESTÁ SEGURO DE QUE DESEA AÑADIR UNA NUEVA ENTRADA?</p>
          <br>
          <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
          <button type="button" class="btn btn-primary" type="submit" onclick="addEntrada()">SI</button>        
        </div>    
      
      </div>
      
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

<!-- Insertar SALIDA-->
<div class="modal fade" id="insertSalida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmar Operación <i class="glyphicon glyphicon-tag"></i></h4>
      </div>
      
    <div class="modal-body">

        <div class="alert alert-info">
          <p>¿ESTÁ SEGURO DE QUE DESEA AÑADIR UNA NUEVA SALIDA?</p>
          <br>
          <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
          <button type="button" class="btn btn-primary" type="submit" onclick="addSalida()">SI</button>        
        </div>    
      
      </div>
      
    </div><!-- /modal-contebt -->
  </div><!-- /modal-dialog -->
</div><!-- /modal -->

  </body>
</html>
