<?php
/*------------------------------------------------------------
 *   Receber, do código em JS, variáveis com o frame do cabelo e dos olhos. Também, cor das luvas, botas e pele.
 *   Usar estas variáveis para escrever no BD, nas respectivas colunas da tabela personagens, a configuração do avatar.
 *   	Estes números serão lidos e interpretados no código em AS2, no arquivo inicializacao_personagem.as.
 *   Função muito útil:>>>print_r($_POST);
--------------------------------------------------------------*/
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../reguaNavegacao.class.php");
	
	
	function validaCorLuvasOuCinto($cor){
		 return (int)((($cor > 40) or ($cor < 1)) ? 1 : $cor);
	}
	
	function validaDadosESalva($corCabelo, $frameCabelo, $frameOlhos, $corPele, $corCinto, $corLuvas){
		$id = $_SESSION['SS_personagem_id'];
		
		// cor do cabelo
		$coresPossiveisCabelo = array('castanho', 'preto', 'loiro', 'ruivo');
		$corCabelo = ((in_array($corCabelo, $coresPossiveisCabelo))? $corCabelo : 'castanho'); // se está no array, é válido
		
		// id/frame do cabelo
		$frameCabelo = (int)((($frameCabelo > 22) or ($frameCabelo < 1)) ? 1 : $frameCabelo); // se é maior que 22 tem algo errado
		
		switch($corCabelo){
			case "preto":
				$frameCabelo += 22;
			break;
			case "loiro":
				$frameCabelo += 44;
			break;
			case "ruivo":
				$frameCabelo += 66;
			break;
		}
		
		// cor dos olhos
		$valoresCorOlhosValidos = array(2,3,6,7);
		$frameolhos = (int)(in_array($frameOlhos, $valoresCorOlhosValidos) ? $frameOlhos : 2); // se está no array, é válido
		
		// cor da pele
		$corPele = (int)((($corPele > 20) or ($corPele < 1)) ? 1 : $corPele); // se é maior que 20 tem algo errado
		
		// cinto e luvas tem a mesma regra, portanto, função auxiliar.
		$corCinto = validaCorLuvasOuCinto($corCinto);
		$corLuvas = validaCorLuvasOuCinto($corLuvas);
		
		$bd = new conexao(); global $tabela_personagens;
		$bd->solicitar("UPDATE $tabela_personagens
				SET personagem_cabelos='$frameCabelo', personagem_olhos='$frameOlhos',
				personagem_cor_pele='$corPele', personagem_cor_cinto='$corCinto',
				personagem_cor_luvas_botas='$corLuvas'
			WHERE personagem_id=$id");
	}
	
	// captura dados e passa para a função
	$corCabelo	= ((isset($_POST['corCabeloSelecionada_php']) and $_POST['corCabeloSelecionada_php'] != "") ? $_POST['corCabeloSelecionada_php'] : "ruivo"); // we love redheads
	$frameCabelo= ((isset($_POST['cabelo_php'])	and $_POST['cabelo_php'] != "")	? $_POST['cabelo_php']		: 1);
	$frameOlhos	= ((isset($_POST['olhos_php'])		and $_POST['olhos_php'] != "")		? $_POST['olhos_php']		: 2);
	$corPele	= ((isset($_POST['cor_pele_php'])	and $_POST['cor_pele_php'] != "")	? $_POST['cor_pele_php']	: 1);
	$corCinto	= ((isset($_POST['cor_cinto_php'])	and $_POST['cor_cinto_php'] != "")	? $_POST['cor_cinto_php']	: 1);
	$corLuvas	= ((isset($_POST['cor_luvas_php'])	and $_POST['cor_luvas_php'] != "")	? $_POST['cor_luvas_php']	: 1);
	
	validaDadosESalva($corCabelo, $frameCabelo, $frameOlhos, $corPele, $corCinto, $corLuvas);
	// That's all, folks!
	
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="criar.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="raphael.js"></script>
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
			$regua->adicionarNivel("Criar Personagem", "criar_personagem.php", false);
			$regua->adicionarNivel("Salvar Personagem");
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
			<div id="ajudante" >
				<div id="rel"><p id="balao" style="width:95%">Bem vindo ao Planeta ROODA! <br />A aparência do seu personagem foi salva.</p></div>
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
		<div class="bts_baixo">
			<input type="button" value="Sair" onclick="fecharColorBox('../../desenvolvimento/', 'relativo')">
		</div>
	</div><!-- fim do conteudo -->

</div> <!-- fim do conteudo_meio -->  
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
</div><!-- fim da geral -->

</body>
</html> 
