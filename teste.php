<? /*



// FIM //
?>
<br>
<div  class="col-md-2" align="center"></div>
<div  class="col-md-8" align="center">
<div class="panel panel-success" align="center" >
<div class="panel-heading"  align="center" action="buscar.php">
<h4>PREENCHA OS CAMPOS PARA PESQUISAR</h4></div>
<nav class="navbar navbar-light bg-light" >
	<form action="" method="post" name="form1" >
		<h3 style="color: red;"></h3>
			<div align="center" class="col-md-6" >
				<div align="center" class="col-md-6" >
					<label><h5>DATA:</h5></label><br>
					<input required="required" id="DATA" name="DATA" class="form-control form-control-sm" onblur="funcao1();" type="date"  placeholder="" aria-label="Search"/> 
					</div></div>
		<div align="center" class="col-md-4" >
		<div class="form-group">
		<?include "listaLoja.php"; ?>
		<label  for="COD_FILIAL"><h5>ESTABELECIMENTO:</h5></label><br>
		<select required name="COD_FILIAL" class="form-control" id="COD_FILIAL">
			<option>Selecione...</option>
				<?php 
					while($row_estab = sqlsrv_fetch_array($querySelect)){
				?>
						<option required type="text" name="COD_FILIAL" value="<? echo $row_estab['COD_FILIAL']?>"><? echo $row_estab['filial']?></option>
				<? 
			} ?>
		</select>
		</div>
		</div>	

		<div  class="col-md-9" align="center">
		<button onclick="window.location.href='index.php';" class='btn btn-success' type="reset" title="Limpar" name="limpar" id=""  value="Limpar" href='http://110.100.2.9/tesouraria'>LIMPAR</button>
		<INPUT class='btn btn-success' title="PESQUISA POR PDV/CUPOM " name="SendPesqUser" id="SendPesqUser"  onblur="funcao1();" align="right"  value="PESQUISAR" type='submit'></input></div>
		<div class="col-md-2" > <button onclick="window.location.href='index.php';" class='btn btn-danger' onclick="deslogar()">SAIR</button>
		
</div>				
	</form>	<input name="Sair" type="button" id="button4" onclick="location.href = '//110.100.2.9/intranet'" value="Sair" />
</div>



<script languege=javascript>

function deslogar(){
    $.post('/', {
        sair:'sair'
    }, function(data){
       if (window.location.origin == undefined)
          window.location.origin = '//110.100.2.9/tesouraria' + window.location.host;
       document.location.href = window.location.origin;
    }, function(x,y,z){
       alert('Não foi possível sair');
       console.log(x,y,z);
    });
}


function funcao1()
{
var COD_FILIAL = document.getElementById("COD_FILIAL");	
var DATA = document.getElementById("DATA");	
var SendPesqUser= document.getElementById("SendPesqUser");
var a= document.getElementById("COD_FILIAL");

</script>

	<? @$a = $COD_FILIAL;
	@$a=$_POST['COD_FILIAL'] ;
 
	SWITCH($a){
	case '000203':
    	include 'conect/conlj3.php';
		break;
	case '000205':
	    include 'conect/conlj5.php';
			break;
	case '000209':
	    include 'conect/conlj9.php';
			break;
	case '000210':
	    include 'conect/conlj10.php';
    	break;
	case '000211':
	    include 'conect/conlj11.php';
		break;
	case '000213':
	    include 'conect/conlj13.php';
		break;
	case '000214':
	    include 'conect/conlj14.php';
		break;
	case '000215':
	    include 'conect/conlj15.php';
		break;
	case '000217':
	    include 'conect/conlj17.php';
		break;
	case '000218':
	    include 'conect/conlj18.php';
		break;
	case '000219':
	    include 'conect/conlj19.php';
		break;
	case '000220':
	    include 'conect/conlj20.php';
		break;
	case '000221':
	    include 'conect/conlj21.php';
		break;
	case '000223':
	    include 'conect/conlj23.php';
		break;
	case '000224':
	    include 'conect/conlj24.php';
		break;
	case '000226':
	    include 'conect/conlj26.php';
		break;
	case '000227':
	    include 'conect/conlj27.php';
		break;
	case '000228':
	    include 'conect/conlj28.php';
		break;
	case '000229':
	    include 'conect/conlj29.php';
		break;
	case '000230':
	    include 'conect/conlj30php';
		break;
	case '000231':
	    include 'conect/conlj31.php';
		break;
	case '000232':
	    include 'conect/conlj32.php';		
		break;
	case '000233':
	    include 'conect/conlj33.php';
		break;
	case '000234':
	    include 'conect/conlj34.php';
		break;
	case '000235':
	    include 'conect/conlj35.php';
		break;
	case '000237':
	    include 'conect/conlj37.php';
		break;
	case '000238':
	    include 'conect/conlj38.php';
		break;
	case '000239':
	    include 'conect/conlj39.php';
		break;
	case '000240':
	    include 'conect/conlj40.php';

		break;
	case '000241':
	    include 'conect/conlj41.php';
		break;
	case '000242':
	    include 'conect/conlj42.php';
		break;
	case '000243':
	    include 'conect/conlj43.php';
		break;
	case '000244':
	    include 'conect/conlj44.php';
		break;
	case '000303':
	    include 'conect/conlJMA3.php';
		break;
	case '000304':
	    include 'conect/conlJMA4.php';
		break;
	case '000305':
	    include 'conect/conlJMA5.php';
		break;
	case '000306':
	    include 'conect/conlJMA6.php';
		break;
	case '000307':
	    include 'conect/conlJMA7.php';
		break;
	case '000308':
	    include 'conect/conlJMA8.php';
		break;
	case '000309':
	    include 'conect/conlJMA9.php';
		break;
	case '000310':
	    include 'conect/conlJMA10.php';
		break;
	case '000311':
	    include 'conect/conlJMA11.php';
		break;		
	case '000312':
	    include 'conect/conlJMA12.php';
		break;	
	case '000313':
	    include 'conect/conlJMA13.php';
		break;	
	case '000314':
	    include 'conect/conlJMA14.php';
		break;
	case '000315':
	    include 'conect/conlJMA15.php';
		break;	
	case '000316':
	    include 'conect/conlJMA16.php'; // NÃO BUSCA//
		break;	
	case '000502':
	    include 'conect/conlJMF2.php';
		break;		
	case '000506':
	    include 'conect/conlJMF6.php';
		break;	
	case '000507':
	    include 'conect/conlJMF7.php';
		break;	
	case '000508':
	    include 'conect/conlJMF8.php';
		break;	
	case '000509':
	    include 'conect/conlJMF9.php';
		break;			
	case '000510':
	    include 'conect/conlJMF10.php';
		break;	
	case '000402':
	    include 'conect/conlJTK2.php';
		break;	
	case '000403':
	    include 'conect/conlJTK3.php';
		break;	
	case '000405':
	    include 'conect/conlJTK5.php';
		break;	
	case '000406':
	    include 'conect/conlJTK6.php';
		break;	


}

?>

 <!-- aqui começa a primeira parte do painel -->

<div class="panel panel-danger">
  <div class="panel-body"><h4>VALOR TOTAL DOS PEDIDOS FECHADOS NA LOJA</h4></div>
  <div class="panel-footer">
  	<?
	
	@$a = $COD_FILIAL;
	@$a = $_POST['COD_FILIAL'] ;
	@$DATA = date('Ymd', strtotime($_POST['DATA']));
	@$DATA= date('Ymd', strtotime($_GET['DATA']));
	@$COD_FILIAL=$_get['COD_FILIAL'] ;
$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
	if($SendPesqUser){
	$DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
   $query2 = "
	declare @data date, @filial varchar(06)
		set @data = '".$DATA."'
		select	lp.codigo_filial_origem as 'FILIAL',
		convert(varchar,lp.data,103) as 'DATA',
		case lpp.TIPO_PGTO
			when '&' then
				'VALE PRESENTE'
			when 'A' then
				'CREDITO POS'
			when 'D' then
				'DINHEIRO'
			when 'E' then
				'DEBITO POS'
			when 'I' then
				'CREDITO TEF'
			when 'K' then
				'DEBITO TEF'
			when 'R' then
				'VALE PRODUTO'
			when 'V' then
				'VALE CLIENTE'
			else
				'OUTRAS'
		end as 'TIPO_PAGAMENTO',
		format(SUM(lpp.valor), 'C', 'pt-br') as 'VALOR_PEDIDO_FECHADO'
from loja_pedido lp
left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
where lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
and lp.DATA = @data
group by lp.codigo_filial_origem, lp.data,lpp.TIPO_PGTO
order by TIPO_PAGAMENTO
"; echo"
						
							<TABLE ALIGN='center' class='table table-bordered'>
							<TR>
							<th align='center'><div align='center' ><h5>FILIAL</h5></div></th>
							<th align='center'><div align='center'><h5>DATA</h5></div></th>
							<th align='center'><div align='center'><h5>TIPO DE PAGAMENTO</h5></div></th>
							<th align='center'><div align='center' ><h5>VALOR PEDIDO FECHADO</h5></div></th>
					
						</TR>";
   
	@$stmtAC2 = Sqlsrv_query($conn, $query2);

	while(@$item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)){
			
      echo "
	  <TR>
	  <TD align='center' class='col-md-1'><font size=2>".$item['FILIAL']."</font></TD> 
	  <TD align='center' class='col-md-1'><font size=2>".$item['DATA']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['TIPO_PAGAMENTO']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['VALOR_PEDIDO_FECHADO']."</font></TD>
	  </TR>" ;

	}}  
echo"</table>";
	?>  
</div>
</div>
<div class="panel panel-danger">
  <div class="panel-body"><h4>VALOR TOTAL DOS TICKETS PAGOS POR TIPO DE PAGAMENTO EMITIDOS PELO LINX<h4/></div>
  <div class="panel-footer">
  	<?

	@$a=$_POST['COD_FILIAL'] ;
	@$DATA=date('Ymd', strtotime($_POST['DATA']));
     
	
	
@$DATA= date('Ymd', strtotime($_GET['DATA']));
@$COD_FILIAL=$_get['COD_FILIAL'] ;
$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
	if($SendPesqUser){
	$DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
   $query = "
		declare @data date, @filial varchar(06)
		set @data = '".$DATA."'
		
		select	lv.codigo_filial as 'FILIAL',
		convert(varchar,lv.data_venda,103) as 'DATA',
		case lvp.TIPO_PGTO
			when '&' then
				'VALE PRESENTE'
			when 'A' then
				'CREDITO POS'
			when 'D' then
				'DINHEIRO'
			when 'E' then
				'DEBITO POS'
			when 'I' then
				'CREDITO TEF'
			when 'K' then
				'DEBITO TEF'
			when 'R' then
				'VALE PRODUTO'
			when 'V' then
				'VALE CLIENTE'
			else
				'OUTRAS'
		end as 'PAGAMENTO',
		format(sum(lvp.VALOR), 'C', 'pt-br') as 'VALOR_TICKET'
from loja_venda lv
inner join loja_venda_parcelas lvp on lvp.LANCAMENTO_CAIXA = lv.LANCAMENTO_CAIXA and lvp.TERMINAL = lv.terminal and lvp.CODIGO_FILIAL = lv.CODIGO_FILIAL
where lv.data_venda = @data and lv.VALOR_PAGO > 0 and lv.terminal <> '100'
group by lv.codigo_filial, lv.data_venda, lvp.TIPO_PGTO
";
   echo"
						
							<TABLE aling='center' class='table table-bordered'>
							<TR>
							<th align='center'><div align='center' ><h5>FILIAL</h5></div></th>
							<th align='center'><div align='center' ><h5>DATA</h5></div></th>
							<th align='center'><div align='center' ><h5>PAGAMENTO</h5></div></th>
							<th align='center'><div align='center' ><h5>VALOR TICKET</h5></div></th>
					
						</TR>";
	@$stmtAC = Sqlsrv_query($conn, $query);
	

	while(@$item = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)){
			
      echo "
	  <TR>
	  <TD align='center' class='col-md-1'><font size=2>".$item['FILIAL']."</font></TD> 
	  <TD align='center' class='col-md-1'><font size=2>".$item['DATA']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['PAGAMENTO']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['VALOR_TICKET']."</font></TD>
	  </TR>" ;

	}}  
echo"</table>";

   
		
	?>  
</div>
</div>
<!-- ------------PARTE 3 ----------------------->
<div class="panel panel-danger">
  <div class="panel-body"><h4>DIFERENÇA NAS PARCELAS DE PAGAMENTO COM A VENDA</h4></div>
  <div class="panel-footer">
  	<?
	

@$COD_FILIAL=$_get['COD_FILIAL'] ;
@$DATA= date('Ymd', strtotime($_GET['DATA']));
$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
	if($SendPesqUser){
	$DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
   $query = "
   	declare @data date, @filial varchar(06)
		set @data = '".$DATA."'
		
		select lv.CODIGO_FILIAL as 'FILIAL',
             convert(varchar,lv.DATA_VENDA,103) as 'DATA',
             lv.TICKET as 'TICKET',
             lv.valor_pago as 'VALOR_TICKET',
             (lv.valor_pago - sum(lvp.valor)) as 'DIFERENCA'
from loja_venda lv
left join loja_venda_parcelas lvp on lvp.LANCAMENTO_CAIXA = lv.LANCAMENTO_CAIXA and lvp.TERMINAL = lv.TERMINAL
where lv.DATA_VENDA = @data
group by lv.CODIGO_FILIAL, lv.DATA_VENDA, lv.ticket, lv.valor_pago
having lv.valor_pago <> sum(lvp.valor)
union all
select lv.CODIGO_FILIAL as 'FILIAL',
             convert(varchar,lv.DATA_VENDA,103) as 'DATA',
             lv.TICKET as 'TICKET',
             lv.valor_pago as 'VALOR_TICKET',
             lv.valor_pago as 'DIFERENCA'
from loja_venda lv
where lv.DATA_VENDA = @data and lv.VALOR_PAGO > 0 and lv.LANCAMENTO_CAIXA not in (select LANCAMENTO_CAIXA from LOJA_VENDA_PARCELAS)
group by lv.CODIGO_FILIAL, lv.DATA_VENDA, lv.ticket, lv.valor_pago

";
echo"
						
							<TABLE ALIGN='center' class='table table-bordered'>
						<TR>
							<th align='center'><div align='center' ><h5>FILIAL</h5></div></th>
							<th align='center'><div align='center'><h5>DATA</h5></div></th>
							<th align='center'><div align='center'><h5>TICKET</h5></div></th>						
							<th align='center'><div align='center'><h5>VALOR DO TICKET</h5></div></th>						
							<th align='center'><div align='center'><h5>DIFERENCA</h5></div></th>
							
						</TR>";
   
	@$stmtAC = Sqlsrv_query($conn, $query) ;

	while(@$item = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC))
	{
		echo "
	  <TR>
	  <TD align='center' class='col-md-1'><font size=2>".$item['FILIAL']."</font></TD> 
	  <TD align='center' class='col-md-1'><font size=2>".$item['DATA']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['TICKET']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['VALOR TICKET']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['DIFERENCA']."</font></TD>

	  </TR>" ;

	}}  
echo"</table>"; ?> 
</div>
</div>
<!---------------------- PARTE 4 -------------------->
<div class="panel panel-danger">
  <div class="panel-body"><h4>DIFERENÇA TOTAL DSK COM A RETAGUARDA</h4></div>
  <div class="panel-footer">
  	<?
	@$a = $COD_FILIAL;
	@$a = $_POST['COD_FILIAL'] ;
	@$DATA = date('Ymd', strtotime($_POST['DATA']));
	@$DATA= date('Ymd', strtotime($_GET['DATA']));
	@$COD_FILIAL=$_GET['COD_FILIAL'] ;
	$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
if($SendPesqUser){

	$DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);

$Abrindo= "

BEGIN

IF (EXISTS(select * from sys.servers where name = '110.100.50.89,1440'))
	EXEC sp_dropserver '110.100.50.89,1440', 'droplogins'
end
begin
-- Criar o servidor vinculado
EXEC sp_addlinkedserver  
@server='110.100.50.89,1440',
@srvproduct='SQL Server'       

-- Criar o login para o servidor vinculado
EXEC sp_addlinkedsrvlogin  
@rmtsrvname = '110.100.50.89,1440',  
@useself = 'false',  
@rmtuser = 'linx',             -- add here your login on Azure DB  
@rmtpassword = 'Poly@123' -- add here your password on Azure DB 

END

";

$query1= "
BEGIN

declare @data date, @filial varchar(06)

set @data = '".$DATA."'  	
set @filial = '".$a."'		


select	t.filial as 'FILIAL',
		t.data as 'DATA',
		format(SUM(t.valor_pedido_fechado), 'C', 'pt-br') as 'DIFERENÇA'
from
(select	lp.codigo_filial_origem as 'FILIAL',
		convert(varchar,lp.data,103) as 'DATA',
		concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) as 'PEDIDO',
		lpp.valor as 'VALOR_PEDIDO_FECHADO'
from loja_pedido lp
left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
where lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
and lp.DATA = @data and not exists(select	lvr.codigo_filial,
											convert(varchar,lvr.data_venda,103),
											lvr.ticket,
											lvr.valor_pago 
								   from [110.100.50.89,1440].LINX_Prod.dbo.loja_venda lvr
				                   where lp.codigo_filial_origem = lvr.codigo_filial collate Latin1_General_CI_AS and lp.data = lvr.data_venda 
								   and concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) = lvr.ticket collate Latin1_General_CI_AS
				                   and lvr.codigo_filial = @filial and lvr.data_venda = @data)) t
group by t.FILIAL, t.data 

end 
";
$QUERY2 = "
BEGIN
declare @data date, @filial varchar(06)

set @data = '".$DATA."'  	
set @filial = '".$a."'		
-- APRESENTA TODOS OS PEDIDOS DSK FECHADOS NA LOJA NÃO ENCONTRADOS NA RETAGUARDA
select	lp.codigo_filial_origem as 'FILIAL',
		convert(varchar,lp.data,103) as 'DATA',
		concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) as 'PEDIDO',
		format(lpp.valor, 'C', 'pt-br') as 'valor_pedido_fechado'
from loja_pedido lp
left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
where lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
and lp.DATA = @data and not exists(select	lvr.codigo_filial,
											convert(varchar,lvr.data_venda,103),
											lvr.ticket,
											lvr.valor_pago 
								   from [110.100.50.89,1440].LINX_Prod.dbo.loja_venda lvr
				                   where lp.codigo_filial_origem = lvr.codigo_filial collate Latin1_General_CI_AS and lp.data = lvr.data_venda 
								   and concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) = lvr.ticket collate Latin1_General_CI_AS
				                   and lvr.codigo_filial = @filial and lvr.data_venda = @data)
END
begin
IF (EXISTS(select * from sys.servers where name = '110.100.50.89,1440'))
	EXEC sp_dropserver '110.100.50.89,1440', 'droplogins'
end

";

		$SV = Sqlsrv_query($conn, $Abrindo);
		$item = sqlsrv_fetch_array($SV, SQLSRV_FETCH_ASSOC);


echo"
	<TABLE ALIGN='center' class='table table-bordered'>
		<TR>
			<th align='center'><div align='center'><h5>FILIAL</h5></div></th>
			<th align='center'><div align='center'><h5>DATA</h5></div></th>
			<th align='center'><div align='center'><h5>DIFERENÇA</h5></div></th>						
		</TR>";
  
 	

	$stmt = Sqlsrv_query($conn, $query1);
	print_r(sqlsrv_errors());
	while(@$item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{
		
		echo "
			<TR>
				<TD align='center' class='col-md-1'><font size=2>".$item['FILIAL']."</font></TD> 
				<TD align='center' class='col-md-1'><font size=2>".$item['DATA']."</font></TD>
				<TD align='center' class='col-md-1'><font size=2>".$item['DIFERENÇA']."</font></TD>
				
			</TR>" ;

	  
}
echo"</table>"; ?> 

<div class="panel panel-danger">
<div class="panel-body"><h4>DETALHAMENTO DSK COM A RETAGUARDA</h4></div>
  <div class="panel-footer"> 
   <? 
   echo" <TABLE ALIGN='center' class='table table-bordered'>
			<TR>
				<th align='center'><div align='center'><h5>FILIAL</h5></div></th>
				<th align='center'><div align='center'><h5>DATA</h5></div></th>
				<th align='center'><div align='center'><h5>PEDIDO</h5></div></th>						
				<th align='center'><div align='center'><h5>VALOR PEDIDO FECHADO</h5></div></th>						
			</TR>";
    $stmt = Sqlsrv_query($conn, $QUERY2) ;

	while($item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{
		echo "
		    <TR>
			  <TD align='center' class='col-md-1'><font size=2>".$item['FILIAL']."</font></TD> 
			  <TD align='center' class='col-md-1'><font size=2>".$item['DATA']."</font></TD>
			 
			  <TD align='center' class='col-md-1'><font size=2>".$item['PEDIDO']."</font></TD>
			  <TD align='center' class='col-md-1'><font size=2>".$item['valor_pedido_fechado']."</font></TD>
		    </TR>" ;

	}
	} 
  echo"</table>";
   ?>
  </div> </div>
</div>
</div>


*/?>
<?PHP

include "visual.html";
include "teste3.php";

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();
?> 
	<div  class="col-md-12" align="center" id="central"> <?
		"</BR>";
		 echo " LISTA DE LOJAS: </br>";
		//echo $user_ip;

		// aqui começa a decisao //

		 switch($user_ip){

		 case '110.100.7.2':
				?><BR>	<form action="teste2.php" name="form1" method="post"><INPUT class='btn btn-success' title="PESQUISA POR PDV/CUPOM "  type='submit' value="110.100.7.2" type='lj40'></input><input type="hidden" value="110.100.7.2" name="lj40"></form><?
							
		 break;
		 case '110.100.72.2':
				echo "LOJA ERRADA";
		 
		 break;
		 case '110.100.2.':
				echo "LOJA ERRADA";
		 
		 break;
		
	}
?> </div>




