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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Planeta ROODA 2.0</title>
		<link type="text/css" rel="stylesheet" href="../../planeta.css" />
		<link type="text/css" rel="stylesheet" href="biblioteca2.css" />
	</head>
	<body>
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
						<button type="button" id="botao_enviar_material">Enviar material</button>
						<button type="button" id="botao_buscar_material">Buscar materiais</button>
					</div>
					<div class="bloco" id="enviar_material">
						<h1>ENVIAR MATERIAL</h1>
						<div>
						<form id="form_envio_material">
							<strong>Tipo de material:</strong> 
							<label id="label_arquivo">Arquivo<input id="input_arquivo" type="radio" name="tipo" value="a"></label><label id="label_link">Link<input id="input_link" type="radio" name="tipo" value="l"></label>
							<br>
							<div class="material_recurso">
								<label class="file_label" style="display:none" id="label_material_arquivo">
									<span class="text">Selecionar arquivo:</span><br>
									<input type="file" name="arquivo" id="material_arquivo"/>
								</label>
								<label class="link_label" style="display:none" id="label_material_link">
									<span class="text">Link:</span>
									<input type="text" name="link" id="material_link" />
								</label>
							</div>
							<label>Título:<br>
								<input type="text" name="titulo" id="material_titulo" />
							</label><br>
							<label>Autor:<br>
								<input type="text" name="autor" id="material_autor" />
							</label><br>
							<label>Palavras do Material:<br>
								<input type="text" name="tags" id="material_tags" />
							</label><br>
							<button id="bota_enviar_material" type="button" class="submit">Enviar</button>
					</form>
						</div>
					</div>
					<div class="bloco" id="materiais_enviados">
						<h1>MATERIAIS ENVIADOS</h1>
						<ul id="ul_materiais">
<?php
$material = new Material();
if ($material->abrirTurma(array('turma' => $turma))) {
	do {
	//	print_r($material);
		switch ($material->getTipo())
		{
			case MATERIAL_ARQUIVO:
				$arquivo = $material->getArquivo();
				$usuario = $material->getUsuario();
				$classes = explode("/",$arquivo->getTipo());
				$classes[] = 'arquivo';
				$classes = implode(' ', $classes);
				break;
			case MATERIAL_LINK;
				$link = $material->getLink();
				$classes = 'link';
				break;
		}

		echo <<<HTML
								<li id="material_{$material->getId()}" class="{$classes}">
									<h2>{$material->getTitulo()}</h2>
									<small>Enviado por {$usuario->getName()} em {$material->getData()} ({$material->getHora()}).</small>
									<p>Autor: {$material->getAutor()}</p>
									<a href="#{$material->getId()}">abrir material</a>
								</li>
HTML;
	} while ($material->proximo());
} 

?>

							<li class="%classes%">
								<h2>%titulo%</h2>
								<small>Enviado por %usuario% em %data% (%hora%).</small>
								<p>Autor: %autor%</p>
								<a href="%link%">%nome%</a>
							</li>
						</ul>
					</div>
				</div>
			</div><!-- fim do conteudo -->
			<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
		</div>
		<!-- JAVASCRIPT -->
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
			label_material_arquivo.style.display = "none";
			label_material_link.style.display = "none";
			if (radio_arquivo.checked) {
				label_radio_arquivo.classList.add('checked');
				label_link.classList.remove('checked');
				label_material_arquivo.style.display = "inline-block";
			} else {
				label_radio_arquivo.classList.remove('checked');
				label_radio_link.classList.add('checked');
				label_material_link.style.display = "inline-block";
			}
		}
		radio_link.onchange = radio_arquivo.onchange;
		radio_arquivo.style.display = "none";
		radio_link.style.display = "none";
		</script>
		<script type="text/javascript" src="../../jquery.js"></script>
	</body>
</html>