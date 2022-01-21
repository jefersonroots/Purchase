<?php 

include('listaloja.php');

 @$produto = $_POST["produto"]; 
 @$grupo = $_POST["grupo"]; 
 @$idagrupamento = $_POST["id_agrupamento"]; 



$DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
$query = "

declare @produto char(12), @GRUPO char(25), @ID_AGRUPAMENTO CHAR(30)
set @GRUPO = '".$grupo."'	--POLYELLE, TEENS&KIDS, POLYELLE-MA, MR.FOOT
set @produto = '".$produto."'
set @ID_AGRUPAMENTO = '".$idagrupamento."'	--CODIGO DO AGRUPAMENTO


SET NOCOUNT ON   EXEC dbo.LX_POLYELLE_ESTOQUE_PRODUTO_GRADE_AGRUPADA_AGRUPAMENTO @produto,@GRUPO, @ID_AGRUPAMENTO


";



echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
<div    class='panel-heading '  ><h4>DETALHAMENTO DO PRODUTO</h4></div>
    <nav style=' width: 100%;overflow:auto;float: none;
  ' class='navbar navbar-light bg-light' align='center'>
        <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
        <thead>
            <TR  style='background-color:   #EBEBEB ;'>
                 <th scope='col' ><div align='center'><h5><b/>PRODUTO</h5></div></th>
                 <th scope='col' ><div align='center'><h5><b/>DESCRICAO </h5></div></th>
                 <th scope='col' ><div align='center'><h5><b/>COR</h5></div></th>
                 <th scope='col' ><div align='center'><h5><b/>ESTOQUE</h5></div></th>
                 <th scope='col' ><div align='center'><h5><b/>GRADE</h5></div></th>
           
                     </TR>
        </thead>";

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

  

echo "<TR> 
<TD align='center'><font size=2>" . $item['PRODUTO'] . "</font></TD>
<TD align='center' ><font size=2>" . $item['DESC_PROD_NF'] . "</font></TD>
<TD align='center' ><font size=2>" . $item['DESC_COR_PRODUTO'] . "</font></TD>
<TD align='center'><font size=2>" . $item['ESTOQUE'] . "</font></TD>
<TD align='center' ><font size=2>" . $item['GRADE'] . "</font></TD>
</TR>
";      
}
?>