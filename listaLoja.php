

<? //CONEXÃO PARA PUXAR LOJAS DO BANCO//
	$host1 = '110.100.50.89,1440';
    $user = 'linx';
    $senha = 'Poly@123';
    $base = 'LINX_Prod';
    $con = array("Database" => "$base" , "UID" => "$user", "PWD" => "$senha");
	$conn1 = sqlsrv_connect($host1, $con);  
@$querySelect = sqlsrv_query($conn1,"
	select COD_FILIAL,filial as 'FILIAL'
	from filiais 
	where REDE_LOJAS in ('03','02','04','05') 
	and COD_FILIAL != '000301' 
	and COD_FILIAL != '000503'
	and TIPO_FILIAL != 'defeitos'  ");
date('Ymd');




?>

