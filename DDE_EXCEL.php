<?php
 @$DATAINICIO= date('Ymd', strtotime($_POST['DATAINICIO']));
 @$DATAFIM= date('Ymd', strtotime($_POST['DATAFIM']));
     @$LINHA = $_POST['RECEBE_LINHA'];
     @$FILIAL = $_POST['FILIAL'];
     @$MARCA = $_POST['MARCA'];
     @$TIPO = $_POST['TIPO'];
     @$PRODUTO = $_POST['PRODUTO'];
     @$FABRICANTE = $_POST['FABRICANTE'];
     @$LOJA_FILTRO = $_POST['LOJA_FILTRO'];
     @$PRODUTO_FILTRO = $_POST['PRODUTO_FILTRO'];
     @$GRUPO_FILTRO = $_POST['GRUPO_FILTRO'];

     echo @$GRUPO_PRODUTO = $_POST['GRUPO_PRODUTO'];
     
     
     @$SUBGRUPO_PRODUTO= $_POST['SUBGRUPO'];
     @$CATEGORIA_PRODUTO= $_POST['CATEGORIA'];


     @$SUBCATEGORIA_PRODUTO= $_POST['SUBCATEGORIA'];

     @$tipofilial1 = $_POST['tipofilial1'];
     @$tipofilial2 = $_POST['tipofilial2'];
     @$tipofilial3 = $_POST['tipofilial3'];
     @$tipofilial4 = $_POST['tipofilial4'];
     @$tipofilial5 = $_POST['tipofilial5'];
     @$CUSTO = $_POST['CUSTO'];

     if( @$_POST['GRUPO_PRODUTO'] == '' ){
           $GRUPO_PRODUTO = @$_POST['GRUPO_PRODUTO_ID'];
     }
     if( @$_POST['SUBCATEGORIA'] == '' ){
         $SUBCATEGORIA_PRODUTO = @$_POST['SUBCATEGORIA_ID'];
    } if( @$_POST['SUBGRUPO'] == '' ){
           $SUBGRUPO_PRODUTO = @$_POST['SUBGRUPO_ID'];
      }
      $vowels = '-';
       $GRUPO_PRODUTO_TRATADO = str_replace($vowels,' ',$GRUPO_PRODUTO);
     $GRUPO_PRODUTO_TRATADO_DIRETA =  rtrim($GRUPO_PRODUTO_TRATADO); '<BR>';



   include "listaloja.php";
