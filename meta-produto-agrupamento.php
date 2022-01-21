<?php
// @$login_cookie = $_COOKIE['login'];
//     if(isset($login_cookie)){

?>
<br>
<!DOCTYPE html>
<html>
<title> Meta Categoria - painel de produtos</title>
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
@$SUBGRUPO_PRODUTO = $_POST['SUBGRUPO_PRODUTO'];
@$periodo = $_POST['periodo'];
@$grupo_produto = $_POST['GRUPO_PRODUTO'];





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

        --OBJETIVO APRESENTAR AS METAS E OS VALORES VENDIDOS DOS AGRUPAMENTOS DE PRODUTOS

        --DECLARA AS VARIAVEIS INICIAIS
        declare @GRUPO CHAR(25), @PERIODOS INT, @ID_AGRUPAMENTO CHAR(30), @GRUPO_PRODUTO VARCHAR(25), @SUBGRUPO_PRODUTO VARCHAR(25)

        --VARIAVEIS
        set @GRUPO = '".$grupo."'
        set @periodos = '".$periodo."'	--USUARIO INFORMA O NÚMERO DE PERÍODOS DESEJADO DE VENDA
        set @ID_AGRUPAMENTO = '".$id_agrupamento."'
        set @GRUPO_PRODUTO = '".$grupo_produto."'
        set @SUBGRUPO_PRODUTO = '".$SUBGRUPO_PRODUTO."'
        


SET NOCOUNT ON EXEC dbo.LX_POLYELLE_PRODUTO_AGRUPAMENTO @GRUPO, @PERIODOS, @ID_AGRUPAMENTO, @GRUPO_PRODUTO, @SUBGRUPO_PRODUTO


        
        ";

   
       
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>$grupo";?>
                                  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp-
                                   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo"$SUBGRUPO_PRODUTO  </h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col' ><div align='center'><h5><b/>PRODUTO</h5></div></th>
                         <th scope='col'><div align='center'><h5><b/>DESCRIÇÃO</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>QTDE VENDIDA</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>VALOR VENDIDO</h5></div></th>
                         <th scope='col'><div align='center'><h5><b/>QTDE ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>VALOR ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>TEMPO ESTOQUE</h5></div></th>
                         <th scope='col' ><div align='center'><h5><b/>RECEBIMENTO</h5></div></th>
        
                   
                             </TR>
                </thead>";

        $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
        
        
            ?> <TR ><TD align="center" class="col-md-1 view_data2" id1="<?php echo $grupo ?>" id2="<?php echo$item['PRODUTO'];?>" 
            id3="<?php echo $id_agrupamento ?>"  ><font size=2><?php echo $item['PRODUTO']?></font></TD> <?php 
            // <TD align='center' class='col-md-1'><font size=2>" . $item['PRODUTO_LOJA'] . "</font></TD> 
            // <TD align='center' class='col-md-1'><font size=2>" . $item['PRODUTO_ESTOQUE'] . "</font></TD> 
            echo "

  <TD align='center'  ><font size=2>" . $item['DESCRICAO'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['QTDE_VENDIDA'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['VALOR_VENDIDO'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['QTDE_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['VALOR_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['TEMPO_ESTOQUE'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['RECEBIMENTO'] . "</font></TD>


  ";
    
  ?>
  <td align="center">
  <form action="metas-valores-agrupamento.php" target="_blank" method="post">
      <input hidden  value="<?php echo $grupo ?>" id="GRUPO" name="GRUPO"></input>
      <input hidden  value="<?php echo $item['PRODUTO']; ?>" id="PRODUTO" name="PRODUTO"></input>
      <input hidden  value="<?php echo $item['DESC_AGRUPAMENTO']; ?>" id="DESC_AGRUPAMENTO" name="DESC_AGRUPAMENTO"></input>
      <input hidden  value="<?php echo $item['AGRUPAMENTO']; ?>" id="agrupamento" name="agrupamento"></input>
      <input hidden  value="<?php echo $periodo ?>" id="periodo" name="periodo"></input>
  
  
<button  class='btn btn-info view_data'  ?>DETALHAR</button>
  </form>
</TD> </TR>
<?php  }
  echo " </nav></table> </div>";


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


<div class="modal fade  bd-example-modal" id="gerarpdf" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" align="center" id="exampleModalLongTitle"></h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <span id="pdf"> </span>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
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

<script>
// $("tr").on('click',function() {
//         var horario;
//          var tableData = $(this).children("td").map(function()         {
//          return $(this).text();
//          }).get();
//          horario =    $.trim(tableData[0]);
//          $('div.text').text(horario);
//          $('#modal-texto').modal('show');
//     });
    
//     $('.btn-salvar').on('click',function(){
//       alert('Salvo');
//        $('#modal-texto').modal('hide');
//     });



    
    $(document).ready(function() {
                $(document).on('click', '.view_data2', function() {
                    var produto = $(this).attr("id2");
                    var grupo = $(this).attr("id1");
                       var id_agrupamento = $(this).attr("id3");
                    
                    if (produto !== '') {
                        var dados = {
                            produto: produto,
                            grupo:grupo,
                            id_agrupamento:id_agrupamento
                            
                        };
                        $.post('gerarpdf.php', dados, function(retorna) {
                            $("#pdf").html(retorna);
                            $('#gerarpdf').modal('show');
                        });
                    }
                });
            });

    </script>
    