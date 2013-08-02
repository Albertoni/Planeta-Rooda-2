<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");

//Variáveis
	$ACESSO_TODOS_PERMITIDOS = 64;
	$EDICAO_ALUNOS_NAO_PODEM = 0;
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = "";
	
	global $nivelProfessor;
	
//Dados para cadastro
	$nome = $_POST['nome'];
	$professor = $_POST['professor'];
	$maeProfessor = $_POST['nomeMaeProfessor'];
	$descricao = $_POST['descricao'];
	$ano = $_POST['ano'];
	$numeroProfessores = $_POST['numeroProfessores'];
	$numeroMonitores = $_POST['numeroMonitores'];
	$numeroAlunos = $_POST['numeroAlunos'];
	
	$conexaoBuscaProfessores = new conexao();
	$conexaoBuscaMonitores = new conexao();
	$conexaoBuscaAlunos = new conexao();
	
	$sqlBuscaProfessores = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroProfessores; $i++){
		if($i != 0){
			$sqlBuscaProfessores .= " OR ";
		}
		$sqlBuscaProfessores .= "usuario_nome = '".$_POST['professor'.$i]."'";
	}
	
	$sqlBuscaMonitores = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroMonitores; $i++){
		if($i != 0){
			$sqlBuscaMonitores .= " OR ";
		}
		$sqlBuscaMonitores .= "usuario_nome = '".$_POST['monitor'.$i]."'";
	}
	
	$sqlBuscaAlunos = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroAlunos; $i++){
		if($i != 0){
			$sqlBuscaAlunos .= " OR ";
		}
		$sqlBuscaAlunos .= "usuario_nome = '".$_POST['aluno'.$i]."'";
	}
	
	$conexaoBuscaProfessores->registros = 0;
	if(0 < $numeroProfessores){
		$conexaoBuscaProfessores->solicitar($sqlBuscaProfessores);
	}
	
	$conexaoBuscaMonitores->registros = 0;
	if(0 < $numeroMonitores){
		$conexaoBuscaMonitores->solicitar($sqlBuscaMonitores);
	}
	
	$conexaoBuscaAlunos->registros = 0;
	if(0 < $numeroAlunos){
		$conexaoBuscaAlunos->solicitar($sqlBuscaAlunos);
	}
	
	if($conexaoBuscaProfessores->erro != '' or  $conexaoBuscaProfessores->registros < $numeroProfessores){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos professores informados.");
	}
	if($conexaoBuscaMonitores->erro != '' or  $conexaoBuscaMonitores->registros < $numeroMonitores){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos monitores informados.");
	}
	if($conexaoBuscaAlunos->erro != '' or  $conexaoBuscaAlunos->registros < $numeroAlunos){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos alunos informados.");
	}

	$dados = '';
