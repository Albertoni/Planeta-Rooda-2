<?php
	$ct = $sessao->codTurma;
	$habilitadas = array(	11948,	12284,	11419	);

$resultadoAfeto=db_busca('SELECT acessoTodos FROM ra_acesso WHERE codTurma='.$codTurma);
if(empty($resultadoAfeto)){
	db_faz('INSERT INTO ra_acesso (codTurma,acessoTodos) VALUES ('.$codTurma.',0)');
	$acessoTodosAfeto=0;
}
else{
	$acessoTodosAfeto=$resultadoAfeto[0]['acessoTodos'];
}


	$rU = db_busca("select * from TurmaUsuario where codUsuario={$sessao->codUsuario} and codTurma=$ct");
		$rU = strtoupper($rU[0]['associacao']);

	if(!in_array($ct,$habilitadas)){
		echo "<script>alert('Ferramenta em teste, com acesso limitado. Agradecemos a paciência e compreensão.');document.location='../../rooda.php';</script>";
	}
	else{
		if($rU=='A' and $acessoTodosAfeto==0){
			echo "<script>alert('Ferramenta com acesso limitado a professores e monitores.');document.location='../../rooda.php';</script>";
		}
	}
?>