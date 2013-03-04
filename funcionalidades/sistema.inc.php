<?

//require("../config.php");
//require_once("../db.inc.php");
//require_once("arte.inc.php");
//require_once("../sessao.inc.php");
//require_once("../hierarquia.inc.php");
require_once("../visao.inc.php");
require_once("../imagens.inc.php");


function nowtimestamp() {
	return date("Y-m-d H:i:s");
}

function getNomeUsuario($codUsuario) {
	$q = "SELECT nomeUsuario, sobrenome FROM DadosPessoais WHERE codUsuario='$codUsuario'";
	$nomeUsuario = db_busca($q);
	return $nomeUsuario[0]['nomeUsuario'] . ' ' . $nomeUsuario[0]['sobrenome'];
}
	
function volta() {
	?>
		<script>
			history.back();
		</script>
	<?php
}

function alertaJS($msg) {
	?>
		<script>
			alert('<?=$msg?>');
		</script>
	<?php
}

function sendMail($from,$to,$subject,$msg) {
	$headers .= "To: $to\r\n";
	$headers .= "From: $from\r\n";
	mail('bernardoalcalde@gmail.com', 'teste', 'mensagem');
}

?>
