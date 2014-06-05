<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("desenho.class.php");
require("../../reguaNavegacao.class.php");

$post_id = 1; //TODO: DEBUG
$user_id = $_SESSION['SS_usuario_id'];
$desenho_id	= isset($_GET['desenho'])?$_GET['desenho']:0;
$turma		= isset($_GET['turma'])?$_GET['turma']:0;
$existente	= isset($_GET['existente'])?$_GET['existente']:0;
$insta		= isset($_GET['queroSerHipster']);

/*
*	Verifica se ele está tentando criar ou abrir um desenho
*	o parâmetro 'existente' só é adicionado a url quando um desenho está sendo aberto
*
*/
$titulo = "";
$desenho_proprio = false;
if ($existente != 0){
	$DES = new Desenho($desenho_id);
	$titulo = $DES->getTitulo();

//	verifica se o desenho pertence ao usuário
//	é preciso saber se o desenho é do usuário, para habilitar ou não a edição do desenho
	$desenho_proprio = $DES->pertenceAoId($user_id);
}

//print_r($DES);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<link type="text/css" rel="stylesheet" href="arte.css" />
<link type="text/css" rel="stylesheet" href="arte_desenho.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="p_arte.js"></script>
<script type="text/javascript" src="../blog/blog.js"></script>
<script src="js/raphael.js" type="text/javascript" charset="utf-8"></script>
<script src="js/rgbcolor.js" type="text/javascript"></script>
<script src="js/planeta_arte_c.js"></script>
<script src="js/instaRooda.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
<script>
	turma = <?php echo $turma?>;
	existente = <?php echo ($existente)?"1":"0"; ?>;
	id_do_desenho = <?php echo $desenho_id?>;
</script>
</head>

<body>
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
		<?php
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Arte", "planeta_arte2.php", false);
			$regua->adicionarNivel("Desenho");
			$regua->imprimir();
		?>
		<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
	</div>
</div>

<div id="geral">

<!-- **************************
			cabecalho
***************************** -->
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
		<div id="esq"></div>
		<div id="procurar_topico" class="bloco" style="padding-bottom: 20px;">
			<h1>DESENHO</h1>
