<?php
// @$login_cookie = $_COOKIE['login'];
//     if(isset($login_cookie)){

?>
<br>
<!DOCTYPE html>
<html>
<title> Meta Categoria - Detalhes</title>
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
<!-- Latest compiled and minified CSS -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8" />
<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous" />
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/popper.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/jquery-3.4.1.js"></script>

<?

include('listaloja.php');
include('visual.html');

@$id_agrupamento = $_POST['agrupamento']; 
@$grupo = $_POST['GRUPO'];
@$periodo = $_POST['periodo'];
@$DESC_AGRUPAMENTO = $_POST['DESC_AGRUPAMENTO'];

if($periodo > 1){
    $mes = "MESES";
}else{
    $mes = "MÊS";
}


echo "<div class='col-md-12'  style='background-color:   #EBEBEB ;' 
align='center'><h3>CONSULTA PARA ";echo $periodo." ".$mes; "</H3></div>";

?>
<br>
<div class="col-md-12" align="center">
<br>
    <script>

    </script>
    <? //////// !!!   !!!   !!!   !!!   TABELA  !!!   !!!   !!!   !!!   !!!   !!!   !!!    /////////                


    //////////////////////// PARTE 1 - PRODUTOS LOJA ////////////////////////

   

        $DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
        $query = "

declare @GRUPO CHAR(25), @PERIODOS INT, @ID_AGRUPAMENTO CHAR(30)

--VARIAVEIS
set @GRUPO = '".$grupo."'
set @periodos = '".$periodo."'	--USUARIO INFORMA O NÚMERO DE PERÍODOS DESEJADO DE VENDA
set @ID_AGRUPAMENTO = '".$id_agrupamento."'


SET NOCOUNT ON   EXEC dbo.LX_POLYELLE_GRUPO_AGRUPAMENTO @GRUPO, @PERIODOS, @ID_AGRUPAMENTO


        
        ";

   
       
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>$grupo";?>
                                  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-
                                   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo"$DESC_AGRUPAMENTO  </h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col' ><div align='center'><h5><b/>GRUPO PRODUTO</h5></div></th>
                         <th scope='col'><div align='center'><h5><b/>QTDE VENDIDA</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>VALOR VENDIDO</h5></div></th>
                         <th scope='col'><div align='center'><h5><b/>QTDE ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>VALOR ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>TEMPO ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>%ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>%VENDA</h5></div></th>
                   
                             </TR>
                </thead>";

        $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
        
         
            echo "

  <TD align='center'  ><font size=2>" . $item['GRUPO_PRODUTO'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['QTDE_VENDIDA'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['VALOR_VENDIDO'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['QTDE_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['VALOR_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['TEMPO_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" .  floatval($item['%ESTOQUE']) . "%</font></TD>
  <TD align='center'  ><font size=2>" .  floatval($item['%VENDA']) . "%</font></TD>
   ";      

?> <td align="center">
<form action="meta-subgrupo.php" target="_blank" method="post">
    <input hidden  value="<?php echo $grupo; ?>" id="GRUPO" name="GRUPO"></input>
    <input hidden  value="<?php echo $id_agrupamento; ?>" id="agrupamento" name="agrupamento"></input>
    <input hidden  value="<?php echo $periodo ?>" id="periodo" name="periodo"></input>
    <input hidden  value="<?php echo  $item['GRUPO_PRODUTO'] ?>" id="GRUPO_PRODUTO" name="GRUPO_PRODUTO"></input>

<button  class='btn btn-info view_data'  ?>DETALHAR</button>
</form>
</TD>
</TR>
   <?PHP      }
        "</nav>";
        echo "</table> </div>";
   


    ?>
</div>



<style>

.modal-dialog {
  width: 100%;
  height: 100%;
  padding: 0;
}

.modal-content {
  height: 100%;
  border-radius: 0;
}

</style>
