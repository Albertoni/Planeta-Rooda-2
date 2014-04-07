<?php
require('../../cfg.php');
require('../../bd.php');



print_r($_GET);

$q = new conexao();
$id = $q->sanitizaString($_GET['id']);
$q->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id='$id'");

?>
<!DOCTYPE html>
<head>
	<title>Edição de usuário</title>
	<meta charset="utf-8">
	<style>
	#campoEmail:invalid {
		background-color: #ffdddd;
	}

	#campoEmail:valid {
		background-color: #ddffdd;
	}
	</style>
<form action="salva_edicao.php?id=<?php echo $id ?>" method="post">
<ul>
	<li>Nome <input required name="usuario_nome" type="text" value="<?=$q->resultado['usuario_nome']?>" /></li>
	<li>Login <input required name="usuario_login" type="text" value="<?=$q->resultado['usuario_login']?>" /></li>
	<li>Digite uma nova senha ou deixe em branco <input name="usuario_senha" type="text" value="" /></li>
	<li>Data de nascimento <input required name="usuario_data_aniversario" type="text" value="<?=$q->resultado['usuario_data_aniversario']?>" /></li>
	<li>Nome da mãe <input required name="usuario_nome_mae" type="text" value="<?=$q->resultado['usuario_nome_mae']?>" /></li>
	<li>E-mail <input required id="campoEmail" name="usuario_email" type="email" value="<?=$q->resultado['usuario_email']?>" /></li>
	<li><input name="Salvar" type="submit" value="Salvar" /></li>
</ul>
</form>