<?php

			if ( ( !$desenho_proprio ) && ( $existente == 1 ) ){
				$html = $DES->visualizar(650,0,"border: 1px solid silver;");
?>
			<div class="sem_estilo">
				Título : <?php echo $titulo; ?>
			</div>
			<center>
				<?php echo $html; ?>
			</center>
			<div class="sem_estilo"></div>
<?php
			} else {

?>
			<div id="tela_div" class="tela">
				<div id="tela_svg" class="tela"></div>
				<div id="tela_canvas_div"><canvas id="tela_canvas" class="tela"></canvas></div>
				<canvas id="canvas_auxiliar" class="tela"></canvas>
			</div>
			<div class="sem_estilo" style= "padding-bottom:0px;">
				Título:
				<div id="progresso_envio_imagem_container" style="position:absolute; right: 70px; margin-bottom:2px; top:50px; display:none">
					<span style="font-size: 8pt; color: #777;"> Salvando</span>
					<div style="display: inline-block; width:100px; height:5px; border: 1px solid gray;">
						<div id="progresso_envio_imagem" style="width:40%; height:100%;  background-color: #6050D0;"></div>
					</div>
				</div>
				<input type="text" id="titulo" size="40" value="<?php echo $titulo ?>"/>

			</div>

			<div id="botoes" style="width:500px; padding: 5px;">
				<img src="icones/novo.png" style="margin:1px;"  onclick="selecionaFerramenta(0);" width="35"/>
				<img src="icones/salvar.png" style="margin:1px;"  onclick="salvarEmPartes();" width="35"/>
				<img src="icones/lapis.png" style="margin:1px;"  onclick="selecionaFerramenta(1);" width="35"/>
				<img src="icones/borracha.png" style="margin:1px" onclick="selecionaFerramenta(8);" width="35"/>
				<img src="icones/linha.png" style="margin:1px" onclick="selecionaFerramenta(5);" width="35"/>
				<img src="icones/preencher.png" style="margin:1px" onclick="selecionaFerramenta(2);" width="35"/>
				<img src="icones/retcheio.png" style="margin:1px" onclick="selecionaFerramenta(10);" width="35"/>
				<img src="icones/retvazio.png" style="margin:1px" onclick="selecionaFerramenta(3);" width="35"/>
				<img src="icones/elipsecheia.png" style="margin:1px" onclick="selecionaFerramenta(11);" width="35"/>
				<img src="icones/elipsevazia.png" style="margin:1px" onclick="selecionaFerramenta(4);" width="35"/>
				<img src="icones/texto.png" style="margin:1px" onclick="selecionaFerramenta(9);" width="35"/>
				<img src="icones/carimbo.png" style="margin:1px" onclick="selecionaCarimbo();selecionaFerramenta(6);" width="35"/>
			</div>
<div class="sem_estilo"></div>
			<CENTER>
			<div id="cores_div">
				<table id="cores_tab" cellspacing="3" height="45">
				<tbody>
				<tr>
					<td rowspan="2" width=10>
						<center>
						<div id="tabela"> <!-- DIV PARA SELECIONAR A ESPESSURA DO TRAÇO -->
							<table id="tamanho_traco" cellspacing="5">
							<tbody><tr>
								<td><img onclick="selecionaLargura(1);" class="traco" id="traco_2" src="../../images/arte/traco_2.png"></td>
								<td><img onclick="selecionaLargura(2);" class="traco" id="traco_4" src="../../images/arte/traco_4.png"></td>
								<td><img onclick="selecionaLargura(4);" class="traco" id="traco_8" src="../../images/arte/traco_8.png"></td>
								<td><img onclick="selecionaLargura(10);" class="traco" id="traco_10" src="../../images/arte/traco_10.png"></td>
								<td><img onclick="selecionaLargura(20);" class="traco" id="traco_15" src="../../images/arte/traco_15.png"></td>
							</tr>
							</tbody></table>
						</div>
						</center>
					</td>
					<td class="amostra_cor2" style="background-color:#000000"></td>
					<td class="amostra_cor2" style="background-color:#666666"></td>

					<td class="amostra_cor2" style="background-color:#330000"></td>
					<td class="amostra_cor2" style="background-color:#BB0000"></td>

					<td class="amostra_cor2" style="background-color:#333300"></td>
					<td class="amostra_cor2" style="background-color:#BBBB00"></td>

					<td class="amostra_cor2" style="background-color:#003300"></td>
					<td class="amostra_cor2" style="background-color:#00BB00"></td>

					<td class="amostra_cor2" style="background-color:#003333"></td>
					<td class="amostra_cor2" style="background-color:#00BBBB"></td>

					<td class="amostra_cor2" style="background-color:#000033"></td>
					<td class="amostra_cor2" style="background-color:#0000BB"></td>

					<td class="amostra_cor2" style="background-color:#330033"></td>
					<td class="amostra_cor2" style="background-color:#BB00BB"></td>

					<td class="amostra_cor2" style="background-color:#FF7777"></td>
					<td class="amostra_cor2" style="background-color:#996622"></td>
					<td class="amostra_cor2" style="background-color:#DD8800"></td>
					<td class="amostra_cor2" style="background-color:#AA0044"></td>
					<td class="amostra_cor2" style="background-color:#00AA44"></td>
					<td class="amostra_cor2" style="background-color:#2244AA"></td>
					<td rowspan=2 width="10"></td>
				</tr>
				<tr>
					<td class="amostra_cor2" style="background-color:#FFFFFF"></td>
					<td class="amostra_cor2" style="background-color:#CCCCCC"></td>

					<td class="amostra_cor2" style="background-color:#770000"></td>
					<td class="amostra_cor2" style="background-color:#FF0000"></td>

					<td class="amostra_cor2" style="background-color:#777700"></td>
					<td class="amostra_cor2" style="background-color:#FFFF00"></td>

					<td class="amostra_cor2" style="background-color:#007700"></td>
					<td class="amostra_cor2" style="background-color:#00FF00"></td>

					<td class="amostra_cor2" style="background-color:#007777"></td>
					<td class="amostra_cor2" style="background-color:#00FFFF"></td>

					<td class="amostra_cor2" style="background-color:#000077"></td>
					<td class="amostra_cor2" style="background-color:#0000FF"></td>

					<td class="amostra_cor2" style="background-color:#770077"></td>
					<td class="amostra_cor2" style="background-color:#FF00FF"></td>

					<td class="amostra_cor2" style="background-color:#FFCCCC"></td>
					<td class="amostra_cor2" style="background-color:#CCAA77"></td>
					<td class="amostra_cor2" style="background-color:#FFBB00"></td>
					<td class="amostra_cor2" style="background-color:#FF0077"></td>
					<td class="amostra_cor2" style="background-color:#00FF77"></td>
					<td class="amostra_cor2" style="background-color:#4477FF"></td>
				</tr>
				</tbody></table>
			</div>
			</CENTER>
			<!-- <a onclick="salvar()"><img id="botao_salvar" align="right" src="../../images/botoes/bt_salvar.png"/></a> -->
<?php
			}
