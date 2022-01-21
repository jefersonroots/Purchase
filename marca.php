<? //CONEXÃO PARA PUXAR LOJAS DO BANCO//
	$host1 = '110.100.50.89,1440';
    $user = 'linx';
    $senha = 'Poly@123';
    $base = 'LINX_Prod';
    $con = array("Database" => "$base" , "UID" => "$user", "PWD" => "$senha");
	$conn1 = sqlsrv_connect($host1, $con);  
@$querySelect = sqlsrv_query($conn1,"
SELECT RTRIM(ps.DESC_PRODUTO_SEGMENTO) as 'MARCA' FROM PRODUTOS_SEGMENTO ps group by ps.DESC_PRODUTO_SEGMENTO
");
date('Ymd');




?>

