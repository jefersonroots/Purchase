<?php

include('visual.html');

$host1 = '110.100.50.89,1440';
$user = 'linx';
$senha = 'Poly@123';
$base = 'LINX_Prod';
$con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
$conn1 = sqlsrv_connect($host1, $con);

$aspas = '"';
@$GRUPO_PRODUTO = $_GET['GRUPO_PRODUTO'];
@$GRUPO_PRODUTO_ID = $_GET['GRUPO_PRODUTO_ID'];

if($GRUPO_PRODUTO || $GRUPO_PRODUTO_ID ){

    $query =  "SELECT '' as 'SELECT_SUBGRUPO' UNION ALL SELECT p.SUBGRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.GRUPO_PRODUTO ='$GRUPO_PRODUTO' group by p.SUBGRUPO_PRODUTO";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div name='SUBGRUPO_ID' id='SUBGRUPO_ID'  class='col-md-6'>
<label>
    <h5><b>SUBGRUPO A:</b></h5>
</label><br>

<select name='SUBGRUPO_ID'  id='SUBGRUPO_ID'  class='form-control'>
";
while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="SUBGRUPO" value="<? echo $reg['SELECT_SUBGRUPO'] ?>">
        <? echo $reg['SELECT_SUBGRUPO'] ?>
    </option> <?
            }}
if($GRUPO_PRODUTO_ID){

    $query =  "SELECT '' as 'SELECT_SUBGRUPO' UNION ALL SELECT p.SUBGRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.GRUPO_PRODUTO ='$GRUPO_PRODUTO_ID' group by p.SUBGRUPO_PRODUTO";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div name='SUBGRUPO_ID' id='SUBGRUPO_ID'  class='col-md-6'>
<label>
    <h5><b>SUBGRUPO F:</b></h5>
</label><br>

<select name='SUBGRUPO_ID'  id='SUBGRUPO_ID'  class='form-control'>
";
while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="SUBGRUPO" value="<? echo $reg['SELECT_SUBGRUPO'] ?>">
        <? echo $reg['SELECT_SUBGRUPO'] ?>
    </option> <?
            }
            echo "</select></div>";
        }else{

           
$query =  "SELECT '' as 'SELECT_SUBGRUPO' UNION ALL SELECT p.SUBGRUPO_PRODUTO FROM PRODUTOS p WHERE p.INATIVO = 0 and p.STATUS_PRODUTO = 3 and p.GRUPO_PRODUTO ='' group by p.SUBGRUPO_PRODUTO";
// echo $query;

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

echo "
<div class='col-md-6'>
<label>
    <h5><b>SUBGRUPO F:</b></h5>
</label><br>

<select name='SUBGRUPO'  id='SUBGRUPO'  class='form-control'>";

while ($reg = sqlsrv_fetch_array($stmtAC2)) {
?><option type="text" name="SUBGRUPO" value="<? echo $reg['SELECT_SUBGRUPO'] ?>">
        <? echo $reg['SELECT_SUBGRUPO'] ?>
    </option> <?
            }
            echo "</select></div>";
        }

        ?>

        <!-- <script> document.getElementById("TIPO_PRODUTO").style.display = 'none';</script> -->