?>
		</div><!-- fim da div procurar_topicos -->
		<div class="bts_baixo">
			<a href="planeta_arte2.php?turma=<?php echo $turma;?>"><img align="left" src="../../images/botoes/bt_voltar.png"/></a>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
	<div id="carimbos">
		<img id="carimbo1" src="imagens/pernalonga1.png" style="display:none;"/>
	</div>

<!-- ********************
	Editor de texto
************************* -->


<div id="float" style="position:absolute; z-index : 500;">
	<textarea  id="texto_b" onkeypress="return captureKeys(event);" style="display:none; overflow-y:auto;"></textarea>
</div>

<div id="editor_barra">
	<img src="imagens/edfonte.png" id="edfonte" class="editor_btns" style="left:2px; z-index : 5;"/>
	<img src="imagens/edbold.png" id="edbold" class="editor_btns" style="left:20px; z-index : 5;"/>
	<img src="imagens/editalic.png" id="editalic" class="editor_btns" style="left:38px; z-index : 5;"/>
	<img src="imagens/edcolor.png" id="edcolor" class="editor_btns" style="left:56px; z-index : 5;"/>
	<img src="imagens/V.png" id="edconfirm" class="editor_btns" style="left:92px; z-index : 5;"/>
	<img src="imagens/X.png" id="edcancel" class="editor_btns" style="left:110px; z-index : 5;"/>
</div>

<div id="editor_tam">
	<img src="imagens/mais.png" id="editor_maior" class="editor_mbtns" style="top:2px; z-index : 5;"/>
	<img src="imagens/menos.png" id="editor_menor" class="editor_mbtns" style="top:15px; z-index : 5;"/>
</div>


