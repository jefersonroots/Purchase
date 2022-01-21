

<meta name="language" content="pt-br" />
<?php
$dt_inicio= date('Ymd', strtotime($_GET['dt_inicio']));
$dt_final= date('Ymd', strtotime($_GET['dt_final']));
$COD_FILIAL= $_GET['COD_FILIAL'];

include "ConectLoja.php";    

		echo "<h1><center><br><br>Data de Consulta  - " .date('d/m/Y', strtotime($_GET['dt_inicio']))." a ".date('d/m/Y', strtotime($_GET['dt_final']))."</center></h1>";

if ($conn==false){
	echo "<b><font color='#FF0000'> Sem conexão com o BD.</font></b><br><br>";
} else {
    $data = date('Y-m-d');
	$sql = sqlsrv_query($conn,"
	declare @dataini date, @datafim date, @filial varchar(06)
	set @dataini = '".$dt_inicio."'		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
	set @datafim = '".$dt_final."'			-- VARIÁVEL PARA ESCOLHA DA DATA FINAL
	set @filial = '".$COD_FILIAL."'				-- VARIÁVEL PARA ESCOLHA DA LOJA


--INICIO SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR+TAMANHO
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 1
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_1 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 2
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_2 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 3
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_3 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 4
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_4 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 5
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_5 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 6
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_6 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 7
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_7 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 8
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_8 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 9
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_9 > 0	and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 10
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_10 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 11
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_11 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 12
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_12 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 13
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_13 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 14
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_14 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 15
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_15 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and p1e.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 16
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_16 > 0 and len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) --ATENÇÃO POSICAO PARA QUANTIDADE	--ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR+TAMANHO


--INICIO SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR+TAMANHO COM REFERENCIA_ITEM (COR+TAMANHO)
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 1
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_1 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 2
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_2 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 3
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_3 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 4
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_4 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 5
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_5 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 6
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_6 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 7
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_7 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2)and ei.SUB_ITEM_TAMANHO = 8
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_8 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 9
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_9 > 0	and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 10
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_10 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 11
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_11 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 12
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_12 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 13
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_13 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 14
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_14 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 15
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_15 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(p1e.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 16
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_16 > 0 and (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2) --ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR+TAMANHO COM REFERENCIA_ITEM (COR+TAMANHO)


--INICIO SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_1 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_2 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_3 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_4 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_5 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_6 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_7 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_8 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_9 > 0	and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_10 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_11 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_12 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_13 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_14 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_15 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = p1e.produto and pb.COR_PRODUTO = p1e.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		p1e.EN_16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12,5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/p1e.EN_16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join ESTOQUE_PROD_ENT pe on pe.FILIAL = e.FILIAL and pe.NF_ENTRADA = e.NF_ENTRADA and pe.NOME_CLIFOR = e.NOME_CLIFOR and e.SERIE_NF_ENTRADA = pe.SERIE_NF_ENTRADA
left join ESTOQUE_PROD1_ENT p1e on p1e.ROMANEIO_PRODUTO = pe.ROMANEIO_PRODUTO and p1e.FILIAL = pe.FILIAL and p1e.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(p1e.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = p1e.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = p1e.PRODUTO and pc.COR_PRODUTO = p1e.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and e.COD_TRANSACAO = 'ENTRADAS_102' and p1e.EN_16 > 0 and (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM PEDIDO UTILIZANDO PRODUTO+COR


--INICIO SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR+TAMANHO
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5), 12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 1
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN1 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 2
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN2 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))))) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 3
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN3 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 4
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN4 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 5
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN5 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 6
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN6 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 7
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN7 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 8
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN8 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 9
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN9 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 10
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN10 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 11
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN11 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 12
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN12 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 13
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN13 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 14
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN14 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 15
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN15 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and lep.COR_PRODUTO = ei.REFERENCIA_ITEM and ei.SUB_ITEM_TAMANHO = 16
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN16 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) > len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))))	--ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR+TAMANHO


--INICIO SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR+TAMANHO COM REFERENCIA_ITEM (COR+TAMANHO)
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5), 12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 1
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN1 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 2
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN2 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 3
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN3 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 4
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN4 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 5
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN5 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 6
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN6 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 7
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN7 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 8
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN8 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 9
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN9 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 10
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN10 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 11
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN11 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 12
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN12 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 13
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN13 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 14
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN14 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 15
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN15 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and left(ltrim(rtrim(lep.COR_PRODUTO)),2) = left(ltrim(rtrim(ei.REFERENCIA_ITEM)),2) and ei.SUB_ITEM_TAMANHO = 16
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN16 > 0 AND (len(rtrim(ei.CODIGO_ITEM)) = len(concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM)))) and LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) > 2)	--ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR+TAMANHO COM REFERENCIA_ITEM (COR+TAMANHO)


