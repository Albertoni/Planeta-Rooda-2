<?php //arquivo para ser incluido no cabecalho dos arquivos do forum
/*
*	Verifica se o usuário pode usar o fórum e se tem a possibilidade de editar conteúdo.
*	retorna tres variáveis:
*	$leitura : boolean ( true, se ele tiver acesso ao forum )
*	$escrita : boolean ( true, se ele puder modificar conteudo no forum )
*	$verifica_user_erro_id : int (0,1,2,3,4,... depende do tipo de erro)
*
*
*
*	Além disto, tem a funçao que verifica se o usuário tem permissao para modificar conteudo de outro usuario
*	permissao(uid:int):boolean
/
$LEITURA = false;
$ESCRITA = false;
$VERIFICA_USER_ERRO_ID = 0;
$USUARIO_ID = $_SESSION['SS_usuario_id'];
$USUARIO_NIVEL = $_SESSION['SS_usuario_nivel_sistema'];
/* verifica o id do forum e procura uma turma com o mesmo id
if (!isset($FORUM_ID))
	$FORUM_ID = (isset($_GET['fid']))? $_GET['fid'] : $_POST['fid'];
/
$FORUM_ID = $_SESSION['SS_grupo_id'];
$SISTEMA = 'Sitema desconhecido';

$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
if($pesquisa1->erro != "") {
	$VERIFICA_USER_ERRO_ID = 1; //erro na conex com o BD
}else{
	$pesquisa1->solicitar("select * from $tabela_grupos where grupo_id = '$FORUM_ID' LIMIT 1"); // 
	if($pesquisa1->erro!= "") 
	{
		$VERIFICA_USER_ERRO_ID = 2; //erro na consulta com a tabela
	}else{
		if ($pesquisa1->registros < 1){
			$VERIFICA_USER_ERRO_ID = 3; //id do fórum inválido
		}else{
			if ($FORUM_ID != $_SESSION['SS_grupo_id']){
				echo $FORUM_ID." ".$_SESSION['SS_grupo_id']."<BR>";
				$VERIFICA_USER_ERRO_ID = 4; //usuario nao pertence ao grupo do forum
			}else{
				//agora sabemos q o forum existe e q o usuario pertence ao grupo.
				$LEITURA = true;
				$SISTEMA = $pesquisa1->resultado['grupo_nome'];
				
				//é necessário saber se o forum tem edicao livre
				if ($pesquisa1->resultado['grupo_edicao_livre'] == '1'){
					//caso tenha... é tudo liberado!!
					$ESCRITA = true;
				}else{
					//e, caso nao tenha... saber se o usuário é um convidado para a edicao
					$convidados_edicao = explode(",",$pesquisa1->resultado['grupo_edicao_convidados']);
					if ($pesquisa1->resultado['grupo_proprietario_id'] == $USUARIO_ID){
						$ESCRITA = true;
					}else{
						$dono_id = $pesquisa1->resultado['grupo_proprietario_id'];
						$pesquisa1->solicitar("select * from $tabela_usuarios where usuario_id = '$dono_id' LIMIT 1"); // 
						$ESCRITA = ($USUARIO_NIVEL < $pesquisa1->resultado['usuario_nivel']);
					}
					
					if (!$ESCRITA){
						foreach ($convidados_edicao as $convidado){
							if ($USUARIO_ID == $convidado) $ESCRITA = true;
						}
					}
					/*
					if (array_search($USUARIO_ID ,$convidados_edicao) != false){
						$ESCRITA = true;
					}
					/
				}
			}
		}
	}
}

function permissao($uid){
	global $BD_host1,$BD_base1,$BD_user1,$BD_pass1,$ESCRITA,$tabela_usuarios,$USUARIO_NIVEL,$USUARIO_ID;
	
	$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	if($pesquisa1->erro != "") {	
		return false;
	}else{
		$pesquisa1->solicitar("select * from $tabela_usuarios where usuario_id = '$uid' LIMIT 1");
		if($pesquisa1->erro != "") {
			return false;
		}else{
			if ($pesquisa1->registros < 1){
				return false;
			}else{
				$uid_nivel = $pesquisa1->resultado['usuario_nivel'];
				if (($USUARIO_NIVEL < $uid_nivel) && ($ESCRITA)){
					return true;
				}else{
					if ($USUARIO_ID == $uid){
						return true;
					}else{
						return false;
					}
				}
			}
		}
	}
}
*/
die("VOCE ESTA INCLUINDO O VERIFICA_SUER2 EM VEZ DO CORRETO");
?>
