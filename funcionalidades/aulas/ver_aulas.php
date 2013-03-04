<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("aula.class.php");
require("../../reguaNavegacao.class.php");

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$turma = "";
if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}





$GAMBIARRA_ENORME = 0; // usado mais tarde pra poupar cpu
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planeta ROODA 2.0</title>
	<link type="text/css" rel="stylesheet" href="../../planeta.css" />
	<link type="text/css" rel="stylesheet" href="aulas.css" />
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="aulas.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>
	<!--[if IE 6]>
	<script type="text/javascript" src="../../planeta_ie6.js"></script>
	<![endif]-->
</head>

<body onload="atualiza('ajusta()');inicia();">
<div id="topo">
	<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Aulas", "planeta_aulas.php?turma=$turma", false);
				$regua->adicionarNivel("Ver");
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
				<div id="rel"><p id="balao">Nesse espaço se pode editar as aulas que foram criadas anteriormente. Basta selecionar a turma que deseja editar as aulas e clique em “Confirmar”.</p></div>
			</div>
		</div>
		<div id="ajuda_base"></div>
	</div>
</div><!-- fim do cabecalho -->
	
<a name="topo"></a>
	
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
	
	<div class="bts_cima">
	<div class="bts_msg" align="left">
		<a href="planeta_aulas.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_voltar.png" /></a>
	</div>
	</div>
<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>
	<div id="criar_topico" class="bloco">
		<h1>AULAS</h1>
<?php
$aulas = getListaAulas($turma);
$id_aula_anterior = 0; // usada no loop abaixo
for($i=0; $i < count($aulas); $i++){
	$nomecriador = new conexao();
	$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$aulas[$i]->getAutor());
?>
		<div class="cor<?=alterna()?>">
			<ul class="margem_raquel">
				<li class="tabela">
					<div class="info">
						<a href="aula.php?turma=<?=$turma?>&id=<?=$aulas[$i]->getId()?>"><p class="nome"><b><?=$aulas[$i]->getTitulo()?></b></p></a>
<?php
	if (($_SESSION['SS_usuario_id'] == $aulas[$i]->getAutor()) or (checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelAdmin) === 1)
		or $usuario->podeAcessar($permissoes['aulas_editarAulas'], $turma)){
?>
							<a class="pode_editar" href="criar.php?turma=<?=$turma?>&aula_id=<?=$aulas[$i]->getId()?>">EDITAR</a>
<?php
		$GAMBIARRA_ENORME = 1;
	}
	if (($_SESSION['SS_usuario_id'] == $aulas[$i]->getAutor()) or (checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelAdmin) === 1)
		or $usuario->podeAcessar($permissoes['aulas_deletarAulas'], $turma)){
?>
							<a class="pode_deletar" href="javascript:if(confirm('Deseja deletar essa aula?'))window.location='deletar_aula.php?turma=<?=$turma?>&id=<?=$aulas[$i]->getId()?>';"><b>DELETAR</b></a>
<?php
		$GAMBIARRA_ENORME = 1;
	}
?>
						<p class="data">Data: <?=$aulas[$i]->getData()?></p>
					</div>
				</li>
				<li>
					<a class="no_underline" href="aula.php?turma=<?=$turma?>&id=<?=$aulas[$i]->getId()?>"><p class="texto_resposta"><?=$aulas[$i]->getDesc()?></p></a>
					<div id="autor" class="criado_por">Criado por: <?=$nomecriador->resultado['usuario_nome']?></div>
<?php
	if ($GAMBIARRA_ENORME === 1){
		if($id_aula_anterior != 0){
			echo "					<a class=\"move_up\" onclick=\"trocaPosicoes(".$aulas[$i]->getId().",$turma,".$aulas[$i-1]->getId().")\">Mover para cima</a> | \n";
		}
		if (isset($aulas[$i+1]))
			echo "					<a class=\"move_down\" onclick=\"trocaPosicoes(".$aulas[$i]->getId().",$turma,".$aulas[$i+1]->getId().")\">Mover para baixo</a>\n";
		$id_aula_anterior = $aulas[$i]->getId();
	}
?>
					<a class="link_aula" href="aula.php?turma=<?=$turma?>&id=<?=$aulas[$i]->getId()?>"><b>Ver aula</b></a>
				</li>
			</ul>
		</div>
<?php
}
?>
	</div><!-- fim da div topicos -->
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->

</body>
</html>
