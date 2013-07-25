<?php
$AVISO = array(); // verifica_user que determina se uma dessas variáveis de erro é mostrada, com a variável "$VERIFICA_USER_ERRO_ID"
$AVISO[0] = "Erro desconhecido!";
$AVISO[1] = "Não foi possível se conectar com o banco de dados.";
$AVISO[2] = "Erro na consulta ao banco de dados.";
$AVISO[3] = "Esse fórum não existe, possivelmente por ter sido acessado diretamente.";
$AVISO[4] = "Você não pertence ao grupo desse fórum.";
$AVISO[5] = "O forum está vazio";
$AVISO[6] = "O tópico está vazio";
$AVISO[7] = "Nenhum resultado para esta pesquisa";
$AVISO[8] = "Este fórum não existe";
$AVISO[9] = "Você não pertence a este fórum";
$AVISO[10]= "O fórum está desabilitado para essa turma.";

function mostraMensagens ($id_msg,$dono, $mensagem, $data, $cor, $editavel,$resposta =false){
	global $FORUM_ID;
	$classe_cor = ($cor == 0)? 'cor1' : 'cor2';
	$vetor_data = explode(",",$data);
?>
<div id="t<?php echo $id_msg?>" class="<?php echo $classe_cor?>">
<ul>
		<li class="tabela">
			<div class="info" <?php echo (!$editavel)? 'style="float:none;"': '';?>>
				<p class="nome"><?php echo $dono?></p>
				<p class="data"><span style="color:#C60;"><?php echo $vetor_data[0]?></span> às <span style="color:#C60;"><?php echo $vetor_data[1]?></span></p>
			</div>
<?php if ($editavel) { ?>
			<div class="bts_msg" align="right">
			<input type="image" src="../../images/botoes/bt_editar.png" onclick="editar(<?php echo $FORUM_ID?>,<?php echo $id_msg?>)"/>
			<input type="image" src="../../images/botoes/bt_excluir.png" onclick="excluir(<?php echo $FORUM_ID?>,<?php echo $id_msg?>,deltipo)"/>
			</div>
<?php } ?>
		</li>
		<li>
			<div class="imagem"></div>
			<div class="limite_resposta">
				<p class="texto_resposta"><?php echo $mensagem?></p>
			</div>
		</li>
<?php if ($resposta) { ?>
		<li>
			<div class="bts_msg" align="right">
				<input type="image" src="../../images/botoes/bt_responder_pq.png" onclick="responder(<?php echo $id_msg?>)"/>
			</div>
		</li>
		<li id="li_resposta_<?php echo $id_msg?>" style="display:none;">
			<textarea class="msg_dimensao" rows="10" id="msg_txt_<?php echo $id_msg?>"></textarea>
			<div class="bts_msg" align="right">
				<input type="image" src="../../images/botoes/bt_enviar_pq.png" onclick="enviarRsp(<?php echo $FORUM_ID?>,<?php echo $id_msg?>)"/>
				<input type="image" src="../../images/botoes/bt_cancelar_pq.png" onclick="cancelarRsp(<?php echo $id_msg?>)"/>
			</div>
		</li>
<?php } ?>
	</ul>
</div>
<?php
}

function mostraTopicos ($id_msg,$dono, $titulo, $mensagem, $data, $quantidade, $cor, $uId){
	global $FORUM_ID;
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);

	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
	
	$classe_cor = ($cor == 0)? 'cor1' : 'cor2';
	$link = "forum_arvore.php?turma=$FORUM_ID&topico=".$id_msg;
	$vetor_data = explode(",",$data);
	if ($quantidade < 1)
		$mens_qnt = "Sem mensagens";
	else if ($quantidade == 1)
			$mens_qnt = "Uma mensagem";
		else if ($quantidade == 2)
				$mens_qnt = "Duas mensagens";
			else
				$mens_qnt = "$quantidade mensagens";
