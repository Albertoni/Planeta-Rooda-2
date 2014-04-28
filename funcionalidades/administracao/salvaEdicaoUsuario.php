<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../funcoes_aux.php');



print_r($_POST);

$q = new conexao();

$id = $q->sanitizaString($_GET['id']);

$nomeUsuario = $q->sanitizaString($_POST['usuario_nome']);
$usuarioLogin = $q->sanitizaString($_POST['usuario_login']);
$data= DateTime::createFromFormat('d/m/Y', $_POST['usuario_data_aniversario']);
$usuarioDataAniversario = $data->format('Y-m-d H:i:s');
$usuarioNomeMae = $q->sanitizaString($_POST['usuario_nome_mae']);
$usuarioEmail = $q->sanitizaString($_POST['usuario_email']);

$q->solicitar(" UPDATE usuarios SET usuario_nome='$nomeUsuario' , usuario_login='$usuarioLogin', 
				usuario_data_aniversario='$usuarioDataAniversario', usuario_nome_mae='$usuarioNomeMae',
				usuario_email='$usuarioEmail' WHERE usuario_id='$id' ");

echo $q->erro;

if($_POST['usuario_senha']!=''){
	$usuarioSenha = crypt($_POST['usuario_senha'], "$2y$07$".gen_salt(22));
	$q->solicitar(" UPDATE usuarios SET usuario_senha='$usuarioSenha' 
				 WHERE usuario_id='$id' ");
	};
