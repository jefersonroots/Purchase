

<meta name="language" content="pt-br" utf8_decode/>
<center> 
<?php
    @$DATAINICIO = date('Ymd', strtotime($_POST['DATAINICIO']));
    @$DATAFIM = date('Ymd', strtotime($_POST['DATAFIM']));
    @$tipofilial1 = $_POST['tipofilial1'];
    @$tipofilial2 = $_POST['tipofilial2'];
    @$tipofilial3 = $_POST['tipofilial3'];
    @$tipofilial4 = $_POST['tipofilial4'];
    @$tipofilial5 = $_POST['tipofilial5'];
    @$grupoAgrup = 100;
	echo "<h1><center><br><br>Data de Consulta  - " .date('d/m/Y', strtotime($_POST['DATAINICIO']))." a ".date('d/m/Y', strtotime($_POST['DATAFIM']))."</h1>";
include('listaloja.php');
    $data = date('Y-m-d');
    $query = "
    --PROGRAMA PAINEL DE PRODUTOS
    --OBJETIVO APRESENTAR AS METAS E OS VALORES VENDIDOS DOS AGRUPAMENTOS DE PRODUTOS
    
    --DECLARA AS VARIAVEIS INICIAIS
    declare @agrup int, @dataini varchar(8), @datafim varchar(8),
            @tipofilial1 varchar(20), @tipofilial2 varchar(20), @tipofilial3 varchar(20), @tipofilial4 varchar(20), @tipofilial5 varchar(20)
    
    --VARIAVEIS QUE O USUÁRIO IRA ESCOLHER
    set @agrup = '".$grupoAgrup."'	--GRUPO REFERENTE AO AGRUPAMENTO A SER UTILIZADO
    set @dataini = '" . $DATAINICIO . "'		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
    set @datafim = '" . $DATAFIM . "'		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL
    
    --VARIAVEIS DE AGRUPAMENTO PARA O USUÁRIO ESCOLHER
    set @tipofilial1 = '".$tipofilial1."'	--ADMINISTRACAO
    set @tipofilial2 = '".$tipofilial2."'		--POLYELLE
    set @tipofilial3 = '".$tipofilial3."'		--TEENS&KIDS
    set @tipofilial4 = '".$tipofilial4."'	--POLYELLE-MA
    set @tipofilial5 = '".$tipofilial5."'		--MR.FOOT
    
    SET NOCOUNT ON   EXEC dbo.LX_POLYELLE_META_CATEGORIA_AGRUPADO @agrup,@dataini,@datafim,@tipofilial1,@tipofilial2,@tipofilial3,@tipofilial4,@tipofilial5
    
    
    ";
	// Definimos o nome do arquivo que será exportado
	$arquivo = "META-POR-CATEGORIA-$DATAINICIO-$DATAFIM.xls";
		// Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo	

     
	print "
    <TABLE BORDER=1>
   
        <TR  style='background-color:   #EBEBEB ;'>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>GRUPO</h5></div></th>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>AGRUPAMENTO</h5></div></th>
             <th scope='col' class='col-md-3'><div align='center'><h5><b/>DESC. AGRUPAMENTO</h5></div></th>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>META VALOR</h5></div></th>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>QTDE LIQUIDO</h5></div></th>
             <th scope='col' class='col-md-2'><div align='center'><h5><b/>VALOR LIQUIDO</h5></div></th>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>DIFERENCA</h5></div></th>
             <th scope='col' class='col-md-1'><div align='center'><h5><b/>%</h5></div></th>

        </TR>

   ";
 
    $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
    // sqlsrv_execute(@$stmtAC2);
    while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
        echo "<TR >
        <TD align='center' class='col-md-1'><font size=2>" . $item['GRUPO'] . "</font></TD> 
        <TD align='center' class='col-md-1'><font size=2>" . $item['AGRUPAMENTO'] . "</font></TD>
        <TD align='center' class='col-md-1'><font size=2>" . $item['DESC_AGRUPAMENTO'] . "</font></TD>
        <TD align='center' class='col-md-2'><font  size=2>" . $item['META_VALOR'] . "</font></TD>
        <TD align='center'   class='col-md-1'><font size=2>" . $item['QTDE_LQUIDO'] . "</font></TD>
        <TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD>
        <TD align='center' class='col-md-2'><font size=2>" . $item['DIFERENCA'] . "</font></TD>
        <TD align='center' class='col-md-1'><font size=2>" . $item['%'] . "%</font></TD>
        </TR> 
		";
	
		
	}   echo"</table>";
    echo "";
    echo "
   
    <tr  style='background-color: #fa2121;'><DIV  style='background-color: #fa2121;'><h1 style='background-color: #fa2121;'><center style='background-color: #fa2121;'><br><br>TOTAIS</center></h1></DIV><tr>
            <TABLE border='1' ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
            <thead>
                <TR  style='background-color:   #EBEBEB ;'>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>GRUPO GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>META VALOR GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>QTDE LIQUIDO GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>VALOR LIQUIDO GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>DIFERENCIAL GERAL</h5></div></th>
                     <th scope='col' class='col-md-3'><div align='center'><h5><b/>% GERAL</h5></div></th>

                </TR>
            </thead>";
            @$next_result = sqlsrv_next_result(@$stmtAC2);

    while (@$item = sqlsrv_fetch_array(@$stmtAC2, SQLSRV_FETCH_ASSOC)) {

        echo "<TR >
<TD align='center' class='col-md-1'><font size=2>" . $item['GRUPO_GERAL'] . "</font></TD> 
<TD align='center' class='col-md-2'><font size=2>" . $item['META_VALOR_GERAL'] . "</font></TD>
<TD align='center' '><font size=2>" . $item['QTDE_LQUIDO_GERAL'] . "</font></TD>
<TD align='center' class='col-md-3'><font  size=2>" . $item['VALOR_LIQUIDO_GERAL'] . "</font></TD>
<TD align='center' class='col-md-1'><font size=2>" . $item['DIFERENCA_GERAL'] . "</font></TD>
<TD align='center' class='col-md-1'><font size=2>" . $item['%_GERAL'] . "%</font></TD>



";
    }
    "</TR></nav>";
    echo "</table> </div></center>";

	sqlsrv_close($conn1);
	


?>
