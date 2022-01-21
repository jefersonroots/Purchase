<? //CONEXÃO PARA PUXAR LOJAS DO BANCO//
	$host1 = '110.100.50.89,1440';
    $user = 'linx';
    $senha = 'Poly@123';
    $base = 'LINX_Prod';
    $con = array("Database" => "$base" , "UID" => "$user", "PWD" => "$senha");
	$conn1 = sqlsrv_connect($host1, $con);  
@$querySelect = sqlsrv_query($conn1,"
select RTRIM(p.FABRICANTE)as 'FABRICANTE' from PRODUTOS p where p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA is not null group by p.FABRICANTE order by p.FABRICANTE
");
date('Ymd');




?>



