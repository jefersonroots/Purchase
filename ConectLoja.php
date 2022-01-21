<?	
	$host = '110.100.72.2';$user = 'sa';$senha = '';$base = 'PDV';
	$con = array("Database" => "$base" , "UID" => "$user", "PWD" => "$senha");
	$conn = sqlsrv_connect($host, $con);  
		
	
		
if ($conn==false){
	echo "<b><font color='#FF0000'> Sem conexão com o BD.</font></b><br><br>";
} else { 
	echo "<b><font color='#FF0000'>  conexão com o BD.</font></b><br><br>";
}
?>