<?php
/*
* Link para debug:
* http://sideshowbob/planeta2_diogo/desenvolvimento/atualizar_dados_ops.php?posicao_x=300&posicao_y=300&terreno_id=1&personagem_id=469&personagem_velocidade=1&rota=NOROESTE
*/

session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

/*---------------------------------------------------
*	Recebe informações sobre o mp no terreno, grava no bd e envia hora atual. Envia para o flash as informações dos ops online. - Guto - 10.05.10
---------------------------------------------------*/
$posicao_x    		= $_POST["posicao_x"];
$posicao_y    		= $_POST["posicao_y"];
$terreno_id   		= $_POST["terreno_id"];
$personagem_id		= $_POST["personagem_id"];
$velocidade 		= $_POST["personagem_velocidade"];  
$rota				= $_POST["rota"];
$debug				= $_POST["debug"];

//se um personagem estiver sem atualização por x min (abaixo) ele é considerado offline    
$limite = strtotime("-5 seconds");
$limite = date("Y-m-d H:i:s", $limite);

$limiteRotas = strtotime("-60 seconds");
$limiteRotas = date("Y-m-d H:i:s", $limiteRotas);

$conexao_falas_personagem = new conexao();

//A hora em que foi feita a última atualização deste personagem.
$conexao_rota = new conexao();
$conexao_rota->solicitar("SELECT *
						FROM $tabela_personagens
						WHERE personagem_id = $personagem_id");
$hora_ultima_atualizacao = $conexao_rota->resultado['personagem_ultimo_acesso'];
//$terreno_id = $conexao_rota->resultado['personagem_terreno_id'];
$conexao_rota->solicitar("SELECT *
						FROM terrenos
						WHERE terreno_id = $terreno_id");
$id_chat_terreno = $conexao_rota->resultado['chat_id'];

//Atualiza dados do personagem online.
$conexao_personagens = new conexao();
$conexao_personagens->solicitar("UPDATE $tabela_personagens 
								SET personagem_posicao_x=$posicao_x, personagem_posicao_y=$posicao_y, 
									personagem_terreno_id=$terreno_id, 
									personagem_velocidade = $velocidade, 
									personagem_ultimo_acesso = now()
								WHERE personagem_id = $personagem_id");

//Encontrar a última rota gravada do personagem.
/*if($rota != ""){ //Se o personagem se moveu, guardar seu movimento.
	$conexao_rota->solicitar("INSERT INTO rotas_personagens (personagem_id, posicao_x, posicao_y, hora_atualizacao, debug)
							VALUES ($personagem_id, $posicao_x, $posicao_y, now(), $debug)");
}*/

//Deletar rotas antigas.
/*$conexao_rota->solicitar("DELETE FROM rotas_personagens
						WHERE hora_atualizacao < '$limiteRotas'");*/

//Encontrar personagens online.
$conexao_personagens->solicitar("SELECT * FROM `$tabela_personagens` 
								WHERE personagem_terreno_id=$terreno_id 
									AND personagem_ultimo_acesso > '$limite' 
								ORDER BY `personagem_id` ASC");
//$statusTerreno = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
//$statusTerreno->solicitar("SELECT terreno_status, terreno_id_autor FROM `$tabela_terrenos` WHERE terreno_id=$terreno_id");
//$terrenoEditado = $statusTerreno->resultado['terreno_status'];
//$idAutor        = $statusTerreno->resultado['terreno_id_autor'];
if($conexao_personagens->erro== ""){
	$dados = '';
	$i=0;
	for($registroAtual=0; $registroAtual < $conexao_personagens->registros ; $registroAtual++){
		$id = $conexao_personagens->resultado['personagem_id'];

		if($personagem_id != $id){
			$personagem_nome      			= $conexao_personagens->resultado['personagem_nome'];
			$personagem_avatar_1  			= $conexao_personagens->resultado['personagem_avatar_1'];
			$personagem_posicao_x 			= $conexao_personagens->resultado['personagem_posicao_x'];
			$personagem_posicao_y 			= $conexao_personagens->resultado['personagem_posicao_y'];
			$personagem_cor_texto 			= $conexao_personagens->resultado['personagem_cor_texto'];
			$personagem_velocidade 			= $conexao_personagens->resultado['personagem_velocidade'];
			$personagem_cabelos 			= $conexao_personagens->resultado['personagem_cabelos'];
			$personagem_olhos 				= $conexao_personagens->resultado['personagem_olhos'];
			$personagem_cor_pele 			= $conexao_personagens->resultado['personagem_cor_pele'];
			$personagem_cor_cinto 			= $conexao_personagens->resultado['personagem_cor_cinto'];
			$personagem_cor_luvas_botas 	= $conexao_personagens->resultado['personagem_cor_luvas_botas'];
			$dados .= '&personagem_nome'.$i.		'='.$personagem_nome;
			$dados .= '&personagem_avatar_1'.$i.		'='.$personagem_avatar_1;
			$dados .= '&personagem_posicao_x'.$i.		'='.$personagem_posicao_x;
			$dados .= '&personagem_posicao_y'.$i.		'='.$personagem_posicao_y;
			$dados .= '&personagem_cor_texto'.$i.		'='.$personagem_cor_texto;
			$dados .= '&personagem_velocidade'.$i.		'='.$personagem_velocidade;
			$dados .= '&personagem_cabelos'.$i.			'='.$personagem_cabelos;
			$dados .= '&personagem_olhos'.$i.			'='.$personagem_olhos;
			$dados .= '&personagem_cor_pele'.$i.		'='.$personagem_cor_pele;
			$dados .= '&personagem_cor_cinto'.$i.		'='.$personagem_cor_cinto;
			$dados .= '&personagem_cor_luvas_botas'.$i.	'='.$personagem_cor_luvas_botas;
			$dados .= '&id'.$i.'='.$id; 

			$conexao_falas_personagem->solicitar("SELECT texto_fala
												FROM falas_personagens
												WHERE id_personagem = $id
													AND data >= '$hora_ultima_atualizacao'
													AND chat_id = $id_chat_terreno");
			for($falaAtual = 0; $falaAtual < $conexao_falas_personagem->registros; $falaAtual++){
				$personagem_fala_texto = $conexao_falas_personagem->resultado['texto_fala'];
				$dados .= '&personagem_fala_texto'.$i.','.$falaAtual.'='.$personagem_fala_texto;
				$conexao_falas_personagem->proximo();
			}
			$dados .= '&personagem_total_falas_recentes'.$i.'='.$conexao_falas_personagem->registros; 
			
			$i = $i+1;
		}
		$conexao_personagens->proximo();
	}
} 

$nLoop = $conexao_personagens->registros;

$dados .= '&ultima_atualizacao3='.date("Y-m-j H:i:s");
//$dados .= '&statusTerreno='.$terrenoEditado;
//$dados .= '&idAutor='.$idAutor;
$exportar = $dados . '&nLoop=' . $nLoop;

echo $exportar;
?>
