<?php
define("MATERIAL_LINK", 1);
define("MATERIAL_ARQUIVO", 2);
class Material { // Eu ia fazer uma piada sobre coisas imateriais, mas nada veio à mente.
	private $codMaterial    = 0;
	private $codTurma       = 0;
	private $codRecurso     = 0; // codigo do link ou arquivo
	private $codUsuario     = 0;
	private $codAutor       = 0;
	private $titulo			= "";
	private $autor			= ""; // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	private $codUsuario		= 0;
	private $nomeUsuario	= "";
	private $refMaterial	= 0;
	private $tipoMaterial   = 0;
	private $data			= "";
	private $hora			= "";
	private $erro			= "";
	private $tags			= "";
	private $arquivo		= NULL; // guarda uma estrutura de arquivo caso o material seja um arquivo
	
	function __construct($id = false) {
		global $tabela_Materiais;
	}
}