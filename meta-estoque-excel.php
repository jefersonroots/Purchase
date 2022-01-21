

<meta name="language" content="pt-br" utf8_decode/>
<center> 
<?php

include('listaloja.php');
@$tipofilial1 = $_POST['tipofilial1'];
@$tipofilial2 = $_POST['tipofilial2'];
@$tipofilial3 = $_POST['tipofilial3'];
@$tipofilial4 = $_POST['tipofilial4'];
@$tipofilial5 = $_POST['tipofilial5'];
@$periodo = $_POST['periodo'];
@$grupo =100;


    $query = "
    --PROGRAMA PAINEL DE PRODUTOS
    --OBJETIVO APRESENTAR AS METAS E OS VALORES VENDIDOS DOS AGRUPAMENTOS DE PRODUTOS
    
    --DECLARA AS VARIAVEIS INICIAIS
    declare @agrup int, @dataini varchar(8), @datafim varchar(8),
            @tipofilial1 varchar(20), @tipofilial2 varchar(20), @tipofilial3 varchar(20), @tipofilial4 varchar(20), @tipofilial5 varchar(20),
            @periodos int
    
    --VARIAVEIS QUE O USUÁRIO IRA ESCOLHER
    set @agrup = '".$grupo."'	--GRUPO REFERENTE AO AGRUPAMENTO A SER UTILIZADO
    set @periodos = '".$periodo."'	--USUARIO INFORMA O NÚMERO DE PERÍODOS DESEJADO DE VENDA
    set @dataini = 	replace(dateadd(day,(-30 * @periodos),cast(GETDATE() as date)),'-','')	--DATA INICIAL A SER PESQUISADA
    set @datafim = replace(cast(GETDATE() as date),'-','')						--DATA FINAL A SER PESQUISADA
    
    
    --VARIAVEIS DE AGRUPAMENTO PARA O USUÁRIO ESCOLHER
    set @tipofilial1 = '".$tipofilial1."'	--ADMINISTRACAO
    set @tipofilial2 = '".$tipofilial2."'	--POLYELLE
    set @tipofilial3 = '".$tipofilial3."'	--TEENS&KIDS
    set @tipofilial4 = '".$tipofilial4."'	--POLYELLE-MA
    set @tipofilial5 = '".$tipofilial5."'		--MR.FOOT
    
       
SET NOCOUNT ON    EXEC dbo.LX_POLYELLE_META_CATEGORIA_AGRUPADO_ESTOQUE @agrup,@dataini,@datafim,@tipofilial1,@tipofilial2,@tipofilial3,@tipofilial4,@tipofilial5,@periodos



";
    if($periodo > 1){
        $mes = "MESES";
    }else{
        $mes = "MÊS";
    }

	// Definimos o nome do arquivo que será exportado
	$arquivo = "META-POR-ESTOQUE.xls";
		// // Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo	

        if($periodo > 1){
            $mes = "MESES";
        }else{
            $mes = "MES";
        }
 
	print "
    <tr  style='background-color: #fa2121;'><DIV  style='background-color: #fa2121;'><h1 style='background-color: #fa2121;'><center style='background-color: #fa2121;'><br><br>CONSULTA PARA $periodo $mes </center></h1></DIV><tr>
    <TABLE border='1' ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
    <thead>
        <TR  style='background-color:   #EBEBEB ;'>
        <th scope='col'  ><div align='center'><h5><b/>GRUPO</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>AGRUPAMENTO</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>DESC. AGRUPAMENTO</h5></div></th>
        <th scope='col' class='col-sm-1'  ><div align='center'><h5><b/>META VALOR</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>QTDE VEND.</h5></div></th>
        <th scope='col' class='col-sm-1' ><div align='center'><h5><b/>VALOR VENDIDO</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>DIFERENCA </h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>%_DIF</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>QTDE ESTOQUE</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>VL ESTOQUE</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>TEMPO ESTOQUE</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>%ESTOQUE</h5></div></th>
        <th scope='col'  ><div align='center'><h5><b/>%VENDA</h5></div></th>
        </TR>

   ";
 
    $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
    // sqlsrv_execute(@$stmtAC2);
    while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
        echo "<TR >
        <TD align='center' ><font size=2>" . $item['GRUPO'] . "</font></TD> 
        <TD align='center' ><font size=2>" . $item['AGRUPAMENTO'] . "</font></TD>
        <TD align='center' ><font size=2>" . $item['DESC_AGRUPAMENTO'] . "</font></TD>
        <TD align='center'  ><font  size=2>" . $item['META_VALOR'] . "</font></TD>
        <TD align='center'   ><font size=2>" . $item['QTDE_VENDIDA'] . "</font></TD>
        <TD align='center' ><font size=2>" . $item['VALOR_VENDIDO'] . "</font></TD>
        <TD align='center'  ><font size=2>" . $item['DIFERENCA'] . "</font></TD>
        <TD align='center' ><font size=2>" .  floatval($item['%_DIF']) . "%</font></TD>
        <TD align='center' ><font size=2>" . $item['QTDE_ESTOQUE']. "</font></TD>
        <TD align='center' ><font size=2>" . $item['VL_ESTOQUE']. "</font></TD>
        <TD align='center' ><font size=2>" . $item['TEMPO_ESTOQUE']. "</font></TD>
        <TD align='center' ><font size=2>" . $item['%ESTOQUE']. "%</font></TD>
        <TD align='center' ><font size=2>" . $item['%VENDA']. "%</font></TD>
      
      
        </TR> 
		";
	
		
	}   echo"</table>";
    echo "";
    echo "
   
    <tr  style='background-color: #fa2121;'><DIV  style='background-color: #fa2121;'><h1 style='background-color: #fa2121;'><center style='background-color: #fa2121;'><br><br>TOTAIS</center></h1></DIV><tr>
            <TABLE border='1' ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
            <thead>
                <TR  style='background-color:   #EBEBEB ;'>
                <th scope='col'  ><div align='center'><h5><b/>GRUPO GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>META VALOR GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>QTDE VENDA GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>VALOR VENDA GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>DIFERENCIAL GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>% GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>QTDE_ESTOQUE_GERAL</h5></div></th>
                <th scope='col'  ><div align='center'><h5><b/>VALOR_ESTOQUE_GERAL</h5></div></th>


                </TR>
            </thead>";
            @$next_result = sqlsrv_next_result(@$stmtAC2);

    while (@$item = sqlsrv_fetch_array(@$stmtAC2, SQLSRV_FETCH_ASSOC)) {

        echo "<TR >
        <TD align='center' ><font size=2>" . $item['GRUPO_GERAL'] . "</font></TD> 
        <TD align='center'  ><font size=2>" . $item['META_VALOR_GERAL'] . "</font></TD>
        <TD align='center' '><font size=2>" . $item['QTDE_VENDA_GERAL'] . "</font></TD>
        <TD align='center'  ><font  size=2>" . $item['VALOR_VENDA_GERAL'] . "</font></TD>
        <TD align='center'   ><font size=2>" . $item['DIFERENCA_GERAL'] . "</font></TD>
        <TD align='center' ><font size=2>" . floatval($item['%_GERAL']) . "%</font></TD>
        <TD align='center' ><font size=2>" . $item['QTDE_ESTOQUE_GERAL']. "</font></TD>
        <TD align='center' ><font size=2>" . $item['VALOR_ESTOQUE_GERAL']. "</font></TD>
        
        
        


";
    }
    "</TR></nav>";
    echo "</table> </div></center>";

	sqlsrv_close($conn1);
	

?>
