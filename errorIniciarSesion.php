<?php 
include 'conexion.php';

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
    
    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/form-elements.css">
    <link rel="stylesheet" href="css/style.css">

	<!-- Javascript -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.backstretch.min.js"></script>
    <script src="js/scripts.js"></script>
        
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
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

       <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg" id="contenedor">
                <div class="container">
                    
					<div class="row">
                        
						<div class="col-sm-8 col-sm-offset-2 text">
                        
							<h1 class="mensaje"><strong>USUARIO O CONTRASEÑA ERRÓNEA</strong></h1>

							<form role="form" action="login.php" method="post" class="login-form">
									<button type="submit" name="registro" class="btn">Volver a pantalla de Login</button>
			                </form>									
                        
						</div>
                    
					</div>
                
				</div>
									
            </div>
        
		</div>
  </body>
</html>