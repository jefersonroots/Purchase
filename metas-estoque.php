<?php
@$login_cookie = $_COOKIE['login'];
    if(isset($login_cookie)){

?>
<br>
<!DOCTYPE html>
<html>
<title>Meta estoque </title>
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

                <div class="col-md-12">
                <div align="center" class="col-md-2"></div>
                <div align="center" class="col-md-4">
                    <label>
                        <h5><b>Período </b></h5>
                    </label>
                    <select  class="form-control"  name="periodo" id="periodo">
                    <option id="periodo" name="periodo" value="1">1 Mês</option>
                    <option id="periodo" name="periodo" value="2">2 Meses </option>
                    <option id="periodo" name="periodo"  value="3">3 Meses</option>
                    <option id="periodo" name="periodo"value="4">4 Meses</option>
                    <option id="periodo" name="periodo"value="5">5 Meses</option>
                    <option id="periodo" name="periodo"value="6">6 Meses</option>
                    <option id="periodo" name="periodo"value="7">7 Meses</option>
                    <option id="periodo" name="periodo"value="8">8 Meses</option>
                    <option id="periodo" name="periodo"value="9">9 Meses</option>
                    <option id="periodo" name="periodo"value="10">10 Meses</option>
                    <option id="periodo" name="periodo"value="11">11 Meses</option>
                    <option id="periodo" name="periodo"value="12">12 Meses</option>
                    </select>
                    <!-- <input id="periodo" name="periodo" type="number" min="1" max="12" step="0.01"  requerid class="form-control form-control-sm"
                       value=""  placeholder="" aria-label="Search" /> -->

                </div>
                
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
                           
                            <input type="submit" id="btnenviar1" value="EXCEL" class="btn btn-info" href="meta-estoque-excel.php"/></input>

                    </div>

                    <div class="col-md-4" align="center">
                        <button onclick="window.location.href='metas-estoque.php';" class='btn btn-success'
                            type="reset" title="Limpar" name="limpar" value="Limpar"
                            href='http://110.100.2.9/compras/metas-estoque.php'>LIMPAR</button>
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
      $('#form1').attr('action', 'meta-estoque-excel.php');
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
    @$periodo = $_POST['periodo'];
    @$grupo = $_POST['GRUPO'];
    $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
    if ($SendPesqUser) {
        $DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
        $query = "
        --PROGRAMA PAINEL DE PRODUTOS
        --OBJETIVO APRESENTAR AS METAS E OS VALORES VENDIDOS DOS AGRUPAMENTOS DE PRODUTOS
        
        --DECLARA AS VARIAVEIS INICIAIS
        declare @agrup int, @dataini varchar(8), @datafim varchar(8),
                @tipofilial1 varchar(20), @tipofilial2 varchar(20), @tipofilial3 varchar(20), @tipofilial4 varchar(20), @tipofilial5 varchar(20),
                @periodos int
        
        --VARIAVEIS QUE O USUÁRIO IRA ESCOLHER
        set @agrup = '".$grupoAgrup."'	--GRUPO REFERENTE AO AGRUPAMENTO A SER UTILIZADO
        set @periodos = '".$periodo."'	--USUARIO INFORMA O NÚMERO DE PERÍODOS DESEJADO DE VENDA
        set @dataini = 	replace(dateadd(day,(-30 * @periodos),cast(GETDATE() as date)),'-','')	--DATA INICIAL A SER PESQUISADA
        set @datafim = replace(cast(GETDATE() as date),'-','')						--DATA FINAL A SER PESQUISADA
        
        
        --VARIAVEIS DE AGRUPAMENTO PARA O USUÁRIO ESCOLHER
        set @tipofilial1 = '".$tipofilial1."'	--ADMINISTRACAO
        set @tipofilial2 = '".$tipofilial2."'	--POLYELLE
        set @tipofilial3 = '".$tipofilial3."'	--TEENS&KIDS
        set @tipofilial4 = '".$tipofilial4."'	--POLYELLE-MA
        set @tipofilial5 = '".$tipofilial5."'		--MR.FOOT
        
           
SET NOCOUNT ON    EXEC dbo.LX_POLYELLE_META_CATEGORIA_AGRUPADO_ESTOQUE @agrup,@dataini,@datafim,@tipofilial1,@tipofilial2,@tipofilial3,@tipofilial4,@tipofilial5,@periodos



";
        if($periodo > 1){
            $mes = "MESES";
        }else{
            $mes = "MÊS";
        }
  
        echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
        <div    class='panel-heading '  ><h4>CONSULTA PARA ";echo$periodo." ".$mes; 
    
         echo"</h4></div>
            <nav style=' width: 100%;overflow:auto;float: none;
            display: block;' class='navbar navbar-light bg-light' align='center'>
                <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
                <thead>
                    <TR  style='background-color:   #EBEBEB ;'>
                         <th scope='col'  ><div align='center'><h5><b/>GRUPO</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>AGRUPAMENTO</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>DESC. AGRUPAMENTO</h5></div></th>
                         <th scope='col' class='col-sm-1'  ><div align='center'><h5><b/>META VALOR</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>QTDE VEND.</h5></div></th>
                         <th scope='col' class='col-sm-1' ><div align='center'><h5><b/>VALOR VENDIDO</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>DIFERENCA </h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>%_DIF</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>QTDE ESTOQUE</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>VL ESTOQUE</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>TEMPO ESTOQUE</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>%ESTOQUE</h5></div></th>
                         <th scope='col'  ><div align='center'><h5><b/>%VENDA</h5></div></th>

                    </TR>
                </thead>";
       
        $stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));
        sqlsrv_execute(@$stmtAC2);
        while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

            echo "<TR >
  <TD align='center' ><font size=2>" . $item['GRUPO'] . "</font></TD> 
  <TD align='center' ><font size=2>" . $item['AGRUPAMENTO'] . "</font></TD>
  <TD align='center' ><font size=2>" . $item['DESC_AGRUPAMENTO'] . "</font></TD>
  <TD align='center'  ><font  size=2>" . $item['META_VALOR'] . "</font></TD>
  <TD align='center'   ><font size=2>" . $item['QTDE_VENDIDA'] . "</font></TD>
  <TD align='center' ><font size=2>" . $item['VALOR_VENDIDO'] . "</font></TD>
  <TD align='center'  ><font size=2>" . $item['DIFERENCA'] . "</font></TD>
  <TD align='center' ><font size=2>" .  floatval($item['%_DIF']) . "%</font></TD>
  <TD align='center' ><font size=2>" . $item['QTDE_ESTOQUE']. "</font></TD>
  <TD align='center' ><font size=2>" . $item['VL_ESTOQUE']. "</font></TD>
  <TD align='center' ><font size=2>" . $item['TEMPO_ESTOQUE']. "</font></TD>
  <TD align='center' ><font size=2>" . floatval($item['%ESTOQUE']). "%</font></TD>
  <TD align='center' ><font size=2>" . floatval($item['%VENDA']). "%</font></TD>



