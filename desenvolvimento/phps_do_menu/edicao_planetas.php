<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");
	
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = "";
	
	$identificacao = $_POST['identificacao'];
	$nome = $_POST['nome'];
	$tipo = $_POST['tipo'];
	$dono = $_POST['dono'];
	$aparencia = $_POST['aparencia'];
	$idsTerrenos = $_POST['idsTerrenos'];
	$nomesTerrenos = $_POST['nomesTerrenos'];
	$acesso = $_POST['acesso'];
	$edicao = $_POST['edicao'];
	
	//Dados Atuais
	$pesquisaChat = new conexao();
	$pesquisaDadosAtuais = new conexao();
	$pesquisaDadosAtuais->solicitar("SELECT * FROM Planetas WHERE Id = $identificacao");
	$nomeAtual = $pesquisaDadosAtuais->resultado['Nome'];
	$tipoAtual = $pesquisaDadosAtuais->resultado['Tipo'];
	$aparenciaAtual = $pesquisaDadosAtuais->resultado['Aparencia'];
	$idsTerrenosAtual = $pesquisaDadosAtuais->resultado['Terrenos'];
	$idDonoAtual = $pesquisaDadosAtuais->resultado['IdResponsavel'];
	$acessoAtual = $pesquisaDadosAtuais->resultado['acesso'];
	$edicaoAtual = $pesquisaDadosAtuais->resultado['edicao'];

	//Tabela Terrenos
	$updateTerrenos = new conexao();
	$pesquisaTerrenoOeste = new conexao();
	$idsTerrenosParaEditar = explode(",", $idsTerrenos);
	$nomesTerrenosParaEditar = explode(",", $nomesTerrenos);
	$ID_TERRENO_CRIADO = -1;
	for($i = 0; $i<count($idsTerrenosParaEditar); $i++){
		if($idsTerrenosParaEditar[$i] != $ID_TERRENO_CRIADO){
			$updateTerrenos->solicitar("UPDATE terrenos SET terreno_grupo_id=$identificacao,terreno_nome='$nomesTerrenosParaEditar[$i]', terreno_permissao_edicao='$edicaoAtual' WHERE terreno_id='$idsTerrenosParaEditar[$i]'");
		} else {
			$pesquisaTerrenoOeste->solicitar("SELECT * FROM terrenos WHERE (terreno_grupo_id=$identificacao) ORDER by terreno_indice");
			$pesquisaTerrenoOeste->ultimo();
			$terreno_id_ultima = $pesquisaTerrenoOeste->resultado['terreno_id'];
			$indice_terreno_no_planeta = $terreno_id_ultima + 1;
		
			$nomeChat = $nomesTerrenosParaEditar[$i].'terreno';
			$pesquisaChat->solicitar("INSERT INTO Chats (nome) VALUES ('$nomeChat')");
			$pesquisaChat->solicitar("SELECT id FROM Chats WHERE nome = '$nomeChat'");
			$idChat = $pesquisaChat->resultado['id'];
			$updateTerrenos->solicitar("INSERT INTO terrenos (terreno_grupo_id, terreno_nome, terreno_permissao_edicao, terreno_indice, chat_id) VALUES ($identificacao, '$nomesTerrenosParaEditar[$i]', '$edicaoAtual', $indice_terreno_no_planeta, $idChat)");
			$idsTerrenosParaEditar[$i] = $updateTerrenos->ultimo_id();
		}
	}
	$idsTerrenos = implode(",", $idsTerrenosParaEditar);
		
	//Tabela Planetas
	$pesquisaEdicaoPlanetaSQL = "UPDATE Planetas SET ";
	if($nome != null){ $pesquisaEdicaoPlanetaSQL.="Nome = '$nome',";
	} else { $pesquisaEdicaoPlanetaSQL.="Nome = '$nomeAtual',"; }
	if($tipo != null){ $pesquisaEdicaoPlanetaSQL.="Tipo = $tipo,";
	} else { $pesquisaEdicaoPlanetaSQL.="Tipo = $tipoAtual,"; }
	if($aparencia != null){ $pesquisaEdicaoPlanetaSQL.="Aparencia = $aparencia,";
	} else { $pesquisaEdicaoPlanetaSQL.="Aparencia = $aparenciaAtual,"; }
	if($idsTerrenos != null){ $pesquisaEdicaoPlanetaSQL.="Terrenos = '$idsTerrenos',";
	} else { $pesquisaEdicaoPlanetaSQL.="Terrenos = '$idsTerrenosAtual',"; }
	if($dono != null){
		$pesquisaDono = new conexao();
		$pesquisaDono->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$dono'");
											 
		if($pesquisaDono->registros == 1){
			$idDono = $pesquisaDono->resultado['usuario_id'];
			$pesquisaEdicaoPlanetaSQL.="IdResponsavel = $idDono,";
		} else if($pesquisaDono->registros > 1){
			$mensagemDeErro = utf8_encode("Ha mais de um usuário com este nome.");
			$operacaoRealizadaComSucesso = false;
		} else {
			$mensagemDeErro = utf8_encode("Não foi possível encontrar este usuário.");
			$operacaoRealizadaComSucesso = false;
		}
	} else { $pesquisaEdicaoPlanetaSQL.="IdResponsavel = $idDonoAtual,"; }
	if($acesso != null){ $pesquisaEdicaoPlanetaSQL.="acesso = '$acesso',"; 
	} else{ $pesquisaEdicaoPlanetaSQL.="acesso = '$acessoAtual',"; }
	if($edicao != null){ $pesquisaEdicaoPlanetaSQL.="edicao = '$edicao'"; 
	} else{ $pesquisaEdicaoPlanetaSQL.="edicao = '$edicaoAtual'"; }
	
	$pesquisaEdicaoPlanetaSQL.=" WHERE Id = $identificacao";

	//$mensagemDeErro = $pesquisaEdicaoPlanetaSQL;
	//$mensagemDeErro = 'AAAAAAAAAAAAAAAAAAAA';
	if($operacaoRealizadaComSucesso){
		$pesquisaEdicaoPlaneta = new conexao();
		$pesquisaEdicaoPlaneta->solicitar($pesquisaEdicaoPlanetaSQL);
	}
	
	$dados = '&operacaoRealizadaComSucesso'    .'='.$operacaoRealizadaComSucesso;
	$dados.= '&mensagemDeErro'                 .'='.$mensagemDeErro;
	
    echo $dados;	
?>
