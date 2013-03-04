<?php
	session_start();
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../reguaNavegacao.class.php");
	$id_char_php = $_SESSION['SS_personagem_id'];
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="criar.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="../../raphael.js"></script>
<script type="text/javascript" src="criar.js"></script>
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
			$regua->adicionarNivel("Criar Personagem");
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
				<div id="rel"><p id="balao">Aqui, você pode encontra um espaço que foi mencionado no vidadeprogramador.com.br</p></div>
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
	
	<div id="criar_personagem" class="bloco">
		<h1>CRIAR PERSONAGEM</h1>
		<ul class="sem_estilo">
			<div id="prever_custom">
				<div id="mp_limpo"></div>
				<div id="pele"></div>
				<div id="bota_luva"></div>
				<div id="acessorios"></div>
				<div id="troca_cabelo"></div>
				<div id="troca_olho"></div>
			</div>
				
			<div id="botoes_custom">
				<div class="botoes_custom_unidade" id="cabelo_bt"></div>
				<div class="botoes_custom_unidade" id="olhos_bt"></div>
				<div class="botoes_custom_unidade" id="pele_bt"></div>
				<div class="botoes_custom_unidade" id="acessorios_bt"></div>
				<div class="botoes_custom_unidade" id="botaeluva_bt"></div>
			</div>
				
			<div id="tabelas">
				<center>
				<table class="amostras" id="cores_roupas" border="0" cellpadding="0" cellspacing="2">
				<tbody>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #EAC1C1;" id="cor1"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #E29696" id="cor2"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #DD8080" id="cor3"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #CE3E3E" id="cor4"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #AA4B4B" id="cor5"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #F2D2B1" id="cor6"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #E2AD81" id="cor7"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #DD945E" id="cor8"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #BC794B" id="cor9"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #966645" id="cor10"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #EAE9BB" id="cor11"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #DBD36E" id="cor12"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #E2D44D" id="cor13"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #CEBB30" id="cor14"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #A5942B" id="cor15"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #C9DDAC" id="cor16"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #B9D882" id="cor17"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #A0BF4C" id="cor18"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #88A02D" id="cor19"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #61721C" id="cor20"><!-- --></div></td>
					</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #B6D8D4" id="cor21"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #8DC9C0" id="cor22"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #68AFA3" id="cor23"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #469183" id="cor24"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #287768" id="cor25"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #B4CCDB" id="cor26"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #94B2D3" id="cor27"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #6296C4" id="cor28"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #467BA3" id="cor29"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #225C7F" id="cor30"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #C8B6DB" id="cor31"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #B69CD8" id="cor32"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #9675CC" id="cor33"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #7454A3" id="cor34"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #50327F" id="cor35"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #bbbbbb" id="cor36"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #999999" id="cor37"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #777777" id="cor38"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #666666" id="cor39"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #333333" id="cor40"><!-- --></div></td>
				</tr>
				</tbody></table>
				
				<table class="amostras" id="cores_pele" border="0" cellpadding="0" cellspacing="2">
				<tbody>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #CCB27F;" id="pele1"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #C99A56;" id="pele2"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #AD6E2B;" id="pele3"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #CC9F5E;" id="pele4"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #BF9364;" id="pele5"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #C29169;" id="pele6"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #9D692F;" id="pele7"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #6C4D30;" id="pele8"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #EDCCA1;" id="pele9"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #BE8A3E;" id="pele10"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #906C54;" id="pele11"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #3D2A19;" id="pele12"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #D0A147;" id="pele13"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #D18D49;" id="pele14"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #E5C892;" id="pele15"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #E0CDAA;" id="pele16"><!-- --></div></td>
				</tr>
				<tr>
					<td><div class="amostra amostra_cor" style="background-color: #C8CE5C;" id="pele17"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #9BA2CC;" id="pele18"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #DB947A;" id="pele19"><!-- --></div></td>
					<td><div class="amostra amostra_cor" style="background-color: #CE9CCC;" id="pele20"><!-- --></div></td>
				</tr>
				</tbody></table>
				
				<div class="amostras" id="cabelos" border="0" cellpadding="0" cellspacing="2">
					<div id="castanho">
						<ul> <!--cabelo8 e cabelo15 possuem o mesmo frame de costas!-->
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo1.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo2.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo3.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo5.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo6.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo7.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo8.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo9.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo10.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo11.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo12.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo13.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo14.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo15.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo17.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo18.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo19.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/castanho/cabelo20.png" /></div></li>
						</ul>
					</div>
					<div id="preto">
						<ul> <!--cabelo8 e cabelo15 possuem o mesmo frame de costas!-->
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo1.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo2.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo3.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo5.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo6.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo7.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo8.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo9.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo10.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo11.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo12.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo13.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo14.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo15.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo17.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo18.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo19.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/preto/cabelo20.png" /></div></li>
						</ul>
					</div>
					<div id="loiro">
						<ul> <!--cabelo8 e cabelo15 possuem o mesmo frame de costas!-->
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo1.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo2.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo3.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo5.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo6.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo7.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo8.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo9.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo10.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo11.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo12.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo13.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo14.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo15.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo17.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo18.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo19.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/loiro/cabelo20.png" /></div></li>
						</ul>
					</div>
					<div id="ruivo">
						<ul> <!--cabelo8 e cabelo15 possuem o mesmo frame de costas!-->
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo1.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo2.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo3.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo5.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo6.png" /></div></li> 
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo7.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo8.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo9.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo10.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo11.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo12.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo13.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo14.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo15.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo17.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo18.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo19.png" /></div></li>
							<li><div class="amostra amostra_cabelo"><img src="images/desenhos/cabelos/ruivo/cabelo20.png" /></div></li>
						</ul>
					</div>
					
					<table class="mudar_cor_cabelo" id="mudar_cor_cabelo" border="0" cellpadding="0" cellspacing="2">
						<tbody>
							<tr>
								<td><a><img class="troca_cabelo" border="0" id="bt_castanho" src="images/desenhos/cabelos/castanho/cabelo2.png" width="50" height="50" /></a></td>
								<td><a><img class="troca_cabelo" border="0" id="bt_preto" src="images/desenhos/cabelos/preto/cabelo2.png" width="50" height="50" /></a></td>
								<td><a><img class="troca_cabelo" border="0" id="bt_loiro" src="images/desenhos/cabelos/loiro/cabelo2.png" width="50" height="50" /></a></td>
								<td><a><img class="troca_cabelo" border="0" id="bt_ruivo" src="images/desenhos/cabelos/ruivo/cabelo2.png" width="50" height="50" /></a></td>
							</tr>
						</tbody>
					</table>
				</div>
					
				<div class="amostras" id="olhos" border="0" cellpadding="0" cellspacing="2">
					<ul>
						<li><div class="amostra amostra_olho"><img src="images/desenhos/olhos/olho2.png" /></div></li>
						<li><div class="amostra amostra_olho"><img src="images/desenhos/olhos/olho3.png" /></div></li>
						<li><div class="amostra amostra_olho"><img src="images/desenhos/olhos/olho6.png" /></div></li>
						<li><div class="amostra amostra_olho"><img src="images/desenhos/olhos/olho7.png" /></div></li>
					</ul>
				</div>
				</center>
			</div>
		</ul>
			
	</div><!-- fim da div tabelas -->
	
	<div class="bts_baixo">
		<form name="salvar_BD" method="post" action="salvar_personagem.php?id_char_php=<?=$id_char_php?>">
			<input type="hidden" id="cabelo_js" name="cabelo_php"/>
			<input type="hidden" id="olhos_js" name="olhos_php"/>
			<input type="hidden" id="cor_pele_js" name="cor_pele_php"/>
			<input type="hidden" id="cor_cinto_js" name="cor_cinto_php"/> 
			<input type="hidden" id="cor_luvas_js" name="cor_luvas_php"/> 
			<input type="hidden" id="corCabeloSelecionada_js" name="corCabeloSelecionada_php"/> 
			<a href="../../tela_inicial_geral.php"><img src="images/botoes/bt_cancelar.png"/></a>
			<input type="image" src="images/botoes/bt_confirm.png" align="right"/>
		</form>
	</div>
	</form>
	</div>
	<!-- fim do conteudo -->

</div> <!-- fim do conteudo_meio -->  
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>

