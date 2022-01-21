<?php
// @$login_cookie = $_COOKIE['login'];
// if (isset($login_cookie)) {

?>
<br>
<!DOCTYPE html>
<html>
<title>Desconto funcionario</title>
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
include('listaloja.php');
?>
<!-- CABEÇALHO DE VENDAS COMECA AQUI -->
<div class="col-md-2" align="center"></div>
<div class="col-md-8" align="center">
    <div class='panel panel-primary'>
        <div class="panel-heading" align="center">
            <h4>CAMPOS DE PESQUISA</h4>
        </div>
        <nav class="navbar navbar-light bg-light" align="center">
            <form action="" name="form" method="POST" align="center" target="">
                <div name="div-mes" class="col-md-3">

                </div>

                <div align="center" class="col-md-6">
                                <br>   
                    <label for="">CPF: </label>
        <input id="cpfcnpj" tabindex="2" maxlength="14" type='text' required name='cpfcnpj' class="form-control "
            onkeypress='mascaraMutuario(this,cpfCnpj)' onblur='clearTimeout()'/>
           
                </div>

                <div class="col-md-12" align="center">
                    
<br><br>
                    <div class="col-md-4" align="center">

                        <button type="submit"onblur="funcao1();"  name="SendPesqUser" id="SendPesqUser" value="exec" class="btn btn-success">EXECUTAR</button>
                    </div>
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4" align="center">
                        <button onclick="window.location.href='desconto-funcionario.php';" class='btn btn-success' type="reset" title="Limpar" name="limpar" id="SendPesqUserBARRA" value="Limpar" href='http://110.100.2.9/compras/dde.php'>LIMPAR</button>
                    </div>

                    <br>
                </div>
            </form></nav></div>
    <?       
   @$DOCUMENTO = $_POST['cpfcnpj'];
    @$vowels = array(".","-","/");
     @$DOCUMENTO_TRATADO   = str_replace($vowels,'',$DOCUMENTO);
  @$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
   if($SendPesqUser){

    
    $query = "
  declare @CPF VARCHAR(19)
  set @CPF = '".$DOCUMENTO_TRATADO."'
  
  SET NOCOUNT ON  EXEC dbo.LX_POLYELLE_DESCONTO_FUNCIONARIO @CPF
   "; 
   
   echo" <div  style='overflow: auto;' class='panel panel-primary' align='center' >
			<div  style='overflow: auto;' class='panel-heading' action='buscar.php' align='center'><h4></h4></div>
				<nav class='navbar navbar-light bg-light' align='center'>
					<TABLE ALIGN='center'  class='table table-bordered table-hover'>
					<thead>
						<TR  style='background-color:   #EBEBEB ;'>
							 <th scope='col' class='col-md-1'><div align='center'><h5><b/>FUNCIONÁRIO</h5></div></th>
						</TR>
					</thead>";
   
	$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

	while($exibe = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)){
       echo" 	<TR><TD align='center'><font size=2>".$exibe['FUNCIONARIO']."</font></TD></TR>";
           
    }
    
    
    echo"</table></div> ";
    ?>  <img tabindex="0"  style=" max-width: 350px;" data-html="true"  src="img/help.png"data-toggle="popover" data-trigger="focus" title="REGRAS DE DESCONTO" data-content="
    <b>35%</b> - Desconto permitido para compras de 1 par de calçados para trabalho a ser utilizado exclusivamente pelo funcionário(a) a cada 90 dias nas formas de pagamento DINHEIRO ou PIX.
    <br>
    <b>20%</b> - Desconto permitido para compras de até 3 pares de calçados ou 3 unidades de produtos para o funcionário(a) ou sua família a cada 30 dias nas formas de pagamento DINHEIRO ou PIX.
    <br>
    <b>10%</b> - Desconto permitido para compras de até 3 pares de calçados ou 3 unidades de produtos para o funcionário(a) ou sua família a cada 30 dias em qualquer forma de pagamento.
    <br>
    <b>OBS:</b> - A Troca precisa ser realizada em separado no TIcket da Venda de novos produtos. Caso a troca realizada seja para outro modelo, irá constar como uma nova operação.
    O desconto a ser aplicado pelo gerente precisa ser inserido no caixa através do LINXPOS, ou seja, o funcionário precisa ter a venda lançada diretamente no caixa pelo gerente. 
    
    " id="image"></img><?php
    echo"<div  style='overflow: auto;' class='panel panel-primary' align='center' >
			<div  style='overflow: auto;' class='panel-heading' action='buscar.php' align='center'><h4>DESCONTOS</h4></div>
				<nav class='navbar navbar-light bg-light' align='center'>
					<TABLE ALIGN='center'  class='table table-bordered table-hover'>
					<thead>
						<TR  style='background-color:   #EBEBEB ;'>
               <th scope='col' class='col-md-1'><div align='center'><h5><b/>DESCONTO</h5></div></th>
							 <th scope='col' class='col-md-3'><div align='center'><h5><b/>RESULTADO</h5></div></th>
						</TR>
					</thead>";
    
    $next_result = sqlsrv_next_result($stmtAC2);
    while($exibe = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)){
        echo"<TR>
              "; ?>  <TD align="center" data-toggle="modal" data-backdrop="static" data-keyboard="false" 
             
              onClick="window.open('detalhar-desconto-funcionario.php?desconto=<?php ECHO $exibe['DESCONTO']?>&cpf=<?php echo $DOCUMENTO ?>',
 '_blank'); return false;"     >
              <div  data-toggle="tooltip" data-placement="top" title="Clique para detalhar" id="triangulo-para-esquerda"></div>
              <font size=2><?php echo $exibe['DESCONTO']?></font></TD>
            <?
        echo" <TD align='center'><font size=2>". utf8_encode($exibe['RESULTADO'])."</font></TD>
            </TR>";
            
     
        }
        
  $porcento = '35%';
   ?>    
