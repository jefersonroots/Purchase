<?php

include('visual.html');

$host1 = '110.100.50.89,1440';
$user = 'linx';
$senha = 'Poly@123';
$base = 'LINX_Prod';
$con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
$conn1 = sqlsrv_connect($host1, $con);

$aspas = '"';
@$linha = $_GET['RECEBE_LINHA'];
@$tipo = $_GET['TIPO'];

if ($linha == "TESTE") {
    $linha = "";
} else {
    $linha = $linha;
}

if ($linha || $tipo) {


    $query =  "declare 	@LINHA VARCHAR(25), @TIPO VARCHAR(25)

set @LINHA = '" . $linha . "'	
set @TIPO = '" . $tipo . "'
IF @LINHA = '' AND @TIPO = ''
	SELECT ''  AS 'SELECT_CATEGORIA' UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 group by pc.CATEGORIA_PRODUTO
IF @LINHA <> '' AND @TIPO = ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA group by pc.CATEGORIA_PRODUTO
IF @LINHA <> '' AND @TIPO <> ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA and p.TIPO_PRODUTO = @TIPO group by pc.CATEGORIA_PRODUTO
IF @LINHA = '' AND @TIPO <> ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.TIPO_PRODUTO = @TIPO group by pc.CATEGORIA_PRODUTO
	
	";
    // echo $query;

    $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

    echo "
<div   class='col-md-6'>
<label>
    <h5><b>CATEGORIA:</b></h5>
</label><br>

<select name='CATEGORIA'   DISABLED id='CATEGORIA'  class='form-control'>
";
    while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="CATEGORIA" value="<? echo $reg['SELECT_CATEGORIA'] ?>">
            <? echo $reg['SELECT_CATEGORIA'] ?>
        </option> <?
                }
                echo "</select></div>";
            } else {


                $query =  "declare 	@LINHA VARCHAR(25), @TIPO VARCHAR(25)

set @LINHA = '" . $linha . "'	
set @TIPO = '" . $tipo . "'
IF @LINHA = '' AND @TIPO = ''
	SELECT ''  AS 'SELECT_CATEGORIA' UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 group by pc.CATEGORIA_PRODUTO
IF @LINHA <> '' AND @TIPO = ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA group by pc.CATEGORIA_PRODUTO
IF @LINHA <> '' AND @TIPO <> ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA and p.TIPO_PRODUTO = @TIPO group by pc.CATEGORIA_PRODUTO
IF @LINHA = '' AND @TIPO <> ''
	SELECT ''  AS 'SELECT_CATEGORIA'UNION ALL SELECT RTRIM(LTRIM(pc.CATEGORIA_PRODUTO)) FROM PRODUTOS p INNER JOIN PRODUTOS_CATEGORIA pc on pc.COD_CATEGORIA = p.COD_CATEGORIA WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.TIPO_PRODUTO = @TIPO group by pc.CATEGORIA_PRODUTO
	
	";
                // echo $query;

                $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

                echo "
<div class='col-md-6'>
<label>
    <h5><b>CATEGORIA:</b></h5>
</label><br>

<select name='CATEGORIA' DISABLED   id='CATEGORIA'  class='form-control'>
";

                while ($reg = sqlsrv_fetch_array($stmtAC2)) {
                    ?><option type="text" name="CATEGORIA" value="<? echo $reg['SELECT_CATEGORIA'] ?>">
            <? echo $reg['SELECT_CATEGORIA'] ?>
        </option> <?
                }
                echo "</select></div>";
            }

                    ?>

<!-- <script> document.getElementById("TIPO_PRODUTO").style.display = 'none';</script> -->


<script>
if ($.trim($('#CATEGORIA_ID[document_type]').val()) != '') {
                    $("#SUBCATEGORIA_DIV").hide();
                    $("#SUBCATEGORIA_ID").show();                 
                }
                $(document).ready(function() {

                    $("#GRUPO_PRODUTO_ID_DIV").show();

                    $('#CATEGORIA_ID').change(function() {

                        $("#SUBCATEGORIA_DIV").hide();
                        $("#SUBCATEGORIA_ID").show();

                        //$('#SUBCATEGORIA_ID').load('subcategoria_post.php?CATEGORIA_ID=' + $('#CATEGORIA_ID').val());
                        $('#SUBCATEGORIA_ID').load('subcategoria_post.php?CATEGORIA=' + $('#CATEGORIA').val());
        

                    
                    
                    });
                });


                // fim subgrupo script js 


                $("#select").change(function() {
                    $(".opicionais").each(function() {
                        $(this).show();
                    });
                    var valor = $(this).val();
                    $("#" + valor).show();
                });

                //
</script> 