<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
</head>
<? //CONEXÃO PARA PUXAR LOJAS DO BANCO//
	$host1 = '110.100.50.89,1440';
    $user = 'linx';
    $senha = 'Poly@123';
    $base = 'LINX_Prod';
    $con = array("Database" => "$base" , "UID" => "$user", "PWD" => "$senha");
	$conn1 = sqlsrv_connect($host1, $con);  
@$querySelect = sqlsrv_query($conn1,"
SELECT COD_PRODUTO_SEGMENTO,RTRIM(PS.DESC_PRODUTO_SEGMENTO) as 'nome_marca' FROM PRODUTOS_SEGMENTO ps  order BY ps.DESC_PRODUTO_SEGMENTO
");
date('Ymd');




?>

