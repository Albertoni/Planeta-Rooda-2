<?php
$AVISO = array();
$AVISO[0] = "ERRO DE BD OU PERMISSAO";
$AVISO[1] = "ERRO DE BD OU PERMISSAO";
$AVISO[2] = "ERRO DE BD OU PERMISSAO";
$AVISO[3] = "ERRO DE BD OU PERMISSAO";
$AVISO[4] = "ERRO DE BD OU PERMISSAO";
$AVISO[5] = "O forum está vazio";
$AVISO[6] = "O tópico está vazio";
$AVISO[7] = "Nenhum resultado para esta pesquisa";
$AVISO[8] = "Este fórum não existe";
$AVISO[9] = "Você não pertence a este fórum";

function mostraArquivo ($id_msg,$dono, $dono, $autor, $data, $cor, $editavel){
	$classe_cor = ($cor == 0)? 'bloco_arquivos_enviados1' : 'bloco_arquivos_enviados2';
	$vetor_data = explode(",",$data);
?>
    <ul id="t<?php echo $id_msg?>" class="<?php echo  $classe_cor?>">
            <li><span class="dados">Enviado&nbsp;Por:</span><span class="valor"><?php echo $dono;?></span></li>
            <li><span class="dados">Autor:</span><span class="valor"><?php echo $autor;?></span></li>
            <li><span class="dados">Título&nbsp;do&nbsp;Material:</span><span class="valor"><?php echo $titulo;?></span></li>
            <li><span class="dados">Palavras&nbsp;do&nbsp;Material:</span><span class="valor"><?php echo $tags;?></span></li>
            <li><span class="dados">Data:</span><span class="valor"><?php echo $data;?></span></li>
            <li><span class="valor"><a href="#"><?php echo $link;?></a></span></li>
            <li><span class="valor"><a href="#" onclick="mostraComentarios(<?php echo $id_msg;?>);"><?php echo $comentarios;?></a></span></li>
			<?php
	if ($editavel){?>
            <li><div class="enviar" align="right">
            <input type="image" class="excluir_arquivo" src="images/botoes/bt_excluir.png" onclick="excluiArq(<?php echo $id_msg?>)"/>
            <input type="image" class="editar_arquivo" src="images/botoes/bt_editar.png" onclick="editaArq(<?php echo $id_msg?>)" /></div></li>
	<?php
	} ?>
    </ul> 
<?php
}

?>