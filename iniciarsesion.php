<?php

include 'conexion.php';

$user = $_POST['form-username']; 
$pass = $_POST['form-password'];
$valido=false;

if( (empty($user)) || (empty($pass)) )
{
	header('Location:login.php');
}

else
{
	//Guardamos en una lista todos los user de la tabla trabajador
	try
	{
		$sql = "SELECT usuario,password,rol FROM trabajador";
		$result = mysqli_query($conexionCentral,$sql);

		//Verificamos el usuario introducido
		while($row = mysqli_fetch_array($result)) 
		{			
			if(($pass==$row[1])&&($user==$row[0]))
			{
				$valido=true;
				if($row[2]=='Administrador')
				{			
					//Compruebo si tenía la sesión abierta, si está abierta se machaca por una nueva y si no se abre una nueva
					try
					{
						$sql4 = "SELECT sesion FROM trabajador WHERE usuario='".$user."' AND password='".$pass."'";
						$result4 = mysqli_query($conexionCentral,$sql4);
						$row4 = mysqli_fetch_array($result4);
						
						if( ($row4[0]==0) || ($row4[0]==1) )
						{
							// Start the session
							session_start();
							
							//Obtengo los datos del trabajador
							try
							{
								$sql5 = "SELECT id_trab,nombre,apellidos,usuario,password FROM trabajador WHERE usuario='".$user."' AND password='".$pass."'";
								$result5 = mysqli_query($conexionCentral,$sql5);
								$row5 = mysqli_fetch_array($result5);
								
								// Set session variables
								$_SESSION["autentificado"]= "SI";					
								$_SESSION["id_trab"] = $row5[0];
								$_SESSION["nombre_trab"] = $row5[1];
								$_SESSION["apellidos_trab"] = $row5[2];
								$_SESSION["user_trab"] = $row5[3];
								$_SESSION["pass_trab"] = $row5[4];			
								
								//Tiempo de último acceso
								$_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s"); 
								
								//Indico que el usuario a iniciado sesión cambiando el campo sesión a 1
								try
								{
									$sql6 = "UPDATE trabajador SET sesion=1 WHERE usuario='".$user."' AND password='".$pass."'";
									$result6 = mysqli_query($conexionCentral,$sql6);
								}
								
								catch(Exception $e)
								{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql6."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
								}						
						
								header('Location:index.php');								
							}
							
							catch(Exception $e)
							{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql5."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
							}						
						}						
					}
					
					catch(Exception $e)
					{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql4."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
					}						
				}
				
				elseif($row[2]=='Trabajador')
				{				
					//Compruebo si tenía la sesión abierta, si está abierta se machaca por una nueva y si no se abre una nueva
					try
					{
						$sql1 = "SELECT sesion FROM trabajador WHERE usuario='".$user."' AND password='".$pass."'";
						$result1 = mysqli_query($conexionCentral,$sql1);
						$row1 = mysqli_fetch_array($result);
						
						if( ($row1[0]==0) || ($row4[0]==1) )
						{
							// Start the session
							session_start();
							
							//Obtengo los datos del trabajador
							try
							{
								$sql2 = "SELECT id_trab,nombre,apellidos,usuario,password FROM trabajador WHERE usuario='".$user."' AND password='".$pass."'";
								$result2 = mysqli_query($conexionCentral,$sql2);
								$row2 = mysqli_fetch_array($result2);
								
								// Set session variables
								$_SESSION["autentificado"]= "SI";					
								$_SESSION["id_trabAlmacen"] = $row2[0];
								$_SESSION["nombre_trab"] = $row2[1];
								$_SESSION["apellidos_trab"] = $row2[2];
								$_SESSION["user_trab"] = $row2[3];
								$_SESSION["pass_trab"] = $row2[4];			
								
								//Tiempo de último acceso
								$_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s"); 
			
								//Indico que el usuario a iniciado sesión cambiando el campo sesión a 1
								try
								{
									$sql3 = "UPDATE trabajador SET sesion=1 WHERE usuario='".$user."' AND password='".$pass."'";
									$result3 = mysqli_query($conexionCentral,$sql3);
								}
	
								catch(Exception $e)
								{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql3."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
								}																		
					
								header('Location:almacen.php');							
							}
									
							catch(Exception $e)
							{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql2."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
							}																		
						}						
					}

					catch(Exception $e)
					{
						//Generamos un log
						$hoy = date("Y-m-d H:i");
						$file = fopen("logs/logs.txt","a");
						fwrite($file, "**********************************************************************************************************************"."\r\n");						  	                    fwrite($file, "Log generado el : " .$hoy."\r\n");  
						fwrite($file, "Se ha realizado con éxito la consulta: " .$sql1."\r\n");
						fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
						fwrite($file, "**********************************************************************************************************************"."\r\n");						
						fclose($file);
					}																							
				}
			}
		}
			
		if($valido==false)
		{
			header('Location:errorIniciarSesion.php');							
		}		
	}
	
	catch(Exception $e)
	{
		//Generamos un log
		$hoy = date("Y-m-d H:i");
		$file = fopen("logs/logs.txt","a");
		fwrite($file, "**********************************************************************************************************************"."\r\n");						  	    fwrite($file, "Log generado el : " .$hoy."\r\n");  
		fwrite($file, "Se ha realizado con éxito la consulta: " .$sql."\r\n");
		fwrite($file, "Mensaje de error: " .$e->getMessage()."\r\n");			  
		fwrite($file, "**********************************************************************************************************************"."\r\n");						
		fclose($file);
	}	
}
mysqli_close($conexionCentral);
?>