?>
<span>
<div id="t<?php echo $id_msg?>" class="<?php echo  $classe_cor?>">
	<div class="esq">
	<div class="imagem"><img src="img_output.php?id=<?=$uId?>"/></div>

	<ul>
		<li><a id="ta<?php echo $id_msg?>" href="<?php echo $link?>"><?php echo $titulo?></a></li>
		<li class="mensagens"><?php echo $mens_qnt;?></li>
		</ul>
		</div>
		<div class="dir">
		<ul>
		<li>
		<div class="limite_topico">
		<div style="height:70px; overflow:hidden;"><a id="tm<?php echo $id_msg?>" href="<?php echo $link?>"><?php echo $mensagem?></a></div>
		</div>
		</li>
		<li class="criado_por">Por: <span style="color:#C60;"><?php echo $dono?></span> em <span style="color:#C60;"><?php echo $vetor_data[0]?></span> às  <span style="color:#C60;"><?php echo $vetor_data[1]?></span></li>
		<li><div class="enviar" align="right">
<?php
if ($user->podeAcessar($permissoes['forum_editarTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_editar.png\" onclick=\"editar($FORUM_ID,$id_msg)\"/>";
}
if ($user->podeAcessar($permissoes['forum_excluirTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluir($FORUM_ID,$id_msg,deltipo)\"/>";
}
?>
		</div></li>
	</ul>
	</div>
</div></span>
<?php
}

function mostraPaginas (&$pags, $pagina, $pesquisa, $lnk="forum.php"){
$listapagina = '';
?>
<div class="troca_paginas">
	<center>
		<div class="paginas_padding">
<?php
	if ($pesquisa){
		$listapagina = '<a href="#" onclick="pesquisar('.$pags[0].',false);" class="primeira"><< Primeira</a><a href="#" onclick="pesquisar('.$pags[1].',false);" class="seguinte">< Anterior</a>';
		for ($i=4; $i< count($pags); $i++){
			if ($pags[$i] != $pagina){
				$listapagina = $listapagina.'<a href="#" onclick="pesquisar('.$pags[$i].',false);" class="numero">'.$pags[$i].'</a>';
			}else{
				$listapagina = $listapagina.'<span class="numero_atual">'.$pags[$i].'</span>';
			}
		}
		$listapagina = $listapagina.'<a href="#" onclick="pesquisar('.$pags[2].',false);" class="seguinte">Próxima ></a><a href="#" onclick="pesquisar('.$pags[3].',false);" class="primeira">Última >></a>';
	}else{
		$listapagina = '<a href="'.$lnk.'&pagina=1" class="primeira"><< Primeira</a><a href="'.$lnk."&pagina=".$pags[1].'" class="seguinte">< Anterior</a>';
		for ($i=4; $i< count($pags); $i++){
			if ($pags[$i] != $pagina){
				$link = $lnk."&pagina=".$pags[$i];
				$listapagina = $listapagina.'<a href="'.$link.'" class="numero">'.$pags[$i].'</a>';
			}else{
				$listapagina = $listapagina.'<a href="#" class="numero_atual">'.$pags[$i].'</a>';
			}
		}
		$listapagina = $listapagina.'<a href="'.$lnk."&pagina=".$pags[2].'" class="seguinte">Próxima ></a><a href="'.$lnk."&pagina=".$pags[3].'" class="primeira">Última >></a>';
	}
echo $listapagina;
?>
				</div>
			</center>
	</div>
<?php
	echo "<script>forum_pg = $pagina;</script>";
}

function mostraAviso ($id){ 
global $AVISO;
?>
<div class="cor1">
	<ul>
		<li>
			<center><p class="texto_resposta"><?php echo $AVISO[$id]?></p></center>
		</li>
	</ul>
</div>
<?php }

function mostraPesquisa ($id_msg, $pai, $dono, $titulo, $mensagem, $data, $cor, $editavel, $uId){
	global $FORUM_ID;
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);

	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
	
	$classe_cor = ($cor == 0)? 'cor1' : 'cor2';
	if ($pai == '-1'){
		$link = "forum_arvore.php?turma=$FORUM_ID&topico=".$id_msg;
	}else{
		$link = "forum_arvore.php?turma=$FORUM_ID&topico=".$pai;
	}
	$vetor_data = explode(",",$data);
?>
<span>
<div id="t<?php echo $id_msg?>" class="<?php echo  $classe_cor?>">
	<div class="esq">
	<div class="imagem"><img src="img_output.php?id=<?=$uId?>"/></div>

	<ul>
		<li><a id="ta<?php echo $id_msg?>" href="<?php echo $link?>"><?php echo $titulo?></a></li>
		</ul>
		</div>
		<div class="dir">
		<ul>
		<li>
		<div class="limite_topico">
		<div style="height:70px; overflow:hidden;"><a id="tm<?php echo $id_msg?>" href="<?php echo $link?>"><?php echo $mensagem?></a></div>
		</div>
		</li>
		<li class="criado_por">Por: <span style="color:#C60;"><?php echo $dono?></span> em <span style="color:#C60;"><?php echo $vetor_data[0]?></span> às  <span style="color:#C60;"><?php echo $vetor_data[1]?></span></li>
		<li><div class="enviar" align="right">
<?php
if ($user->podeAcessar($permissoes['forum_editarTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_editar.png\" onclick=\"editar($FORUM_ID,$id_msg)\"/>";
}
if ($user->podeAcessar($permissoes['forum_excluirTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluir($FORUM_ID,$id_msg,deltipo)\"/>";
}
?>
		</div></li>
	</ul>
	</div>
</div></span>
<?php
}
//mostraArvore($mens->msgId,$mens->msgUserName,$mens->msgTexto,$mens->msgData,($i % 2), $mens->msgGrau, true, $mens->msgUserId);
function mostraArvore ($id_msg,$dono, $mensagem, $data, $cor, $grau, $resposta, $uId){
	global $FORUM_ID;
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);

	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
	
	//$classe_cor = ($cor == 0)? 'cor1' : 'cor2';
	$classe_cor = 'cor3';
	$vetor_data = explode(",",$data);
	$res = '';
	$editavel = true;
	for ($i=0;$i<$grau; $i++)
		$res .= "Re.:";
	
?>
<div id="t<?php echo $id_msg?>" class="<?php echo  $classe_cor?>">
<ul>
		<li class="tabela">
		<div class="info" <?php echo (!$editavel)? 'style="float:none;"': '';?>>
			<p class="nome"><?php echo "<b>$res</b> $dono";?></p>
			<p class="data"><span style="color:#C60;"><?php echo $vetor_data[0]?></span> às  <span style="color:#C60;"><?php echo $vetor_data[1]?></span></p>
		</div>
			<div class="bts_msg" align="right">
<?php
if ($user->podeAcessar($permissoes['forum_editarTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_editar.png\" onclick=\"editar($FORUM_ID,$id_msg)\"/>";
}
if ($user->podeAcessar($permissoes['forum_excluirTopico'], $FORUM_ID)) {
	echo "		<input type=\"image\" src=\"../../images/botoes/bt_excluir.png\" onclick=\"excluir($FORUM_ID,$id_msg,deltipo)\"/>";
}
?>
			</div>
		</li>
		<li>
			<div class="imagem"><img src="img_output.php?id=<?=$uId?>"/></div>
			<div class="limite_resposta">
				<p class="texto_resposta"><?php echo $mensagem?></p>
			</div>
		</li>
<?php if ($user->podeAcessar($permissoes['forum_responderTopico'], $FORUM_ID)) { ?>
		<li>
			<div class="bts_msg" align="right">
				<input type="image" src="../../images/botoes/bt_responder_pq.png" onclick="responder(<?php echo $id_msg?>)"/>
			</div>
		</li>
		<li id="li_resposta_<?php echo $id_msg?>" style="display:none;">
			<textarea class="msg_dimensao" rows="10" id="msg_txt_<?php echo $id_msg?>"></textarea>
			<div class="bts_msg" align="right">
			<input type="image" src="../../images/botoes/bt_enviar_pq.png" onclick="enviarRsp(<?php echo $FORUM_ID?>,<?php echo $id_msg?>)"/>
			<input type="image" src="../../images/botoes/bt_cancelar_pq.png" onclick="cancelarRsp(<?php echo $FORUM_ID?>,<?php echo $id_msg?>,deltipo)"/>
			</div>
		</li>
<?php } ?>
	</ul>
</div>
<?php
}


?>
