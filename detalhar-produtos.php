<?php
// @$login_cookie = $_COOKIE['login'];
//     if(isset($login_cookie)){

?>
<br>
<!DOCTYPE html>
<html>
<title> Meta Categoria - Detalhes</title>
<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
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

@$datainiciocabecalho = date('d/m/Y', strtotime($_POST['DATAINICIO']));
@$datafinalcabecalho = date('d/m/Y', strtotime($_POST['DATAFIM']));

@$DATAINICIO = date('Ymd', strtotime($_POST['DATAINICIO']));
@$DATAFIM = date('Ymd', strtotime($_POST['DATAFIM']));
@$id_agrupamento = $_POST['agrupamento']; 
@$grupo = $_POST['GRUPO'];


echo "<div class='col-md-12'  style='background-color:   #EBEBEB ;' align='center'><h3>Consulta de $datainiciocabecalho - $datafinalcabecalho </BR> $grupo</H3></div>";

?>
<br>
<div class="col-md-6" align="center">
<br>
    <script>

    </script>
    <? //////// !!!   !!!   !!!   !!!   TABELA  !!!   !!!   !!!   !!!   !!!   !!!   !!!    /////////                


    //////////////////////// PARTE 1 - PRODUTOS LOJA ////////////////////////

   

        $DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
        $query = "

        declare @GRUPO char(25), @dataini date, @datafim date, @linha VARCHAR(25), @tipo_produto varchar(25), @ID_AGRUPAMENTO CHAR(30)
        set @GRUPO = '".$grupo."'	--POLYELLE, TEENS&KIDS, POLYELLE-MA, MR.FOOT
        set @dataini = '".$DATAINICIO."'	--USUARIO ESCOLHE
        set @datafim = '".$DATAFIM."'	--USUARIO ESCOLHE
        set @ID_AGRUPAMENTO = '".$id_agrupamento."'	--CODIGO DO AGRUPAMENTO
        
        SET NOCOUNT ON  EXEC LX_POLYELLE_PRODUTOS_TOP20_AGRUPAMENTO @GRUPO, @dataini, @datafim, @ID_AGRUPAMENTO
        
        


     
         
        ";

   
       
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>PRODUTO VENDIDO</h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col' class='col-md-1'><div align='center'><h5><b/>PRODUTO</h5></div></th>
                         <th scope='col' class='col-md-3'><div align='center'><h5><b/>DESCRICAO PRODUTO</h5></div></th>
                         <th scope='col' class='col-md-1'><div align='center'><h5><b/>QTDE</h5></div></th>
                   
                             </TR>
                </thead>";

        $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
        sqlsrv_execute(@$stmtAC2);
        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
        
            ?> <TR ><TD align="center" class="col-md-1 view_data2" id1="<?php echo $grupo ?>" id2="<?php echo$item['PRODUTO_LOJA'];?>" 
            id3="<?php echo $id_agrupamento ?>"  ><font size=2><?php echo $item['PRODUTO_LOJA']?></font></TD> <?php 
            // <TD align='center' class='col-md-1'><font size=2>" . $item['PRODUTO_LOJA'] . "</font></TD> 
            echo "

  <TD align='center' class='col-md-2'><font size=2>" . $item['DESCRICAO_PRODUTO_LOJA'] . "</font></TD>
  <TD align='center' class='col-md-2'><font size=2>" . $item['QTDE_PRODUTO_LOJA'] . "</font></TD>
   ";      

?>
<!-- <td align="center">
    <form action="detalhar-meta.php" target="_blank" method="post">
        
        <input hidden  value="<?php echo $item['AGRUPAMENTO']; ?>" id="agrupamento" name="agrupamento"></input>
        <input hidden  value="<?php echo $item['LOJA']; ?>" id="loja" name="loja"></input>
        <input hidden value="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>" id="DATAINICIO" name="DATAINICIO"></input>
        <input hidden value="<?php ECHO $DATAFIM = $_POST['DATAFIM']; ?>" id="DATAFIM" name="DATAFIM"></input>
  <button  class='btn btn-info view_data' id1="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>">DETALHAR</button>
    </form>
</TD> -->
   <?PHP      }
        "</TR></nav>";
        echo "</table> </div>";
   


    ?>
</div>

<div class="col-md-0" align="center">

</div>

<div class="col-md-6" align="center">
<br>
 <?php
          //////////////////////// PARTE 3 - PRODUTOS LOJA ////////////////////////

      
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>ESTOQUE</h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col' class='col-md-1'><div align='center'><h5><b/>PRODUTO</h5></div></th>
                         <th scope='col' class='col-md-3'><div align='center'><h5><b/>DESCRICAO PRODUTO</h5></div></th>
                         <th scope='col' class='col-md-1'><div align='center'><h5><b/>ESTOQUE</h5></div></th>
                         <th scope='col' class='col-md-1'><div align='center'><h5><b/>STATUS</h5></div></th>
                         
                             </TR>
                </thead>";

                @$next_result = sqlsrv_next_result(@$stmtAC2);

        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

            ?> <TR ><TD align="center" class="col-md-1 view_data2" id1="<?php echo $grupo ?>" id2="<?php echo$item['PRODUTO_ESTOQUE'];?>" 
            id3="<?php echo $id_agrupamento ?>"  ><font size=2><?php echo $item['PRODUTO_ESTOQUE']?></font></TD> <?php 
            // <TD align='center' class='col-md-1'><font size=2>" . $item['PRODUTO_LOJA'] . "</font></TD> 
            // <TD align='center' class='col-md-1'><font size=2>" . $item['PRODUTO_ESTOQUE'] . "</font></TD> 
            echo "

  <TD align='center' class='col-md-2'><font size=2>" . $item['DESCRICAO_PRODUTO_ESTOQUE'] . "</font></TD>
  <TD align='center' class='col-md-2'><font size=2>" . $item['ESTOQUE_PRODUTO'] . "</font></TD>
  <TD align='center' class='col-md-2'><font size=2>" . $item['STATUS_PRODUTO_ESTOQUE'] . "</font></TD>
   ";
?>
<!-- <td align="center">
    <form action="detalhar-meta.php" target="_blank" method="post">
        
        <input hidden  value="<?php echo $item['AGRUPAMENTO']; ?>" id="agrupamento" name="agrupamento"></input>
        <input hidden  value="<?php echo $item['LOJA']; ?>" id="loja" name="loja"></input>
        <input hidden value="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>" id="DATAINICIO" name="DATAINICIO"></input>
        <input hidden value="<?php ECHO $DATAFIM = $_POST['DATAFIM']; ?>" id="DATAFIM" name="DATAFIM"></input>
  <button  class='btn btn-info view_data' id1="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>">DETALHAR</button>
    </form>
</TD> -->
   <?PHP      }
        "</TR></nav>";
        echo "</table> </div>";
   


    ?>
</div>


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
    