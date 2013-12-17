<?php

include_once('../../../usuarios.class.php');
include_once('../model/Personalidade/fatoresPersonalidadePersistente.php');

$usuario_post = $_POST['nomeUsuario'];

//Definição de constantes
const SEM_ERRO = 0;
const ERRO_MUITOS_USUARIOS = 1;
const ERRO_POUCOS_USUARIOS = 2;
const ERRO_BD = 3;

//Inicializações
$erro = SEM_ERRO;

//Início
$usuariosComNome = Usuario::buscaPorNome($usuario_post);
$usuariosComNome = $usuariosComNome[0];
$fatoresPersonalidade = new fatoresPersonalidadePersistente($usuariosComNome->getId());
$fatoresPersonalidade->busca();

if($erro == SEM_ERRO){
	//Dados recebidos da view
	$usuario			 = $usuario_post;
	$assistencia		 = $fatoresPersonalidade->__get("assistencia");
	$intracepcao		 = $fatoresPersonalidade->__get("intracepcao");
	$afago				 = $fatoresPersonalidade->__get("afago");
	$deferencia			 = $fatoresPersonalidade->__get("deferencia");
	$afiliacao			 = $fatoresPersonalidade->__get("afiliacao");
	$dominancia			 = $fatoresPersonalidade->__get("dominancia");
	$denegacao			 = $fatoresPersonalidade->__get("denegacao");
	$desempenho			 = $fatoresPersonalidade->__get("desempenho");
	$exibicao			 = $fatoresPersonalidade->__get("exibicao");
	$agressao			 = $fatoresPersonalidade->__get("agressao");
	$ordem				 = $fatoresPersonalidade->__get("ordem");
	$persistencia		 = $fatoresPersonalidade->__get("persistencia");
	$mudanca			 = $fatoresPersonalidade->__get("mudanca");
	$autonomia			 = $fatoresPersonalidade->__get("autonomia");
	$heterossexualidade	 = $fatoresPersonalidade->__get("heterossexualidade");
}


?>