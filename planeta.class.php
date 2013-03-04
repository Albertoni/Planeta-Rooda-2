<?php
	
require_once("funcoes_aux.php");
require_once("cfg.php");
require_once("bd.php");
require_once("usuarios.class.php");

class Planeta{
//dados
	/*
	* Id do planeta no banco de dados.
	*/
	private $id;
	
	/*
	* Nome do planeta no banco de dados.
	*/
	private $nome;
	
	/*
	* Id no banco de dados do principal terreno do planeta.
	*/
	private $idTerrenoPrincipal;

	/*
	* Aparência do planeta.
	*/
	private $aparencia;
	const PLANETA_VERDE='1';
	const PLANETA_GRAMA='2';
	const PLANETA_LAVA='3';
	const PLANETA_GELO='4';
	const PLANETA_URBANO='5';
	const PLANETA_QUARTO='6';
	
//métodos
	function Planeta(){
		
	}
	
	/*
	* Dada uma id, preenche os campos com os dados do planeta encontrados no banco de dados.
	* @param idPlaneta_param Id do planeta a ser buscada no banco de dados.
	*/
	public function openPlaneta($idPlaneta_param){
		$this->id = $idPlaneta_param;
		
		$conexaoDadosPlaneta = new conexao();
		$conexaoDadosPlaneta->solicitar("SELECT * FROM Planetas WHERE Id=".($this->id));
		
		$terrenos = $conexaoDadosPlaneta->resultado['Terrenos'];
		$terrenos = explode(",", $terrenos);
		
		$this->nome = $conexaoDadosPlaneta->resultado['Nome'];
		$this->idTerrenoPrincipal = $terrenos[0];
		$this->aparencia = $conexaoDadosPlaneta->resultado['Aparencia'];
	}
	
	public function getId(){ return $this->id; }
	public function getNome(){ return $this->nome; }
	public function getAparencia(){ return $this->aparencia; }
	public function getIdTerrenoPrincipal(){ return $this->idTerrenoPrincipal; }
	
	/*
	* Retornará a id do icone deste planeta em IconesPlanetas
	*/
	public function getIdIcone(){
		$conexaoImagemIcone = new conexao();
		$conexaoImagemIcone->solicitar("SELECT * 
										FROM IconesPlanetas 
										WHERE planeta=".$this->id);
		return $conexaoImagemIcone->resultado['id'];
	}
	
	
}



?>