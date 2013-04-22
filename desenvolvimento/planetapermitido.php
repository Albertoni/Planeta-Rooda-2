<?php
	session_start();
	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require("../funcoes_aux.php");
	
	/*---------------------------------------------------
	*	Retorna os terrenos principais de planetas que o usuário pode acessar.
	---------------------------------------------------*/
	$PLANETA_ACESSO_TODOS = 1 << 6; //64
	$PLANETA_ACESSO_PROFESSORES = 1 << 0; //1
	$PLANETA_ACESSO_ALUNOS = 1 << 1; //2
	$PLANETA_ACESSO_VISITANTES = 1 << 2; //4
	$PLANETA_ACESSO_NENHUM = 0; //0
	
	$usuario_id = $_POST['usuario_id'];
	$dados = "";
	
	$pesquisa_usuario = new conexao(); 	
	$pesquisa_planetas = new conexao();
	if(($pesquisa_usuario->erro!= "") or ($pesquisa_planetas->erro!= "")) { 
		$dados = "&erroAdm=1"; 
	} else {
		$dados = "";
		$dados .= "&erroAdm=0"; 
		
		$pesquisa_usuario->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id=$usuario_id");
		if($pesquisa_usuario->erro != ""){
			$dados .= "&erroAdm=2"; 
		}
		$nivel_usuario = $pesquisa_usuario->resultado['usuario_nivel'];
		$usuario_admin = (checa_nivel($nivel_usuario, $nivelAdmin) === 1) ? 1 : 0;
		$usuario_coordenador = 0;//(checa_nivel($nivel_usuario, $nivelCoordenador) === 1) ? 1 : 0;
		$usuario_professor = (checa_nivel($nivel_usuario, $nivelProfessor) === 1) ? 1 : 0;
		$usuario_monitor = 0;//(checa_nivel($nivel_usuario, $nivelMonitor) === 1) ? 1 : 0;
		$usuario_aluno = (checa_nivel($nivel_usuario, $nivelAluno) === 1) ? 1 : 0;
		$usuario_visitante = (checa_nivel($nivel_usuario, $nivelVisitante) === 1) ? 1 : 0;
			/*echo "nivel_usuario=".$nivel_usuario;
			echo "\n";
			echo "usuario_admin=".$usuario_admin;
			echo "\n";
			echo "usuario_coordenador=".$usuario_coordenador;
			echo "\n";
			echo "usuario_professor=".$usuario_professor;
			echo "\n";
			echo "usuario_monitor=".$usuario_monitor;
			echo "\n";
			echo "usuario_aluno=".$usuario_aluno;
			echo "\n";
			echo "usuario_visitante=".$usuario_visitante;
			echo "\n";*/
		$pesquisa_planetas->solicitar("SELECT * FROM Planetas");
		if($pesquisa_planetas->erro != ""){
			$dados .= "&erroAdm=2"; 
		}
		
		$planetas_com_permissao_acesso_encontrados = 0;
		for($planeta = 0; $planeta < count($pesquisa_planetas->itens); $planeta++){
			$permissao_acesso_planeta = (int) $pesquisa_planetas->resultado['acesso'];
			$id_dono_planeta = $pesquisa_planetas->resultado['IdResponsavel'];
			
			$usuario_dono_planeta = ($id_dono_planeta == $usuario_id) ? 1 : 0;
			$planeta_nao_permite_acesso = ($permissao_acesso_planeta == $PLANETA_ACESSO_NENHUM) ? 1 : 0;
			$planeta_permite_acesso_todos = (($permissao_acesso_planeta & $PLANETA_ACESSO_TODOS) != 0) ? 1 : 0;
			$planeta_permite_acesso_professores = (($permissao_acesso_planeta & $PLANETA_ACESSO_PROFESSORES) != 0) ? 1 : 0;
			$planeta_permite_acesso_alunos = (($permissao_acesso_planeta & $PLANETA_ACESSO_ALUNOS) != 0) ? 1 : 0;
			$planeta_permite_acesso_visitantes = (($permissao_acesso_planeta & $PLANETA_ACESSO_VISITANTES) != 0) ? 1 : 0;
			
			$nome_planeta = ($pesquisa_planetas->resultado['Nome']);
			
			$usuario_pode_acessar = (($usuario_admin 
										|| $usuario_coordenador
			                            || $usuario_dono_planeta)
									//||
									//($planeta_nao_permite_acesso
									//	&& ($usuario_admin 
									//		|| $usuario_dono_planeta)) 
									|| $planeta_permite_acesso_todos
									|| ($planeta_permite_acesso_professores && $usuario_professor)
									|| ($planeta_permite_acesso_alunos && $usuario_aluno)
									|| ($planeta_permite_acesso_visitantes && $usuario_visitante)
									);
			if($usuario_pode_acessar != 1){
				$usuario_pode_acessar = 0;
			}

			/*echo "NOME_PLANETA=".$nome_planeta."-------------------------";
			echo "\n";
			echo "nivel_usuario=".$nivel_usuario;
			echo "\n";
			echo "usuario_admin=".$usuario_admin;
			echo "\n";
			echo "usuario_coordenador=".$usuario_coordenador;
			echo "\n";
			echo "usuario_professor=".$usuario_professor;
			echo "\n";
			echo "usuario_monitor=".$usuario_monitor;
			echo "\n";
			echo "usuario_aluno=".$usuario_aluno;
			echo "\n";
			echo "usuario_visitante=".$usuario_visitante;
			echo "\n";
			echo "acesso_planeta=".$permissao_acesso_planeta;
			echo "\n";
			echo "usuario_dono_planeta=".$usuario_dono_planeta;
			echo "\n";
			echo "planeta_nao_permite_acesso=".$planeta_nao_permite_acesso;
			echo "\n";
			echo "planeta_permite_acesso_todos=".$planeta_permite_acesso_todos;
			echo "\n";
			echo "planeta_permite_acesso_professores=".$planeta_permite_acesso_professores;
			echo "\n";
			echo "planeta_permite_acesso_alunos=".$planeta_permite_acesso_alunos;
			echo "\n";
			echo "planeta_permite_acesso_visitantes=".$planeta_permite_acesso_visitantes;
			echo "\n";
			echo "usuario_pode_acessar=".$usuario_pode_acessar;
			echo "\n";*/
			
			if($usuario_pode_acessar){
				$indice = $planetas_com_permissao_acesso_encontrados;
				
				$id_planeta = ($pesquisa_planetas->resultado['Id']);
				$nome_planeta = ($pesquisa_planetas->resultado['Nome']);
				
				$dados .= '&id_planeta'.$indice.'='.$id_planeta;
				$dados .= '&nome_planeta'.$indice.'='.$nome_planeta;
				
				$planetas_com_permissao_acesso_encontrados++;
			}
			
			$pesquisa_planetas->proximo();
		}
	}
	
	$dados  .= '&numero_planetas_encontrados='.$planetas_com_permissao_acesso_encontrados;
	echo utf8_encode($dados);
?>