--INICIO SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 1  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN1 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5), 12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN1,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN1 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 2  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN2 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN2,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA  and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN2 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 3  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN3 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN3,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN3 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 4  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN4 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN4,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN4 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 5  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN5 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN5,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN5 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 6  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN6 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN6,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN6 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 7  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN7 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN7,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN7 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 8  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN8 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN8,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN8 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 9  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN9 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN9,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN9 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 10  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN10 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN10,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN10 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 11  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN11 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN11,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN11 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 12  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN12 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN12,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN12 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 13  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN13 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN13,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN13 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 14  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN14 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN14,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN14 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 15  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN15 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN15,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN15 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
UNION ALL
select	E.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,e.RECEBIMENTO,103) as 'DATA',
		e.nf_entrada as 'NOTA_FISCAL',
		e.SERIE_NF_ENTRADA as 'SERIE',
		e.CHAVE_NFE as 'CHAVE',
		E.VALOR_SUB_ITENS as 'SUBTOTAL',
		E.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		e.DESCONTO,
		e.VALOR_TOTAL,
		ei.ITEM_IMPRESSAO as 'SEQ',
		ei.REFERENCIA,
		(select DISTINCT MAX(CONCAT(rtrim(pb.produto),rtrim(pb.cor_produto),rtrim(pb.grade))) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16 AND pb.INATIVO = 0) AS 'SKU',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		CONCAT(RTRIM(p.desc_prod_nf),' ',RTRIM(pc.desc_cor_produto),' ',(select DISTINCT MAX(rtrim(pb.grade)) from PRODUTOS_BARRA pb where pb.PRODUTO = lep.produto and pb.COR_PRODUTO = lep.cor_produto and pb.TAMANHO = 16  AND pb.INATIVO = 0)) as 'DESCRICAO',	--ATENÇÃO TAMANHO DE ACORDO COM A GRADE
		lep.EN16 as 'QTDE',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.UNIDADE,
		ei.PRECO_UNITARIO,
		str(round((E.VALOR_IMPOSTO_AGREGAR*(ei.porcentagem_item_rateio/100)),5),12, 5) as 'IMPOSTO_AGREGAR',
		str(round(ei.VALOR_DESCONTOS/lep.EN16,5),12,5) as 'DESCONTO',	--ATENÇÃO POSICAO PARA QUANTIDADE
		ei.CODIGO_FISCAL_OPERACAO as 'CFOP'
from ENTRADAS e
inner join ENTRADAS_ITEM ei on ei.NF_ENTRADA = e.NF_ENTRADA and e.NOME_CLIFOR = ei.NOME_CLIFOR and ei.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
inner join FILIAIS f on f.FILIAL = e.FILIAL
left join LOJA_ENTRADAS le on le.FILIAL = e.FILIAL and le.NUMERO_NF_TRANSFERENCIA = e.NF_ENTRADA and le.SERIE_NF_ENTRADA = e.SERIE_NF_ENTRADA
left join LOJA_ENTRADAS_PRODUTO lep on lep.ROMANEIO_PRODUTO = le.ROMANEIO_PRODUTO and lep.FILIAL = le.FILIAL and lep.PRODUTO = ei.REFERENCIA and (rtrim(ltrim(lep.COR_PRODUTO)) = rtrim(ltrim(ei.REFERENCIA_ITEM)) or rtrim(ltrim(ei.REFERENCIA_ITEM)) = '' or ei.REFERENCIA_ITEM is null)
left join PRODUTOS p on p.PRODUTO = lep.PRODUTO
left join PRODUTO_CORES pc on pc.PRODUTO = lep.PRODUTO and pc.COR_PRODUTO = lep.COR_PRODUTO
where e.recebimento between @dataini and @datafim and f.COD_FILIAL = @filial
and e.TIPO_ENTRADAS = 'PRODUTOS ACABADOS' and (e.COD_TRANSACAO = 'ENTRADAS_112' OR e.COD_TRANSACAO = 'ENTRADAS_109') and e.DATA_CANCELAMENTO is null and lep.EN16 > 0 AND (concat(rtrim(ltrim(ei.REFERENCIA)),rtrim(ltrim(ei.REFERENCIA_ITEM))) = rtrim(ei.CODIGO_ITEM) and (LEN(rtrim(ltrim(ei.REFERENCIA_ITEM))) <= 2 or ei.REFERENCIA_ITEM is null)) --ATENÇÃO POSICAO PARA QUANTIDADE
--FIM SPED MANUAL PARA ENTRADA COM ROMANEIO DA LOJA COM ENTRADA POR PRODUTO+COR


