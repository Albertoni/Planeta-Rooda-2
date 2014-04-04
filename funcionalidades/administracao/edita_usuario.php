<?php 
require('../../cfg.php');
require('../../bd.php');



print_r($_GET);

$q = new conexao();
$id = $q->sanitizaString($_GET['id']);
$q->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id='$id'");

?>
<li>Nome <input name="usuarionome" type="text" value="<?=$q->resultado['usuario_nome']?>" /></li>
<li>Login <input type="text" value="<?=$q->resultado['usuario_login']?>" /></li>
<li>Senha <input type="text" value="<?=$q->resultado['usuario_senha']?>" /></li>
<li>Nova Senha <input type="text" value="<?=$q->resultado['usuario_nova_senha']?>" /></li>
<li>Data de Nascimento <input type="text" value="<?=$q->resultado['usuario_data_aniversario']?>" /></li>
<li>Nome da mãe<input type="text" value="<?=$q->resultado['usuario_nome_mae']?>" /></li>
<li>E-mail <input type="text" value="<?=$q->resultado['usuario_email']?>" /></li>

<?php
$nomeUsuario = $q->sanitizaString($_POST['usuarionome']);
$usuarioLogin = $q->sanitizaString($_POST['usuario_login']);
$usuarioSenha = $q->sanitizaString($_POST['usuario_senha']);
$usuarioNovaSenha = $q->sanitizaString($_POST['usuario_nova_senha']);
$usuarioDataAniversario = $q->sanitizaString($_POST['usuario_data_aniversario']);
$usuarioNomeMae = $q->sanitizaString($_POST['usuario_nome_mae']);
$usuarioEmail = $q->sanitizaString($_POST['usuario_email']);

$q->solicitar("UPDATE usuarios SET usuario_nome='$nomeUsuario' , usuaio_login='$usuarioLogin', usuario_senha='$usuarioSenha', 
				usuario_nova_senha='$usuarioNovaSenha', usuario_data_aniversario='$usuarioDataAniversario', usuario_nome_mae='$usuarioNomeMae',
				usuario_email='$usuarioEmail' WHERE usuario_id='$id' )");
				