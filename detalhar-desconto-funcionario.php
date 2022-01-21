
<br>
<?PHP include "visual.html";
include "function.php";
include "listaloja.php";
?>


<title>Detalhar Desconto Funcionario</title>
<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />

<div class="modal fade  bd-example-modal-lg" id="visualizar" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" align='center' id="exampleModalLongTitle">Detalhes do Ticket</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span id="visualiza"> </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
<?

 @$DOCUMENTO = $_GET['cpf'];
 @$DESCONTO = $_GET['desconto'];
@$vowels = array(".","-","/");
 @$DOCUMENTO_TRATADO   = str_replace($vowels,'',$DOCUMENTO);
  @$CPF = $_GET['cpf'];

 $query = "
declare @CPF VARCHAR(19), @PERC VARCHAR(03)
set @CPF = '".$DOCUMENTO_TRATADO."'
set @PERC = '".$DESCONTO."'

SET NOCOUNT ON  EXEC dbo.LX_POLYELLE_DESCONTO_FUNCIONARIO_DETALHADO @CPF,@PERC

 "; 
 ?>
<div class="col-md-2" align="center"></div>
<div class="col-md-8" align="center">
 <?
 echo" <div  style='overflow: auto;' class='panel panel-primary' align='center' >
          <div  style='overflow: auto;' class='panel-heading' action='buscar.php' align='center'><h4>DESCONTOS SELECIONADO - $DESCONTO</h4></div>
              <nav class='navbar navbar-light bg-light' align='center'>
                  <TABLE ALIGN='center'  class='table table-bordered table-hover'>
                  <thead>
                      <TR  style='background-color:   #EBEBEB ;'>
                           <th scope='col' class='col-md-1'><div align='center'><h5>LOJAS <b/></h5></div></th>
                           <th scope='col' class='col-md-1'><div align='center'><h5>TICKET<b/></h5></div></th>
                           <th scope='col' class='col-md-1'><div align='center'><h5>DATA<b/></h5></div></th>
                           <th scope='col' class='col-md-3'><div align='center'><h5>VALOR VENDA BRUTA<b/></h5></div></th>
                           <th scope='col' class='col-md-2'><div align='center'><h5>VALOR TROCA<b/></h5></div></th>
                           <th scope='col' class='col-md-1'><div align='center'><h5>DESCONTO<b/></h5></div></th>
                           <th scope='col' class='col-md-1'><div align='center'><h5>%DESCONTO <b/></h5></div></th>
                           <th scope='col' class='col-md-2'><div align='center'><h5>VALOR PAGO<b/></h5></div></th>
                      </TR>
                  </thead>";
 
  $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

  while($exibe = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)){
     echo" 	<TR>
     
     <TD align='center'><font size=2>".$exibe['LOJAS']."</font></TD>
     <TD align='center'><font size=2>".$exibe['TICKET']."</font></TD>
     <TD align='center'><font size=2>".$exibe['DATA']."</font></TD>
     <TD align='center'><font size=2>".$exibe['VALOR_VENDA_BRUTA']."</font></TD>
     <TD align='center'><font size=2>".floatval($exibe['VALOR_TROCA'])."</font></TD>
     <TD align='center'><font size=2>".$exibe['DESCONTO']."</font></TD>
     <TD align='center'><font size=2>".$exibe['%DESCONTO']."</font></TD>
     <TD align='center'><font size=2>".floatval($exibe['VALOR_PAGO'])."</font></TD>
";
?>
<td align="center">
   
<button  class='btn btn-info view_data'   data-toggle="modal" data-backdrop="static" data-keyboard="false"
 id="<?php  echo  $exibe['TICKET']; ?>";
 id2="<?php echo $exibe['LOJAS']; ?>";
 id3="<?php echo $exibe['DATA']; ?>";
 >DETALHAR</button>

</TD>
     <?
  ECHO"   </TR>";
         
  }
  
  
  echo"</table></div> ";




?>

<script>

$(document).ready(function() {
                $(document).on('click', '.view_data', function() {
                    var ticket = $(this).attr("id");
                    var loja = $(this).attr("id2");
                    var data = $(this).attr("id3");
               
                    if (ticket !== '') {
                        var dados = {
                            ticket: ticket,
                            loja: loja,
                            data: data,
                         
                        };
                        $.post('visualizar.php', dados, function(retorna) {
                            $("#visualiza").html(retorna);
                            $('#visualizar').modal('show');
                         
                        });
                    }
                });
            });

</script>