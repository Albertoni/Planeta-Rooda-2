<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../funcoes_aux.php');

$q = new conexao();

$id = $q->sanitizaString($_POST['usuario_id']);

$nomeUsuario = $q->sanitizaString($_POST['usuario_nome']);
$usuarioLogin = $q->sanitizaString($_POST['usuario_login']);
$data= DateTime::createFromFormat('d/m/Y', $_POST['usuario_data_aniversario']);
$usuarioDataAniversario = $data->format('Y-m-d H:i:s');
$usuarioNomeMae = $q->sanitizaString($_POST['usuario_nome_mae']);
$usuarioEmail = $q->sanitizaString($_POST['usuario_email']);

$q->solicitar(" UPDATE usuarios SET usuario_nome='$nomeUsuario' , usuario_login='$usuarioLogin', 
				usuario_data_aniversario='$usuarioDataAniversario', usuario_nome_mae='$usuarioNomeMae',
				usuario_email='$usuarioEmail' WHERE usuario_id='$id' ");

if($q->erro != ""){
	echo "Aconteceu algum erro ao salvar os dados do aluno: ".$q->erro;
}

if($_POST['usuario_senha']!=''){
	$usuarioSenha = crypt($_POST['usuario_senha'], "$2y$07$".gen_salt(22));
	$q->solicitar("UPDATE usuarios SET usuario_senha='$usuarioSenha' 
				 WHERE usuario_id='$id'");
};

if($q->erro != ""){
	echo "Aconteceu algum erro ao salvar a senha nova do aluno: ".$q->erro;
}else{
	echo "<script>alert('Salvo com sucesso, clique para voltar.');</script>";
	magic_redirect("lista_usuarios.php");
}