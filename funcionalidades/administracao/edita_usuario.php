<?php 
require('../../cfg.php');
require('../../bd.php');



print_r($_GET);

$q = new conexao();
$id = $q->sanitizaString($_GET['id']);
$q->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id='$id'");

?>
<li>Nome <input type="text" value="<?=$q->resultado['usuario_nome']?>" /></li>
<li>Login <input type="text" value="<?=$q->resultado['usuario_login']?>" /></li>
<li>Senha <input type="text" value="<?=$q->resultado['usuario_senha']?>" /></li>
<li>Nova Senha <input type="text" value="<?=$q->resultado['usuario_nova_senha']?>" /></li>
<li>Data de Nascimento <input type="text" value="<?=$q->resultado['usuario_data_aniversario']?>" /></li>
<li>Nome da mãe<input type="text" value="<?=$q->resultado['usuario_nome_mae']?>" /></li>
<li>E-mail <input type="text" value="<?=$q->resultado['usuario_email']?>" /></li>