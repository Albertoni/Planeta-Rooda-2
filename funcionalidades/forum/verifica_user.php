<?php //arquivo para ser incluido no cabecalho dos arquivos do forum
/*
*	Verifica se o usu�rio pode usar o f�rum e se tem a possibilidade de editar conte�do.
*	retorna tres vari�veis:
*	$leitura : boolean ( true, se ele tiver acesso ao forum )
*	$escrita : boolean ( true, se ele puder modificar conteudo no forum )
*	$verifica_user_erro_id : int (0,1,2,3,4,... depende do tipo de erro)
*
*
*
*	Al�m disto, tem a fun�ao que verifica se o usu�rio tem permissao para modificar conteudo de outro usuario
*	permissao(uid:int):boolean
*/

require('../../usuarios.class.php');

global $_SESSION;

$LEITURA = false;
$ESCRITA = false;
$VERIFICA_USER_ERRO_ID = 0;
$USUARIO_ID = $_SESSION['SS_usuario_id'];
$USUARIO_NIVEL = $_SESSION['SS_usuario_nivel_sistema'];

global $turma;
$FORUM_ID = isset($_GET['turma'])?$_GET['turma']:(isset($_POST['turma'])?$_POST['turma']:die("A ID DA TURMA N�O FOI PASSADA CORRETAMENTE. Favor voltar e tentar novamente. Caso isso continue a ocorrer, entre em contato com a equipe.")); // Passando para funcionar com turmas e n�o mais por terreno

$SISTEMA = 'Sistema desconhecido';

/*$pesquisa1 = new conexao();
if($pesquisa1->erro != "") {
	$VERIFICA_USER_ERRO_ID = 1; //erro na conex com o BD
}else{
	$pesquisa1->solicitar("select * from $tabela_turmas where codTurma = '$FORUM_ID' LIMIT 1");
	if($pesquisa1->erro!= ""){
		$VERIFICA_USER_ERRO_ID = 2; //erro na consulta com a tabela
	}else{
		if ($pesquisa1->registros < 1){
			$VERIFICA_USER_ERRO_ID = 3; //id do f�rum inv�lido
		}else{
			$perm = checa_permissoes(TIPOFORUM, $FORUM_ID);
			
			if ($perm === false){
				$VERIFICA_USER_ERRO_ID = 10; // N�o pode visualizar o forum porque este est� desabilitado para a turma
			}else{
				//Qualquer um pode ler qualquer f�rum, de acordo com as especifica��es.
				$LEITURA = true;
				$SISTEMA = $pesquisa1->resultado['nomeTurma'];
				
				
				//� necess�rio saber se o forum tem edicao livre
				//caso tenha... � tudo liberado!!
				$ESCRITA = true;
			}
		}
	}
}*/

// Verifica se tem permiss�o para escrever em algum lugar e editar algum post. ACHO.

function permissao($uid, $post_id, $acaoExecutada){
	/*\
	 * $uid == id do usuario, n�o necess�rio, REMOVER
	 * $post_id == id do post, necess�rio para o limite de 5 minutos para edi��o
	 * $acaoExecutada == Acao que o usu�rio quer fazer, provavelmente ser� uma dessas:
	 *   TODO
	 *  
	 *  
	\*/
	global $tabela_forum, $FORUM_ID;
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);
	
	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
	
	
	if($user->podeAcessar($permissoes[$acaoExecutada], $FORUM_ID)){
		$q = new conexao();
		$q->solicitar("SELECT msg_data FROM $tabela_forum WHERE msg_id = $post_id");
		
		if (!tempo_edicao_forum($q->resultado['msg_data'])){// funcoes_aux.php
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

?>
