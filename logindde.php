<?php 
 include 'conec.php'; 


  if (isset($entrar)) {
           
    $verifica = $conn->query("
    	SELECT * 
    	FROM usuarios 
    	WHERE login = '$login' AND senha = '$senha'  AND grupo in (1,4,5) 
		
		")
    	
    	 or die
    	 ("erro ao selecionar");
    	
     	 if (mysqli_num_rows($verifica)<=0)
     	 {
	        echo"<script language='javascript' type='text/javascript'>
	        alert('Login e/ou senha incorretos \\n ou \\n Você não tem permissão de acesso.' );window.location
	        .href='login.html';</script>";
	        die();
	      }else{
	        setcookie("login",$login,time()+12000);
	        header("Location:dde.php");
      }
  }
?>