//Tabela Planetas
	$pesquisaExistenciaTurma = new conexao();
	$pesquisaExistenciaTurma->solicitar("SELECT * FROM Turmas WHERE nomeTurma='$nome'");
	if(1 <= $pesquisaExistenciaTurma->registros){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Desculpe, já existe uma turma com o nome '".$nome."'.");
	}
	
	$pesquisaCadastroSQL = "INSERT INTO $tabela_turmas (nomeTurma, profResponsavel, descricao, serie, Escola) VALUES";
	if($nome != null){
		$pesquisaCadastroSQL.="('$nome',";
	} else {
		$pesquisaCadastroSQL.="('',";
	}
	if($professor != null){
		$pesquisaProfessor = new conexao();
		if($maeProfessor == null){
			$pesquisaProfessor->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$professor'");
		} else {
			$pesquisaProfessor->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$professor' AND usuario_nome_mae = '$maeProfessor'");
		}
		
		if($pesquisaProfessor->registros == 1){
			$idProfessor = $pesquisaProfessor->resultado['usuario_id'];
			$pesquisaCadastroSQL.="$idProfessor,";
			$numProfessoresEncontrados = 1;
		} else if(1 < $pesquisaProfessor->registros){
			$mensagemDeErro = "Desculpe. Existe mais de um professor com este nome.";
			
			$numProfessoresEncontrados = 0;
			for ($i=1;$i<=count($pesquisaProfessor->itens);$i++){
				$numProfessoresEncontrados = $numProfessoresEncontrados + 1;
			
				$dados .= '&professor_nome'.$i.		        	'='.$pesquisaProfessor->resultado['usuario_nome'];
				$dados .= '&mae_professor_nome'.$i.		        '='.$pesquisaProfessor->resultado['usuario_nome_mae'];
			
				$pesquisaProfessor->proximo();
			}
			$operacaoRealizadaComSucesso = false;
		} else {
			$numProfessoresEncontrados = 0;
			$mensagemDeErro = utf8_encode("Desculpe. Não foi possível encontrar o professor ".$professor.".");
			$operacaoRealizadaComSucesso = false;
			
			$pesquisaProfessor->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$professor'");
			if(1 <= $pesquisaProfessor->registros){
				$mensagemDeErro = utf8_encode("Desculpe. Não foi possível encontrar o professor ".$professor." com a mãe ".$maeProfessor.".");
			}
		}
	} else {
		$pesquisaCadastroSQL.="'',";
	}
	if($descricao != null){
		$pesquisaCadastroSQL.="'$descricao',";
	} else {
		$pesquisaCadastroSQL.="'',";
	}
	if($ano != null){
		$pesquisaAno = new conexao();
		$pesquisaAno->solicitar("SELECT * FROM Anos WHERE nome = '$ano'");
		
		if($pesquisaAno->registros == 1){
			$idAno = $pesquisaAno->resultado['id'];
			$pesquisaCadastroSQL.="$idAno, 1)";
		} else {
			$pesquisaCadastroSQL.="NULL, 1)";
		}
	} else {
		$pesquisaCadastroSQL.="'', 1)";
	}
	
	if($operacaoRealizadaComSucesso){
		$conexaoCadastro = new conexao();
		$conexaoCadastro->solicitar($pesquisaCadastroSQL);
		//$mensagemDeErro="1";
		if($conexaoCadastro->erro != ''){
			$mensagemDeErro.="3";
			$operacaoRealizadaComSucesso = false;
			//$mensagemDeErro.= $conexaoCadastro->erro;
			//$mensagemDeErro.="\n";
			$mensagemDeErro = "Desculpe. Houve um erro no banco de dados.";
		} else {
			$mensagemDeErro.="2";
			$idTurma = $conexaoCadastro->ultimo_id();
			$conexaoTerreno = new conexao();
			$pesquisaTerrenoSQL = "INSERT INTO $tabela_terrenos (terreno_nome) VALUES ('$nome')";
			$conexaoTerreno->solicitar($pesquisaTerrenoSQL);
			//$mensagemDeErro.= $conexaoTerreno->erro;
			//$mensagemDeErro.="\n";
			if($conexaoTerreno->erro == ''){
				$idTerreno = $conexaoCadastro->ultimo_id();
			}
			
			$pesquisaChat = new conexao();
			$pesquisaChat->solicitar("INSERT INTO Chats (nome) VALUES ('$nome')");
			$mensagemDeErro.="INSERT INTO Chats (nome) VALUES ($nome)";
			$mensagemDeErro.=$pesquisaChat->erro.'';
			
			$mensagemDeErro.="Ano eh ".$ano."\n";
			if($ano != "Outro"){
				$conexaoPlanetaAno = new conexao();
				$conexaoPlanetaAno->solicitar("SELECT * FROM Anos WHERE nome = '$ano'");
				$idPlanetaAno = $conexaoPlanetaAno->resultado['planeta'];
				
				//$mensagemDeErro.="nao eh outro>";
				//$mensagemDeErro.="\n";
				//$mensagemDeErro.="terreno=".$idTerreno."\n"."planeta=".$idPlanetaAno."\n";
			} else {
				$idPlanetaAno = 'NULL';
						//$mensagemDeErro.="eh outro>";
				$conexaoPlanetaOutro = new conexao();
				$conexaoPlanetaOutro->solicitar("SELECT * FROM Planetas WHERE Nome = 'Outro'");
				if(count($conexaoPlanetaOutro->itens) == 0){
					$conexaoTerreno = new conexao();
					$conexaoTerreno->solicitar("INSERT INTO $tabela_terrenos (terreno_nome) VALUES ('Outro')");
						//$mensagemDeErro.="\nnao achou>";
						//$mensagemDeErro.= '1='.$conexaoTerreno->erro;
						//$mensagemDeErro.="\n";
					$idTerreno = $conexaoTerreno->ultimo_id();
					
					$conexaoPlanetaOutro->solicitar("INSERT INTO Planetas (Tipo, Nome, Terrenos, IdResponsavel, IdsPais) VALUES (1, 'Outro', '$idTerreno', 0, '')");
						//$mensagemDeErro.= '2='.$conexaoPlanetaOutro->erro;
						//$mensagemDeErro.="\n";
					$idPlanetaAno = $conexaoPlanetaOutro->ultimo_id();
						//$mensagemDeErro.="terreno=".$idTerreno."\n"."planeta=".$idPlanetaAno."\n";
				} else {
					//$mensagemDeErro.="\nachou>";
					$conexaoPlanetaOutro = new conexao();
					$conexaoPlanetaOutro->solicitar("SELECT * FROM Planetas WHERE Nome = 'Outro'");
						//$mensagemDeErro.="\nresultado>".$conexaoPlanetaOutro->resultado;
					$idPlanetaAno = $conexaoPlanetaOutro->resultado['Id'];
						//$mensagemDeErro.="terreno=".$idTerreno."\n"."planeta=".$idPlanetaAno."\n";
				}
			}
			
			$conexaoPlaneta = new conexao();
			$pesquisaPlanetaSQL = "INSERT INTO Planetas (Tipo, Nome, Terrenos, IdResponsavel, IdsPais, acesso, edicao) VALUES (2, '$nome', '', $idProfessor, $idPlanetaAno, '$ACESSO_TODOS_PERMITIDOS', '$EDICAO_ALUNOS_NAO_PODEM')";
			$conexaoPlaneta->solicitar($pesquisaPlanetaSQL);
				//$mensagemDeErro.='\npesquisa='.$pesquisaPlanetaSQL.'';
				//$mensagemDeErro.='\nerro='.$conexaoPlaneta->erro;
			
			$conexaoPlaneta->solicitar("SELECT * FROM Planetas WHERE Tipo=2 AND Nome='$nome' AND Terrenos=$idTerreno AND IdResponsavel=$idProfessor AND IdsPais=$idPlanetaAno");
			$idPlaneta = $conexaoPlaneta->resultado["Id"];
			$conexaoTerreno->solicitar("UPDATE terrenos SET terreno_grupo_id=$idPlaneta WHERE terreno_id=$idTerreno");
			//$mensagemDeErro.= $conexaoPlaneta->erro;
			//$mensagemDeErro.="\n";
			//$mensagemDeErro.=$pesquisaPlanetaSQL;
			//$mensagemDeErro.="\n";
		
			$pesquisaEdicaoTurmas = new conexao();
			if($operacaoRealizadaComSucesso){
				$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
												WHERE codTurma = $idTurma
													AND associacao = $nivelProfessor");
			}
			
			$conexaoTerreno->solicitar("INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ($idTurma, $idProfessor, $nivelProfessor)");
			
			if($operacaoRealizadaComSucesso and 0 < $numeroProfessores){
				$sqlInsercaoProfessores = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
				for($i=0; $i<$numeroProfessores; $i++){
					if($i != 0){
						$sqlInsercaoProfessores .= ",";
					}
					$sqlInsercaoProfessores .= " (".$idTurma.",".($conexaoBuscaProfessores->resultado['usuario_id']).",".$nivelProfessor.")";
					$conexaoBuscaProfessores->proximo();
				}
				$pesquisaEdicaoTurmas->solicitar($sqlInsercaoProfessores);
				if($pesquisaEdicaoTurmas->erro != ''){
					$operacaoRealizadaComSucesso = false;
					$mensagemDeErro = utf8_encode("Erro na gravação dos professores.");
				}
			}
			
			if($operacaoRealizadaComSucesso){
				$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
												WHERE codTurma = $idTurma
													AND associacao = $nivelMonitor");
			}
			
			if($operacaoRealizadaComSucesso and 0 < $numeroMonitores){
				$sqlInsercaoMonitores = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
				for($i=0; $i<$numeroMonitores; $i++){
					if($i != 0){
						$sqlInsercaoMonitores .= ",";
					}
					$sqlInsercaoMonitores .= " (".$idTurma.",".($conexaoBuscaMonitores->resultado['usuario_id']).",".$nivelMonitor.")";
					$conexaoBuscaMonitores->proximo();
				}
				$pesquisaEdicaoTurmas->solicitar($sqlInsercaoMonitores);
				if($pesquisaEdicaoTurmas->erro != ''){
					$operacaoRealizadaComSucesso = false;
					$mensagemDeErro = utf8_encode("Erro na gravação dos monitores.");
				}
			}
			
			if($operacaoRealizadaComSucesso){
				$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
												WHERE codTurma = $idTurma
													AND associacao = $nivelAluno");
			}
			
			if($operacaoRealizadaComSucesso and 0 < $numeroAlunos){
				$sqlInsercaoAlunos = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
				for($i=0; $i<$numeroAlunos; $i++){
					if($i != 0){
						$sqlInsercaoAlunos .= ",";
					}
					$sqlInsercaoAlunos .= " (".$idTurma.",".($conexaoBuscaAlunos->resultado['usuario_id']).",".$nivelAluno.")";
					$conexaoBuscaAlunos->proximo();
				}
				$pesquisaEdicaoTurmas->solicitar($sqlInsercaoAlunos);
				if($pesquisaEdicaoTurmas->erro != ''){
					$operacaoRealizadaComSucesso = false;
					$mensagemDeErro = utf8_encode("Erro na gravação dos alunos.");
				}
			}
		}
	}
	
//Exportação
    $dados.= '&operacaoRealizadaComSucesso'    .'='.$operacaoRealizadaComSucesso;
	$dados.= '&numProfessoresEncontrados'      .'='.$numProfessoresEncontrados;
	$dados.= '&mensagemDeErro'                 .'='.$mensagemDeErro;

    echo $dados;
//A partir do fim do php, não escrever absolutamente nada. Nem código.
?>
