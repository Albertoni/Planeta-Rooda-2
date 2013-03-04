<?php
/*
* Registra uma entrada do interROODA que corresponde a acesso ao planeta.
*/
session_start();

require("../cfg.php");
require("../bd.php");
require("../funcoes_aux.php");

/*
@author Yuri Pelz Gossmann 
@date 2012-08-09 -> 2012-08-20
movido para registra_interROODA por Diogo em 2012-10-03
INÍCIO
*/
$acessoPlaneta=new conexao();
$acessoPlaneta->solicitarSI('SELECT id_acesso,id_terreno,funcionalidade,data_hora
							 FROM '.$tabela_acessos_planeta.'
							 WHERE id_acesso=(SELECT MAX(id_acesso)
											  FROM '.$tabela_acessos_planeta.'
											  WHERE id_usuario='.intval($_SESSION['SS_usuario_id']).')');

$terreno_id = $_GET['idTerrenoAtual'];
$numeroCaracteresIgnorados = $_GET['numeroCaracteresIgnorados'];

if(isset($_GET['linkFuncionalidade'])){
	$link=strtolower($_GET['linkFuncionalidade']);
	switch(substr($link,$numeroCaracteresIgnorados,31+$numeroCaracteresIgnorados)){
		case 'funcionalidades/biblioteca/bibl': if(substr($link,31+$numeroCaracteresIgnorados,10)==='ioteca.php')
													$funcionalidade='biblioteca';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/blog/blog_inici': if(substr($link,31+$numeroCaracteresIgnorados,5)==='o.php')
													$funcionalidade='blog';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/forum/forum.php': $funcionalidade='forum';
												break;
		case 'funcionalidades/portfolio/portf': if(substr($link,31+$numeroCaracteresIgnorados,8)==='olio.php')
													$funcionalidade='portfolio';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/criar_personage': if(substr($link,31+$numeroCaracteresIgnorados,34)==='m/criar_personagem.php?id_char_as=')
													$funcionalidade='aparencia';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/arte/planeta_ar': if(substr($link,31+$numeroCaracteresIgnorados,7)==='te2.php')
													$funcionalidade='arte';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/pergunta/planet': if(substr($link,31+$numeroCaracteresIgnorados,14)==='a_pergunta.php')
													$funcionalidade='pergunta';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/aulas/planeta_a': if(substr($link,31+$numeroCaracteresIgnorados,8)==='ulas.php')
													$funcionalidade='aulas';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/roodaplayer/ind': if(substr($link,31+$numeroCaracteresIgnorados,6)==='ex.php')
													$funcionalidade='player';
												else
													$funcionalidade='';
												break;
		case 'funcionalidades/gerenciamento_f': if(substr($link,31+$numeroCaracteresIgnorados,31)==='uncionalidades_turmas/index.php')
													$funcionalidade='gerenc_funcio_turmas';
												else
													$funcionalidade='';
												break;
		default: $funcionalidade='';
				 break;
	}
}
else
	$funcionalidade='';

$agora=date('Y-m-d H:i:s');
$duracao=intval(strtotime($agora)-strtotime($acessoPlaneta->resultado['data_hora']));
if($acessoPlaneta){
	$acessoPlaneta->solicitarSI('UPDATE '.$tabela_acessos_planeta.'
								 SET duracao='.$duracao.'
								 WHERE id_acesso='.$acessoPlaneta->resultado['id_acesso']);
	if(((intval($terreno_id)!=intval($acessoPlaneta->resultado['id_terreno']))||($funcionalidade!=$acessoPlaneta->resultado['funcionalidade']))&&($duracao>1)){
		$acessoPlaneta->solicitarSI('INSERT
									 INTO '.$tabela_acessos_planeta.' (id_usuario,id_terreno,funcionalidade,data_hora,duracao)
									 VALUES ('.intval($_SESSION['SS_usuario_id']).','.intval($terreno_id).',"'.$funcionalidade.'","'.$agora.'",0)');
	}
}
else
	$acessoPlaneta->solicitarSI('INSERT
								 INTO '.$tabela_acessos_planeta.' (id_usuario,id_terreno,funcionalidade,data_hora,duracao)
								 VALUES ('.intval($_SESSION['SS_usuario_id']).','.intval($terreno_id).',"'.$funcionalidade.'","'.$agora.'",0)');
/*
FIM
*/


?>