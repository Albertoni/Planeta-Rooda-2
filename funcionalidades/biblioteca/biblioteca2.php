<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");
require_once("../../usuarios.class.php");
require_once("../../funcoes_aux.php");
require_once("../../reguaNavegacao.class.php");
session_start();
$turma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
$usuario_id = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;

if ($usuario_id >= 0)
{
	$usuario = new Usuario();
	$usuario->openUsuario($usuario_id);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Planeta ROODA 2.0</title>
		<link type="text/css" rel="stylesheet" href="../../planeta.css" />
		<link type="text/css" rel="stylesheet" href="biblioteca.css" />
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
					<!-- SELEÇÂO DE TURMA -->
					<div class="bloco" id="select_turma">
						<h1>SELECIONAR TURMA</h1>
						<div class="cor1">
<?php
$turmasDoUsuario = $usuario->getTurmas();
if (count($turmasDoUsuario) > 0) {
?>
							<form method="get" id="troca_turma">
								<select name="turma" id="select_turma">
<?php
	foreach ($turmasDoUsuario as $t) {
?>
									<option value="<?=$t['codTurma']?>"><?=$t['nomeTurma']?></option>
<?php
	}
?>
								</select>
								<button type="submit" class="confirmar">Confirmar</button>
							</form>
<?php
} else {
?>
									<div class="aviso">Você não está em nenhuma turma.</div>
<?php
}
?>
						</div>
					</div>
					<!-- FIM DA SELEÇÂO DE TURMA -->
					<div class="bloco" id="materiais">
						<h1>TURMA</h1>
						<button type="button" id="botao_enviar_material">Enviar material</button>
						<button type="button" id="botao_buscar_material">Buscar materiais</button>
					</div>
					<div class="bloco" id="buscar_materiais">
						<h1>BUSCAR MATERIAIS</h1>
					</div>
					<div class="bloco" id="enviar_material">
						<h1>ENVIAR MATERIAL</h1>
						<div>
						<form id="form_envio_material">
							Tipo de material
							<button>Arquivo</button>
							<button>Link</button>
							<fieldset class="material_material">
								<legend>Material</legend>
								<label>Título:<br>
									<input type="text" name="titulo" id="material_titulo" />
								</label><br>
								<label>Palavras do Material:<br>
									<input type="text" name="tags" id="material_tags" />
								</label><br>
								<label>Arquivo:<br>
									<input type="file" name="arquivo" id="material_arquivo" />
								</label><br>
								<label>Link:<br>
									<input type="text" name="link" id="material_link" />
								</label><br>
								<button id="bota_enviar_material" type="button" class="submit">Enviar</button>
							</fieldset>
						</form>
						</div>
					</div>
				</div>
			</div><!-- fim do conteudo -->
			<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
		</div>
		<script>
		;(function () {
			var botao_enviar_material  = document.getElementById("botao_enviar_material");
			var botao_buscar_materiais = document.getElementById("botao_buscar_materiais");
			var enviar_material  = document.getElementById("enviar_material");
			var buscar_materiais = document.getElementById("buscar_materiais");
		}());
		</script>
	</body>
</html>