<div id="tabela_cores" style="width:140px; height:100px; top:50px; position:absolute; display:none; z-index: 5;">
<div style="width:100%; height:100%; opacity:0.5; filter: alpha(opacity=50);background-color:black;"></div>
<center>
<table border=0 cellpadding=0 cellspacing=2 style="top:0px; left:2px; position:absolute; width:140px; height:100px;">
<tr>
<td><div class="amostra_cor" style="background-color:#000000"></div></td>
<td><div class="amostra_cor" style="background-color:#993300"></div></td>
<td><div class="amostra_cor" style="background-color:#333300"></div></td>
<td><div class="amostra_cor" style="background-color:#003300"></div></td>
<td><div class="amostra_cor" style="background-color:#003366"></div></td>
<td><div class="amostra_cor" style="background-color:#000080"></div></td>
<td><div class="amostra_cor" style="background-color:#333399"></div></td>
<td><div class="amostra_cor" style="background-color:#333333"></div></td>
</tr><tr>
<td><div class="amostra_cor" style="background-color:#800000"></div></td>
<td><div class="amostra_cor" style="background-color:#FF6600"></div></td>
<td><div class="amostra_cor" style="background-color:#808000"></div></td>
<td><div class="amostra_cor" style="background-color:#008000"></div></td>
<td><div class="amostra_cor" style="background-color:#008080"></div></td>
<td><div class="amostra_cor" style="background-color:#0000FF"></div></td>
<td><div class="amostra_cor" style="background-color:#666699"></div></td>
<td><div class="amostra_cor" style="background-color:#808080"></div></td>
</tr><tr>
<td><div class="amostra_cor" style="background-color:#FF0000"></div></td>
<td><div class="amostra_cor" style="background-color:#FF9900"></div></td>
<td><div class="amostra_cor" style="background-color:#99CC00"></div></td>
<td><div class="amostra_cor" style="background-color:#339966"></div></td>
<td><div class="amostra_cor" style="background-color:#33CCCC"></div></td>
<td><div class="amostra_cor" style="background-color:#3366FF"></div></td>
<td><div class="amostra_cor" style="background-color:#800080"></div></td>
<td><div class="amostra_cor" style="background-color:#999999"></div></td>
</tr><tr>
<td><div class="amostra_cor" style="background-color:#FF00FF"></div></td>
<td><div class="amostra_cor" style="background-color:#FFCC00"></div></td>
<td><div class="amostra_cor" style="background-color:#FFFF00"></div></td>
<td><div class="amostra_cor" style="background-color:#00FF00"></div></td>
<td><div class="amostra_cor" style="background-color:#00FFFF"></div></td>
<td><div class="amostra_cor" style="background-color:#00CCFF"></div></td>
<td><div class="amostra_cor" style="background-color:#993366"></div></td>
<td><div class="amostra_cor" style="background-color:#C0C0C0"></div></td>
</tr><tr>
<td><div class="amostra_cor" style="background-color:#FF99CC"></div></td>
<td><div class="amostra_cor" style="background-color:#FFCC99"></div></td>
<td><div class="amostra_cor" style="background-color:#FFFF99"></div></td>
<td><div class="amostra_cor" style="background-color:#CCFFCC"></div></td>
<td><div class="amostra_cor" style="background-color:#CCFFFF"></div></td>
<td><div class="amostra_cor" style="background-color:#99CCFF"></div></td>
<td><div class="amostra_cor" style="background-color:#CC99FF"></div></td>
<td><div class="amostra_cor" style="background-color:#FFFFFF"></div></td>
</tr>
</table>
</center>
</div>

<div id="selFontes" style="top:10px; left:100px; position:absolute; width:130px; height:85px; overflow-y:auto; display:none; z-index: 5;">
<div style="width:100%; height:100%; background-color:black; opacity:0.5; filter: alpha(opacity=50); display:float;"></div>
<div style="width:100%; height:100%; top:0px; position:absolute;">
<div id="fnt_arial" class="sel_fonte">&nbsp;Arial</div>
<div id="fnt_sans" class="sel_fonte">&nbsp;Sans-Serif</div>
<div id="fnt_tahoma" class="sel_fonte">&nbsp;Tahoma</div>
<div id="fnt_times" class="sel_fonte">&nbsp;Times New Roman</div>
<div id="fnt_verdana" class="sel_fonte">&nbsp;Verdana</div>
</div>
</div>

