<?php

// include "visual.html";
include "listaloja.php";

  @$data =  $_POST['data']; echo"<br>";
 //echo $datainiciopainel = date('Ymd', strtotime($data));
  $data2   =  date('Ymd',strtotime(str_replace('/','-',$data)));
  @$ticket = $_POST['ticket'];   
  @$loja = $_POST['loja'];

$query = "SET NOCOUNT ON EXEC LX_POLYELLE_DESCONTO_DETALHADO_VENDA '" . $loja . "','" . $ticket . "','" . $data2 . "' ";

echo "<div class='col-md-12'>";

echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
   <div    class='panel-heading '  ><h4>DADOS GERAIS";

echo "</h4></div>
       <nav style=' width: 100%;overflow:auto;float: none;
       display: block;' class='navbar navbar-light bg-light' align='center'>
           <TABLE ALIGN='center'  id='exemplo'  style='overflow: auto;'  class='table table-bordered table-hover'>
           <thead>
               <TR  style='background-color:   #EBEBEB ;'>
                    <th scope='col'  ><div align='center'><h5><b/>FILIAL</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>TICKET</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>DATA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VALOR BRUTO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VALOR TROCA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>%DESCONTO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VL DESCONTO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VALOR PAGO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>MOTIVO DESCONTO</h5></div></th>

              
               </TR>
           </thead>";

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

    echo "<TR >
                <TD align='center' ><font size=2>" .  utf8_encode($item['FILIAL']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['TICKET']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['DATA']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['VALOR_BRUTO']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['VALOR_TROCA']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['%_DESCONTO']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['VL_DESCONTO']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['VALOR_PAGO']) . "</font></TD> 
                <TD align='center' ><font size=2>" .  utf8_encode($item['MOTIVO_DESCONTO']) . "</font></TD> 

";
}
echo "</TR></table> </div>";

//->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> PARTE 2 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<-// 


echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
   <div    class='panel-heading '  ><h4> OPERADORES ";

echo "</h4></div>
       <nav style=' width: 100%;overflow:auto;float: none;
       display: block;' class='navbar navbar-light bg-light' align='center'>
           <TABLE ALIGN='center'  id='exemplo'  style='overflow: auto;'  class='table table-bordered table-hover'>
           <thead>
               <TR  style='background-color:   #EBEBEB ;'>
                    <th scope='col'  ><div align='center'><h5><b/>NOME VENDEDOR</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>NOME CAIXA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>CODIGO CLIENTE</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>NOME CLIENTE</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>TIPO</h5></div></th>
              
               </TR>
           </thead>";
@$next_result = sqlsrv_next_result(@$stmtAC2);
while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

    echo "<TR >
        <TD align='center' ><font size=2>" .  utf8_encode($item['NOME_VENDEDOR']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['NOME_CAIXA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['CODIGO_CLIENTE']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['NOME_CLIENTE']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['TIPO']) . "</font></TD> 
        ";
}
echo "</TR></table> </div>";
//->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> PARTE 3 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<-// 


echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
   <div    class='panel-heading '  ><h4>FORMA DE PAGAMENTO";

echo "</h4></div>
       <nav style=' width: 100%;overflow:auto;float: none;
       display: block;' class='navbar navbar-light bg-light' align='center'>
           <TABLE ALIGN='center'  id='exemplo'  style='overflow: auto;'  class='table table-bordered table-hover'>
           <thead>
               <TR  style='background-color:   #EBEBEB ;'>
                    <th scope='col'  ><div align='center'><h5><b/>PAGAMENTO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>ADMINISTRADORA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VALOR</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>PARCELAS</h5></div></th>
            

              
               </TR>
           </thead>";
@$next_result = sqlsrv_next_result(@$stmtAC2);
while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

    echo "<TR >
        <TD align='center' ><font size=2>" .  utf8_encode($item['PAGAMENTO']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['ADMINISTRADORA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['VALOR']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['PARCELAS']) . "</font></TD> 
 
        
        
        ";
}
echo "</TR></table> </div>";

//->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> PARTE 4 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<-// 


echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
   <div    class='panel-heading '  ><h4>PRODUTOS";

echo "</h4></div>
       <nav style=' width: 100%;overflow:auto;float: none;
       display: block;' class='navbar navbar-light bg-light' align='center'>
           <TABLE ALIGN='center'  id='exemplo'  style='overflow: auto;'  class='table table-bordered table-hover'>
           <thead>
               <TR  style='background-color:   #EBEBEB ;'>
                    <th scope='col'  ><div align='center'><h5><b/>CODIGO BARRA</h5></div></th>
                    <th scope='col'  ><div align='center' class='col-md-2'><h5><b/>DESCRICAO PRODUTO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>QTDE</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>PRECO TABELA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>DESC. TABELA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>VL DESC</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>PRECO FINAL</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>OPERACAO</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>DATA VENDA</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>FILIAL</h5></div></th>
                    <th scope='col'  ><div align='center'><h5><b/>TICKET</h5></div></th>

              
               </TR>
           </thead>";
@$next_result = sqlsrv_next_result(@$stmtAC2);
while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

    echo "<TR >
        <TD align='center' ><font size=2>" .  utf8_encode($item['CODIGO_BARRA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['DESCRICAO_PRODUTO']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['QTDE']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['PRECO_TABELA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['DESC_TABELA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['VL_DESC']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['PRECO_FINAL']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['OPERACAO']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['DATA_VENDA']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['FILIAL']) . "</font></TD> 
        <TD align='center' ><font size=2>" .  utf8_encode($item['TICKET']) . "</font></TD> 
        ";
        ?>
 
        
        
        <?

}
echo "</TR></table> </div>";

?>

