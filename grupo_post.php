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

if($linha == "TESTE"){
    $linha = "";
}else{
    $linha = $linha;
}

if($linha || $tipo ){


$query =  "
declare 	@LINHA VARCHAR(25), @TIPO VARCHAR(25)

set @LINHA = '".$linha."'	
set @TIPO = '".$tipo."'
IF @LINHA = '' AND @TIPO = '' 
	SELECT '' AS 'GRUPO_PRODUTO' UNION ALL  SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 group by p.GRUPO_PRODUTO
IF @LINHA <> '' AND @TIPO = ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA group by p.GRUPO_PRODUTO
IF @LINHA <> '' AND @TIPO <> ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA and p.TIPO_PRODUTO = @TIPO group by p.GRUPO_PRODUTO
IF @LINHA = '' AND @TIPO <> ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.TIPO_PRODUTO = @TIPO group by p.GRUPO_PRODUTO


";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
@$vowels = array(" ");
echo "
<div   class='col-md-6'>
<label>
    <h5><b>GRUPO:</b></h5>
</label><br>

<select  DISABLED name='GRUPO_PRODUTO_ID'  id='GRUPO_PRODUTO_ID'  class='form-control'>
";
while ($rege = sqlsrv_fetch_array($stmtAC2)) {
?><<option type="text" id="GRUPO_PRODUTO_ID" name="GRUPO_PRODUTO_ID"
value='<? echo $tratado = str_replace($vowels,'-',$rege['GRUPO_PRODUTO']) ?>'>
  <? echo $rege['GRUPO_PRODUTO'] ?>
</option>
    
    
    
    <?
            }
            echo "</select></div>";
        }else{

           
$query =  "declare 	@LINHA VARCHAR(25), @TIPO VARCHAR(25)

set @LINHA = '".$linha."'	
set @TIPO = '".$tipo."'
IF @LINHA = '' AND @TIPO = '' 
	SELECT '' AS 'GRUPO_PRODUTO' UNION ALL  SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 group by p.GRUPO_PRODUTO
IF @LINHA <> '' AND @TIPO = ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA group by p.GRUPO_PRODUTO
IF @LINHA <> '' AND @TIPO <> ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.LINHA = @LINHA and p.TIPO_PRODUTO = @TIPO group by p.GRUPO_PRODUTO
IF @LINHA = '' AND @TIPO <> ''
	SELECT ''AS 'GRUPO_PRODUTO'  UNION ALL SELECT p.GRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.TIPO_PRODUTO = @TIPO group by p.GRUPO_PRODUTO

";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div class='col-md-6'>
<label>
    <h5><b>GRUPO:</b></h5>
</label><br>

<select  name='GRUPO_PRODUTO' DISABLED id='GRUPO_PRODUTO'  class='form-control'>";

while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" id="GRUPO_PRODUTO" name="GRUPO_PRODUTO"
value='<? echo $row_tratado = str_replace($vowels,'-',$row_estab['GRUPO_PRODUTO']) ?>'>
  <? echo $row_estab['GRUPO_PRODUTO'] ?>
</option> <?
            }
            echo "</select></div>";
        }

        ?>

<script>
if ($.trim($('#GRUPO_PRODUTO_ID[document_type]').val()) != '') {
                    $("#SUBGRUPO_DIV").hide();
                    $("#SUBGRUPO_ID").show();                 
                }
                $(document).ready(function() {

                    $("#GRUPO_PRODUTO_ID_DIV").show();

                    $('#GRUPO_PRODUTO_ID').change(function() {

                        $("#SUBGRUPO_DIV").hide();
                        $("#SUBGRUPO_ID").show();

                        $('#SUBGRUPO_ID').load('subgrupo_post.php?GRUPO_PRODUTO=' + $('#GRUPO_PRODUTO_ID').val());
                  
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