<!-- <div id="div_escolhe_carimbos" class="bloco" style="background-color: #FFFFFF; border: 3px solid #555555; width:700px; height:500px; position:fixed; top: 50%; left:50%; margin-left: -350px; margin-top: -250px; display:none; z-index:100;"> -->
<div id="fundo_escolhe_carimbos" style="opacity:0.6; background-color:white; width:100%; height:100%; position:fixed; top:0px; left:0px; display:none; z-index:99;"></div>
<div id="div_escolhe_carimbos" class="bloco" style="width:700px; height:425px; position:fixed; top: 50%; left:50%; margin-left: -350px; margin-top: -250px; display:none; z-index:100;">
<h1>Carimbos</h1>
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td><img id="carimbo1" src="carimbos/carimbo_1.png" width="70" class="classe_carimbo"/></td>
<td><img id="carimbo2" src="carimbos/carimbo_2.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo3" src="carimbos/carimbo_4.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo4" src="carimbos/carimbo_6.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo5" src="carimbos/carimbo_8.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo6" src="carimbos/carimbo_11.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo7" src="carimbos/carimbo_12.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo8" src="carimbos/carimbo_47.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo9" src="carimbos/carimbo_48.png" width="70" class="classe_carimbo" /></td>
</tr>
<tr>
<td><img id="carimbo21" src="carimbos/carimbo_26.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo22" src="carimbos/carimbo_27.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo23" src="carimbos/carimbo_46.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo24" src="carimbos/carimbo_44.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo25" src="carimbos/carimbo_30.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo26" src="carimbos/carimbo_50.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo27" src="carimbos/carimbo_32.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo28" src="carimbos/carimbo_33.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo29" src="carimbos/carimbo_34.png" width="70" class="classe_carimbo" /></td>
</tr>
<tr>
<td><img id="carimbo31" src="carimbos/carimbo_35.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo32" src="carimbos/carimbo_36.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo33" src="carimbos/carimbo_37.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo34" src="carimbos/carimbo_38.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo35" src="carimbos/carimbo_51.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo36" src="carimbos/carimbo_40.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo37" src="carimbos/carimbo_52.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo38" src="carimbos/carimbo_53.png" width="70" class="classe_carimbo" /></td>
<td><img id="carimbo39" src="carimbos/carimbo_43.png" width="70" class="classe_carimbo" /></td>
</tr>
</table>
<br />
<img align="left" src="../../images/botoes/bt_voltar.png" onclick="fechaEscolhaDeCarimbos();" style="cursor:pointer; margin: 5px;" />
</div>
<?php
	$src = "";
	if ($existente != 0){
		$src = $DES->getDesenho();
	}

	echo "<img id='imagem_fonte' src='$src' style='visibility:hidden;'/>";
?>

<!-- ********************
	Fim do editor de texto
************************* -->
<?php

	if ($insta){
?>
		<div onclick="aplicaFiltro1(contexto);" style="position:fixed; top:100px; left: 50px; border-top:1px solid silver; border-left:1px solid silver; border-right:1px solid black; border-bottom:1px solid black; width: 100px; margin: 10px; cursor:pointer;">
			<div style="border-top:1px solid white; border-left:1px solid white; border-right:1px solid grey; border-bottom:1px solid grey;">
				<div style="background-color: silver; padding: 4px; text-align:center;">
					<span style="font-family: Tahoma;" > Aplicar filtro 1 </span>
				</div>
			</div>
		</div>

		<div onclick="aplicaFiltro2(contexto);" style="position:fixed; top:130px; left: 50px; border-top:1px solid silver; border-left:1px solid silver; border-right:1px solid black; border-bottom:1px solid black; width: 100px; margin: 10px; cursor:pointer;">
			<div style="border-top:1px solid white; border-left:1px solid white; border-right:1px solid grey; border-bottom:1px solid grey;">
				<div style="background-color: silver; padding: 4px; text-align:center;">
					<span style="font-family: Tahoma;" > Aplicar filtro 2 </span>
				</div>
			</div>
		</div>

		<div onclick="aplicaFiltro3(contexto);" style="position:fixed; top:160px; left: 50px; border-top:1px solid silver; border-left:1px solid silver; border-right:1px solid black; border-bottom:1px solid black; width: 100px; margin: 10px; cursor:pointer;">
			<div style="border-top:1px solid white; border-left:1px solid white; border-right:1px solid grey; border-bottom:1px solid grey;">
				<div style="background-color: silver; padding: 4px; text-align:center;">
					<span style="font-family: Tahoma;" > Aplicar filtro 3 </span>
				</div>
			</div>
		</div>
<?
	}
?>
</body>
</html>