</div>
</div>
</div>
</div>
</div>
</div>
    </nav>
    </nav>

     <!-- <a tabindex="0" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?">Dismissible popover</a>       -->
     
       <div  class="modal " data-backdrop="false"  id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">DETALHES DO DESCONTO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <h6>35% - </h6> 

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
   <?
    
    
    }
    ?>
           <style type="text/css">

               
               #image {
    width:30px;
    height:30px;
    position:absolute;
    top:60px;
    left:6%;
    margin-top:400px;
    margin-right:270px;
}
#visualizar{  
    
    width:60%;
    height:40%;
 
    margin-top:180px;
    /* //margin-left:1000px; */
    margin-right:900px;

}
/* .visualizar{  
    top:60px;
    left:6%;
    margin-top:-50px;
    margin-left:-50px;
    background-color: #004000;

} */
#triangulo-para-esquerda {
	/* width: 20px;
	height: 20px;
	border-radius: 50%;    */
    align-items: right;
  float:right;
;
  border-right:13px solid #004000 ; 
  border-bottom: 13px solid transparent; 
  border-right:13px solid #004000 ; 
}
            </style>
          <script>
              
$(function () {
  $('[data-toggle="popover"]').popover()
})
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
                        $.post('ajuda.php', dados, function(retorna) {
                            $("#visualiza").html(retorna);
                            $('#visualizar').modal('show');
                         
                        });
                    }
                });
            });
function funcao1()
{
var SendPesqUser = document.getElementById("SendPesqUser");	
}

function mascaraMutuario(o, f) {
    v_obj = o
    v_fun = f
    setTimeout('execmascara()', 1)
}

function execmascara() {
    v_obj.value = v_fun(v_obj.value)
}

function cpfCnpj(v) {

    //Remove tudo o que não é dígito
    v = v.replace(/\D/g, "")

    if (v.length <= 13) { //CPF

        //Coloca um ponto entre o terceiro e o quarto dígitos
        v = v.replace(/(\d{3})(\d)/, "$1.$2")

        //Coloca um ponto entre o terceiro e o quarto dígitos
        //de novo (para o segundo bloco de números)
        v = v.replace(/(\d{3})(\d)/, "$1.$2")

        //Coloca um hífen entre o terceiro e o quarto dígitos
        v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")

    } else { //CNPJ

        //Coloca ponto entre o segundo e o terceiro dígitos
        v = v.replace(/^(\d{2})(\d)/, "$1.$2")

        //Coloca ponto entre o quinto e o sexto dígitos
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")

        //Coloca uma barra entre o oitavo e o nono dígitos
        v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")

        //Coloca um hífen depois do bloco de quatro dígitos
        v = v.replace(/(\d{4})(\d)/, "$1-$2")


    }

    return v
}
$('#motivo').on('keypress', function() {
      var regex = new RegExp("^[a-zA-Z0-9-Zàèìòùáéíóúâêîôûãõ\b]+$");
      var _this = this;
      // Curta pausa para esperar colar para completar
      setTimeout( function(){
          var texto = $(_this).val();
          if(!regex.test(texto))
          {
              $(_this).val(texto.substring(0, (texto.length-1)))
          }
      }, 100);
  });
</script>


    </div>
</div>

<?
// } else {
//     echo "<script language='javascript' type='text/javascript'>
// 						alert('Login e/ou senha incorretos');window.location
// 						.href='logindde.html';</script>";
//     die();
// }
?>