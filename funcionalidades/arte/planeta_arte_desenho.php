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
<h1> teste </h1>
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
	$src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnsAAAGLCAYAAAC/TlQTAAAgAElEQVR4nOzdeVyN6f8/8GYwM2Yaqc7aXmqmUakYJUuUdULWhIixZN+XyFiGUkOyDrJVZMtYsifZl4SobCl7RbQRreec1++Pz8/5jhFazjnXOaf38/Hwh85939erzGO83Pd9XZcGCCGEEEKI2tJgHYAQQoh6efz4MesIhJB/obJHCCFEZh49egQej4eYmBjWUQgh/x+VPUIIITJ14cIF8Hg8HDp0iHUUQgio7BFCCJGDq1evQiAQYO/evayjEFLrUdkjhBAiFzdu3IBAIMD27dtZRyGkVqOyRwghRG5u374NAwMDhIWFsY5CSK1FZY8QQohcpaamwsjICOvWrWMdhZBaicoeIYQQuXv48CFMTU2xfPly1lEIqXWo7BFCCFGIp0+f4qeffkJQUBDrKITUKlT2CCGEKExWVhZ++eUXzJs3j3UUQmoNKnuEEEIUKjs7G7a2tvD19WUdhZBagcoeIYQQhcvNzUWzZs0wceJESCQS1nEIUWtU9gghhDBRUFAAJycnjBo1igofIXJEZY8QQggzhYWFaNu2LYYOHQqRSMQ6DiFqicoeIYQQpt69e4eOHTuif//+KC8vZx2HELVDZY8QQghzxcXF6Nq1K3r37o3S0lLWcQhRK1T2CCGEKIXS0lL07t0bXbt2RXFxMes4hKgNKnuEEEKURnl5Ofr374+OHTvi3bt3rOMQohao7BFCCFEqIpEIQ4cORdu2bVFYWMg6DiEqj8oeIYQQpSORSDBq1Cg4OTmhoKCAdRxCVBqVPUIIIUpJIpFg4sSJaNasGXJzc1nHIURlUdkjhBCi1Hx9fWFra4vs7GzWUQhRSVT2CCGEKL158+bhl19+QVZWFusohKgcKnuEEEJUQlBQEH766Sc8ffqUdRRCVAqVPUIIISpj+fLlMDU1xcOHD1lHIURlUNkjhBCiUtatWwcjIyOkpqayjkKISqCyRwghROWEhYXBwMAAt2/fZh2FEKVHZY8QQohK2r59OwQCAW7cuME6CiFKjcoeIYQQlbV3714IBAJcvXqVdRRClBaVPUIIISrt0KFD4PF4uHDhAusohCglKnuEEEJUXkxMDHg8Hs6cOcM6CiFKh8oeIYQQtXDmzBnweDycOHGCdRRClAqVPUIIIWrjwoUL4PP5OHLkCOsohCgNKnuEEELUSkJCAvh8Pvbv3886CiFKgcoeIYQQtZOYmAihUIjdu3ezjkIIc1T2CCGEqKWUlBTo6elh27ZtrKMQwhSVPUIIIWrr7t270NfXp8JHajUqe4QQQtTanTt3oKenh507d7KOQggTVPYIIYSovdu3b9M7fKTWorJHCKkVSkpKcPPmTURFRWH16tVYsGABJkyYgIkTJ2LhwoXYsGED0tLSWMckcpSSkgKBQICoqCjWUQhRKCp7hBC18+rVKxw9ehQLFiyAu7s7zM3NUb9+fVhbW6NPnz4YN24c5s2bh5UrV2LFihX4448/4O3tDaFQCE9PT7x9+5b1t0DkJCkpCXp6eoiOjmYdhRCFobJHCFFpb9++xdmzZ7Fs2TJ4enrC1NQUDRs2RMeOHeHn54e9e/ciNTUV5eXlX7xWUVERhgwZgn79+ikgOWElPj4efD4f8fHxrKMQohBU9gghKqOoqAjx8fH4+++/MWzYMNjY2OCHH36Ak5MTJk6ciMjISNy7dw8SiaTaYxQXF4PL5eLx48cyTE6UTVxcHHg8HpKTk1lHIUTuqOwRQpRSYWEhzp8/j5UrV2LIkCGwsbFB/fr10bRpU4wYMQLr1q1DYmIiysrKZD529+7dafeFWiAyMhKGhoZ49uwZ6yiEyBWVPUIIc2VlZbh48SKWLVuGgQMHwtLSEt9//z0cHBwwevRobNiwAdevX0dpaalC8gwYMAA7duxQyFiErSVLlsDGxgZv3rxhHYUQuaGyRwhRuLKyMsTFxWHevHlo164dNDU1YWdnh/HjxyMsLAxJSUmVesdOXlxdXREbG8tsfKJYo0aNQv/+/VnHIERuqOwRQhTi1atXCA8Ph4eHB7S1teHg4AA/Pz8cPXoUr1+/Zh3vAzweD5mZmaxjEAUpLi6Gvb091q9fzzoKIXJBZY8QIjclJSXYu3cvevToAW1tbfTp0wdhYWHIzs5mHe2T7t69C0NDQ9YxiIKlpqaCx+MhMTGRdRRCZI7KHiFE5jIyMjBt2jTo6urC2dkZYWFhKCwsZB2rUhYvXozx48ezjkEY2L17NywsLFBcXMw6CiEyRWWPECIzDx8+xKhRo6Crq4tp06bh4cOHrCNVWfPmzREXF8c6BmGkX79+mD9/PusYhMgUlT1CSI29evUKo0aNApfLxdy5c5GTk8M6UrU8ffoUurq6TCeHELYyMjKgq6tLy7EQtUJljxBSbWKxGOvXrwefz8fEiRORn5/POlKNLFq0CKNHj2YdgzA2Y8YMepRP1AqVPUJItSQnJ6NZs2ZwdnZGUlIS6zg1Vl5eDgMDA9pRgSA7OxsNGzZUulnihFQXlT1CSJVIJBKsXr0aXC4XW7ZsqdHWZMpk7969cHZ2Zh2DKInevXtj8+bNrGMQIhNU9gghlZadnY2uXbvCwcEBaWlprOPIlKurK3bv3s06BlESO3fuRM+ePVnHIEQmqOwRQirl4sWL0NPTg5+fn1z2o2Xp9u3b0NPTU9h2bET5ZWZmgsvlso5BiExQ2SOEfFF4eDh4PB6OHDnCOopcDBo0CIsXL2YdgygZbW1tlZ1ZTsi/UdkjhHySSCTC9OnTYW5ujjt37rCOIxcPHz4Eh8NR+ZnERPYsLS3V9r97UrtQ2SOEVKiwsBBubm5wdXVFbm4u6zhyM2bMGMyePZt1DKKEbGxsaHY2UQtU9gghH8nJyYGDgwNGjBih1gsMZ2VlQUdHR6n36iXsGBkZ4dGjR6xjEFJjVPYIIR949uwZGjdujNmzZ6vNsiqfMnPmTEycOJF1DKKEiouLUb9+fbX+xw6pPajsEUKkUlNTYWJiguDgYNZR5O7Vq1fQ1dXFkydPWEchSighIQG2trasYxAiE1T2CCEAgOvXr0MoFGLLli2soyjEtGnTaEss8kmLFi3C1KlTWccgRCao7BFCcO3aNfD5fOzfv591FIXIyMgAh8PBixcvWEchSqp58+aIjY1lHYMQmaCyR0gt977oRUdHs46iMD4+Ppg1axbrGERJXb9+HaamphCLxayjECITVPYIqcWuXbsGHo+HgwcPso6iMOnp6eByucjLy2MdhSipIUOGICgoiHUMQmSGyh4htdTVq1drXdEDgIEDB8Lf3591DKKkUlNTweVyUVBQwDoKITJDZY+QWuh90Tt8+DDrKAqVnJwMoVCIt2/fso5ClFT//v0REBDAOgYhMkVlj5BaJjk5GXp6erXqHb333N3dsXLlStYxiJKKi4uDiYkJ3r17xzoKITJFZY+QWiQtLQ36+vrYtWsX6ygKd/nyZRgbG6OkpIR1FKKESkpKYGlpWeteayC1A5U9QmqJp0+fwsjICJs3b2YdhQlXV9das4YgqbqZM2eiT58+rGMQIhdU9gipBV68eAFzc/Na+wjzxIkTsLS0pK2vSIXOnTsHfX19vHr1inUUQuSCyh4hai4nJwc2NjZYtGgR6yhMSCQSODo6IioqinUUooRevnwJY2NjenxL1BqVPULUWGFhIRwdHTFz5kzWUZjZt28f7O3tsXLlShw6dIh1HKJERCIRXFxcMGfOHNZRCJErKnuEqKnS0lK4urpizJgxrKMwIxaLYW1tjSlTpqBOnTo4cOAA60hEiUyZMgVdunSBSCRiHYUQuaKyR4gakkgk8PLygru7e63+i2zr1q347rvv8P3330NHRwelpaWsIxElsX37dpibmyM/P591FELkjsoeIWpo4sSJcHV1rdXrhZWUlMDExATff/89WrRogenTp7OORJTEjRs3wOVykZyczDoKIQpBZY8QNRMYGAgbG5tav91TSEgIevbsibt374LH4yEtLY11JKIEMjMzYWpqShN2SK1CZY8QNRIREQFTU1M8e/aMdRSm8vLywOPxcOfOHUREROC3335jHYkogYKCAtjY2CAwMJB1FEIUisoeIWoiOjoaAoGA7mABmDFjBkaPHg0AcHBwwJEjRxgnIqzRhCVSm1HZI0QNxMfHg8PhID4+nnUU5h4/fgwOh4Pnz58jISEBjRo1glgsZh2LMCQWi+Hl5YW+ffvW6glLpPaiskeIiktOToZQKMSJEydYR1EKgwcPxvz58wEA3t7eWLp0KdtAhLkxY8bA1dWVZmOTWovKHiEq7NmzZzA0NERkZCTrKErhxo0b0NPTQ2FhIV69egUdHR3k5OSwjkUYoglLhFDZI0Rlvd8GbdmyZayjKI0OHTpg3bp1AIC//voLw4YNY5yIsLRp0yYYGxvX+glLhFDZI0QFvXv3Do6Ojpg1axbrKEojJiYGlpaWKC8vh1gshrGxMa5fv846FmEkPDwcRkZGNGGJEFDZI0TliEQiuLu7w8vLCxKJhHUcpSAWi2Fra4v9+/cD+N/M5BYtWjBORVjZtWsX9PT0cO/ePdZRCFEKVPYIUSESiQSDBw+Gu7s7ysvLWcdRGuHh4WjdurX09507d8a2bdsYJiKs/PPPPxAKhUhJSWEdhRClQWWPEBUya9YsODo61upt0P6ruLgYRkZGuHz5MgDg/v374PF4NPOyFjp06BAEAgFu3rzJOgohSoXKHiEqYu3atbCyssLLly9ZR1EqQUFB6NOnj/T3kydPxpw5cxgmIiwcP34cXC4XV69eZR2FEKVDZY8QFRAdHU2zCiuQk5MDLpcrfQm/sLAQHA4HT548YZyMKNLJkychEAhw6dIl1lEIUUpU9ghRcvHx8RAKhUhOTmYdRelMnjwZ48ePl/5+w4YN6Nmzp8LGz87Oxpo1a7Br1y6FjUk+FBcXBy6Xi3PnzrGOQojSorJHiBJLS0uDvr4+4uLiWEdROg8ePACXy0V2drb0a7a2tjh58qRcxy0qKsKOHTvg5uaG+vXro0ePHtL3BYlixcTEgM/nU9Ej5Auo7BGipF6+fAkLCwvaHeMTBgwYAH9/f+nvz58/j19++UUuy9GIxWKcOnUKQ4cOhZaWFiwtLREUFITMzEyZj0Uq59ixY+Dz+bhw4QLrKIQoPSp7hCih94smBwYGso6ilBISEqCvr//BrGRPT0+sWrVKpuPcvn0bvr6+MDIygpaWFnx8fOi9MCVw+PBh8Hg8uqNKSCVR2SNEyZSWlsLNzQ1jxoxhHUVpubi4YNOmTdLfZ2VlQUdHRyb7n2ZnZ2P58uVo1qwZvv76a3To0AGRkZEoKiqq8bVJzUVHR4PH4yEhIYF1FEJUBpU9QpSIRCKBl5cX3N3dIRKJWMdRSocPH4aVldUHP5+FCxfWqBwXFRUhKioKXbt2Rb169WBkZIS5c+ciPT1dFpGJjOzbtw8CgYC2wSOkiqjsEaJEaNHkzysvL4elpSWOHj36wdf09PRw69atKl1LIpHg7Nmz8PHxgZaWFurXrw8vLy/ExsZCLBbLOjqpoT179kAoFCIxMZF1FEJUDpU9QpTE2rVrYW5uTosmf8aqVavQuXPnD762e/dutGvXrtLXSE9Px9y5c9GoUSNoaGjAyckJoaGhMnkETORj586dtDMGITVAZY8QJRAdHQ2BQCBdHJh8LC8vD3w+/6M7eC1atMD+/fs/e25ubi7Wr18PJycnaGhogMfjYcqUKbR2oQoICwuDvr5+le/cEkL+D5U9QhiLj48Hh8NBfHw86yhKbfLkyRg7duwHXzt9+jR+/vnnCh+7lpWV4cCBA+jTpw++/fZb1KtXDz169MCBAwdQVlamqNikBv7++28YGxvj3r17rKMQotKo7BHCUFpaGgQCAaKjo1lHUWqpqangcrkfPeLu0qXLB7Nygf8tyzJ+/HhwOBxoaGjAxsYGISEhHyy+TJTfX3/9hUaNGuHRo0esoxCi8qjsEcLIy5cvYW5ujrVr17KOovTc3d0RHBz8wddu3rwJPT09lJaW4smTJwgICIClpSU0NDRoTTwVN2/ePPzyyy+0aDUhMkJljxAG3i+aPGvWLNZRlF5sbCwsLCxQWlr6wde9vLzg7OwMFxcXfP3117QmnhqQSCSYPn067O3t6U4sITJEZY8QBROJRHB3d4eXl5dctvZSJyKRCDY2NtIJGCKRCMeOHYOXlxfq1KkDDQ0NNGrUCAsXLsSTJ08YpyU1IZFIMHr0aDg6OiIvL491HELUCpU9QhRszJgxcHV1/ehOFflYaGgoXFxckJycjGnTpkFPTw8aGhrQ0NBAx44daU08NSESiTB48GC0bdsWb968YR2HELVDZY8QBQoMDISNjQ2t6VYJz549A4/Hg7a2trTgOTk5ISAgAD/88AOysrJYRyQyUFpaCg8PD3Tp0oUWEydETqjsEaIgkZGRMDQ0xLNnz1hHUVqFhYWIjIyEm5ub9DGtnp4efH19cffuXQD/e3nfx8eHcVIiC8XFxejatSt69uxJd7oJkSMqe4QoQFxcHLhcLi3iW4Hi4mLs27cPHh4eqF+/vvQuXoMGDbB///4P1sQrLCwEj8ejxafVwNu3b+Hq6oqBAweivLycdRxC1BqVPULkLDk5GVwuF3FxcayjKI2ysjIcO3YM3t7e0NLSkha892vitWnTBosXL/7ovBUrVsDDw4NBYiJLBQUFaNWqFUaMGAGRSMQ6DiFqj8oeIXL07NkzGBsbIzIyknUU5sRiMc6cOYMxY8ZIFzzW0NCAjo4Oxo8fj2vXrgEAzp49CxMTExQXF39wfnl5OYyMjKTHEdWUk5ODpk2bYuLEiTQbnRAFobJHiJwUFBTAxsam1i+afOXKFUyZMgX6+vrSglevXj24ublh+/btH6yJJxaL0axZM+zevfuj60RERKB9+/aKjE5k7Pnz57C2tsbs2bNZRyGkVqGyR4gcvHv3Dm3atKm1iyanpKRgzpw5aNSokbTgaWhowMHBAatWrfrkgrlhYWFo1arVR3d8JBIJrK2tERMTo4j4RA6ePHmCn376CQEBAayjEFLrUNkjRMZq66LJ6enp8Pf3h5WVFb766itpwWvUqBHmzp2L1NTUz55fWFgIfX19XL169aPPDh06BHt7e3lFJ3KWnp4OExMTrFixgnUUQmolKnuEyFhtWjQ5MzMTISEhcHBwwHfffYfvvvsOderUga6uLsaOHYtLly5VuvD+8ccf8Pb2rvCzNm3aVPholyi/O3fuwNDQEBs2bGAdhZBai8oeITJUGxZNfv36NcLCwtChQwf88MMP0NHRgZaWFn788Ud4eHggOjr6g+VSKuPJkyfQ1dVFRkbGR5+dOXMGFhYWtDyHCkpMTIRQKKQJSoQwRmWPEBmJjIyEhYWFWm7gXlZWhujoaPTr1w9aWlrQ1dWFrq4ufvjhB7i4uGDz5s01KrgDBgzAggULKvysffv22LJlS7WvTdi4fPkyeDwe9u3bxzoKIbUelT1CZODYsWPQ19dXq8V+JRIJzp8/j9GjR0NXVxdcLhdcLhdaWlqwsbFBUFAQnj59WuNxLl++DENDwwq3yrpw4QJMTU3prp6KOX36NPh8Po4ePco6CiEEVPYIqbH4+HhwOBzEx8ezjiITt2/fhp+fH0xMTMDhcMDn88Hj8aCvr4/p06cjKSlJZmNJJBK0aNEC27Ztq/DzLl26YOPGjTIbj8jf/v37wePxcObMGdZRCCH/H5U9QmogLS0NAoEA0dHRrKPUSGZmJoKDg2Fvbw9tbW0IhULo6elBW1sbQ4cOxcmTJyEWi2U+bmRkJBwcHCqcxJGQkABjY+NaMdFFXWzatAn6+vq4ceMG6yiEkH+hskdINb18+RLm5uYqu2hyaWkpoqKi4ObmhgYNGkBfXx/6+vrQ0tJCt27dsGvXrg8WPJa1d+/ewdjYGBcvXqzw827duqnsz7Y2Wrx4MRo1aqRWrzIQoi6o7BFSDe/evYOjoyMWLlzIOkqV3b59G1OmTAGfz0fDhg3B4/GgpaUFJycnrF69Gi9fvlRIjoULF8LT07PCzxITE2FgYICSkhKFZCHVJ5FIMHnyZNja2uL58+es4xBCKkBlj5AqKisrg7u7O8aMGcM6SqUVFhZi48aNcHJygoaGBr7++mt88803MDc3x/z58xV+NyYzMxMcDgePHz+u8PPevXtj5cqVCs1Eqq6srAyDBw9G27ZtkZ+fzzoOIeQTqOwRUgUSiQReXl5wd3eHSCRiHeeLLl26hGHDhkFTU1O6owWXy8X48eNx6dIlZrmGDh0KPz+/Cj9LTk6GUCiU6yNkUnPv3r2Dm5sb3N3d6c+KECVHZY+QKpg1axYcHR0rXCZEWeTk5CAkJASWlpbSgle/fn14enri0KFDVV7wWNauXr0KPT09FBYWVvi5p6cngoODFZyKVEVubi6cnJwwbNgwlfhHDyG1HZU9Qipp7dq1MDc3V9g7bVWVnJyMkSNHon79+tJHte3bt0dYWBhev37NOp5UmzZtsHnz5go/u3v3Lvh8Pt6+favgVKSyMjIyYG1tDV9f31q19zMhqozKHiGVEB0dDWNjY6WbaSgWixEdHQ1XV1fpXTxLS0sEBgbiyZMnrON9ZM+ePbC3t//kMi5eXl4IDAxUcCpSWampqTAxMaE7r4SoGCp7hHxBfHw8hEIhkpOTWUeRKisrQ0REBH755RdoaGhAR0cH48ePx5UrV1hH+6SSkhKYmpri9OnTFX5+//598Hg8vHnzRrHBSKUkJCRAKBQiIiKCdRRCSBVR2SPkM+7evQsej4e4uDjWUQAAxcXFWLNmDUxMTPDtt9+iR48eOHDggEosPPzXX3+hV69en/x86NChKrmUTW1w9OhR8Pl8HDp0iHUUQkg1UNkj5BNevnwJCwsLREZGso6C5ORkzJw5E0KhEI6Ojli9ejVyc3NZx6q0Fy9egMvlIj09vcLP7927By6Xi4KCAgUnI18SHh4OgUCgNtsBElIbUdkjpALvF01etGgRswy5ublYvXo1mjVrBg0NDXTs2BEJCQnM8tTEyJEjMX369E9+3r9/fyxevFiBiUhlBAYGwtTUFKmpqayjEEJqgMoeIf8hEong7u4OLy8vhc82FIlEOHLkCDw8PPDtt99CQ0MDjo6OSvMYuTqSkpIgEAg+edfu/ec0A1d5iMViTJgwAXZ2dsjKymIdhxBSQ1T2CPmPMWPGwNXVVaHvwaWnp2Pu3LkwNDSEnp4evv76a+jp6WHHjh0qv7yFi4sL1q1b98nPe/TogeXLlyswEfmckpIS9O3bF66urkq1ZA8hpPqo7BHyL0uWLIGNjY1C3h0rLCxEWFgYnJ2dwefzMWnSJIwcORK6urqYPXv2JxcdViVRUVGws7P75MK7CQkJMDAwQHFxsYKTkYrk5+ejXbt28PT0VIlJP4SQyqGyR8j/934tvWfPnsl1nHPnzmHo0KHQ1taWzqZNSkpCy5Yt4ezsrDbvRxUXF8PExASnTp365DGdO3fG+vXrFZiKfEpGRgaaNGmCyZMnq/zdZELIh6jsEYL/raXH5/Pltpbeq1evsGzZMlhaWuKXX35BSEgIsrOzUV5ejkWLFoHL5WLt2rVq9ZfswoUL4eHh8cnPz507B1NTU+bbtxHgzp07tFgyIWqMyh6p9R4+fAiBQIDo6GiZXlcikeDkyZPw9PREw4YNMXToUFy4cEH6eXp6Olq0aIHOnTvL/W6ioj158gQcDuezu3g4OzsjPDxcgalIRc6cOQOBQKAUSwwRQuRDZcre9u3b8fDhQ9YxiJopKCiAjY0N/v77b5ldMysrC4GBgWjUqBHs7OywZs2aj94BDA8PB4/Hw6pVq9Tqbt57/fv3x7x58z75eUxMDCwtLT/5Lh9RjLCwMAgEApw5c4Z1FEKIHKlE2SsuLsbAgQMhFAphamqKYcOGITIykpYEIDUiEonQvXt3jBkzpsbXEovFOHLkCHr16gVtbW34+PhUuCZeYWEhPD09YWNjg5SUlBqPq4zOnTsHY2NjvHv3rsLPJRIJHBwcsGvXLgUnI+9JJBLMnj0bjRo1wr1791jHIYTImUqUvX+7ffs21qxZg969e0NXVxeNGzfGuHHjsHfvXuTk5LCOR1TItGnT4OrqWqO7SxkZGViwYAGMjIzg4OCAjRs3fnIW7d27d2FpaYmRI0eq7exTkUgEOzs7REVFffKY6Oho2NraquUdTVVQVFSEvn37onXr1nj16hXrOIQQBVC5svdvYrEY169fR3BwMNzc3NCgQQPY29tj2rRpOHz4sFosXUHkY9OmTbCwsKjWEitisRjHjx9Hr169oKOjg7FjxyIpKemz5+zevRscDgdhYWHVTKwaQkND0aZNm09+LhaL0aRJE5m/H0kqJzs7G05OThg0aBBKSkpYxyGEKIhKl73/Ki8vx8WLF+Hv7w8XFxdoamqiZcuWmDNnDuLi4tT2bgqpmtOnT4PP5yMtLa1K5718+RJLlixBo0aNYG9vjw0bNnzxHxRlZWWYMmUKTE1NcePGjZrEVnr5+fkQCASf/T537doFR0dHBaYi7926dQtmZmZYuHAh3VUlpJZRq7L3X8XFxYiLi8OcOXPQsmVLaGpqwsXFBf7+/rh48SLKy8tZRyQKlpaWBn19/SptP3b+/HkMHDgQDRs2xO+//44rV65U6rysrCy0adMGXbt2RX5+fnUjq4yJEyd+9v3H8vJyWFpa4sSJEwpMRYD/TYjh8/nYuXMn6yiEEAbUuuz9V2FhIQ4fPoxp06bB3t4eDRo0gJubG4KDg3H9+nWIxWLWEYkcFRYWwsrKCmvXrv3isQUFBVizZg2srKxgaWmJFStWIC8vr9JjnT17Fvr6+rXmLsqdO3fA4XA++w5YeHg42rZtq7hQBACwZs0aCAQCnD9/nnUUQggjtars/VdOTg727t2LcePGoXHjxtDV1UXv3r2xZs0a3L59m3U8IkMSiQQeHh4YPmB2ZNcAACAASURBVHz4Z4+7fv06RowYAW1tbXh6euLUqVNVLmshISHg8/mIiYmpSWSV0qlTJ6xcufKTn5eWlsLU1BTnzp1TYKraraioCN7e3rC1tUV6ejrrOIQQhmp12fuvrKwsREZGYtiwYTA1NYVQKMTAgQMRGhqKlJQUuvOnwpYtW4Zff/21wpfSS0pKsHXrVjg6OsLExAQBAQF48eJFlccoLi6Gt7c3mjZt+tnFhNXN/v37YW1t/dnXItatW4cuXbooMFXt9vDhQ9jZ2WHQoEGfXAKHEFJ7UNn7jIcPH2Lz5s0YMmQILCwsoKOjg65du8Lf3x+nT5+m/4mqiLNnz0IgEHxUwB49eoRZs2aBz+ejS5cuOHToULULfVZWFlq0aAFPT08UFRXJIrZKKCkpgZmZGWJjYz95TFFREQwMDCpcd5DI3pEjR6QLdhNCCEBlr0qeP3+Offv2Ydq0aWjZsiW+//57ODo6YvLkyYiKikJGRgbriOQ/MjIyoKenJ50UIJFIcPz4cbi7u4PD4WDatGlVnpX7X1evXoWRkRH8/f1rxft5/xYYGIiePXt+9piQkJAvHkNqTiwWY8GCBTA0NPxgWz5CCKGyVwNFRUU4d+4cgoKC0L17d/B4POjp6aFHjx7w9/dHTExMlV7qJ7JVWlqKli1bIiAgAPn5+QgJCYGFhQWaNm2KTZs2yeQO3I4dO8DlcnHgwAEZJFYtGRkZ4HA4n93GsLCwEAKBAMnJyQpMVvvk5eWha9eucHZ2xvPnz1nHIYQoGSp7Mvb48WP8888/mDFjBlxcXNCgQQOYm5ujf//+WLZsGc6cOUM7fSjIpEmTYGFhgeHDh0NbWxuDBg3C5cuXZXJtsVgMPz8/mJmZ1doiM2jQIPj5+X32GH9/fwwcOFBBiWqnGzduwMzMDJMnT6blpAghFaKyJ2disRh3797F1q1bMWHCBLRu3Rra2trg8XhwdXXF+PHjsW7dOpw7dw65ubms46qFkpISrF+/Hl999RWMjY2xePFivHz5UmbXf/PmDdzd3dGuXbtau93U5cuXYWBg8NlFpXNzc8HhcGgmqJxIJBIsX74cPB4Pu3fvZh2HEKLEqOwxkpmZidjYWCxfvhwjR45Ey5YtoaWlBYFAgA4dOmDs2LEIDg7Gvn37cOPGDbx+/Zp1ZKX39OlT+Pn5gc/n4+uvv8bWrVtrtO9tRR48eABra2uMGjUKZWVlMr22qhCLxfj111+xffv2zx43c+ZMjB49Wm454uPjMW/ePPj4+GDcuHEICQnB1atXa8Ws+aysLHTq1AktW7bEo0ePWMchhCg5KntKJiMjAzExMVi1ahWmTJmCHj16wMbGBpqamtDV1UXz5s3Rr18/zJo1C6GhoYiNjUV6enqtLR4SiQSxsbHo1asXdHV1MXnyZNjZ2WH69OkyH+v06dMQCASVWpRZnW3evBktW7b87GSUrKws6OrqymXSUmFhITw8PGBkZAQ/Pz+EhoZi1apVGDduHKytrcHlcuHp6YnQ0FDcuXNH7SbN7N27FwKBAH/++Sc9tiWEVAqVPRWSnZ2N+Ph47NixAwEBARg+fDhcXV1hYmKCb7/9Fvr6+mjRogV69+6NiRMnYunSpdi+fTvOnTuHBw8eqNXG5wUFBVi5ciV+/vln2NnZYcOGDXj79i3CwsLQpEkTmX+vGzZsAJ/Px6lTp2R6XVXz+vVr6Onp4erVq589bsyYMZgxY4bMxy8oKMCvv/4KHx+fT06wefbsGcLCwuDt7Q0zMzNwOBz07dsXR48eVenil52djX79+sHS0lJm754SQmoHKntqory8HBkZGbh06RL++ecfrFixAtOmTcOAAQPQunVrmJiY4JtvvgGfz4e9vT26d++OsWPHwt/fHxERETh58iTu3Lmj9I+Lk5KSMGrUKOjo6GDAgAEfLDHx6NEjcDgcpKSkyGw8kUiEyZMnw9LSkt49w/8mvYwcOfKzxzx48AAcDkfm76BKJBL07NkTo0aNqtJ5mZmZ2LBhA+zs7NC2bVuV/HPcsWMHhEIhfH19UVxczDoOIUTFUNmrZZ4/f47r168jOjoaa9asgZ+fH7y9veHi4gJLS0s0aNAA9evXR6NGjdCqVSv07dsXEyZMQEBAAMLCwnD06FEkJSXhxYsXCrtLUlZWhp07d6JNmzYwMDCAv79/hTtcdO3aFX/99ZfMxi0oKMBvv/2GTp06oaCgQGbXVVU3btwAn8//4mzywYMH488//5T5+GvWrIGTkxNKS0urdb5YLEZISAj09fVVZjvEtLQ0/Pbbb7Cxsfni3VRCCPkUKnvkI+/evUN6ejrOnz+PPXv2YOXKlfDz88OQIUOkf/Hw+XzUq1cP+vr6aN68Obp37w4fHx/Mnz8f69atQ3R0NOLj4/Hs2bNq/+WckZGBuXPnQigUon379ti7d+8nJ1wcPnwYlpaWMnt3MT09HVZWVpgwYYLMJ3moIolEgpYtW2Ljxo2fPS4lJQUCgQBv3ryR6fgZGRngcrm4d+9eja8VEREBMzMzmWeUpbdv38LPzw+6uroIDg6md/MIITVCZY9UW1lZGTIyMpCQkICDBw8iNDQUCxYswOjRo+Hu7g5HR0cYGBjgm2++AZfLhZWVFVxcXDBw4EBMnjwZgYGBCA8Px5EjR5CYmIjMzEyUlpbi1KlT6NOnD3R1dTFhwgTcvXv3szlKSkpgYWGBmJgYmXxfZ86cgVAoxLp162RyPXWwadMmODk5ffFurru7O5YvXy7z8QcOHIj58+fL7Ho+Pj4YMWKEzK4nK2KxGFu3boWxsTG8vLyQlZXFOhIhRA1Q2SMK8fLlS9y6dQunTp3C9u3bsXz5cvj6+mLIkCFwc3NDkyZNoKWlha+++gp169aFUChE27Zt4eXlhcmTJyMoKAjh4eE4evQobty4gczMTOndjsps2VVZGzduhEAgwMmTJ2VyPXWQk5MDPp+PGzdufPa4+Ph4GBkZyfydssTERAiFQrx9+1Zm13z9+jV4PJ5M7hTKyuHDh2FjY4NWrVrRBAxCiExR2SNM3bp1C2PGjIGOjg769euHM2fOIDs7G7du3UJcXBwiIyOxfPlyzJw5U/oY2c7ODnp6eqhbty44HA7q1KmDli1bwsvLC1OmTMFff/0lLYY3b95EVlbWFx+DiUQiTJ06FT///DPu37+voO9eNYwcORKTJk364nGurq7YtGmTzMfv0qWLXJa7CQwMxIABA2R+3ao6fvw42rRpAysrKxw8eJB1HEKIGqKyRxSuvLwce/bsQbt27aCnp4cFCxZU+3HVqFGj4O3tjZMnTyIyMhIhISGYOXMmvL298dtvv8HW1hZCoRD16tUDj8eDtbU12rdvj0GDBmHKlClYsmQJQkND0bx5czg5OeHu3bv0jt6/XL58Gfr6+l+cpR0bG4uff/5Z5u+WJSYmwsjISC7rSBYWFkJbW7vCyT7yJhKJsHPnTtjZ2aFJkyaIjIysFYtBE0LYoLJHFObly5cICAiAgYEB2rZti6ioqBr9Jf7q1Svo6OhUqihKJBJkZ2cjJSUFsbGx2LZtG0JCQqR3FQ0NDWFjYwOhUIi6deuCz+fD2toaHTp0wKBBgzB16lQsXboUEREROHbsGJKSkpCVlaXWxVAkEsHe3v6LO2VIJBI4Ojpi165dMs/wfk9peRkxYgQCAwPldv3/evHiBRYvXgxTU1M4OzvjyJEjKr32HyFENVDZI3J39epVDBkyBNra2hg5ciSSkpJkct3AwMAavWSfkpICY2NjBAUFffB1iUSCFy9eICUlBSdOnMC2bduwbNkyTJ8+Hd7e3ujSpQuaNGkCgUCAevXqgc/nw8bGBh07dpQWw+DgYGzduhXHjx9HUlISnj9/rnLFcNWqVXB1df3icfv27YO9vb3MS8vjx4/B4XA+u/9uTV25cgU//fST3K4P/K80x8bGwtPTE9ra2hg+fDiuXbsm1zEJIeTfqOwRuSgtLcX27dvRokULmJqaYunSpcjLy5PZ9SUSCczMzJCQkFCt8+Pi4sDj8Wp8N0osFuPFixdITk7GiRMnsHXrVixbtgzTpk3D4MGD0blzZ2kxrFu3LgQCAWxsbNCpUycMHjwYU6dOxZIlS7BlyxYcPHgQFy9eRGpqKvLz82uUq6Yqu9SJWCxG48aNcezYMZlnmDBhAmbPni3z6/6Xqakpbt68KdNrisVinD9/HuPHj4dAIMCvv/6KNWvW0HqNhBAmqOwRmcrKysK8efMgFArRsWNHREdHy+VdpEuXLqFx48bVOvfAgQPg8Xg4e/asjFN9nlgsxvPnz5GcnIyYmBhs3boVS5cuxfTp0zF06FB069YNLVq0gLm5ORo0aIB69epBIBDA2toa7dq1g4eHB8aOHYt58+Zh1apV2LFjB06cOIGbN28iIyNDprNge/XqVamFkbdu3YrWrVvLbNz3Xr16BV1dXYUsPTJ16lSZLOuSm5uL3bt3Y8SIEdDX14etrS0CAgJUcscOQoh6obJHZOLixYvo378/dHR0MG7cuC+ujVdTs2bNwty5c6t83rZt26Cnp4fExEQ5pJKt0tJSZGZmIjk5GadOncLOnTuxevVqLFiwAOPHj0f//v3h6uqKJk2aQE9PD9988w00NTVhamoKBwcHuLm5wdvbG1OnTsXixYuxadMmHDhwABcuXMDdu3fx6tWrCsc9cOAALC0tv7i/cGlpKUxNTXH+/HmZf++LFi364rZssnLp0iU0adKkyuc9f/4c+/fvh6+vL5ycnPDjjz/Czc0Nq1evRlpamhySEkJI9VDZI9VWVFSETZs2wc7ODj/99BNWrVqlsL11mzVrhkuXLlXpnA0bNsDQ0FDuRZSlN2/eID09HZcvX8ahQ4cQHh6O4OBgzJw5E8OGDYO7uztatmyJn376Cdra2qhTpw74fD4aN24MZ2dnuLu7Q1NTE4MHD8aKFSsQGRmJY8eOIT4+HqmpqcjOzpZOqlm9ejXc3Nxk/j0UFRWBz+cr7M9JLBaDx+PhyZMnn/w8LS0Ne/fuxcKFC+Hh4QEzMzPo6Oiga9eu+PPPP3Hy5Enas5YQorSo7JEqe/ToEWbOnAkul4uuXbvi+PHjCp1RWFxcjO+//75Kf7lGRkbCyMgIDx8+lGMy1VNeXo7nz58jJSUFZ86cgZubG1q2bIk///wTEydOxMCBA9G5c2c4ODjAwsICXC4XdevWhaamJurUqQNzc3O0adMG7u7uGDJkCCZNmoQFCxZg5cqViIiIwMGDB3H+/Hlcu3YN6enpyMzMRF5e3mf/ewkPD0fXrl0V9jN4/fo1unfvDl9fX0RFRSE4OBjjxo2Dm5sbGjdujPr168PU1BTdu3fH7NmzsXPnTty5c4dm0RJCVAaVPVIpEokEJ0+eRM+ePaGrq4upU6cyexcpKSkJ1tbWlT7+4MGD0NfXR0pKihxTqb6rV69CKBQiNzf3i8cuWLAA3bt3R1JSEs6cOYMDBw4gLCwMISEhmDdvHiZMmIBBgwahW7duaNWqFZo1awYzMzMIhUJoa2tDQ0MD3333HbS1tWFqaorGjRujWbNmaN++PRo2bIjWrVtj5MiR8PHxgY+PDyZNmgRfX9+Pfvn5+SEoKOijX7NmzYKvry+mTp0KHx8fDBs2DB4eHujQoQMcHR1hZWUFIyMjfPfdd2jQoAGEQiE4HA769u2LKVOmYOXKlTh06BBu3bqFd+/eKeCnTwgh8kNlj3xWSUkJNm3aBCsrK1hbW2P9+vUy3baqOo4dO4YuXbpU6tgHDx6Ay+UiPj5ezqlUm0gkQtOmTbFt27YvHpufnw8ul1vjnUaKioqQl5eHBw8e4NatW7h27Rr+/vtvCAQC7N69G6GhodJfK1asqLDUBQQEVFgCFy9ejKCgIAQHByM0NBSbNm1CVFQUTpw4gcuXLyMlJQVPnjyR3h1+8eIFdHR0VG55HEIIqQwqe6RCubm5CAgIgFAoROfOnREbG8s6ktT27dsxcODALx4nEong5OSEFStWKCCValu2bBk6duxYqWPnzJmD4cOHyyXH0KFDsWTJErlc+0uaNGlC/ygghKglKnvkAw8ePMCECROgo6ODoUOHIjk5mXWkj1S27G3evBlt27ald6u+4NGjR+ByuZV6LP/ixQvo6uri6dOnMs+Rk5MDHR2dT84Slrdx48Zh6dKlTMYmhBB5orJHAPxvJwEPDw9wuVz4+fnh+fPnrCN90rFjx9CpU6cvHte+fXvaWL4SOnfu/NEuIp8yfvx4TJ06VS45li5dCm9vb7lcuzJ27dqFHj16MBufEELkhcpeLXf+/Hl07NgRpqamWLVqFfP38SojJSWlUgsq29nZVXuHjdoiIiIC9vb2KC8v/+Kx77cvy87OlnkOsVgMc3Nzpo9RMzMzoaurS3eCCSFqh8peLXXu3Dm4urrC1NQUmzdvlq6dpgpKS0uhqan5xVmSf/zxB9zd3VFUVKSgZKolOzsbPB6v0gtMDx06FPPmzZNLlmPHjuHXX3+Vy7WrwszMDLdv32YdgxBCZIrKXi1z9uxZuLi4wMzMDFu2bKnUHR1l1KpVK8TFxX32mNLSUnh7e0NPTw9LlizBs2fPFJRONXh6esLX17dSx96+fRt8Pl9ui2Z3794dW7Zskcu1q2LIkCFYt24d6xiEECJTVPZqicTERLi6usLc3BxhYWEqW/LeW7RoESZNmlSpY5OSkjBkyBDweDyYm5tj2LBhWLZsGY4cOYK0tDSV/1lUR3R0NCwsLCq9MHXfvn3lNkv2/eNhZbgDu3HjRnh5ebGOQQghMkVlT829evUKI0eOBI/Hw9q1a9Wm2Ny5cwf6+vpV+n4kEglu3bqFdevWYeLEiejcuTNMTExQr149CIVC2Nvbo2vXrvj9998xc+ZMBAYGYsOGDfjnn39w6tQpXLt2DQ8ePEB2drZKL7RbUFAAQ0NDnD17tlLHX716FQYGBnIrY35+fpg8ebJcrl1VqampMDIyYh2DEEJkisqemiotLUVwcDB4PB6mTZuG/Px81pFkrnXr1ti/f3+NryMSiZCVlYXr16/j8OHD2Lx5MwIDAzFz5kyMGDECvXv3Rrt27dC0aVOYmZmBy+Wifv36+Oqrr6CtrQ0jIyM0btwYDg4O6NChA/r06YMhQ4Zg3Lhx8PX1/WAR4PXr10sXCt69ezeioqIQFRWFmJgYxMbGIjY2FgkJCbh27RquXbuG+/fv48GDB3jw4AFevnyJvLw85OXl1ai0jxo1CqNGjar08Z06dUJoaGi1x/uc8vJyCIVC3LlzRy7Xr47P7ZNLCCGqiMqeGoqNjYW5uTm6dOmCtLQ01nHkJioqCk5OTszGF4vFyMvLw+PHj3Hr1i3Ex8cjNjYWe/bsQVhYGFavXo2goKAPdnZ4v/2Xj48P+vXrBw8PD3h4eKBjx47o0KEDOnTogObNm6NZs2Zo1qwZLCwsYGZmBjMzM3A4HGhra0NbWxt169aFhoYGNDQ0oKmpKf26oaGh9HgbGxvpdZydndGhQwe0b98eX331FTp37gwPDw94e3tXuCXZ/Pnzpdm//fZbrFmzBqGhoQgPD5cW1OjoaGlBPXv2rLSgJiUlSQvq06dPpQX1zZs3H/0M9+7dizZt2jD40/u0Pn36IDIyknUMQgiRGSp7aqSwsBBjxoyBkZERjhw5wjqO3InFYtja2iI6Opp1FKbevHkjLVRPnz6VFq2kpCRpATt79iwOHTqEH3/8EUFBQdLCFh4eXuGWZPPnz4evry++//579OvXT1oIvb29pQXV3d1dWlCdnZ2lxdLGxkZaOA0NDaVFVFNTU1pQ69atC21tbdSrVw8//vij9HgLCwvpdZo3by69fseOHaXj/juPj4/PB2X6ff7326SFhoYiIiJC+v0eO3ZMWlDf/2xu3rwp/ZllZWUhICAAv//+u9q88kAIIVT21ERycjIsLCwwfPhwuc2YVEYHDx6EtbU1xGIx6yhKb9asWejXr1+lj4+Ojoatra1cfrbl5eVITEwEl8tFRkaGtGzdv39fWsISEhKkxSwmJkZa2P69b+769es/eEz+vvRNnTpVWgYHDx4sLYpdunSR3uF8XyqbNGkiLZtCoRANGjRAnTp1UKdOHWk5fV9YeTye9FgrKys0a9YMrVq1kt4pHTFiBKZMmYK5c+diyZIlCA0Nxc6dO3Hy5Encu3dPKSahEEJqHyp7amDHjh3gcrnYvn076yhMODk5Ydu2baxjKLXExETweLxKL4hcXl6Oxo0b49ChQ3LLNGvWLLntxlET5eXl0NTURGFhIYD/Tex5f+c0OztbWkxTUlJw7do1XLhwAceOHUNUVBQ2bNiAZcuW4c8//8T06dPh4+MDT09PuLi4wMLCAt999x04HA7s7OzQvXt3TJ06FTt27MCjR4/YftOEELVGZU/FBQcHw8TERCn3sFWUixcvQl9fXy0nochCeXk5mjZtioiIiEqfExoaChcXF7llKi0thUAgwL179+Q2Rk20adPmi+s4Vld2djZu3LiBgwcPYsmSJejTpw/4fD6aNGmC4OBgackkhBBZobKnwgIDA2FtbY2MjAzWUZgbN24cRowYwTqGUgoKCqrUXsLvFRYWQigU4tq1a3LLtGfPHrRr105u16+pqVOnYvHixQobTywW4/z58+jfvz+EQiFiYmIUNjYhRP1R2VNR69atg7m5OV6+fMk6ilJ48+YNjI2NcfLkSdZRlMr9+/fB4XCq9Jhw/vz5cl9YuEOHDtixY4dcx6iJPXv2oFevXkzGPnXqFPh8Pk6cOMFkfEKI+qGyp4KSkpLA4XDUelmV6jh69CgaNWqEt2/fso6iFCQSCdq2bYsVK1ZU+pysrCzo6uri8ePHcsuVnp4OLpeL0tJSuY1RU48fP4ZAIGA2/qVLlyAQCOjVBEKITFDZUzEikQg2NjbYunUr6yhKaciQIRg9ejTrGEohNDQUTk5OEIlElT7Hx8cHM2bMkGMqwNfXF9OnT5frGLIgEAjw9OlTZuP7+Phg7ty5zMYnhKgPKnsqZuvWrXB2dmYdQ2m9fv0aZmZmMtlZQ5VlZGSAy+Xi1q1blT7n9u3b4PP5cr2bVFpaCj6fj9TUVLmNISs9e/bEnj17mI1/584d6OnpoaysjFkGQoh6oLKnYmxtbem9tC+Ij48Hn8/Hs2fPWEdhxt3dHQsWLKjSOd26dcPy5cvllOh/du3aBVdXV7mOISsBAQGYNm0a0wwuLi6IiopimoEQovqo7KmQu3fvwsDAgBYQroTFixejXbt2VXqEqS52794NKyurKr0Td+rUKTRq1Eju79G1a9dOZcpLXFwc87vokZGR6N69O9MMhBDVR2VPhYSGhmL48OGsY6gEkUgEFxcX+Pv7s46iULm5uRAKhbh8+XKlz5FIJGjWrJncS1hSUhIMDAxUZhuy169fQ1NTk2neN2/eQEtLCwUFBcwyEEJUH5U9FTJ16lQsXbqUdQyVkZmZCaFQiLNnz7KOojBDhgzBpEmTqnROZGQkWrRoAYlEIqdU/zNy5EgsWrRIrmPImpWVFW7cuME0Q69evRAZGck0AyFEtVHZUyFeXl70P/0qOn78OAwMDCq9TZgqi4mJgampaZV2YCguLoaxsTEuXLggx2RAfn4+GjZsqHJ/DsOGDcP69euZZggKCpL7DGlCiHqjsqdCqOxVzx9//IGOHTuq9ft7eXl5MDIyQmxsbJXOW7JkCXr37i2nVP9n6dKl8Pb2lvs4srZ+/Xr8/vvvTDMsWbJEKfcQJoSoDip7KoQe41bP+/f3/vzzT9ZR5GbQoEGYMGFClc7JyckBl8uV+zIoYrEY5ubmVXqPUFncvHkTVlZWzMaXSCRwcnLCvn37mGUghKg+KnsqZNWqVRg7dizrGCrp+fPn0NfXV8tla6KiovDzzz+jqKioSudNnjwZ48ePl1Oq/3Po0CE4ODjIfRx5KC8vh6amJpMJEhKJBL6+vmjdurXKTGohhCgnKnsq5OLFi2jatCnrGCrr1KlTEAqFyMzMZB1FZrKyssDn85GQkFCl89LT08HhcBSyt3Lnzp0REREh93HkpU2bNoiLi1PomNnZ2ejbty9atGihcu85EkKUD5U9FVJaWsrsLoO68Pf3V5s7JRKJBG5ubpg/f36Vz/Xw8MDixYtlH+o/7t+/r/T74H7J1KlTERQUpJCxioqKsGLFCnC5XPj6+qK4uFgh4xJC1BuVPRXTq1cvhIeHs46hsiQSCX777TeV2Jv1S9avX4/mzZtXubhevnwZRkZGVX7sWx0TJkzAH3/8Ifdx5Gnnzp3o06ePXMfIz8/H0qVLIRQK0atXL9y+fVuu4xFCahcqeypmx44dcHNzYx1DpeXk5MDExESl989NS0sDl8vF3bt3q3xu69atFfIPhsLCQnA4HDx58kTuY8lTWloajIyM5HLtO3fuYMyYMdDR0cHAgQORlJQkl3EIIbUblT0VU1RUBB0dHTx+/Jh1FJV25coVcLlcpKens45SZSKRCC1btsTKlSurfO7hw4dhbW2tkC33/v77b/Tt21fu4yiCjo4OXrx4IZNr5ebmYu3atXBycoKenh7mzp2LrKwsmVybEEIqQmVPBU2fPl0tHkOytnbtWtjY2ODt27eso1TJ4sWL0aFDhyrveCEWi2Fra4vo6Gg5Jfs/EokEjRs3xunTp+U+liJ06tQJhw8frvb5IpEIR44cgYeHBxo0aAAPDw8cOXJErdd+JIQoDyp7KujBgwfgcDjIz89nHUXljRgxAr1795b7VmGycvPmTfD5fDx9+rTK527fvh1OTk5ySPWxuLg4WFtbK2QsRfjjjz+qNRHm9u3b8PX1hZ6eHpycnLB+/Xrk5ubKPiAhhHwGlT0V9fvvv2PBggWsY6i80tJStGrVSiV+liUlJbC2tsa2bduqfG5ZWRkaNWqEM2fOyCHZx3r06IENGzYoZCxFOHDgQKXflX348CECAgLQpEkTGBoawtfXjz6gFAAAIABJREFUt1rvVhJCiKxQ2VNR75e0yMnJYR1F5WVnZ8PQ0FDpdymYMWNGtd+BW7t2Lbp06SLjRBV78uQJOBxOlfboVXZZWVngcrmf/DwjIwMhISFwcHAAj8fD2LFjcf78eYW8G0kIIV9CZU+FjR8/XiE7INQG165dA5/Px5UrV1hHqdCVK1cgFArx6tWrKp/79u1bCAQCJCYmyiHZx3x9fTFlyhSFjKVI+vr6ePTokfT3L1++xNq1a+Hs7AxdXV0MGzYMJ06coPfwCCFKh8qeCsvJyQGfz0dKSgrrKGohOjoaAoFA6R65lZaWwtraGrt27arW+f7+/ujfv7+MU1WsqKgIPB4PaWlpChlPkd6vcRkWFobOnTujYcOGGDhwIA4ePIiSkhLW8Qgh5JOo7Km4VatWoVOnTqxjqI3IyEgYGhri2bNnrKNIzZs3Dz179qzWubm5ueBwOApbYiYsLAy//fabQsZSlPz8fISFheHnn3/Gt99+iz59+mD37t0KWZSaEEJkgcqeiisvL4eVlRUOHDjAOoraWLlyJSwsLBSyb+yXJCcng8/nV3s/3xkzZmDMmDEyTvVp9vb2OHbsmMLGk5f8/HxERESga9eu0NLSQu/eveHn5wdnZ2fW0QghpMqo7KmBmJgYWFhY0KMkGZo7dy5sbW3x/PlzZhlEIhF+/fVXbN68uVrnZ2RkQFdXV2EL9l6+fBkmJiYqOymhoKAAERER6NatGxo2bIhevXphx44d0okmubm5aNCggcp+f4SQ2ovKnpro27cv5syZwzqGWlm0aBEsLCzw8OFDJuP/9ddf6NSpU7XXAPTx8cGsWbNknOrTBg4cCA0NDURGRipszJp6/fo1tm7diu7du0NLSws9e/bEjh078ObNmwqPNzc3x507dxSckhBCaobKnpp4/vw5+Hw+bty4wTqKWvn7778hFApx/vx5hY57//59cDicahfN90vz5OXlyThZxbKysqCjowNNTU2ln5yRn5+Pbdu2wd3dHVpaWujRowciIyM/WfD+bcCAAQrZV5gQQmSJyp4aCQsLQ7NmzVBeXs46ilo5fvw4eDwe1q5dq5DxJBIJWrdujVWrVlX7Gp6enggMDJRhqs9btGgR2rdvDwMDA4WNWRV5eXnYsmWL9B08d3d3REZG4vXr11W6TkhICC13RAhROVT21EynTp0QFBTEOobaSU1NRdOmTdGrVy+5L2S9bt06tG7dutrvhiUmJkJPTw/v3r2TcbKKicViGBkZYfz48Rg0aJBCxqyMV69eYePGjejcuTMaNGiAPn36YPv27ZW6g/cp58+fh6OjowxTEkKI/FHZUzOPHz8Gh8NBamoq6yhqp6SkBNOnT4dAIKjWlmWVIYu1E3v27ImVK1fKMNXnHT58GA4ODhg0aBA2bdqksHErkp2djXXr1qFDhw5o0KABPDw8EBUVhbdv38rk+u/evcMPP/yAsrIymVyPEEIUgcqeGlq9enWN7gyRz0tISICdnR3atWuHhIQEmV577NixmDhxYrXPT0pKglAoVOgacN26dcOWLVtgYGDA5H29zMxMrFmzBu3atZMudLx37165/QyaNGmCa9euyeXahBAiD1T21JBYLEbbtm0RHBzMOoraKi8vx4YNG2BgYIDevXvL5C//lJQU8Pn8Gj0m7tOnD0JCQmqcpbKePHkCXV1dJCUlKfR9vWfPnuH/tXfncTWmjf/AZ3meGZqmnJbTpmiTnTqmZI9Dyr4cGbtBY8lYwrE3mDg0mrKUDApZ5iA7cUgMjTi2mSGGFJN9ShPaT5/fH/N9+j0emQl17jrn8369+qNzd+7rU3nxcd/3dV1hYWFo27YtTExMMGTIEOzduxd5eXmVPvaoUaOwZs2aSh+HiKiisOzpqLS0NJibm+PKlStCR9Fpubm5WL58OWxtbeHl5YXdu3e/9S2+Hj16YOXKlW+d5ZdffoGlpaXWntUD/trdY+LEiVi3bl2lP6+XlpaG5cuXw9PTEyYmJhg5ciQOHDiAgoKCSh33f61ZswajRo3S6phERO+CZU+Hbdq0CY0bN+Ziy1pQWFiILVu2wNPTE2KxGFOmTMHFixfL/f4rV67A2tr6na5M+fn5YenSpW/9/jdVVFQEa2tr/Prrr5X2vF5qaiqWLl2Kzz77DObm5hg9ejSOHDki6DNzarUaTZs2FWx8IqI3xbKn42QyGaZOnSp0DL2SkpKCmTNnwsHBAfb29pg6dSoSEhL+tnT7+flh+fLlbz3mzZs3IRaLS3d70Ia4uDi0adMGAF56Xu/Bgwc4ffo0Nm3ahIULF2Ls2LEYMGAAOnfujBYtWqBVq1aQSqXw8fGBv78/FAoFdu7ciUePHgH4a+bz4sWL4erqCrFYjLFjx0KlUqG4uFhr39vfKSwsxCeffKLVK6hERO+CZU/HZWZmonbt2jh27JjQUfTSlStXsGDBAnh4eODTTz9F586dsWTJEpw6daq0LDx58gS1atV6p6IWEBCAuXPnVlTscvH29kZgYCBCQkLwwQcfoF27dhCJRDA3N4enpycGDRqEOXPmICIiAtu3b8eRI0eQnJyMM2fOQKVS4eDBg4iIiMC0adPQpk0b1KhRAzVq1IChoSEGDhyIxMTEKlPw/pe7u7vWF9omInpbLHt6QKVSwc7OTmu7KVDZsrOzsXfvXkyePBkeHh745JNPIJFIIJVK0aRJExw/fvyt9uJ98uQJjI2NK3UP3Pz8fCQnJyMiIgKjRo1C8+bN8e9//xsSiQSurq7o2LEjjh8/jidPnpTrfM+fP0dcXBy++OILWFhYoFGjRpDL5Vi/fj3mzp0LOzs7SCQSREVFaf2ZvPIICAjQ6kQYIqJ3wbKnJyZNmgQ/Pz+hY9B/yc/PR1JSEpo0aYKuXbuiQ4cOMDc3h0gkQps2beDv74/vvvsOR48exa1bt157G3jRokUYM2ZMheX6448/cOLECaxatQqjR4+Gq6srDAwM4ObmhjFjxmDNmjXo06dP6eMB/9mN4p+kp6cjIiICXbt2hZGREaRSKcLDw8vcEk6j0UClUsHHxwd2dnaIiIioUqUvJiYGn3/+udAxiIjKhWVPT+Tl5aFRo0bVapN6fdGiRQucP3++9PMnT57gxIkTiIyMREBAADp27AhHR0d8/PHHsLS0RIsWLdC7d2989dVXCAkJgZWVFb7//nvcvXv3jSYuPHv2DMnJyVi3bh2mTJkCqVQKKysr1KpVC23atMGXX36JyMhInDt37qWiWVBQAAsLC9y4cQPZ2dkwNjYuc9uxBw8eYOvWrfD394ezszPMzc0xbNgwKJXKN9qm7OzZs/D19YWTkxMOHjxY7vdVpmvXrsHZ2VnoGERE5cKyp0cuXboEsViM9PR0oaPQf2nQoEG5d8x48OABkpOTsWvXLoSFhcHLywv29vZo1aoVbGxs8K9//QvGxsZwdnaGp6cnevTogWHDhmH48OEYPHgwunXrBnd3d1hbW6NmzZpo3rw5hg0bhmXLliE+Ph537979xwy7du2Cl5cXgL+ucPXp0wfAX2vu7dixA+PHj0fDhg0hEolKd/P4+eefUVJS8vY/JPy1R7GzszN69eqFtLS0dzrXu9JoNDAyMuKjEURULbDs6Zlly5bBy8uLu2tUIV9++SVmzZr1Vu/18vLCrl27AADFxcW4efMmNm7ciMmTJ6Njx46wtbXFRx99BLFYDBcXF7i6ukIikaB58+ZwdHSESCTC+++/D0NDQ9jY2KBBgwbw8PBAly5dIJPJMGbMGAQGBmLhwoUICwvDhg0b0KpVK0ydOhUxMTGwt7cvLXbGxsbo3r07vv32W1y8eLFS/owVFBQgODgYZmZmiIiIeOcC+S68vLxw9OhRwcYnIiovlj09U1xcjPbt22PZsmVCR6H/k5GRgTp16uCrr74q8/m1sty9exfR0dEwNDTE0KFDIZFIYGBgAAcHB/To0QOzZs3C1q1bceXKlXI965aVlYVLly5h3759WLVqFaZNm4a+ffvCxcUFH374Id57772XPho0aABXV1d8+OGHqFu3LszNzWFkZIT33nsPBgYGMDExgYODAxo2bAiJRIJOnTrB19cXMpkMo0aNwvjx4yGXyzF//nwoFAqEh4cjKioKmzdvhlKpxKFDh6BSqXD27Fmo1Wr89ttvSE1NRWZmJrKyspCSkgIPDw907ty5XFcjK8P06dMRHBwsyNhERG+CZU8PpaenQywWc3eNKuTx48eYNm0azMzM0KxZMwwfPhzBwcFYu3Ytdu7cicOHD6N79+5o3LgxDA0NYWJiAhsbG9ja2mLs2LGYO3cuVqxYgRUrVkChUEChUEAul2PKlCnw9/fHoEGDIJPJIJVK0a5dO0gkEtSvXx+1a9eGoaEhPvzww9KC9p9yNmDAAEyePBnfffcdduzYgZ9++glr165F165dAQAbNmxAv379Xvlenj9/jszMTKSmpuLXX3+FWq3GsWPHcODAASiVSnz//fdYtWoVFAoFvv76a8jlcnz11Vfw9/fH4MGDIZPJ4OPjA6lUCg8PD0gkEjg5OcHBwQEmJiaoVasW3nvvPbz//vuoWbMmPvjgA1hYWJRml0gkaN++PaRSKbp16waZTIYBAwbA398f/v7+CAwMhFwuR1BQEBQKBUJCQhAVFYWoqChs374dSqUSBw4cgEqlQmJiItRqNdRqNVJTU5GamopHjx4hKysLsbGxpbewiYiqMpY9PfWf3TW0sZcolV9+fj7Onz+PtWvXYsaMGRg9ejT69u0LqVSKBg0alBaZjh074r333kPfvn0hk8nwxRdfwN/fHwEBAZDL5ZDL5VAoFFi+fDmioqIQGxsLpVL5UoFJSUnB3bt3kZOTU+58kyZNKr0q7O3tDaVSWVk/in+k0WiQlZUFlUoFW1tbDBs2rPRKYGJiIlQqVWnB3L59e2mhCwkJgUKhQFBQEORyOQIDA0uL4IABAyCTydCtWzdIpVK0b9++tEA6ODjAwcEBYrEYIpEINWvWLC2dIpEIIpEIdnZ2L13RdHd3h1QqhVQqRb9+/SCTyTB8+HD4+/tjwoQJL/2uFAoFVq1ahaioKMTExECpVGLnzp1QqVRQqVRITk6GWq3G1atXkZqaivT0dGRlZSErK4uPZRDR32LZ02N+fn6YPHmy0DHoLZw9exZNmjTR+rienp748ccfkZGRARMTE+Tm5mo9Q1mePn2K3r17o2XLlrhz547WxjU3N0dGRkZp6UpPT3/piubZs2dLy9qOHTugVCoRExODqKio0qub/7kKK5fLMWHCBPj7+2P48OGQyWTo169faVl0d3eHRCJBw4YN4eDgADs7u9KS+f7775feQheJRBCLxaXl9HVXO/38/EpL7rRp0yCXy/H1119DoVDg22+/LS3HP/zwA5RKJQ4ePAiVSoWTJ09CrVbjwoULpVc7/3N7nYiqJpY9PZaVlcXdNaqpefPmYebMmVoft2nTprh8+TKCg4Mxbtw4rY//d0pKShAaGgpLS0scOHBAK2P6+Phgz549WhmrPJ4/f46srCw8fPgQqampuHXrVult6BMnTkClUmH//v1QKpXYtm1baaFbtmwZFAoF5s+fD7lcjqlTp5YWQZlMBplMBl9f35ceA3BzcystlCKRqPT2+gcffACRSAQzMzM4ODigXr16kEgkaNmyJaRSaemzm0OHDoW/vz8mTZoEuVyOBQsWYOnSpYiKisKmTZtKr0T/+OOPUKvVuHXrVmmx5pVMojfDsqfnjh8/jtq1a+Phw4dCR6E34OrqKsh2XQMHDsT69evh4OAAtVqt9fHL46effkKdOnUgl8tRVFRUqWPNnz8f8+bNq9Qxqpv/3F5/8uQJUlNTcf36dajVaiQlJb10a33Tpk2IiopCWFgYFAoF5s2bhxkzZsDf3x9Dhw5F//79IZVK0bp169Lb6NbW1qVXMj/66COIRCLUqVMHLi4ucHd3R+fOnUtnkU+bNg2LFi3CihUrsGnTJuzduxeJiYm4dOkS0tLSuLcx6RWWPcKcOXPQpUsXQZexoPK7d+8eTExMBNk3dv/+/TA2NkbLli21Pvab+OOPP9CtWze0bdsWGRkZlTbO/v374e3tXWnnp9fLz89HVlYW0tLSkJKSgrNnz+LIkSNQKpWlVyvnzJmDgIAADBkyBD169EC7du3QrFkz1K1bFwYGBjA0NISLiwvatm2LAQMGYOLEiVi0aBE2bNiAAwcO4Nq1a6/duYaoOmHZIxQXF6NNmzZQKBRCR6Fy2Ldvn6AFQ6lUavW5uLdVUlIChUIBKyurSntU4eHDhzA1Na2Uc1Ple/bsGa5fv45Tp07hhx9+QHh4OGbPno2RI0fC19cXLi4uqFGjBurUqQNfX18EBQXh4MGDVeZZVaLyYtkjAH+t22ZpaYmffvpJ6Cj0DxYuXAi5XC50jGojMTERlpaW+Pbbbyvl/HZ2drh161alnJuEV1xcjNu3b2PPnj2YO3cuOnToACMjI/j7+yMzM1PoeETlwrJHpfbt24e6detyVl0V169fP2zfvl3oGNXK3bt3IZFIMGjQoAp/Vou/D/3z5MkTjBw5Er169RI6ClG5sOzRSyZNmsSFYqu4Fi1a4Ny5c0LHqHby8vIwbNgwuLq6VujeukuWLMHUqVMr7HxU9V29ehUtW7bE119/LXQUonJh2aOXFBQUwM3NDatXrxY6Cr2Gi4sLrl+/LnSMaissLAyWlpYV9hzfsWPH0K5duwo5F1VdGo0G8fHx6NOnDywtLbF69WpOaqNqg2WPXnHr1i2IxWJcunRJ6ChUBisrK9y7d0/oGNXaiRMnYGVlhdDQ0Hc+V3Z2Nj799FNBZkdT5crJycHOnTsxYsQImJubo0WLFoiKisKzZ8+Ejkb0Rlj2qExbt26Fi4sL/1KrgiwsLLguYgW4c+cO3NzcMHjw4HeeXeni4oJffvmlgpKRkG7evInw8HB07twZRkZG8Pb2xqpVqyr01j+RtrHs0WuNGjUKQ4cOFToG/Q9XV1dcvHhR6Bg6ITc3F0OGDIGrqyvS09Pf+jxDhgxBdHR0xQUjrSgpKcHVq1cRGRmJQYMGoXbt2rCxscGoUaMQFxfH/+ySzmDZo9d68eIFGjZsiE2bNgkdhf6Lt7c34uPjhY6hU7777jtYWloiISHhrd4fFhaG8ePHV3AqqmjFxcVQq9X47rvv0Lt3b5iZmcHJyQkjR45EdHQ0l9AhncWyR3/r119/hVgsRkpKitBR6P9MnDix0taM02cJCQmwtLREeHj4G783KSkJn332WSWkonfx8OFD7N27F3PmzIFUKoWRkREaN26MCRMmYPv27Xz2lfQGyx79o6ioKDRr1gx5eXlCRyH8tYNFjx49hI6hk9LS0tC8eXMMHz78jf685+bmwsDAAAUFBZWYjv5Obm4uTp8+jeXLl8PPzw916tSBiYkJunbtWrrzBRdBJn3Fskfl4ufnx9tUVcSjR48gEomg0WiEjqKTXrx4gYEDB8Ld3f2N9tV1dXXl+odaotFocPXqVURHR2PcuHFwdXWFgYEB3N3dMXHiRGzevBk3btwQOiZRlcGyR+WSnZ0NR0dHKJVKoaMQ/lpYmc/tVZ7/7KtrY2OD06dPl+s9o0eP5vqUlUCj0SAlJQWxsbGYMmUK2rZti08//RTOzs4YPHgwwsPDcfbsWeTn5wsdlajKYtmjcrt06RLEYjGuXr0qdBS9t2rVKgwaNEjoGDrv8OHDsLCwQFRU1D9+7dq1azFixAgtpNJdJSUluHHjBrZs2YKpU6eiffv2MDY2hpOTE/z8/LBs2TIkJCQgOztb6KhE1QrLHr2RzZs3o169evzLVmCZmZmoVasWnj59KnQUnXfz5k00bNgQY8eO/dtn8i5evIhGjRppMVn1VlJSgt9++w3btm3DtGnT0KFDBxgZGcHBwQEymQxLly7FsWPH+GecqAKw7NEbmzRpEnr16sWtggQ2fPhwfPPNN0LH0As5OTno1asXWrdu/doFrYuKimBoaMi12cpQVFSEK1euICYmBpMmTULbtm1hZGQEe3t79O/fH0uWLIFKpUJWVpbQUYl0EssevbGCggK0bdsWCxcuFDqKXrt27RrEYjFevHghdBS9UFJSggULFsDW1hZqtbrMr/H09MTJkye1nKxqycvLw9mzZ7FmzRp8+eWXaNGiBQwMDNCgQQN8/vnnCAkJwbFjxzgzlkiLWPborTx48AC2trY4fPiw0FH0Wr9+/RAWFiZ0DL2ye/duiMVi7N69+5VjkyZNwvLlywVIJYw///wTiYmJ+O677zBs2DA0btwYNWvWhJubG7744gusWrUKZ86c4dVOIoGx7NFbO3PmDCwsLHDz5k2ho+itK1euwNLSEn/++afQUfSKWq2Gra0tQkNDX3p98+bN8PPzEyhV5Xr8+DEOHz6MJUuWQCaTwdHREYaGhmjVqhXGjx+PdevW4cKFC1xrkKgKYtmjd7Jx40Y4OTnh8ePHQkfRWyNHjsSsWbOEjqF37t69iyZNmmD8+PEoLi4GAFy/fh2Ojo4CJ3t3d+7cQVxcHObPn48ePXrAxsYGtWrVQqdOnRAYGIgtW7bg2rVrpd83EVVtLHv0zmbOnAkPDw8+OyaQjIwMmJqa4u7du0JH0Ts5OTnw8fGBj48PcnJyUFJSAmNjY/zxxx9CRysXjUaDGzduYOvWrZgxYwakUilMTExgZWWFbt26Yc6cOYiLi8Pt27eFjkpE74Blj95ZSUkJBg8eDJlMxhm6Apk3bx6GDRsmdAy9VFxcjLFjx6Jp06b4/fffIZVKcejQIaFjvaKwsBCXL1/G+vXrMXHiRLRp0waGhoZwcHBA//798c033+DQoUN48OCB0FGJqIKx7FGFKCgogJeXF2bOnCl0FL2Uk5MDa2trXLhwQegoeis0NBS2trbo2rUr5s+fL2iW7OxsnDp1CqtXr8aYMWMgkUhQs2ZNNGzYEEOGDMG3336LEydOcA07Ij3BskcV5vHjx3ByckJERITQUfRSVFQUOnXqJHQMvRYXFwcDAwN4e3trZbzi4mLcuHEDSqUSc+bMQc+ePWFvbw9DQ0O0bNkS/v7+WL16Nc6ePcvHLIj0GMseVaibN2/Czs4OMTExQkfRO0VFRWjYsCEOHDggdBS9lpiYiBo1alT4rdzMzEycOHECK1aswOjRo9GiRQt88skncHR0RJ8+fRAUFISdO3fi1q1b0Gg0FTo2EVVvLHtU4a5fvw5ra2ts27ZN6Ch658CBA2jYsCGKioqEjqLX7O3tUaNGjTLX4vsnRUVFuHr1KrZt24aZM2eiW7dusLW1hZGREVq3bo1x48ZhzZo1SEpKQk5OTiWkJyJdw7JHleLXX3+FpaUlduzYIXQUvSOVSnkrXWADBw7EV199BWtr67+90vrkyRMcO3YMoaGhGDlyJNzc3GBgYABnZ2f0798fCxcuxJ49e3D79m1OfiKit8ayR5XmypUrsLa2fqurG/T2rly5AgsLCy60LKCwsDBMmDABZ8+ehZmZGRITE/Hzzz8jNjYWM2bMgLe3N6ytrVGrVi20b98eAQEBWLt2LZKTk/lsHRFVOJY9qlQXLlx47dZSVHm++OILBAYGCh1DLz18+BArVqxA7dq1MXToUDg6OuL999+Hg4MDBgwYgODgYOzfvx937twROioR6QmWPap0ly5dQu3atbFixQqho+iNx48fw8LCApcuXRI6is7Ky8vDxYsXsXHjRgQGBqJz584Qi8UwMTFBhw4d8K9//QuRkZE4f/48YmJi4OjoiMzMTKFjE5EeYtkjrUhPT0ejRo0QGBjIZ4+0ZN26dXB3d+eWVu+oqKgIKSkpUCqVmD9/Pvr164d69eqhZs2aaNq0KT7//HMoFAocPnwYGRkZpe9zd3fHjz/+WPr59OnT4e3tzd8HEWkdyx5pTVZWFjp06ACZTIa8vDyh4+i8kpIStG/fHkuXLhU6SrVQUlKC27dvY9++fViyZAkGDRqE5s2bo2bNmnB2dkbfvn0xb948/PDDD7h69SoKCwv/9nyTJk3C8uXLSz8vLi6GVCrF119/XdnfChHRS1j2SKvy8/MxaNAgtG3bttrsH1qd/f7777C0tMTx48eFjlKl3L9/H0ePHkVoaChGjRoFd3d3GBoaws7ODj4+Ppg+fTo2btwItVqN3NzctxojNjYW/fv3f+m1Bw8e8PY6EWkdyx5pXUlJCebMmYPatWvj1KlTQsfReYmJibCystLLCQFZWVk4deoUIiMjMW7cOLRv3x4mJiYwNzdHx44dMXHiRERFReHMmTPIzs6u0LFv3ryJ2rVrv/J6dHQ03NzcuBYiEWkNyx4JJj4+HlZWVli4cCFX/K9kkZGRcHBwQHp6utBRKsWzZ89w7tw5bNiwAYGBgejSpQtsbGxgZGQET09P+Pv7Izw8HMePH8ejR4+0lsvc3BwPHjx45fUuXbogLCxMazmISL+x7JGg7t27By8vL3Tq1KnMfxSp4kRERMDKygqJiYlCR3krJSUlSE9Px5EjR7By5UqMHz8eUqkUtra2qFmzJtzc3DB06FAsXboUBw8erBLFtnv37oiLi3vl9StXrsDS0hLPnz8XIBUR6RuWPRJccXExgoKCYGNjg8OHDwsdR6cdPXoU1tbWGD9+PB4/fix0nDJlZmbi/Pnz2Lp1K+bPnw8/Pz+4urrCwMAANjY26NixI8aNG4ewsDDEx8cjLS2tyl4ZXrBgAWbMmFHmsYEDByI4OFjLiYhIH7HsUZWRkJCAunXrYujQoVW2iOiCp0+fIiAgACYmJpg0aRLUarVWxy8sLMStW7dw9OhRrFmzBjNmzED//v3h5uaGWrVqwdjYGK6urpDJZJg7dy5iY2Nx/vz5arkP7NGjR9GhQ4cyj924cQNisZgz04mo0rHsUZXy7NkzBAYGQiwWY/369VyTrxL9/vvvCAoKgpOTE+zs7DBy5EisWbMGp0+ffqtb6i9evMCF+zX1AAAWW0lEQVTDhw/x888/4+DBg4iOjsaiRYsQEBCA3r17w9PTE7a2tvj4449Rt25ddOzYEaNHj8bixYuxfft2nDt3TudmaGdnZ8PIyOi1a+t17doVmzdv1nIqItI3LHtUJV2+fBmfffYZ2rZti2vXrgkdR+elpKRg9erVGDNmDDw8PGBpaYmPPvoItra2cHBwgIuLCyQSCSQSCby8vNCiRQs0aNAAtra2EIlEeP/992FgYACxWIwmTZrAx8cHI0aMwJw5c7By5UrExcUhKSkJd+7c+cf16XRN/fr1cfny5TKPxcXFoU2bNlpORET6hmWPqiyNRoOVK1fC3Nwcs2fPxrNnz4SOpFfy8/Nx584dpKam4vr161Cr1VCr1Th+/DjOnTuHa9eu4c6dO8jKyqqyz8xVBSNGjMCaNWvKPFZUVAQrKyvcuHFDy6mISJ+w7FGVd+/ePQwbNgxisRhhYWEoKCgQOhJRua1ZswajRo167fEJEyZwlxMiqlQse1Rt/PLLL+jRowfq1KmDjRs38moSVQuXL19Go0aNXnv86NGj8PT01GIiItI3LHtU7Zw+fRpt2rRB48aNsW/fPqHjEP2t4uJiGBsbv3bySWFhIUQiER4+fKjlZESkL1j2qNrav38/mjRpglatWmH//v2cuUtVlq+vL3bv3v3a43379sXWrVu1mIiI9AnLHlVrGo0GP/zwAyQSCRo1aoSYmBi9m+1JVZ9CocDkyZNfezw8PBxjx47VYiIi0icse6QzVCpV6fZZoaGh1XIRXtJNP/30E1xdXV97/OLFi2jYsKEWExGRPmHZI51z4cIF+Pn5wdzcHHPmzNHqxvdEZSksLISRkRGePn1a5nGNRgORSIQnT55oORkR6QOWPdJZqampGDduHExMTDB8+HAkJycLHYn0WOfOnXHgwIHXHu/UqRP3hiaiSsGyRzovMzMTISEhcHJygkQiwfr165Gbmyt0LNIzixYtwvTp0197fNq0aQgODtZiIiLSFyx7pDc0Gg0OHz6MHj16wNTUFIGBgfjtt9+EjkV64tSpU/Dw8Hjt8W3btqFfv35aTERE+oJlj/RSWloaZs6cCQsLC3h7e2Pv3r2v3ayeqCLk5+fD0NDwtdv+paSkwNHRUcupiEgfsOyRXsvPz8fmzZvRqlUrWFtbQy6XIyUlRehYpKM6dOiA+Pj4Mo9pNBp88skn3AOaiCocyx7R/0lJScHUqVMhFovRrl07REdH8x/eKiQ+Pr7ar6E4f/58zJ49+7XH3dzccO7cOS0mIiJ9wLJH9D8KCwuxZ88e9OrVCyKRCCNGjMDJkye5Q4eA/vzzT3h5ecHa2hrz5s1DRkaG0JHeyvHjx9GmTZvXHh8yZAhiYmK0mIiI9AHLHtHfePToEZYtW4b69evDxcUFCoUC9+7dEzqW3rp27RoCAgJgamqKvn37QqVSVasS/uLFCxgaGr52NvjixYshl8u1nIqIdB3LHlE5JSUlwd/fHyYmJujatStiY2N5m1cgz549Q1RUFJo3b4569eohNDT0tQsWVzWtW7dGQkJCmcf27NmD7t27azkREek6lj2iN5Sbm4sffvgBPXr0gEgkwpAhQxAfH8/ZvAJJSkrC4MGDIRKJMGrUKKjVaqEj/a1Zs2YhKCiozGMpKSlwcnLSbiAi0nkse0Tv4MmTJ1i5ciU8PT1hZWWFKVOm4MKFC0LH0kuPHz+GQqGAvb093N3dER0djby8PKFjvSI+Ph5t27Yt81hBQQE+/vhjFBUVaTkVEekylj2iCnLz5k0EBQXB0dERDRs2xOLFi3Hnzh2hY+kdjUaDgwcPwtfXF+bm5pg2bRpu3rwpdKxSubm5MDQ0RE5OTpnH7e3tq1ReIqr+WPaIKkFSUhImTJgAMzMztGvXDlFRUcjMzBQ6lt65ffs25HJ56eLZe/bsqRK32zt16oR9+/aVeczb2xsHDx7UciIi0mUse0SVqLCwEHv37oWfnx+MjY3RvXt3bN68mRM7tCw/Px+xsbHw9PREnTp1EBwcjIcPHwqWR6FQICAgoMxj48ePx4oVK7SciIh0GcsekZY8e/YMW7ZsQffu3WFkZAQ/Pz/ExcUhPz9f6Gh65dKlS6WzqgcOHIiTJ09qPcPFixfh4uJS5rGwsLDXFkEiorfBskckgMzMTHz//ffw8vKCSCTCyJEjOaNXy7Kzs7Fy5Uo0aNAATZo0QURExGufo6toJSUlEIvFSE9Pf+XY/v370bVrV63kICL9wLJHJLD79+8jLCwMHh4eEIvFmDBhAk6fPl2tFguuzkpKSpCQkACZTAYTExOMGzcOP//8c6WPO2jQIKxdu/aV169fv87lV4ioQrHsEVUhqampCA4ORuPGjWFnZ4fp06dzKRctun//PhYuXAgbGxu0a9cO27ZtQ0FBQaWMFR0djQEDBrzyemFhIWrUqFHt9wEmoqqDZY+oivrll18wZ84cODs7w9nZGbNnz8bly5eFjqUXioqKsGvXLkilUlhaWmL27Nll3nJ9FxkZGTAxMYFGo3nlmL29PW7cuFGh4xGR/mLZI6oGLly4ALlcDnt7e9SvXx9BQUH49ddfhY6lF27cuIEpU6bA1NQUvXr1Qnx8fJkF7W00atQI586de+X1rl27cvkVIqowLHtE1UxycjICAwNhZ2eHRo0aYeHChbwKpAUvXrzAunXrIJFI4OTkhJCQEPzxxx/vdM7JkycjODj4ldcDAgIQHh7+TucmIvoPlj2iaqqkpARJSUmYNGkSbGxs0KxZMwQHB+PWrVtCR9N5ycnJGDFiBGrVqoURI0YgOTn5rc5z8OBBtG/f/pXXly9fjilTprxjSiKiv7DsEekAjUaDU6dOISAgAJaWlpBIJFi6dCnS0tKEjqbT/vjjD4SEhMDJyQkSiQTr1q3Dixcvyv3+58+f49NPP31lke3t27dDJpNVdFwi0lMse0Q6pri4GAkJCRg3bhzEYjE8PDywfPly3L17V+hoOkuj0SA+Ph69evWCqakppkyZUu5b615eXjhw4MBLr50+fRqtW7eujKhEpIdY9oh0WHFxMY4ePYrRo0fDzMwMrVu3Rnh4OO7duyd0NJ2Vnp6O2bNnw9LSElKpFLt27UJRUdFrv37JkiWYOHHiS6/dvn0bderUqeSkRKQvWPaI9ERhYSEOHz6MESNGwMTEBO3atcPq1asF3SNWlxUUFGDbtm1o164dbGxssHDhQty/f/+Vr1Or1WjQoMEr7/3oo4+4sDYRVQiWPSI9VFBQgP3792Po0KEQiUTo2LEj1qxZgydPnggdTSf9/PPPGDduHExMTCCTyZCQkFBa5DQaDczMzF65zW5hYcEiTkQVgmWPSM/l5eVh9+7d+Pzzz1GrVi14e3tj3bp1yMzMFDqazsnJyUFERASaNGmCBg0aYOXKlcjOzsbAgQOxfv36l77Wzc0N58+fFygpEekSlj0iKpWbm4sdO3ZAJpPB2NgYvr6+2LhxI54+fSp0NJ1z8uRJDBw4ECYmJujQoQO8vb1fOt6zZ0/s2bNHoHREpEtY9oioTM+fP8e2bdvQp08fGBsbo0ePHti8eTOLXwV7+PAh5HI5PvjgA7Rs2RKxsbHIz8/HuHHjsHr1aqHjEZEOYNkjon+Uk5OD2NhY9OzZE8bGxujWrRtiYmKQlZUldDSd0bRpUyxduhTe3t6wsLBAu3btMH78eKFjEZEOYNkjojeSk5ODLVu2oE+fPjAyMoKPjw/Wr1//zluH6buZM2di/vz5AICbN2+ia9euqFGjBnx9fXHw4MEK24+XiPQPyx4RvbXnz59j+/bt6N+/P4yMjNClSxdERUVxVu9bSEhIgIeHR+nnKpUKXl5eiI6Ohru7O+zt7aFQKPD48WMBUxJRdcSyR0QV4sWLF9ixYwf8/PxgbGyMTp06ITIyksuHlFNhYSGMjY1Lr5CmpKTAxcWl9LharcaoUaMgEokwePBgJCUlCRWViKoZlj0iqnC5ubmIi4vDoEGDIBKJ0L59e6xatarMRYXp/+vduze2bNkC4K/b5YaGhq98zdOnTxEaGop69eqhefPmiIqKemVvXSKi/8ayR0SVKi8vD3v37sWQIUNgYmKCtm3bIjw8HL///rvQ0aqciIgIDB8+vPRzIyOj185+LikpgUqlQt++fWFqaoqAgABcu3ZNS0mJqDph2SMircnPz8eBAwcwYsQImJqaolWrVggNDcWdO3eEjlYlpKenw8LConR3jYYNG+Lq1av/+L6MjAzMmzcP1tbW8PLyglKpRGFhYWXHJaJqgmWPiARRWFiIQ4cOYdSoUTAzM4OHhwdCQkKQlpYmdDRB1a9fHxcuXAAASKVSHDlypNzvLSwshFKphJeXF6ysrBAUFISMjIzKikpE1QTLHhEJrqioCEeOHIG/vz/MzMzQokULKBQKpKamCh1N6yZNmoQlS5YAAEaOHIkNGza81XmuXr2KgIAAmJqaol+/fjh+/HjpFUMi0i8se0RUpRQXF+P48eMYO3YsLCws4OrqisWLF+PGjRtCR9OK+Ph4tG3bFgAwb948LFy48J3O9+zZM0RGRqJZs2aoX78+wsPDkZ2dXRFRiaiaYNkjoiqruLgYiYmJmDBhAqysrNC0aVMsWrRIpyci5ObmwsjICNnZ2YiMjIS/v3+FnfvHH3/E559/DhMTE4wZMwaXLl2qsHMTUdXFskdE1YJGo8GpU6fw1VdfoXbt2mjcuDHmz5+PK1euCB2twnXt2hVxcXHYs2cPevbsWeHnf/jwIYKDg1GnTh14enqW7sdLRLqJZY+Iqp2SkhKcOXMGU6dORd26deHk5AS5XI5z587pxHNp4eHhGDNmDJKSkl7aVaOiFRcXY8+ePaX78c6cOVPvJ8gQ6SKWPSKq9tRqNWbNmgUXFxfUqVMHU6dOxZkzZ6rtfrIpKSmws7PDjRs34OzsrJUxb968icDAQJiZmaF79+44dOhQtf35EdHLWPaISKf88ssvWLBgAZo2bQpra2tMmDABx48fR3FxsdDR3oiTkxOOHTsGkUik1XFzc3OxYcMGfPbZZ3BwcMCyZctKt3AjouqJZY+IdNb169exePFiSCQSmJmZYfTo0Th06BAKCgqEjvaP5HI5Zs2ahQ8//BBFRUWCZDh37hxGjBgBkUiEYcOGITk5WZAcRPRuWPaISC+kpaVh+fLlaNWqFUxNTTF8+HDs3bsXeXl5Qkcr04ULF+Ds7AyxWIyHDx8KmiUzMxMhISFwcnKCRCLBunXrkJubK2gmIio/lj0i0jv37t3DqlWr4OXlhVq1asHPzw9KpRLPnz8XOtpLHB0d8eGHH1aZ7eQ0Gg3i4+PRs2dPmJqaYtq0abh9+7bQsYjoH7DsEZFee/ToEaKiotClSxcYGRmhd+/e2Lx5c5VYeHjx4sV47733kJWVJXSUV9y+fRvTpk2DqakpevbsiaNHj+rETGgiXcSyR0T0f7KyshATE4OePXvC2NgY3bp1w4YNGwSboPDixQscOnRIkLHL68WLF1i7di2aNm2K+vXrY9WqVcjJyRE6FhH9F5Y9IqIy5OTkYPv27ZDJZDA2NkaHDh0QGhqql/v1ltepU6cwYMAAmJqaYuLEiXqzxR1RVceyR0T0D/Ly8nDw4EGMGTMGFhYWaNasGebNmwe1Ws1bl2XIyMjA3LlzYWlpic6dO2Pv3r1cs49IQCx7RERvQKPRICkpCXK5HPXq1YOdnR0CAgKgUqlQWFgodLwqJT8/H5s3b4a7uzvs7e0REhJSJZ8/JNJ1LHtERO8gJSUFCoUCLVu2hImJCQYPHgylUsnn1v5HcnIyhgwZApFIhDFjxujknsZEVRXLHhFRBbl//z6ioqLg6+sLIyMj+Pj4ICoqCvfv3xc6WpXx6NEjBAcHo3bt2mjfvj127dpV7XY3IapuWPaIiCpBTk4OlEolBg8eDFNTU3h4eEChUCAlJUXoaFVCUVERduzYgVatWsHBwQHh4eG8GkpUSVj2iIgqWWFhIVQqFQICAmBnZwcnJydMnjwZR44cqbI7eGjT2bNnIZPJYGZmhunTp+Pu3btCRyLSKSx7RERadvHiRSxevBitWrWCkZERevXqhcjISL0vOenp6Zg6dSpMTU0xcOBAXLp0SehIRDqBZY+ISECZmZnYtm0bBg8eDHNzczRp0gTTp0+HSqXS26t+f/75J0JCQmBjYwNfX1/8+OOPQkciqtZY9oiIqgiNRoOffvoJQUFBaNWqFT799FN06dIFISEhuHz5st6t6Zefn4+1a9fC0dERbdq0qfK7iRBVVSx7RERVVHZ2Nnbv3o3x48fD2dkZFhYWGDx4MNauXatXEz2Ki4uxdetWNG3aFC1btkRCQoLQkYiqFZY9IqJqIi0tDevXr8ewYcNQt25dWFhYoH///lixYgWuXLmi87tUaDQabNu2DU5OTujSpQvUarXQkYiqBZY9IqJq6u7du4iNjcWXX36JBg0awMjICB06dMD06dOxc+dO3LlzR+iIlaKwsBCRkZGwsbHBhAkT8OzZM6EjEVVpLHtERDri6dOnOHLkCL755hv06tULVlZWsLCwQOfOnTF16lRs2LAB586dw/Pnz4WOWiGePn2KkSNHwt7eHidPnhQ6DlGVxbJHRKTDMjIycOjQISxduhRDhw6Fm5sbDAwMULduXXTq1An+/v5YunQpduzYgQsXLuDBgwfVbiLIoUOHYG5uzlm7RK/BskdEpGeKi4tx69YtHD16FBEREZg2bRr69OmD5s2bw8LCAv/+979Ru3ZteHp6onfv3vD398fs2bMRGhqKjRs34sCBAzh79iyuXbuGe/fuVYnbqMuXL4dMJhM6BlGVxLJHREQvKSgowO+//46kpCTs3r0bkZGRWLRoESZPnoyhQ4fCx8cH7u7uqF+/PqysrPDJJ5/ggw8+gEgkgr29PZo1a4Z27dqhe/fuGDRoEMaOHQu5XA6FQlH6ERUVVfqhVCqhVCqxY8cOqFQqqFQqHDt2DGq1GgkJCaWvqVQqHDlyBEqlEtHR0QgJCcGYMWPQunVrmJmZIT4+XugfHVGVxLJHRETvrLi4GFlZWUhNTcWlS5eQmJiIffv2ITY2FhEREVAoFJDL5aUf/v7+8Pf3x5gxYyCTySCTydC/f39IpVJIpVJ06tQJEokEXl5epa9JpVJ07twZMpkMw4cPx5QpUxAZGYnjx4/r7QLUROXBskdERESkw1j2iIiIiHQYyx4RERGRDmPZIyIiItJhLHtEREREOoxlj4iIiEiHsewRERER6TCWPSIiIiIdxrJHREREpMNY9oiIiIh0GMseERERkQ5j2SMiIiLSYSx7RERERDqMZY+IiIhIh7HsEREREekwlj0iIiIiHcayR0RERKTDWPaIiIiIdBjLHhEREZEOY9kjIiIi0mEse0REREQ6jGWPiIiISIex7BERERHpMJY9IiIiIh3GskdERESkw1j2iIiIiHQYyx4RERGRDmPZIyIiItJhLHtEREREOoxlj4iIiEiHsewRERER6TCWPSIiIiIdxrJHREREpMNY9oiIiIh0GMseERERkQ5j2SMiIiLSYSx7RERERDqMZY+IiIhIh7HsEREREekwlj0iIiIiHcayR0RERKTDWPaIiIiIdBjLHhEREZEOY9kjIiIi0mEse0REREQ67P8B5NS7sfj7t68AAAAASUVORK5CYII=";
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