if ($conn1==false){
	echo "<b><font color='#FF0000'> Sem conexao com o BD.</font></b><br><br>";
} else {
 if($LOJA_FILTRO ==''){
    
     $LOJA_FILTRO = 'N';
  
 } if($PRODUTO_FILTRO ==''){
    
    $PRODUTO_FILTRO = 'N';
 
}
if($GRUPO_FILTRO ==''){
    
    $GRUPO_FILTRO = 'N';
 
}

 $query ="
   SET NOCOUNT ON EXEC dbo.LX_POLYELLE_DDE 
 '".$DATAINICIO."',
 '".$DATAFIM."',
 '".$tipofilial1."',
 '".$tipofilial2."',
 '".$tipofilial3."',
 '".$tipofilial4."',
 '".$tipofilial5."',
 '".$LINHA."',
 '".$FILIAL."',
 '".$MARCA."',
 '".$FABRICANTE."',
 '".$TIPO."',
 '".$PRODUTO."',
 '".$GRUPO_PRODUTO_TRATADO_DIRETA."',
 '".$SUBGRUPO_PRODUTO."',
 '".$CATEGORIA_PRODUTO."',
 '".$SUBCATEGORIA_PRODUTO."',
 '".$LOJA_FILTRO."',
 '".$PRODUTO_FILTRO."',
 '".$GRUPO_FILTRO."',
 '".$CUSTO."'
 ";
       
    
		$arquivo = "DDE-$DATAINICIO-$DATAFIM.xls";
		//Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
	//	Envia o conteúdo do arquivo	
        $exec = sqlsrv_query($conn1, $query);  
        if ($exec==0){
         //   echo "Favor, confira o periodo e selecione datas validas.<br><br></center>";
        }else{
            print "<TABLE BORDER=1><TR>
  
            ";
            if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'N'){
                echo "
                <TH>LOJA</TH>
                <TH>PRODUTO</TH>
                <TH>DESCRICAO PRODUTO</TH>";
            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'N'&& $GRUPO_FILTRO== 'N'){ // SEM COLUNAS 

            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'S'&& $GRUPO_FILTRO== 'N'){ // APENAS A COLUNA DE LOJA
                echo "<TH>LOJA</TH>";

            }if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'N'&& $GRUPO_FILTRO== 'N'){ // APENAS A COLUNA PRODUTO E DESCRICAO
                echo"
                <TH>PRODUTO</TH>
                <TH>DESCRICAO PRODUTO</TH>";
            }
            if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'S'){
                echo "
                <TH>LOJA</TH>
                <TH>GRUPO</TH>
                <TH>SUBGRUPO</TH>
                <TH>CATEGORIA</TH>
                <TH>SUBCATEGORIA</TH>
               
                <TH>PRODUTO</TH>
                <TH>DESCRICAO PRODUTO</TH>         
                ";
            }
            if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'S'){ // SEM COLUNAS 
                ECHO "<TH>GRUPO</TH>
                <TH>SUBGRUPO</TH>
                <TH>CATEGORIA</TH>
                <TH>SUBCATEGORIA</TH>";
            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'S'){ // APENAS A COLUNA DE LOJA
                ECHO "
                <TH>LOJA</TH>
                <TH>GRUPO</TH>
                <TH>SUBGRUPO</TH>
                <TH>CATEGORIA</TH>
                <TH>SUBCATEGORIA</TH>
               
                ";

            }if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'S'){ // APENAS A COLUNA PRODUTO E DESCRICAO
                echo"   
                <TH>PRODUTO</TH>
                <TH>DESCRICAO PRODUTO</TH>
                <TH>GRUPO</TH>
                <TH>SUBGRUPO</TH>
                <TH>CATEGORIA</TH>
                <TH>SUBCATEGORIA</TH>
                ";
            }
            
            echo"
          
            <TH>LINHA</TH>
            <TH>MARCA</TH>
            <TH>FABRICANTE</TH>
            <TH>TIPO</TH>
            <TH>QTDE_ESTOQUE</TH>
            <TH>VALOR_ESTOQUE</TH>
            <TH>QTDE_VENDIDA</TH>
            <TH>VALOR_VENDA</TH>
            <TH>MARKUP</TH>
            <TH>VENDA_CUSTO</TH>
            <TH>DIAS</TH>
            <TH>D.D.E.</TH>

            </TR>";
            $exec = sqlsrv_query($conn1, $query);    
            
    // ECHO $query;
	
            while($exibe = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)){	
                // ECHO $query;
               $html = "<TR>"; 

               if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'S'&& $GRUPO_FILTRO== 'N' ){
                echo "
                <TD align='center'><font size=1>".$exibe['LOJA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['PRODUTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['DESCRICAO_PRODUTO']."</font></TD>";
            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'N'){ // SEM COLUNAS 

            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'N'){ // APENAS A COLUNA DE LOJA
             echo" <TD align='center'><font size=1>".$exibe['LOJA']."</font></TD>    ";

            }if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'N'){ // APENAS A COLUNA PRODUTO E DESCRICAO
                echo"    <TD align='center'><font size=1>".$exibe['PRODUTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['DESCRICAO_PRODUTO']."</font></TD>";
            }
//// se for sim 
            if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'S' ){
                echo "
                <TD align='center'><font size=1>".$exibe['LOJA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['GRUPO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['SUBGRUPO']."</font></TD
                <TD align='center'><font size=1>".$exibe['CATEGORIA']."</font></TD
                <TD align='center'><font size=1>".$exibe['SUBCATEGORIA']."</font></TD
              
                <TD align='center'><font size=1>".$exibe['PRODUTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['DESCRICAO_PRODUTO']."</font></TD>";
            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'S'){ // SEM COLUNAS 
                ECHO "<TD align='center'><font size=1>".$exibe['GRUPO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['SUBGRUPO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['CATEGORIA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['SUBCATEGORIA']."</font></TD>";
            }if($PRODUTO_FILTRO == 'N' && $LOJA_FILTRO== 'S' && $GRUPO_FILTRO== 'S'){ // APENAS A COLUNA DE LOJA
            echo"
            <TD align='center'><font size=1>".$exibe['LOJA']."</font></TD> 
            <TD align='center'><font size=1>".$exibe['GRUPO']."</font></TD>
            <TD align='center'><font size=1>".$exibe['SUBGRUPO']."</font></TD>
            <TD align='center'><font size=1>".$exibe['CATEGORIA']."</font></TD>
            <TD align='center'><font size=1>".$exibe['SUBCATEGORIA']."</font></TD>
              ";

            }if($PRODUTO_FILTRO == 'S' && $LOJA_FILTRO== 'N' && $GRUPO_FILTRO== 'S'){ // APENAS A COLUNA PRODUTO E DESCRICAO
                echo"    <TD align='center'><font size=1>".$exibe['PRODUTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['DESCRICAO_PRODUTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['GRUPO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['SUBGRUPO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['CATEGORIA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['SUBCATEGORIA']."</font></TD>";
            }









               echo "
             
                <TD align='center'><font size=1>".$exibe['LINHA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['MARCA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['FABRICANTE']."</font></TD>
                <TD align='center'><font size=1>".$exibe['TIPO']."</font></TD>
               <TD align='center'><font size=1>".$exibe['QTDE_ESTOQUE']."</font></TD>
                <TD align='center'><font size=1>".$exibe['VALOR_ESTOQUE']."</font></TD>
                <TD align='center'><font size=1>".$exibe['QTDE_VENDIDA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['VALOR_VENDA']."</font></TD>
                <TD align='center'><font size=1>".$exibe['MARKUP']."</font></TD>
                <TD align='center'><font size=1>".$exibe['VENDA_CUSTO']."</font></TD>
                <TD align='center'><font size=1>".$exibe['DIAS']."</font></TD>
                <TD align='center'><font size=1>".$exibe['D.D.E.']."</font></TD>
                
                "
                ;
                printf($html);
                
            }
                
        }
}

