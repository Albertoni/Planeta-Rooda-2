<?php
	/*require_once("../class/planeta.php");
	require_once("../cfg.php");
	require_once("../bd.php");
	require_once("../funcoes_aux.php");
	require_once("../reguaNavegacao.class.php");
	require_once("../usuarios.class.php");
	require_once("../turma.class.php");
	require_once("../AlteracoesTurmasUsuario.php");
	
	session_start();
	
	if (!isset($_SESSION['SS_usuario_id'])){ // Se isso não estiver setado, o usuario não está logado
		die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>");
	}
	
	
	$usuario = new Usuario();
	$usuario->openUsuario($_SESSION['SS_usuario_id']);
	*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- CSS -->
		<link href="menus.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../jquery.js"></script>
	</head>
	<body>
		<div id="containerMenu">
			<div id="menuEsquerda">
				<div id="infoTurma">
					Nesse exemplo posicionamos o elemento a 40px do topo e a 
					20px da esquerda em relação ao lugar que ele ocuparia no 
					fluxo do documento. Vale lembrar que utilizando 
					position:relative todo esse espaço que foi definido ainda 
					continua sendo ocupado pelo elemento na página. 
				</div>
				<div id="wrapperBotoesEsquerda">
					<div id="botaoContatos" class="botaoEsquerda"></div>
					<div id="botaoFuncionalidade" class="botaoEsquerda"></div>
					<div id="botaoPlaneta" class="botaoEsquerda"></div>
				</div>
			</div>
			<div id="menuDireita">
				<div id="wrapperClasses">
					<div class="botaoDireita" id="botaoProfessores"></div>
					<div class="botaoDireita" id="botaoMonitores"></div>
					<div class="botaoDireita" id="botaoAlunos"></div>
				</div>
				<div id="listaMembrosTurma">
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
				</div>
			</div>
		</div>
	</body>
</html>

<!--
BACKUP DO HTML ORIGINAL

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="menus.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../jquery.js"></script>
	</head>
	<body>
		<div id="containerMenu">
			<div id="menuEsquerda">
				<div id="infoTurma">
					Nesse exemplo posicionamos o elemento a 40px do topo e a 
					20px da esquerda em relação ao lugar que ele ocuparia no 
					fluxo do documento. Vale lembrar que utilizando 
					position:relative todo esse espaço que foi definido ainda 
					continua sendo ocupado pelo elemento na página. 
				</div>
				<div id="wrapperBotoesEsquerda">
					<div id="botaoContatos" class="botaoEsquerda"></div>
					<div id="botaoFuncionalidade" class="botaoEsquerda"></div>
					<div id="botaoPlaneta" class="botaoEsquerda"></div>
				</div>
			</div>
			<div id="menuDireita">
				<div id="wrapperClasses">
					<div class="botaoDireita" id="botaoProfessores"></div>
					<div class="botaoDireita" id="botaoMonitores"></div>
					<div class="botaoDireita" id="botaoAlunos"></div>
				</div>
				<div id="listaMembrosTurma">
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma">NOME DO MALUCO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
					<div class="membroTurma comFundo">NOME DO PIRADO <a href="#"><img class="botaoUsuario iconeDeletar"></a><a href="#"><img class="botaoUsuario iconeCarteira"></a><a href="#"><img class="botaoUsuario iconePromocao"></a></div>
				</div>
			</div>
		</div>
	</body>
</html>


 -->