--INICIO SPED MANUAL PARA DEVOLUÇÕES DE VENDA
UNION ALL
SELECT	f.FILIAL as 'LOJA',
		f.CGC_CPF as 'CNPJ',
		convert(varchar,nf.EMISSAO,103) as 'DATA',
		nf.NF_NUMERO as 'NOTA_FISCAL',
		nf.SERIE_NF as 'SERIE',
		nf.CHAVE_NFE as 'CHAVE',
		nf.VALOR_TOTAL_ITENS as 'SUBTOTAL',
		nf.VALOR_IMPOSTO_AGREGAR AS 'IMPOSTO_AGREGAR',
		nf.DESCONTO,
		nf.VALOR_TOTAL,
		nfi.ITEM_IMPRESSAO as 'SEQ',
		nfi.REFERENCIA,
		nfi.CODIGO_ITEM AS 'SKU',
		nfi.DESCRICAO_ITEM as 'DESCRICAO',
		nfi.QTDE_ITEM as 'QTDE',
		nfi.UNIDADE,
		nfi.PRECO_UNITARIO,
		nfi.VALOR_ENCARGOS as 'IMPOSTO_AGREGAR',
		nfi.VALOR_DESCONTOS as 'DESCONTO',
		nfi.CODIGO_FISCAL_OPERACAO as 'CFOP'
FROM LOJA_NOTA_FISCAL nf
inner join LOJA_NOTA_FISCAL_ITEM nfi on nfi.CODIGO_FILIAL = nf.CODIGO_FILIAL and nfi.NF_NUMERO = nf.NF_NUMERO and nfi.SERIE_NF = nf.SERIE_NF
inner join FILIAIS f on f.COD_FILIAL = nf.CODIGO_FILIAL
WHERE nf.CODIGO_FILIAL = @filial and nf.EMISSAO between @dataini and @datafim
AND RECEBIMENTO = 1 and DATA_CANCELAMENTO is null
--FIM SPED MANUAL PARA DEVOLUÇÕES DE VENDA

order by LOJA,DATA,NOTA_FISCAL,SEQ,SKU

");
if($dt_inicio>$dt_final){
		echo "<h2 style='color:red'><center><br>Favor, confira o período e selecione datas válidas.<br>"; 
	echo "(data inicial sempre será MENOR que data final !)<br><br></h2></center>";
	echo"<center><input type='button'  class='btn btn-warning' value='Voltar' onClick='history.go(-1)'></center>";
}else{
	// Definimos o nome do arquivo que será exportado
	$arquivo = "SPED_MANUAL_EXCEL_FILIAL-$COD_FILIAL-$dt_inicio-$dt_final.xls";
		// Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo	

if ($sql==0){
	echo "<center>Favor, confira o período e selecione datas válidas.<br><br></center>";
}else{
	print "<TABLE BORDER=1><TR><TH>LOJA</TH><TH>CNPJ</TH><TH>DATA</TH><TH>NOTA FISCAL</TH><TH>SERIE</TH><TH>CHAVE</TH><TH>SUBTOTAL</TH><TH>IMPOSTO AGREGAR</TH><TH>DESCONTO</TH><TH>VALOR TOTAL</TH><TH>SEQ</TH><TH>REFERENCIA</TH><TH>SKU</TH><TH>DESCRICAO</TH><TH>QTDE</TH><TH>UNIDADE</TH><TH>PRECO UNITARIO</TH><TH>IMPOSTO AGREGAR</TH><TH>DESCONTO</TH><TH>CFOP</TH></TR>";
	while($exibe = sqlsrv_fetch_array($sql)){	
		$html = "<TR>
		<TD align='center'><font size=1>".$exibe['LOJA']."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe[(string) 'CNPJ'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".$exibe['DATA']."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe[(string) 'NOTA_FISCAL'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['SERIE'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['CHAVE'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".str_replace(".",",",$exibe['SUBTOTAL'])."</font></TD>
		<TD align='center'><font size=1>".str_replace(".",",",$exibe['IMPOSTO_AGREGAR'])."</font></TD>
		<TD align='center'><font size=1>".str_replace(".",",",$exibe['DESCONTO'])."</font></TD>
		<TD align='center'><font size=1>".str_replace(".",",",$exibe['VALOR_TOTAL'])."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['SEQ'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['REFERENCIA'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['SKU'].str_repeat('"',1)."</font></TD>
		<TD align='center'><font size=1>".$exibe['DESCRICAO']."</font></TD>
		<TD align='center'><font size=1>".$exibe['QTDE']."</font></TD>
		<TD align='center'><font size=1>".$exibe['UNIDADE']."</font></TD>
		<TD align='center'><font size=1>".str_replace(".",",",$exibe['PRECO_UNITARIO'])."</font></TD>
		<TD align='center'><font size=1>". str_replace(".",",",$exibe['IMPOSTO_AGREGAR'])."</font></TD>
		<TD align='center'><font size=1>". str_replace(".",",",$exibe['DESCONTO'])."</font></TD>
		<TD align='center'><font size=1>".str_repeat('="',1).$exibe['CFOP'].str_repeat('"',1)."</font></TD>
		
		"
		;
		printf($html);
		
	}
		
}
	sqlsrv_close($conn);
	
}
}
?>
