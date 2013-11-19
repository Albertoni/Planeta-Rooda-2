<?php
header('Content-type: text/html; charset=utf-8');
require_once("cfg.php");
require_once("bd.php");
require_once("funcoes_aux.php");

$usuario = usuario_sessao();
if (!$usuario) { die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>"); })

/* APAGAR ISSO DEPOIS DO CURSO */

if (isset($_POST['codTurma']) and isset($_POST['codAluno']) and isset($_POST['nivel'])){
	// tudo numerico, só pegar os ints
	$codTurma = (int) $_POST['codTurma'];
	$codAluno = (int) $_POST['codAluno'];
	$nivel = (int) $_POST['nivel'];

	// o cara tem que ser professor na turma para adicionar alunos
	global $nivelProfessor;
	$nivel = $usuario->getNivel($codTurma);
	if($nivel == $nivelProfessor){
		$q = new conexao();
		$q->solicitar("INSERT INTO TurmasUsuario VALUES ($codTurma, $codAluno, $nivel)");
	}else{
		die("S&oacute; professores podem adicionar usuarios a uma turma.");
	}
}

?>
<!DOCTYPE html>
<html>
<body>
<form method="post">
<ul>
	<li>Turma:
		<select name="codTurma">
<?php
$listaTurmas = new conexao();
$listaTurmas->solicitar("SELECT codTurma, nomeTurma FROM Turmas ORDER BY codTurma DESC");

for ($i=0;$i<$listaTurmas->registros;$i++){
	echo "<option value=\"".$listaTurmas->resultado['codTurma']."\">".$listaTurmas->resultado['nomeTurma']."</option>\n";
	$listaTurmas->proximo();
}
?>
		</select>
	<li>Aluno:
		<select name="codAluno">

<?php
$listaAlunos = new conexao();
$listaAlunos->solicitar("SELECT usuario_id, usuario_nome FROM usuarios ORDER BY usuario_id DESC LIMIT 100");

for($i=0; $i < $listaAlunos->registros; $i++){
	echo "<option value=\"".$listaAlunos->resultado['usuario_id']."\">".$listaAlunos->resultado['usuario_nome']."</option>\n";
	$listaAlunos->proximo();
}
?>
		</select>
	<li>Nivel: <select name="nivel">
			<option value="4">Professor</option>
			<option value="8">Monitor</option>
			<option value="16">Aluno</option>
		</select>
	<li><input type="submit" value="Adiciona!">
</ul>
</form>
</body>
</html>
