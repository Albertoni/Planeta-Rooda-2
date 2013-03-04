<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
<link type="text/css" rel="stylesheet" href="planeta_pergunta.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../forum/forum.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="../../planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">
<img src="../../images/fundos/fundo4.png" width="100%" height="100%" style="position:fixed" />
<div id="topo">
	<div id="centraliza_topo">
		<p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Planeta Pergunta</a></p>
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
	<div id="conteudo"> <!-- tem que estar dentro da div 'conteudo_meio' -->
	<form>
	<div class="bts_cima">
		<a href="planeta_pergunta.html"><input type="image" src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
	<div id="criar_topico" class="bloco">
		<h1>PERGUNTAS</h1>
		<ul class="sem_estilo">
			<li class="tabela">Pergunta 1
				<textarea rows="3"></textarea>
			</li>
			</li>
		</ul>
	</div>
	
	<div id="criar_topico" class="bloco" style="border:0; background-color:transparent;">
		<table width="100%" cellpadding="0px" cellspacing="0">
			<tr style="background-color:#53686F; height:30px; " >
				<td style="width:65px; ">
					<h1 style="margin:0px; font-color:#FFFFFF">OPÇÕES</h1>
				</td>
				<td style="width:65px; border-left: 2px solid #EEE8EF">
					<div align="center">
						<h1 style="margin:0; margin-left:-15px">V</h1>
					</div>
				</td>
				<td style="width:65px; border-left: 2px solid #EEE8EF">
					<div align="center">
						<h1 style="margin:0; margin-left:-15px">F</h1>
					</div>
				</td>
			</tr>
			<tr style="background-color:#EEE8EF">
				<td> Opção 1:
					<input type="text" size=60 style="width:350px" />
				</td>
				<td style="width:65px; border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
				<td style="width:65px; border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
			</tr>
			<tr style="background-color:#E7C7ED">
				<td>Opção 2:
					<input type="text" size=60 style="width:350px" />
				</td>
				<td style="border-left: 2px solid white;">
					<div align="center"><input type="radio" /></div>
				</td>
				<td	style="border-left: 2px solid white;">
					<div align="center"><input type="radio" /></div>
				</td>
			</tr>
			<tr style="background-color:#EEE8EF"">
				<td>Opção 3:
					<input type="text" size=60 style="width:350px" />
				</td>
				<td style="border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
				<td style="border-left:2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
			</tr>
			<tr style="background-color:#E7C7ED">
				<td>Opção 4:
					<input type="text" size=60 style="width:350px" />
				</td>
				<td style="border-left: 2px solid white;">
					<div align="center"><input type="radio" /></div>
				</td>
				<td style="border-left: 2px solid white;">
					<div align="center"><input type="radio" /></div>
				</td>
			</tr>
			<tr style="background-color:#EEE8EF"">
				<td>Opção 5:
					<input type="text" size=60 style="width:350px" />
				</td>
				<td style="border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
				<td style="border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" /></div>
				</td>
			</tr>
			<tr style="background-color:#E7C7ED">
				<td>
					<a href="planeta_pergunta_criar_mais.html"><b>Mais Perguntas</b></a>
				</td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	
	<div class="bts_baixo">
		<a href="planeta_pergunta.html"><input type="image" src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
	</form>
	</div>
	<!-- fim do conteudo -->
	
	
	

</div> <!-- fim do conteudo_meio -->	
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