";
    
        ?>
        <td align="center">
        <form action="detalhar-meta-estoque.php" target="_blank" method="post">
            <input hidden  value="<?php echo $item['GRUPO']; ?>" id="GRUPO" name="GRUPO"></input>
            <input hidden  value="<?php echo $item['DESC_AGRUPAMENTO']; ?>" id="DESC_AGRUPAMENTO" name="DESC_AGRUPAMENTO"></input>
            <input hidden  value="<?php echo $item['AGRUPAMENTO']; ?>" id="agrupamento" name="agrupamento"></input>
            <input hidden  value="<?php echo $periodo ?>" id="periodo" name="periodo"></input>
        
        
      <button  class='btn btn-info view_data'  ?>DETALHAR</button>
        </form>
    </TD> </TR>
     <?php  }
        echo " </nav></table> </div>";


    echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
    <div    class='panel-heading '  ><h4>TOTAIS</h4></div>
        <nav style=' width: 100%;overflow:auto;float: none;
        display: block;' class='navbar navbar-light bg-light' align='center'>
            <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
            <thead>
                <TR  style='background-color:   #EBEBEB ;'>
                     <th scope='col'  ><div align='center'><h5><b/>GRUPO GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>META VALOR GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>QTDE VENDA GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>VALOR VENDA GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>DIFERENCIAL GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>% GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>QTDE_ESTOQUE_GERAL</h5></div></th>
                     <th scope='col'  ><div align='center'><h5><b/>VALOR_ESTOQUE_GERAL</h5></div></th>

                </TR>
            </thead>";
            @$next_result = sqlsrv_next_result(@$stmtAC2);

    while (@$item = sqlsrv_fetch_array(@$stmtAC2, SQLSRV_FETCH_ASSOC)) {

        echo "<TR >
<TD align='center' ><font size=2>" . $item['GRUPO_GERAL'] . "</font></TD> 
<TD align='center'  ><font size=2>" . $item['META_VALOR_GERAL'] . "</font></TD>
<TD align='center' '><font size=2>" . $item['QTDE_VENDA_GERAL'] . "</font></TD>
<TD align='center'  ><font  size=2>" . $item['VALOR_VENDA_GERAL'] . "</font></TD>
<TD align='center'   ><font size=2>" . $item['DIFERENCA_GERAL'] . "</font></TD>
<TD align='center' ><font size=2>" . floatval($item['%_GERAL']) . "%</font></TD>
<TD align='center' ><font size=2>" . $item['QTDE_ESTOQUE_GERAL']. "</font></TD>
<TD align='center' ><font size=2>" . $item['VALOR_ESTOQUE_GERAL']. "</font></TD>



";
    }
    "</TR></nav>";
    
    echo "</table> </div>";
    }


    ?>
</div>























<?

 }else{
				echo"<script language='javascript' type='text/javascript'>
						alert('Login e/ou senha incorretos');window.location
						.href='loginmetavalor.html';</script>";
						die();
					
						}