<?php
	session_start();
	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require_once("../funcoes_aux.php");
	/*---------------------------------------------------
	*	Retorna o id do terreno principal do sistema para o usuário deseja - Guto - 21.05.10
	---------------------------------------------------*/
	$sistema_id = $_POST['sistema_id'];
		
	$pesquisar = new conexao($BD_host1, $BD_base1, $BD_user1, $BD_pass1); 		//Conexão para as pesquisas no Bd - Guto - 21.05.10
	if ($pesquisar->erro != "") {
		$dados = "&erroAdm=1"; 
	} else {
		//$pesquisar->solicitar("select * from $tabela_terrenos WHERE terreno_grupo_id = $sistema_id and terreno_indice = 1 and terreno_nivel = 1");
		$pesquisar->solicitar("select * from Planetas WHERE Id = $sistema_id");
		if ($pesquisar->erro != "") {	
			$dados = "&erroAdm=2"; 
		} else {
			$terrenos = $pesquisar->resultado['Terrenos'];
			if($terrenos != ""){
				$terrenos = explode(",", $terrenos);
				$dados = "&terreno_id=".$terrenos[0];
				$dados .= "&erroAdm=0";
			} else {
				$dados = "&erroAdm=4";
			}
		}	
	}
	echo utf8_encode($dados);
?>