<?php
require_once("../turma.class.php");
require("../usuarios.class.php");

session_start();

if (!isset($_SESSION['SS_usuario_id'])){ // Se isso não estiver setado, o usuario não está logado
	die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>");
}



function imprimeListaUsuarios($lista){
	for($i=0; $i<count($lista); $i++){
		$nome = $lista[$i]->getName();
		$userId = $lista[$i]->getId();
		$comFundo = $i%2 ? "membroTurma" : "membroTurma comFundo";
	
	
		echo "						<div class=\"$comFundo\">
							<span id=\"nomeUser$userId\">$nome</span>";
		
		if(isProfessor($_SESSION['SS_usuario_id'], $turma)){
			echo"
							<a class=\"botaoUsuario iconeDeletar\" onclick=\"removeUsuario($userId, $idTurma);\"></a>
							<a href=\"#\" class=\"botaoUsuario iconeCarteira\" onclick=\"mostraCarteira($userId);\"></a>
							<a href=\"#\" class=\"botaoUsuario iconePromocao\" onclick=\"preparaTrocaNivel($userId, $idTurma)\"></a>";
		}
		
		echo "\n						</div>";
	}
}

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$idTurma = (int) $_GET['turma'];

$turma = new turma($idTurma);
$turma->carregaMembros();

$professores = $turma->getProfessores();
$monitores = $turma->getMonitores();
$alunos = $turma->getAlunos();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- CSS -->
		<link href="menus.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="ajax.js"></script>
		<script type="text/javascript" src="menuTurma.js"></script>
		<script type="text/javascript" src="../jquery.js"></script>
	</head>
	<body>
		<div id="fundo_lbox">
		</div>
		<div id="light_box_carteira" class="light_box">
		<h2>Carregando...</h2>
		</div>
		<div id="light_box_troca" class="light_box">
			<h2 id="frase_nivel">Para que nivel deseja alterar esse usuário?</h2>
			<div onclick="efetuaTrocaNivel('aluno');" id="botao_troca_aluno" class="botao_troca"></div>
			<div onclick="efetuaTrocaNivel('monit');" id="botao_troca_monitor" class="botao_troca"></div>
			<div onclick="efetuaTrocaNivel('profe');" id="botao_troca_professor" class="botao_troca"></div>
		</div>
		<div id="containerMenu">
			<div id="menuEsquerda">
				<div id="infoTurma">
					<?= ($turma->getDescricao() != "" ? $turma->getDescricao() : "Turma sem descrição.")."\n"; ?>
				</div>
				<div id="wrapperBotoesEsquerda">
					<div id="botaoContatos" class="botaoEsquerda"></div>
					<div id="botaoFuncionalidade" class="botaoEsquerda"></div>
					<div id="botaoPlaneta" class="botaoEsquerda"></div>
				</div>
			</div>
			<div id="menuDireita">
				<div id="wrapperClasses">
					<div class="botaoDireita" id="botaoProfessores" onclick="mostraLista(1)"></div>
					<div class="botaoDireita" id="botaoMonitores" onclick="mostraLista(2)"></div>
					<div class="botaoDireita" id="botaoAlunos" onclick="mostraLista(3)"></div>
				</div>
				<div id="listasMembrosTurma">
					<div id="listaProfessores" class="listaMembros">
						<div class="membroTurma comFundo"><span id="nomeUser414">Juan Vizente</span><a class="botaoUsuario iconeDeletar" onclick="removeUsuario(414, 1081);"></a><a href="#" class="botaoUsuario iconeCarteira" onclick="mostraCarteira(414);"></a><a href="#" class="botaoUsuario iconePromocao" onclick="trocaNivel(414, 1081)"></a></div>
<?php
imprimeListaUsuarios($professores);
?>
					</div>
					<div id="listaMonitores" class="listaMembros">
<?php
imprimeListaUsuarios($monitores);
?>
					</div>
					<div id="listaAlunos" class="listaMembros">
<?php
imprimeListaUsuarios($alunos);
?>
					</div>
				</div>
			<div id="botaoAdicionar"></div>
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
