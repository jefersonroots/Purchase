<?php
@$login_cookie = $_COOKIE['login'];
    if(isset($login_cookie)){

?>
<br>
<!DOCTYPE html>
<html>
<title>Meta Por Categoria </title>
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
@$grupoAgrup = 100;
?>
<!-- CABEÇALHO DE VENDAS COMECA AQUI -->
<div class="col-md-3" align="center"></div>
<div class="col-md-6" align="center">
    <div class='panel panel-primary'>
        <div class="panel-heading" align="center">
            <h4>CAMPOS DE PESQUISA</h4>
        </div>
        <nav class="navbar navbar-light bg-light" align="center">
                <form action="" class="form" target="" id="form1" name="form1" method="post">
                <div name="div-mes" class="col-md-2">

                </div>

                <div align="center" class="col-md-4">
                    <label>
                        <h5><b>DATA INÍCIO:</b></h5>
                    </label><br>

                    <input id="DATAINICIO" name="DATAINICIO" required class="form-control form-control-sm"
                        onblur="funcao1();" type="date" placeholder="" aria-label="Search" />

                </div>

                <div class="col-md-4">
                    <label>
                        <h5><b>DATA FIM:</b></h5>
                    </label><br>

                    <input id="DATAFIM" name="DATAFIM" required class="form-control form-control-sm" onblur="funcao1();"
                        type="date" placeholder="" aria-label="Search" />

                </div>
                <div class="col-md-12">
                <div class="col-md-4"></div>
                
                <div align="center" class="col-md-4">
                    <label>
                        <h5><b>GRUPO</b></h5>
                    </label>

                    <input id="GRUPO" name="GRUPO" disabled class="form-control form-control-sm"
                       value="<?echo$grupoAgrup?>" type="text" placeholder="" aria-label="Search" />

                </div>
                <BR><BR><BR><BR><BR>
                </div>
              

                <div class=" col-md-2 "></div>
                <div class=" panel panel-primary col-md-8">

                    <label>
                        <b>
                            <h5><b>AGRUPAMENTO:</b></h5>
                        </b>
                    </label>
                    <br>
                    ADMINISTRAÇÃO
                    <input type="checkbox"  id="tipofilial1" name="tipofilial1" value="POLYELLE" />
                    <BR> <BR>
                    POLYÉLLE
                    <input type="checkbox" checked id="tipofilial2" name="tipofilial2" value="POLYELLE" />
                    &nbsp; &nbsp; TEENS&KIDS
                    <input type="checkbox" checked id="tipofilial3" name="tipofilial3" value="TEENS&KIDS" /><br><br>
                    POLYÉLLE-MA
                    <input type="checkbox" checked id="tipofilial4" name="tipofilial4" value="POLYELLE-MA" />
                    &nbsp; &nbsp; MR.FOOT
                    <input type="checkbox" checked id="tipofilial5" name="tipofilial5" value="MR.FOOT" />
                    <BR>
                    <br>
                </div>

                <div class="col-md-12" align="center">
                    <div class="col-md-4" align="center">
                    

                        <button class='btn btn-success' title="PESQUISA POR PDV/CUPOM " name="SendPesqUser"
                            id="SendPesqUser" align="right" value="PESQUISAR">PESQUISAR</button>
                    </div>    </form>
                    <div class="col-md-4">
                        <!-- <button onclick="javascript:form.action='painelvendaexcel.php';" value="EXCEL" target="_blank"
                            class="btn btn-success" >EXCEL</button> -->
                           
                            <input type="submit" id="btnenviar1" value="EXCEL" class="btn btn-info" href="painelvendaexcel.php"/></input>

                    </div>

                    <div class="col-md-4" align="center">
                        <button onclick="window.location.href='meta-categoria.php';" class='btn btn-success'
                            type="reset" title="Limpar" name="limpar" value="Limpar"
                            href='http://110.100.2.9/compras/meta-categoria.php'>LIMPAR</button>
                    </div>
        
            <br>
    </div>
    <br><br>
    <br>

    </TD>

    <!-- AQUI FICA OS MODAIS 1° MODAL DE DETALHES E O 2° É MODAL DO PDF -->




    <style type="text/css">
    p {
        background-color: #bf0000;
    }

    label {
        font-weight: bold;
    }

    .piscar {
        color: #FFF;
        font-size: 20px;
        -webkit-animation-name: blinker;
        -webkit-animation-duration: 1s;
        -webkit-animation-timing-function: linear;
        -webkit-animation-iteration-count: infinite;
        -moz-animation-name: blinker;
        -moz-animation-duration: 1s;
        -moz-animation-timing-function: linear;
        -moz-animation-iteration-count: infinite;
        animation-name: blinker;
        animation-duration: 1s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }

    @-moz-keyframes blinker {
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    @-webkit-keyframes blinker {
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    @keyframes blinker {
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    .custom-height-modal {
        width: 185% !important;
        right: 250;
    }
    </style>
    <p>

    </p>


</div>
</div>
<br>
<div class="col-md-12" align="center">

    <script>
        $("#btnenviar1, #SendPesqUser").click(function(){
  //Recebe o id do botão clicado
  var id = $(this).attr('id');
  //Verifica qual foi o botão clicado através do id do mesmo e seta o action correspondente
  if (id == 'btnenviar1'){
      $('#form1').attr('action', 'meta-categoria-excel.php');
  }
  else {
      $('#form1').attr('action', '');
  }
});
    function funcao1() {
        var DATAINICIO = document.getElementById("DATAINICIO");
        var DATAFIM = document.getElementById("DATAFIM");
        var SendPesqUser = document.getElementById("SendPesqUser");
        var tipofilial1 = document.getElementById("tipofilial1");
        var tipofilial2 = document.getElementById("tipofilial2");
        var tipofilial3 = document.getElementById("tipofilial3");
        var tipofilial4 = document.getElementById("tipofilial4");
        var tipofilial5 = document.getElementById("tipofilial5");


    }
    </script>
    <? //////// !!!   !!!   !!!   !!!   TABELA  !!!   !!!   !!!   !!!   !!!   !!!   !!!    /////////                


    //////////////////////// PARTE 1 BUSCA POR TICKET ////////////////////////

    @$DATAINICIO = date('Ymd', strtotime($_POST['DATAINICIO']));
    @$DATAFIM = date('Ymd', strtotime($_POST['DATAFIM']));
    @$tipofilial1 = $_POST['tipofilial1'];
    @$tipofilial2 = $_POST['tipofilial2'];
    @$tipofilial3 = $_POST['tipofilial3'];
    @$tipofilial4 = $_POST['tipofilial4'];
    @$tipofilial5 = $_POST['tipofilial5'];

    $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
    if ($SendPesqUser) {


        $DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
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

        @$datainiciopainel = date('d/m/Y', strtotime($_POST['DATAINICIO']));

       @$datafimpainel = date('d/m/Y', strtotime($_POST['DATAFIM']));
        date('d/m/Y', strtotime($_POST['DATAINICIO']));
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>Consulta de "; 
        echo  @$datainiciopainel ." - ". @$datafimpainel;
         echo"</h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col' class='col-sm-2'><div align='center'><h5><b/>GRUPO</h5></div></th>
                         <th scope='col' class='col-sm-1'><div align='center'><h5><b/>AGRUPAMENTO</h5></div></th>
                         <th scope='col' class='col-sm-2'><div align='center'><h5><b/>DESC. AGRUPAMENTO</h5></div></th>
                         <th scope='col' class='col-sm-1'><div align='center'><h5><b/>META VALOR</h5></div></th>
                         <th scope='col' class='col-sm-1'><div align='center'><h5><b/>QTDE</h5></div></th>
                         <th scope='col' class='col-sm-2'><div align='center'><h5><b/>VALOR LÍQUIDO</h5></div></th>
                         <th scope='col' class='col-sm-2'><div align='center'><h5><b/>DIFERENCA </h5></div></th>
                         <th scope='col' class='col-sm-1'><div align='center'><h5><b/>%</h5></div></th>

                    </TR>
                </thead>";

        $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
        sqlsrv_execute(@$stmtAC2);
        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

            echo "<TR >
  <TD align='center' class='col-md-1'><font size=2>" . $item['GRUPO'] . "</font></TD> 
  <TD align='center' class='col-md-1'><font size=2>" . $item['AGRUPAMENTO'] . "</font></TD>
  <TD align='center' class='col-md-1'><font size=2>" . $item['DESC_AGRUPAMENTO'] . "</font></TD>
  <TD align='center' class='col-md-2'><font  size=2>" . $item['META_VALOR'] . "</font></TD>
  <TD align='center'   class='col-md-1'><font size=2>" . $item['QTDE_LQUIDO'] . "</font></TD>
  <TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD>
  <TD align='center' class='col-md-2'><font size=2>" . $item['DIFERENCA'] . "</font></TD>
  <TD align='center' class='col-md-1'><font size=2>" .  floatval($item['%']) . "%</font></TD>



";
    
        ?><td align="center">
        <form action="detalhar-produtos.php" target="_blank" method="post">
            <input hidden  value="<?php echo $item['GRUPO']; ?>" id="GRUPO" name="GRUPO"></input>
            <input hidden  value="<?php echo $item['AGRUPAMENTO']; ?>" id="agrupamento" name="agrupamento"></input>
        
            <input hidden value="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>" id="DATAINICIO" name="DATAINICIO"></input>
            <input hidden value="<?php ECHO $DATAFIM = $_POST['DATAFIM']; ?>" id="DATAFIM" name="DATAFIM"></input>
      <button  class='btn btn-info view_data' id1="<?php echo $DATAINICIO = $_POST['DATAINICIO']; ?>">DETALHAR</button>
        </form>
    </TD> <?php  }
        echo " </TR></nav></table> </div>";
    }

    echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
    <div    class='panel-heading '  ><h4>TOTAIS</h4></div>
        <nav style=' width: 100%;overflow:auto;float: none;
        display: block;' class='navbar navbar-light bg-light' align='center'>
            <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
            <thead>
                <TR  style='background-color:   #EBEBEB ;'>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>GRUPO GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>META VALOR GERAL</h5></div></th>
                     <th scope='col' class='col-md-2'><div align='center'><h5><b/>QTDE LQUIDO GERAL</h5></div></th>
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
<TD align='center'   class='col-md-1'><font size=2>" . $item['DIFERENCA_GERAL'] . "</font></TD>
<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_GERAL']) . "%</font></TD>



";
    }
    "</TR></nav>";
    
    echo "</table> </div>";



    ?>
</div>























<?

 }else{
				echo"<script language='javascript' type='text/javascript'>
						alert('Login e/ou senha incorretos');window.location
						.href='loginmeta.html';</script>";
						die();
					
						}