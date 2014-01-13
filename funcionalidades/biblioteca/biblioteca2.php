<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");
require_once("material.class.new.php");
$usuario = usuario_sessao();
$turma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
if (!$usuario) { die("voce nao esta logado"); }
$assocUsuario = $usuario->getSimpleAssoc();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Planeta ROODA 2.0</title>
		<link type="text/css" rel="stylesheet" href="../../planeta.css" />
		<link type="text/css" rel="stylesheet" href="biblioteca2.css" />
	</head>
	<body onload="BIBLIOTECA.init();">
		<div id="topo">
			<div id="centraliza_topo">
				<?php 
					$regua = new reguaNavegacao();
					$regua->adicionarNivel("Biblioteca");
					$regua->imprimir();
				?>
				<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
			</div>
		</div>
		<div id="geral">

			<noscript>
				<p>O seu javascript está desabilitado, essa pagina depende de javascript para funcionar corretamente.</p>
			</noscript>
			<!-- **************************
						cabecalho
			***************************** -->
			<div id="cabecalho">
				<div id="ajuda">
					<div id="ajuda_meio">
						<div id="ajudante">
							<div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
							<div id="rel"><p id="balao">Na biblioteca estão os materiais enviados em forma de arquivos ou links para o acesso dos participantes da turma, servindo para publicação e organização de materiais a serem acessados.</p></div>
						</div>
					</div>
					<div id="ajuda_base"></div>
				</div>
			</div><!-- fim do cabecalho -->
			<!-- **************************
						conteudo
			***************************** -->
			<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
			<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
				<div id="conteudo">
					<div class="bloco" id="materiais">
						<h1>NOME DA TURMA</h1>
						<button type="button" id="botao_enviar_material" onclick="toggleEnviar()">Enviar material</button>
						<button type="button" id="botao_buscar_material">Buscar materiais</button>
					</div>
					<div class="bloco" id="enviar_material" style="display: none;">
						<h1>ENVIAR MATERIAL<button type="button" class="bt_fechar" onclick="toggleEnviar()">fechar</button></h1>
						<div>
						<form id="form_envio_material" method="post" enctype="multipart/form-data" action="biblioteca.json.php?turma=<?=$turma?>&amp;acao=enviar">
							<strong>Tipo de material:</strong> 
							<label id="label_arquivo">Arquivo<input id="input_arquivo" type="radio" name="tipo" value="a"></label><label id="label_link">Link<input id="input_link" type="radio" name="tipo" value="l"></label>
							<br>
							<div class="material_recurso">
								<label class="file_label" style="display:none" id="label_material_arquivo">
									<span class="text">Selecionar arquivo:</span><br>
									<input type="file" name="arquivo" />
								</label>
								<label class="link_label" style="display:none" id="label_material_link">
									<span class="text">Link:</span>
									<input type="text" name="link" required />
								</label>
							</div>
							<label>Título:<br>
								<input type="text" name="titulo" required />
							</label><br>
							<label>Autor:<br>
								<input type="text" name="autor" />
							</label><br>
							<label>Palavras do Material:<br>
								<input type="text" name="tags" />
							</label><br>
							<button id="bota_enviar_material" type="submit" class="submit">Enviar</button>
						</form>
						</div>
					</div>
					<div class="bloco" id="editar_material" style="display: none;">
						<h1>EDITAR MATERIAL<button type="button" class="bt_fechar" name="fechar">fechar</button></h1>
						<div>
						<form id="form_edicao_material" method="post" enctype="multipart/form-data" action="biblioteca.json.php?turma=<?=$turma?>&amp;acao=editar">
							<input type="hidden" name="id" value="0" />
							<label>Título:<br>
								<input type="text" name="titulo" required />
							</label><br>
							<label>Autor:<br>
								<input type="text" name="autor" />
							</label><br>
							<label>Palavras do Material:<br>
								<input type="text" name="tags" />
							</label><br>
							<button id="bota_enviar_material" type="submit" class="submit">Enviar</button>
						</form>
						</div>
					</div>
					<div class="bloco" id="materiais_enviados">
						<h1>MATERIAIS ENVIADOS</h1>
						<ul id="ul_materiais">
							<li>Carregando materiais...</li>
						</ul>
					</div>
				</div>
			</div><!-- fim do conteudo -->
			<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
		</div>
		<!-- JAVASCRIPT -->
		<script src="../../jquery-1.10.2.min.js"></script>
		<script src="../../js/rooda.js"></script>
		<script src="../../js/ajax.js"></script>
		<script src="../../js/ajaxFileManager.js"></script>
		<script>
		/* -  /
		Array.prototype.forEach.call(document.getElementsByTagName("label"), function (l) {
			if (l.control.type === 'file') {
				t = document.createElement("span");
				l.appendChild(t);
				l.classList.add("file_label");
				l.control.hidden = true;
				l.control.onchange = function () {
					var files = [], i;
					for (i = 0; i < l.control.files.length; i++) {
						files.push(l.control.files[i].name);
					}
					t.innerHTML = files.join(", ");
				}
			}
		});
		/* - */
		;(function () {
			var botao_enviar_material  = document.getElementById("botao_enviar_material");
			var botao_buscar_materiais = document.getElementById("botao_buscar_materiais");
			var enviar_material  = document.getElementById("enviar_material");
			var buscar_materiais = document.getElementById("buscar_materiais");
		}());


		var label_radio_arquivo = document.getElementById("label_arquivo");
		var radio_arquivo = label_radio_arquivo.control;
		var label_radio_link = document.getElementById("label_link");
		var radio_link = label_radio_link.control;
		var label_material_arquivo = document.getElementById("label_material_arquivo");
		var label_material_link = document.getElementById("label_material_link");
		radio_arquivo.onchange = function () {
			var changeEvent = new Event('change');
			label_material_arquivo.style.display = "none";
			label_material_link.style.display = "none";
			label_material_arquivo.control.required = false;
			label_material_link.control.required = false;
			if (radio_arquivo.checked) {
				label_radio_arquivo.classList.add('checked');
				label_link.classList.remove('checked');
				label_material_arquivo.style.display = "inline-block";
				label_material_arquivo.control.required = true;
				label_material_link.control.value = "";
				label_material_link.control.dispatchEvent(changeEvent);
			} else {
				label_radio_arquivo.classList.remove('checked');
				label_radio_link.classList.add('checked');
				label_material_link.style.display = "inline-block";
				label_material_link.control.required = true;
				label_material_arquivo.control.value = "";
				label_material_arquivo.control.dispatchEvent(changeEvent);
			}
		}
		radio_link.onchange = radio_arquivo.onchange;
		radio_arquivo.style.display = "none";
		radio_link.style.display = "none";
		var toggleEnviar = (function () {
			var enviarDiv = document.getElementById('enviar_material');
			return function () {
				if (enviarDiv.style.display !== 'none') {
					enviarDiv.style.display = 'none';
				} else {
					enviarDiv.style.display = 'block';
				}
			};
		}());
		</script>
		<script type="text/javascript" src="biblioteca2.js"></script>
	</body>
</html>