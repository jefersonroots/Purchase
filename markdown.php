<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
</head>
<?php 
@$login_cookie = $_COOKIE['login'];
    if(isset($login_cookie)){
       
       ?>
<br>
<!DOCTYPE html>
<html>
<title>Orçamento</title>
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

include('visual.html');
?>
<!-- CABEÇALHO DE VENDAS COMECA AQUI -->
<div class="col-md-3" align="center"></div>
<div class="col-md-6" align="center">
    <div class='panel panel-primary'>
        <div class="panel-heading" align="center">
            <h4>CAMPOS DE PESQUISA</h4>
        </div>
        <nav class="navbar navbar-light bg-light" align="center">
        <form action="markdownexcel.php" name="form" method="POST" align="center" target="_blank">
                <div class=" panel  col-md-12 " align="center">
            
                    
                    <div class="form-group col-md-5   " align='center'>
                        <?include "linha.php"; ?>
                        <label for="LINHA">
                            <h5><b>LINHA:</b></h5> 
                        </label><br>
                        <select name="LINHA" class="form-control" id="LINHA">
                            <option></option>
                            <?php 
							while($row_estab = sqlsrv_fetch_array($querySelect))
							{
						?>
                            <option type="text" name="LINHA" value="<? echo $row_estab['LINHA']?>">
                                <? echo $row_estab['LINHA']?>
                            </option>
                            <? 
							} 
						?>
                        </select>
                    </div>
                
              <div id="divisoria" class="col-md-2"></div>
                    <div class="form-group col-md-5">
                        <?include "marcaporcodigo.php"; ?>
                        <label for="MARCA">
                            <h5><b>MARCA:</b></h5>
                        </label><br>
                        <select required name="MARCA" class="form-control" id="MARCA">
                            <option>selecione uma marca...</option>
                            <?php 
							while($row_estab = sqlsrv_fetch_array($querySelect))
							{
						?>
                            <option  type="text" name="MARCA" value="<? echo $row_estab['COD_PRODUTO_SEGMENTO']?>">
                                <? echo $row_estab['nome_marca']?>
                            </option>
                            <? 
							} 
						?>
                        </select>
                    </div>
                    <div class="form-group col-md-12"></div>
        
                    <div class="form-group col-md-5">
                        <label for="PRODUTO">
                            <h5><b>PRODUTO:</b></h5>
                        </label><br>
                        <input type="text" class="form-control form-control-sm"checked id="PRODUTO" name="PRODUTO" value="" />
                    </div>

                    <div id="divisoria" class="col-md-2"></div>
             
                    <div class="form-group col-md-5">
                        <?include "fabricante.php"; ?>
                        <label for="FABRICANTE">
                            <h5><b>FABRICANTE:</b></h5>
                        </label><br>
                        <select name="FABRICANTE" class="form-control" id="FABRICANTE">
                            <option></option>
                            <?php 
							while($row_estab = sqlsrv_fetch_array($querySelect))
							{
						?>
                            <option type="text" name="FABRICANTE" value="<? echo $row_estab['FABRICANTE']?>">
                                <? echo $row_estab['FABRICANTE']?>
                            </option>
                            <? 
							} 
						?>
                        </select>
                    </div>
                    
                    
                    <div  class="col-md-12 "><div  class="col-md-3 "></div>  <div class="form-group col-md-6">
                        
                        <?include "listaLoja.php"; ?>
                        <label for="LOJA">
                            <h5><b>LOJA:</b></h5>
                        </label><br>
                        <select name="LOJA" class="form-control" id="LOJA">
                            <option></option>
                            <?php 
							while($row_estab = sqlsrv_fetch_array($querySelect))
							{
						?>
                            <option type="text" name="LOJA" value="<? echo $row_estab['COD_FILIAL']?>">
                                <? echo $row_estab['FILIAL']?>
                            </option>
                            <? 
							} 
						?>
                        </select>
                    </div></div>
                    <div  class="col-md-3 "></div>
                    
                    <div  class=" panel panel-primary col-md-6 ">
             
                    
             <label>
                 <b>
                     <h5><b>AGRUPAMENTO:</b></h5>
                 </b>
             </label>
             
             <BR>ADMINISTRAÇÃO
             <input type="checkbox"  id="tipofilial1" name="tipofilial1" value="ADMINISTRACAO" />
              &nbsp; POLYELLE
             <input type="checkbox"  id="tipofilial2" name="tipofilial2" value="POLYELLE" /><br><br>
             &nbsp; TEENS&KIDS
             <input type="checkbox"  id="tipofilial3" name="tipofilial3" value="TEENS&KIDS" /><br><br>
             &nbsp; POLYELLE-MA
             <input type="checkbox"  id="tipofilial4" name="tipofilial4" value="POLYELLE-MA" />
             &nbsp;  MR.FOOT
             <input type="checkbox"  id="tipofilial5" name="tipofilial5" value="MR.FOOT" />
             <BR>
             <br>
                             </div>
                </div>
         

                <div class="col-md-12" align="center">
                    <div class="col-md-4" align="center">
                     
	<button type="submit" class="btn btn-success">EXECUTAR</button>
                    </div>
                    <div class="col-md-4">
                        <?
                           
                        //    if(empty($DATAINICIO)){
                        //    $DATAINICIO = date("d-m-Y", strtotime($_POST['DATAINICIO']));
                        //     $DATAFIM = date("d-m-Y", strtotime($_POST['DATAFIM']));
                        //    echo "<h4>Intervalo de consulta: <br><br> ".@$DATAINICIO." - ".@$DATAFIM;
                        // }else{

                        // }
                           
                           ?>
                    </div>

                    <div class="col-md-4" align="center">
                        <button onclick="window.location.href='markdown.php';" class='btn btn-success' type="reset"
                            title="Limpar" name="limpar" id="SendPesqUserBARRA" value="Limpar"
                            href='http://110.100.2.9/compras/markdown.php'>LIMPAR</button>
                    </div>

                    <br>
                </div>
                <br><br>
                <br>

                </TD>
            </form>
            <!-- AQUI FICA OS MODAIS 1° MODAL DE DETALHES E O 2° É MODAL DO PDF -->


            </script>
            <style type="text/css">
            p {
                background-color: #bf0000;
            }

            label { 
                font-weight: bold;}
       
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

<?
 }else{
				echo"<script language='javascript' type='text/javascript'>
						alert('Login e/ou senha incorretos');window.location
						.href='login_markdown.html';</script>";
						die();
					
						}
	?>