<? 
include "conec.php";
$jsh = $_POST['lj40'];

if($jsh) 
{
	echo "LOJA CERTA ! ";
	$link = mysqli_connect("110.100.50.5", "sac", "2713", "sac");    

	$sql = "select fantasia from estab";
	
	$tbbs = mysqli_query($link,$sql,);
	@$row_usuario = mysqli_fetch_assoc($tbbs);
	
	 echo"<input class='form-control form-control-sm'  readonly='readonly'   name='FANTASIA'  value='". @$row_usuario['fantasia']."'/>";
	
}else {
	echo "LOJA ERRADA ! ";
}



?>