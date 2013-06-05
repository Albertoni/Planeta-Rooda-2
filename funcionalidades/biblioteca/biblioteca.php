<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");
require_once("../../usuarios.class.php");
require_once("../../linkbiblioteca.class.php");
require_once("../../funcoes_aux.php");
require_once("material.class.php");
require_once("../../reguaNavegacao.class.php");

// CONSTANTES PARA getNomeDono
define("MODO_ARQUIVO", 1);
define("MODO_LINK", 2);

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
	$_SESSION['biblio_turma'] = (int)$turma; // usado para verificar quando o cara for deletar, editar, aprovar, etc. um material
	// A TIPAGEM PRA INT É MUITO IMPORTANTE. NÃO A REMOVA SEM FALAR COM O JOÃO ANTES 13/02/13. (TEM A VER COM SUPERGLOBAIS E PONTEIROS)
}else{
	die('Por favor volte e tente novamente, a turma desejada n&atilde;o foi especificada.');
}

$permissoes = checa_permissoes(TIPOBIBLIOTECA, $turma);

if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

	if (isset($_POST['envia_titulo'])){
		if($usuario->podeAcessar($permissoes['biblioteca_enviarMateriais'], $turma)){
			$envia_titulo = $_POST['envia_titulo'];
			$envia_autor = $_POST['envia_autor'];
			$envia_tags = $_POST['envia_tags'];
		
			switch($_POST['e_material']){
				case "arquivo":
					$a = new material($_POST['envia_titulo'], $_POST['envia_autor'], 'a', $_FILES['userfile'], $turma, $_POST['envia_tags']);
					
					if ($a->temErro()){
						$alertMsg = $a->file->getErrosString();
					}else{
						$alertMsg = "UPLOAD com sucesso";
					}
?>
				<script type="text/JavaScript">
					alert("<?=$alertMsg?>");
				</script>
<?
			
				break;
				case "link":
					$l = new material($_POST['envia_titulo'], $_POST['envia_autor'], 'l', $_POST['endereco_link'], $turma, $_POST['envia_tags']);
					if ($l->temErro()){
						echo "<script type=\"text/JavaScript\">alert('".$l->erro."')</script>";
					}
				break;
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="biblioteca.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="biblioteca.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
</head>
<body onload="atualiza('ajusta()');inicia();">

<div id="fundo_lbox"></div>
<div id="light_box" class="bloco">
	<h1>COMENTÁRIOS</h1>
	<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
	<div class="recebe_coments">
	<ul class="sem_estilo" id="ie_coments">
		<ul>
			<li class="tabela_blog">
				Carregando comentários! Por favor, espere um instante.
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
				<input type="image" src="../../images/botoes/bt_confir_pq.png" />
			</div>
		</li>
	</ul>
	</div>
</div>

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
	
	
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->

	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->	
	<div id="esq"><!-- coluna da esquerda -->
<?php
if (sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>
		<div class="bloco" id="procurar_material">
			<h1>PROCURAR MATERIAL</h1> 
				<form name="procurar_material" method="post">
				<ul class="sem_estilo">
				<li><input type="text" name="material_procurado"/></li>
				<li><input type="radio" name="p_material" value="titulo"/>Título</li>
				<li><input type="radio" name="p_material" value="nome"/>Nome do Arquivo</li>
				<li><input type="radio" name="p_material" value="autor"/>Autor</li>
				<li><input type="radio" name="p_material" value="uploader"/>Enviado por...</li>
				<li><input type="radio" name="p_material" value="tags"/>Palavras do Material</li>
				<li><span class="exemplo">(Escreva as tags separadas por vírgula. Ex: Matemática, Português, Artes)</span></li>
				<li><div class="enviar" align="right">
						<input name="botao_procurar" type="image" onClick="procurar_material.submit()" src="../../images/botoes/bt_procurar.png"/>
					</div>
				</li>
				</ul>
				</form>
		</div><!-- fim da procurar_material -->
<?php

if($usuario->podeAcessar($permissoes['biblioteca_enviarMateriais'], $turma)){
?>
		<div class="bloco" id="enviar_material">
			<h1>ENVIAR MATERIAL</h1>
			<form name="enviar_material" method="post" enctype="multipart/form-data">
				<ul class="sem_estilo">
					<li class="espaco_base">Título: <div><input type="text" name="envia_titulo" value="" /></div></li><br />
					<li class="espaco_base">Autor: <div><input type="text" name="envia_autor" value="" /></div></li><br />
					<li class="espaco_base">Palavras do Material: <span class="exemplo"><br />(Escreva as tags separadas por vírgula. Ex: Matemática, Português, Artes)</span><div><input type="text" name="envia_tags" value="" /></div></li><br />
					<li><input type="text" name="endereco_link" id="tipo_link" /></li>
					<li><input type="hidden" name="refresh" value="1" /></li>
					<li id="tipo_arquivo"><div id="browse">
						<input name="userfile" type="file" id="file_real2" size="0" />
					</div></li>
					<div id="tipo_mensagem" style="display: block">Selecione um tipo de material:</div>
					<li>
						<label><input type="radio" name="e_material" value="link" onclick="tipoMaterial('tipoLink');" />
						Link</label>
						<label><input type="radio" name="e_material" value="arquivo" onclick="tipoMaterial('tipoArquivo');" />
						Arquivo</label>
					</li>
					<li><div class="enviar" align="right">
							<input name="botao_enviar" type="image" onClick="enviar_material.submit();" src="../../images/botoes/bt_enviar.png"/>
						</div>
					</li>
				</ul>
			</form>
		</div>
	</div><!-- fim da enviar_material -->
<?php
}
?>
	<div id="dir"><!-- coluna da direita -->
		<div class="bloco" name="bloco_arquivos" id="arquivos_enviados">
			<h1>ARQUIVOS ENVIADOS</h1>
<?php
resultado_procura();
?>

		</div>
	</div><!-- fim da arquivos_enviados -->
	</div><!-- fim do conteudo -->
</div>

<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->



<?
function imprime_arquivo($idMaterial, $idFile, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado){
	if($materialAprovado == 0){
		echo "			<ul class='naoAprovado' id='file$idFile'>";
	}else{
		echo "			<ul id='file$idFile'>";
	}
	
	
	echo "				<li><span class='dados'>Enviado&nbsp;Por:</span><span class='valor'>$nomeDono</span></li>
				<li class='tabela' ><span class='dados'>Autor:</span><span class='valor' id='autor$idMaterial' >$autor</span></li>
				<li class='tabela'><span class='dados'>Título&nbsp;do&nbsp;Material:</span><span class='valor' id='titulo$idMaterial'>$titulo</span></li>
				<li class='tabela'><span class='dados'>Nome&nbsp;do&nbsp;Arquivo:</span><span class='valor' id='nome$idMaterial'>$nome</span></li>
				<li class='tabela'><span class='dados'>Palavras&nbsp;do&nbsp;Material:</span><span class='valor' id='tags$idMaterial'>$tags</span></li>
				<li><span class='dados'>Data:</span><span class='valor'>$dataHora</span></li>
				<li class='tabela'><span class='valor'><a href='../../downloadFile.php?id=$idFile' target='_blank' >$nome</a></span></li>
				<li class='numcomentarios'><span onclick=\"loadComentarios('light_box', 'comentarios.php', 'post_id=$idMaterial');abreFechaLB()\">$numComentarios Comentários</span></li>
				<li><div class='enviar' align='right'>";
	
	if($usuario->podeAcessar($permissoes['biblioteca_editarMateriais'], $turma)){
		echo "					<input type='image' id='botao_esquerdo$idMaterial' src='../../images/botoes/bt_excluir.png' onclick=\"excluirFile($idMaterial, 'a');\"/>";
	}
	if($usuario->podeAcessar($permissoes['biblioteca_excluirArquivos'], $turma)){
		echo "					<input type='image' id='botao_direito$idMaterial' src='../../images/botoes/bt_editar.png' onclick='editarFile(\"$idMaterial\",\"$autor\",\"$titulo\", \"$nome\", \"$tags\", \"a\");' />";
	}
	if(($materialAprovado == 0) and ($usuario->podeAcessar($permissoes['biblioteca_aprovarMateriais'], $turma))){
		echo "					<input type='image' id='botao_aprovar$idMaterial' src='../../images/botoes/bt_aprovar.png' onclick='aprovarMaterial(\"$idMaterial\");' />";
	}
	echo "					</div>
				</ul>";
}

function imprime_link($idFile, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado){
	if($materialAprovado == 0){
		echo "			<ul class='naoAprovado' id='file$idFile'>";
	}else{
		echo "			<ul id='file$idFile'>";
	}

	echo "				<li><span class='dados'>Enviado&nbsp;Por:</span><span class='valor'>$nomeDono</span></li>
				<li class='tabela' ><span class='dados'>Autor:</span><span class='valor' id='autor$idFile' >$autor</span></li>
				<li class='tabela'><span class='dados'>Título&nbsp;do&nbsp;Material:</span><span class='valor' id='titulo$idFile'>$titulo</span></li>
				<li class='tabela'><span class='dados'>Endereço:</span><span class='valor' id='nome$idFile'><a href=\"$endereco\">$endereco</a></span></li>
				<li class='tabela'><span class='dados'>Palavras&nbsp;do&nbsp;Material:</span><span class='valor' id='tags$idFile'>$tags</span></li>
				<li><p class='pedreiragem'>&nbsp;</p></li>
				<li class='numcomentarios'><span onclick=\"loadComentarios('light_box', 'comentarios.php', 'post_id=$idFile');abreFechaLB()\">$numComentarios Comentários</span></li>
				<li><div class='enviar' align='right'>";
	
	if($usuario->podeAcessar($permissoes['biblioteca_excluirArquivos'], $turma)){
		echo "					<input type='image' id='botao_esquerdo$idFile' src='../../images/botoes/bt_excluir.png' onclick=\"excluirFile($idFile, 'l');\"/>";
	}
	if($usuario->podeAcessar($permissoes['biblioteca_editarMateriais'], $turma)){
		echo"					<input type='image' id='botao_direito$idFile' src='../../images/botoes/bt_editar.png' onclick='editarFile(\"$idFile\",\"$autor\",\"$titulo\", \"$endereco\", \"$tags\", \"l\");' />";
	}
	if(($materialAprovado == 0) and ($usuario->podeAcessar($permissoes['biblioteca_aprovarMateriais'], $turma))){
		echo "					<input type='image' id='botao_aprovar$idFile' src='../../images/botoes/bt_aprovar.png' onclick='aprovarMaterial(\"$idFile\");' />";
	}
	echo '				</div>
			</ul>';
}

function imprimeMaterial($arrayDados, $numComentarios, $usuario, $permissoes){
	$idFile	=	$arrayDados['refMaterial'];
	$tipo	=	$arrayDados['tipoMaterial'];
	
	$id_dono= $arrayDados['codUsuario'];
	$autor	=	$arrayDados['autor']		? $arrayDados['autor']		:"Autor não especificado";
	$titulo	=	$arrayDados['titulo']		? $arrayDados['titulo']		:"Titulo não especificado";
	$nome	=	$arrayDados['material']		? $arrayDados['material']	:"Nome não especificado";
	$tags	=	$arrayDados['palavras']		? $arrayDados['palavras']	:"Tags não especificadas";
	$dataHora=	$arrayDados['data']			? $arrayDados['data'].' '.$arrayDados['hora']:"Data não especificada";
	$materialAprovado = $arrayDados['materialAprovado'];
	
	if($materialAprovado == 0){
		echo "			<ul class='naoAprovado' id='file$idFile'>";
	}else{
		echo "			<ul id='file$idFile'>";
	}
	
	echo "				<li><span class='dados'>Enviado&nbsp;Por:</span><span class='valor'>$nomeDono</span></li>
				<li class='tabela' ><span class='dados'>Autor:</span><span class='valor' id='autor$idFile' >$autor</span></li>
				<li class='tabela'><span class='dados'>Título&nbsp;do&nbsp;Material:</span><span class='valor' id='titulo$idFile'>$titulo</span></li>";
	
	if($tipo == "a"){
		echo "				<li class='tabela'><span class='dados'>Nome&nbsp;do&nbsp;Arquivo:</span><span class='valor' id='nome$idFile'>$nome</span></li>
				<li class='tabela'><span class='dados'>Palavras&nbsp;do&nbsp;Material:</span><span class='valor' id='tags$idFile'>$tags</span></li>
				<li><span class='dados'>Data:</span><span class='valor'>$dataHora</span></li>
				<li class='tabela'><span class='valor'><a href='../../downloadFile.php?id=$idFile' target='_blank' >$nome</a></span></li>";
	}else{
		echo "				<li class='tabela'><span class='dados'>Endereço:</span><span class='valor' id='nome$idFile'><a href=\"$nome\">$nome</a></span></li>
				<li class='tabela'><span class='dados'>Palavras&nbsp;do&nbsp;Material:</span><span class='valor' id='tags$idFile'>$tags</span></li>
				<li><p class='pedreiragem'>&nbsp;</p></li>";
	}
	
	if($usuario->podeAcessar($permissoes['biblioteca_excluirArquivos'], $turma)){
		echo "					<input type='image' id='botao_esquerdo$idFile' src='../../images/botoes/bt_excluir.png' onclick=\"excluirFile($idFile, 'l');\"/>";
	}
	if($usuario->podeAcessar($permissoes['biblioteca_editarMateriais'], $turma)){
		echo"					<input type='image' id='botao_direito$idFile' src='../../images/botoes/bt_editar.png' onclick='editarFile(\"$idFile\",\"$autor\",\"$titulo\", \"$endereco\", \"$tags\", \"l\");' />";
	}
	if(($materialAprovado == 0) and ($usuario->podeAcessar($permissoes['biblioteca_aprovarMateriais'], $turma))){
		echo "					<input type='image' id='botao_aprovar$idFile' src='../../images/botoes/bt_aprovar.png' onclick='aprovarMaterial(\"$idFile\");' />";
	}
	echo '				</div>
			</ul>';
}

function getNomeDono($id_dono, $modo){
	$dono = new Usuario();
	if ($id_dono != 0){
		$dono->openUsuario($id_dono);
		return $dono->getName();
	} else {
		// ok, deu erro, resta definir se o que não tem dono é arquivo ou link
		switch($modo){
			case MODO_ARQUIVO:
				$termo = "Arquivo";
				break;
			case MODO_LINK:
				$termo = "Link";
				break;
			default:
				$termo = "ERRO AO TENTAR SE RECUPERAR DE UM ERRO";
		}
		
		return "ERRO - $termo não tem dono."; // Melhor prevenir.
	}
}

function getNumeroComentarios($idFile){
	$q = new conexao(); global $tabela_biblioComentarios;
	$q->solicitar("SELECT COUNT(*) FROM $tabela_biblioComentarios WHERE codMaterial = $idFile");
	return $q->resultado["COUNT(*)"];
}

function resultado_procura(){
	global $tabela_Materiais;
	global $permissoes;
	global $usuario;
	global $turma;
	
	if($usuario->podeAcessar($permissoes['biblioteca_aprovarMateriais'], $turma)){
		$consultaBase = "SELECT * FROM $tabela_Materiais WHERE codTurma = $turma"; // mostra tanto os aprovados quanto os não aprovados
	}else{
		$consultaBase = "SELECT * FROM $tabela_Materiais WHERE codTurma = $turma AND materialAprovado = 1"; // status normal: Só exibe material aprovado
	}
	
	if (isset($_GET['minha_biblioteca']) and $_GET['minha_biblioteca'] == 1){ // Só os materiais que o usuario deu upload.
		$consulta=new conexao();
		$consulta->solicitar("SELECT * FROM $tabela_Materiais WHERE codUsuario =".$_SESSION['SS_usuario_id']);
		
	} else if (isset($_POST['material_procurado'])){ // Pesquisa normal
		global $tabela_arquivos;
		global $tabela_usuarios;
		$procurar=$_POST['material_procurado'];
		$tipo_procura=$_POST['p_material'];
		$consulta=new conexao();
		
		// prepara 
		switch($tipo_procura){
			case "titulo":
				$condicaoConsulta = "titulo LIKE '%$procurar%'";
			break;
			case "nome":
				$condicaoConsulta = "material LIKE '%$procurar%'";
			break;
			case "autor":
				$condicaoConsulta = "autor LIKE '%$procurar%'";
			break;
			case "uploader":
				$condicaoConsulta = "codUsuario=(
							SELECT usuario_id
							FROM $tabela_usuarios 
							WHERE usuario_login='$procurar'
								OR LOWER(usuario_nome)
								LIKE LOWER('%$procurar%')
						)";
			break;
			case "tags":
				$condicaoConsulta="";
				$tags=explode(",",$procurar);
				for($i=0 ; $i< count($tags) ; $i++){
					$tags[$i]=trim($tags[$i]);
					$singleTag = $tags[$i];
					if($i>0){
						$condicaoConsulta.=" OR ";
					}
					$condicaoConsulta.="palavras REGEXP '^$singleTag$|^$singleTag,|,$singleTag,|,$singleTag$'"; // socorro
					// Matches: 1- Se for tag única; 2- no começo da linha; 3- no meio; e 4- no fim.
				}
				
			break;
		}
		
		// Agora se faz a consulta
		$consulta->solicitar("$consultaBase AND $condicaoConsulta");
		
		$alterna_cor='1';
		for ($i=0 ; $i< $consulta->registros ; $i++){
			$idMaterial = $consulta->resultado['codMaterial'];
			$idFile	= $consulta->resultado['refMaterial'];
			$tipo	=	$consulta->resultado['tipoMaterial'];
			
			$id_dono= $consulta->resultado['codUsuario'];
			$autor	=	$consulta->resultado['autor']		? $consulta->resultado['autor']		:"Autor não especificado";
			$titulo	=	$consulta->resultado['titulo']		? $consulta->resultado['titulo']	:"Titulo não especificado";
			$nome	=	$consulta->resultado['material']	? $consulta->resultado['material']	:"Nome não especificado";
			$tags	=	$consulta->resultado['palavras']	? $consulta->resultado['palavras']	:"Tags não especificadas";
			$dataHora=	$consulta->resultado['data']		? $consulta->resultado['data'].' '.$consulta->resultado['hora']:"Data não especificada";
			$materialAprovado = $consulta->resultado['materialAprovado'];
			
			$numComentarios = getNumeroComentarios($idFile);
			
			//imprimeMaterial($consulta->resultado, $numComentarios, $usuario, $permissoes);
			
			if ($tipo == "a"){
				$nomeDono = getNomeDono($id_dono, MODO_ARQUIVO);
				imprime_arquivo($idMaterial, $idFile, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado);
			}else{
				$nomeDono = getNomeDono($id_dono, MODO_LINK);
				imprime_link($idMaterial, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado);
			}
			$consulta->proximo();
			
		}
		
		
	}
	else // SE NÃO TEM NADA SETADO, MOSTRA TODO SANTO ARQUIVO, ASSIM COMO AS GURIAS PEDIRAM
	{
		$user_id = $_SESSION['SS_usuario_id'];
		global $tabela_Materiais;
		global $tabela_links;
		global $tabela_usuarios;
		global $nivelAdmin;
		
		$consulta	=	new conexao();
		
		$comentarios=	new conexao();
		
		$tipo = TIPOBIBLIOTECA; // facilitar a query
		
		
		
		/*////////////////////////////////////////////
		// MOSTRA OS ARQUIVOS E SÓMENTE OS ARQUIVOS //
		////////////////////////////////////////////*/
		
		$consulta->solicitar("$consultaBase AND tipoMaterial = 'a'");
		for ($i=0; $i < $consulta->registros; $i++){
			if ($i != 0) {echo "<hr>";}
			
			$idMaterial = $consulta->resultado['codMaterial'];
			$idFile= $consulta->resultado['refMaterial'];
			
			$id_dono= $consulta->resultado['codUsuario'];
			$autor	= $consulta->resultado['autor']			? $consulta->resultado['autor']		: "Autor não especificado";
			$titulo	= $consulta->resultado['titulo']		? $consulta->resultado['titulo']	: "Título não especificado";
			$nome	= $consulta->resultado['material']		? $consulta->resultado['material']	: "Nome não especificado";
			$tags	= $consulta->resultado['palavras']		? $consulta->resultado['palavras']	: "Tags não especificadas";
			$dataHora = $consulta->resultado['data']		? $consulta->resultado['data'].' '.$consulta->resultado['hora']:"Data não especificada";
			$materialAprovado = $consulta->resultado['materialAprovado'];
			
			$nomeDono = getNomeDono($id_dono, MODO_ARQUIVO);
			
			$numComentarios = getNumeroComentarios($idFile);
			
			imprime_arquivo($idMaterial, $idFile, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado);
			
			$consulta->proximo();
		}
		
		/*///////////////////
		// MOSTRA OS LINKS //
		///////////////////*/
		
		$consulta->solicitar("$consultaBase AND tipoMaterial = 'l'");
		
		if (($i != 0) and ($consulta->registros != 0)) { // Se tinha arquivos e tem links pra exibir, divide eles
		echo "<hr class=\"hrgrande\">";
		}
		
		for ($i=0; $i < $consulta->registros; $i++){
			if ($i != 0) {echo "<hr>";}
			
			$idMaterial	= $consulta->resultado['codMaterial'];
			$id_dono	= $consulta->resultado['codUsuario'];
			$autor		= $consulta->resultado['autor'];
			$titulo		= $consulta->resultado['titulo'];
			$endereco	= $consulta->resultado['material'];
			$tags		= $consulta->resultado['palavras'];
			$id_dono	= $consulta->resultado['codUsuario'];
			$dataHora	= $consulta->resultado['data'] ? $consulta->resultado['data'].' '.$consulta->resultado['hora']:"Data não especificada";
			$materialAprovado = $consulta->resultado['materialAprovado'];
			
			
			$nomeDono = getNomeDono($id_dono, MODO_LINK);
			
			$numComentarios = getNumeroComentarios($idFile);
			
			imprime_link($idMaterial, $nomeDono, $autor, $titulo, $nome, $tags, $dataHora, $numComentarios, $usuario, $permissoes, $turma, $materialAprovado);
			
			$consulta->proximo();
		}
	}
}//FIM resultado_procura
?>
</body>
</html>

