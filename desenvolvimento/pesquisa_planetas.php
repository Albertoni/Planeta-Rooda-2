<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require("../../cfg.php");
	require("../../bd.php");

	$id_usuario_que_procura = $_POST['usuario_id'];
	$nome = $_POST['dado_pesquisado'];
	$posicao_dado_para_retorno = $_POST['pos_tupla_resultado_pesquisa'];
	
	$consulta = new conexao();

	if($nome != null){
		$consulta->solicitar("SELECT * FROM Planetas WHERE Nome LIKE '%$nome%'");
	} else {
		$consulta->solicitar("SELECT * FROM Planetas");
	}	
	
	$dados = '&dado_pesquisado'.			    '='.$nome;
	
	$numDadosEncontrados = 0;
	for ($i=1;$i<=count($consulta->itens);$i++){
		$numDadosEncontrados = $numDadosEncontrados + 1;
		
		if($i == $posicao_dado_para_retorno){
			$identificacao = $consulta->resultado['Id'];
			$tipo = $consulta->resultado['Tipo'];
			$nome = $consulta->resultado['Nome'];
			$idDono = $consulta->resultado['IdResponsavel'];
			$terrenos = $consulta->resultado['Terrenos'];
			$niveis_acesso_permitido = $consulta->resultado['acesso'];
			$niveis_edicao_permitida = $consulta->resultado['edicao'];
			
			$consultaDono = new conexao();
			$consultaDono->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id = $idDono");
			$dono = $consultaDono->resultado['usuario_nome'];
			
			$dados .= '&identificacao'.				'='.$identificacao;
			$dados .= '&tipo'.		    			'='.$tipo;
			$dados .= '&nome'.	      				'='.$nome;
			$dados .= '&dono'.	        			'='.$dono;
			if($terrenos != null and isset($terrenos) and $terrenos != ''){ 
				$terrenos = explode(",", $terrenos);
				for($j = 0; $j < count($terrenos); $j++){
					$pesquisaTurma = new conexao();
					$pesquisaTurma->solicitar("SELECT * FROM $tabela_terrenos WHERE terreno_id = $terrenos[$j]");
					$idTerreno = $pesquisaTurma->resultado['terreno_id'];
					$nomeTerreno = $pesquisaTurma->resultado['terreno_nome'];
					$tipoTerreno = $pesquisaTurma->resultado['terreno_solo'];
					$avatarDefaultTerreno = $pesquisaTurma->resultado['terreno_avatar_default'];
					$dados .= '&idTerreno'.$j.    '='.$idTerreno;
					$dados .= '&nomeTerreno'.$j.  '='.$nomeTerreno;
					$dados .= '&tipoTerreno'.$j.  '='.$tipoTerreno;
					//$dados .= '&avatarDefaultTerreno'.$j.     '='.$avatarDefaultTerreno;
				}
				$dados .= '&num_terrenos'.        '='.$j;
			}
			else{
				$j = 0;
				$dados .= '&num_terrenos'.        '='.$j;
			}
			$dados .= '&niveis_acesso_permitido'.	'='.$niveis_acesso_permitido;
			$dados .= '&niveis_edicao_permitida'.	'='.$niveis_edicao_permitida;
		}
	
		$consulta->proximo();
	}

	$dados .= '&numDadosEncontrados='.$numDadosEncontrados; 

    echo $dados;	
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
