<?php

include('visual.html');

$host1 = '110.100.50.89,1440';
$user = 'linx';
$senha = 'Poly@123';
$base = 'LINX_Prod';
$con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
$conn1 = sqlsrv_connect($host1, $con);

$aspas = '"';
    @$CATEGORIA = $_GET['CATEGORIA'];
     @$CATEGORIA_ID = $_GET['CATEGORIA_ID'];
 
if($CATEGORIA || $CATEGORIA_ID){

  $query =  "	SELECT '' AS 'SELECT_SUBCATEGORIA'UNION ALL SELECT RTRIM(LTRIM(psc.SUBCATEGORIA_PRODUTO)) FROM PRODUTOS_SUBCATEGORIA psc where psc.COD_CATEGORIA = (SELECT pc.COD_CATEGORIA FROM PRODUTOS_CATEGORIA pc WHERE pc.CATEGORIA_PRODUTO = '$CATEGORIA')";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div   class='col-md-6'>
<label>
    <h5><b>SUBCATEGORIA:</b></h5>
</label><br>

<select name='SUBCATEGORIA_ID'  id='SUBCATEGORIA_ID'  class='form-control'>
";
while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="SUBCATEGORIA" value="<? echo $reg['SELECT_SUBCATEGORIA'] ?>">
        <? echo $reg['SELECT_SUBCATEGORIA'] ?>
    </option> <?
            }
            echo "</select><BR></div>";
           }else{

           
            $query =  "	SELECT '' AS 'SELECT_SUBCATEGORIA'UNION ALL SELECT RTRIM(LTRIM(psc.SUBCATEGORIA_PRODUTO)) FROM PRODUTOS_SUBCATEGORIA psc where psc.COD_CATEGORIA = (SELECT pc.COD_CATEGORIA FROM PRODUTOS_CATEGORIA pc WHERE pc.CATEGORIA_PRODUTO = '')";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div class='col-md-6'>
<label>
    <h5><b>SUBGRUPO:</b></h5>
</label><br>

<select name='SUBCATEGORIA_ID'  id='SUBCATEGORIA_ID'  class='form-control'>";

while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="SUBCATEGORIA" value="<? echo $reg['SELECT_SUBCATEGORIA'] ?>">
        <? echo $reg['SELECT_SUBCATEGORIA'] ?>
    </option> <?
            }
            echo "</select><BR></div>";
        }

        ?>

        <!-- <script> document.getElementById("TIPO_PRODUTO").style.display = 'none';</script> -->