<?php
/*
* Protótipo do planeta afeto.
*/
session_start();

require_once("../../usuarios.class.php");
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../reguaNavegacao.class.php");
require_once("grafico.class.php");
require_once("subjetividade.class.php");
require_once("afetividade.class.php");


?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="ui.core.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="../../planeta.css" rel="stylesheet" type="text/css">
		<script src="../../planeta.js" type="text/javascript"></script>
		<script type="text/javascript" src="../lightbox.js"></script>
		<script type="text/javascript" charset="utf-8">
			window.onload = function () {
				var r = Raphael("grafico1", 620, 620);
				GRAFICO = new Pelota(515,r);
				var dados = [<?php /*echo implode(',',$dados);*/ ?>];
				GRAFICO.pts(dados);
			};
		</script>
		<script type="text/javascript" language="javascript">
			$(function tornarDraggableInforgrafo () {
				$("#infografo").draggable();
			});
			
			$(document).ready(function(){	
				document.getElementById('abre_ajuda').onclick = function(){
					document.getElementById('infografo').style.display = 'block';
				}
				document.getElementById('fecha_info').onclick = function(){
					document.getElementById('infografo').style.display = 'none';
				}
				
				$('.botao').click(function(){
					$('.botao').removeClass('botao_ativo');
					$(this).addClass('botao_ativo');
					$('.conteudos').css('display','none');
					$('#conteudo' +this.id.substr(5)).css('display','block');
				});
			});
		</script>
		<style type="text/css">
			html { font-family:Verdana, Arial, Helvetica, sans-serif}
			#logo { width:100%; height:130px; background-color:#333333; margin-bottom:10px; position:relative; float:left}
			#logo img { position:relative; top:50%; margin-top:-50px}
			.bg0 { background-color:#7B8694; color:#FFFFFF; font-size:10px; font-weight:bold;}
			.bg1 { background-color:#E1E4E8; color:#333333; font-size:10px; font-weight:bold;}
			.bg2 { background-color:white; color:#333333; font-size:10px; font-weight:bold;}
			.largura { width:100%}
			.largura2 { width:48%; padding:2px 3px}
			#ajudaafeto { background-color:#1A1A1A; margin-bottom:0px; float:left}
			#ajudaafeto2 { background-color:#1A1A1A; margin-bottom:0px; float:left}
			#ajudaafeto3 { background-color:#1A1A1A; margin-bottom:0px; float:left}
			#ajuda img { cursor:pointer}
			#nome, #periodo { padding:2px 0; margin:2px 0; float:left}
			#inicio_fim { float:left; margin:2px 0}
			#inicio { float:left}
			#fim { float:right}
			.botao { width:32%; padding:3px; height:50px; background-color:#ACB1B5; cursor:pointer}
			#botoes { margin-top:20px; float:left}
			#botao1 { float:left}
			#botao1 p{ margin-left:28px}
			#botao1 img{ margin-top:0}
			#botao2 { float:left; margin-left:4px}
			#botao2 p{ margin-left:18px}
			#botao3 { float:right}
			#botao3 p{ margin-left:30px}
			.botao p { margin-top:12px; margin-right:7px; font-size:12px; float:left}
			.botao img {margin:3px 0; float:left}
			.botao_ativo { height:53px; background-color:#E1E4E8}
			#infografo { display:none; position:absolute; z-index:10; cursor:move; top:180px; background-color:#AAAAAA; border:1px solid #333333}
			#fecha_info { background-color:#AAAAAA; cursor:pointer; padding:5px; color:white}
			.conteudos { display:none; width:100%; background-color:#E1E4E8; float:left;}
			#conteudo1 { display:block}
			.graficos { position:relative}
			.grafico { width:422px; background-color:black}
			.resultado { vertical-align:top; color:white; font-size:10px; background-color:#444}
			.resultado_ul { list-style:none; padding:0; float:left; width:100%}
			.resultado li { padding:10px; text-align:left; float:left}
			.conteudos ul {position:relative;padding:0; list-style:none;}
			.afeto li { position:relative; margin:2px; padding:2px}
			li.tabela { padding:0; margin:10px}
			.tabela table { width:100%;}
			.tabela div { position:relative}
			.tipo { float:left; margin-top:4px}
			#legendageral img { margin:3px}
		</style>
	</head>

	<body onload="atualiza('ajusta()');inicia();">

		<img width="100%" height="100%" style="position:fixed" src="../../images/fundos/fundo2.png">
		<div id="topo">
			<div id="centraliza_topo">
				<?php 
					$regua = new reguaNavegacao();
					$regua->adicionarNivel("Afeto");
					$regua->imprimir();
				?>
				<p id="bt_ajuda">
					<span class="troca">OCULTAR AJUDANTE</span>
					<span class="troca" style="display:none">CHAMAR AJUDANTE</span>
				</p>
			</div>
		</div>
	
		<style type="text/css"> 
			body{background-color:#FFFFFF;} /*Necessário, pois quando exibido em lightbox o fundo fica preto.*/
		</style>
	
		<div id="geral">
			<div id="cabecalho">
				<div id="ajuda">
					<div id="ajuda_meio">
						<div id="ajudante">
							<div id="personagem">
								<img align="left" height="145" alt="Ajudante" src="../../images/desenhos/ajudante.png">
							</div>
							<div id="rel">
								<p id="balao" style="margin-top: 33.5px;">Descrição do planeta afeto... Esta descrição deve ser substituída pela final. </p>
							</div>
						</div>
					</div>
					<div id="ajuda_base"></div>
				</div>
			</div>
			<a name="topo"></a>
			<div id="conteudo_topo"></div>
			<div id="conteudo_meio" style="height: 1719px;">
				<div id="conteudo">
					<?php
						$usuario = new usuario();
						
						$afetividade = new afetividade("01-01-1900", "01-01-2100", $usuario);
						
						$grafico = new grafico();
						$grafico->setDivisaoPeriodo(grafico::DIVISAO_SEMANAS);
						$grafico->setAfetividade($afetividade);
						$grafico->imprimir();
					?>
				</div>
			</div>
			<div id="conteudo_base"></div>
		</div>
	</body>
</html>


