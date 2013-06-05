<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../reguaNavegacao.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="portfolio.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Portfólio");
				$regua->imprimir();
			?>
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
					<div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
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
			<div id="esq_port_ini" style="float:left">
				<div id="meus_port" class="bloco">
					<h1>MEUS PORTFÓLIOS</h1>
					<ul class="sem_estilo">
						<a href="#"><img src="../../images/desenhos/meu_portfolio.png" border="0" /></a>
					</ul>
				</div>
			</div>
			<div id="dir">
				<div id="outros_port" class="bloco">
					<h1>OUTROS PORTFÓLIOS</h1>
					<ul class="sem_estilo">
						<a href="#"><img src="../../images/desenhos/outros_portfolios.png" border="0" /></a>
					</ul>
				</div>
			</div> 
			<div style="clear:both;"></div>
		</div><!-- Fecha Div conteudo -->
	
	</div><!-- Fecha Div conteudo_meio -->   
	
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
	
	</div><!-- fim da geral -->

</body>
</html>
