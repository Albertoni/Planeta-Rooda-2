<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="../portfolio/portfolio.css" />
<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
<link type="text/css" rel="stylesheet" href="../blog/blog.css" />
<link type="text/css" rel="stylesheet" href="arte.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="p_arte.js"></script>
<script type="text/javascript" src="../blog/blog.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<h1>COMENTÁRIOS</h1>
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
		<div class="recebe_coments">
		<ul class="sem_estilo" id="ie_coments">	
			<ul>
			<li class="tabela_blog">
				FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
			</li>
			<li class="tabela_blog">
				FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
			</li>
		</ul>
			<li id="novo_coment">
				POSTAR NOVO COMENTÁRIO
			</li>
			<li>
				<textarea class="msg_dimensao" rows="10"></textarea>
			</li>
			<li>
				<div class="enviar" align="right">
					<img src="../../images/botoes/bt_confir_pq.png" />
				</div>
			</li>
		</ul>
		</div>
	</div>


<div id="topo">
	<div id="centraliza_topo">
		<p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Aluno Fulaninho de Tal</a> > <a href="#">Planeta Arte</a></p>
		<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
	</div>
</div>

<div id="geral">

<!-- **************************
			cabecalho
***************************** -->
<div id="cabecalho">
	<div id="ajuda">
		<div id="ajuda_meio">
			<div id="ajudante">
				<div id="personagem"></div>
				<div id="rel"><p id="balao">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. 
				Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque 
				habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p></div>
			</div>
		</div>
		<div id="ajuda_base"></div>
	</div>
</div><!-- fim do cabecalho -->
	
	
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
		<div id="esq"></div>
		<div id="procurar_topico" class="bloco">
			<h1>CRIAR CARIMBO</h1>
			<div class="sem_estilo"></div>
			<div id="botoes">
				<img src="../../images/botoes/bt_lapis.png" style="margin-top:5px"/>
				<img src="../../images/botoes/bt_giz.png"/>
				<img src="../../images/botoes/bt_borracha.png" style="margin-top:5px"/>
				<img src="../../images/botoes/bt_retangulo.png"/>
				<img src="../../images/botoes/bt_reta.png" style="margin-top:5px"/>
				<img src="../../images/botoes/bt_circulo.png" style="margin-top:10px"/>
				<img src="../../images/botoes/bt_caderno.png" style="margin-left:40px"/>
			</div>
			<div id="tabela">
				<table id="tamanho_traco" cellspacing="10">
				<tbody><tr>
					<td><img onclick="setCPLineWidth(2);" class="traco" id="traco_2" src="../../images/arte/traco_2.png"></td>
					<td><img onclick="setCPLineWidth(4);" class="traco" id="traco_4" src="../../images/arte/traco_4.png"></td>
					<td><img onclick="setCPLineWidth(8);" class="traco" id="traco_8" src="../../images/arte/traco_8.png"></td>
					<td><img onclick="setCPLineWidth(10);" class="traco" id="traco_10" src="../../images/arte/traco_10.png"></td>
					<td><img onclick="setCPLineWidth(15);" class="traco" id="traco_15" src="../../images/arte/traco_15.png"></td>
				</tr>
				</tbody></table>
			</div>
			<a onclick="alert('AQUI O DESENHO DEVERIA SER SALVO')"><img align="right" src="../../images/botoes/bt_salvar.png"/></a>
		</div><!-- fim da div procurar_topicos -->
		<div class="bts_baixo">
			<a href="planeta_arte2.php"><img align="left" src="../../images/botoes/bt_voltar.png"/></a>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>
</html>
