

<?php

@$LINHA = $_POST['LINHA'];

@$MARCA = $_POST['MARCA'];
@$LOJA = $_POST['LOJA'];

@$PRODUTO = $_POST['PRODUTO'];
@$FABRICANTE = $_POST['FABRICANTE'];

@$tipofilial1 = $_POST['tipofilial1'];
@$tipofilial2 = $_POST['tipofilial2'];
@$tipofilial3 = $_POST['tipofilial3'];
@$tipofilial4 = $_POST['tipofilial4'];
@$tipofilial5 = $_POST['tipofilial5'];

//

if($tipofilial1 == null && $tipofilial2 == null && $tipofilial3== null && $tipofilial4 == null && $tipofilial5== null   )
{
     echo  "<script>alert('É PRECISO SELECIONAR AO MENOS 1 AGRUPAMENTO!');</script>";
     echo "<script>javascript:window.close();</script>";
}
else
{
   



///






include "listaloja.php";
if ($conn1 == false) {
    echo "<b><font color='#FF0000'> Sem conexao com o BD.</font></b><br><br>";
} else {

    $query = "
   SET NOCOUNT ON 
   EXEC dbo.LX_MARKDOWN '" . $tipofilial1 . "',
   '" . $tipofilial2 . "',
   '" . $tipofilial3 . "',
   '" . $tipofilial4 . "',
   '" . $tipofilial5 . "'
   , '" . $LINHA . "',
   '" . $MARCA . "',
   '" . $FABRICANTE . "',
   '" . $PRODUTO . "',
   '" . $LOJA . "'";

    $hoje = date('d-m-Y');
    $arquivo = "MARKDOWN_$hoje.xls";
    //Configurações header para forçar o download
    header("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
    header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
    header("Content-Description: PHP Generated Data");
    //	Envia o conteúdo do arquivo	
    $exec = sqlsrv_query($conn1, $query);
    if ($exec == 0) {
        echo  "<script>alert('A CONSULTA NÃO RETORNOU DADOS.');</script>";
     echo "<script>javascript:window.close();</script>";
    } else {
        print "<TABLE BORDER=1><TR>
            <TH>LINHA</TH>
            <TH>MARCA</TH>
            <TH>FABRICANTE</TH>
            <TH>PRODUTO</TH>  
            <TH>DESCRICAO_PRODUTO</TH>
            <TH>ESTOQUE</TH>
            <TH>RECEBIMENTO</TH>
            <TH>COMPRA</TH>
            <TH>PRECO</TH>

            </TR>";
        $exec = sqlsrv_query($conn1, $query);

        // ECHO $query;

        while ($exibe = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)) {
            // ECHO $query;
            $html = "<TR>
                <TD align='center'><font size=1>" . $exibe['LINHA'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['MARCA'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['FABRICANTE'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['PRODUTO'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['DESCRICAO_PRODUTO'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['ESTOQUE'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['RECEBIMENTO'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['COMPRA'] . "</font></TD>
                <TD align='center'><font size=1>" . $exibe['PRECO'] . "</font></TD>               
                ";
            printf($html);
        }
    }
}}
