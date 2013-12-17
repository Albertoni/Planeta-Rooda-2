<?php
/**
* Representação básica de um planeta no banco de dados.
*/

	//BD
include_once(dirname(__FILE__)."/../../bd.php");
include_once(dirname(__FILE__)."/../../cfg.php");

	//interface
include_once("objetobd.php");

	//class
include_once(dirname(__FILE__)."/../terreno.php");

class PlanetaBD extends ObjetoBD{
//dados
		//Aparências
	const APARENCIA_VERDE='1';
	const APARENCIA_GRAMA='2';
	const APARENCIA_LAVA='3';
	const APARENCIA_GELO='4';
	const APARENCIA_URBANO='5';
	const APARENCIA_QUARTO='6';
	
		//Dados
	protected $Id;
	protected $Nome;
	protected $Aparencia;
	protected $EhVisitante;
	protected $IdTerrenoPrincipal;
	protected $IdTerrenoPatio;
	protected $IdTurma;
	const NOME_TABELA = "Planetas";

//métodos
	protected function PlanetaBD($id_param = self::ID_OBJETO_NAO_SALVO, $nome_param, $aparencia_param, 
			$ehVisitante_param, $idTerrenoPrincipal_param = self::ID_OBJETO_NAO_SALVO, $idTerrenoPatio_param = self::ID_OBJETO_NAO_SALVO, $idTurma_param = self::ID_OBJETO_NAO_SALVO){
		$this->Id = $id_param;
		$this->Nome = $nome_param;
		$this->Aparencia = $aparencia_param;
		$this->EhVisitante = $ehVisitante_param;
		$this->IdTerrenoPrincipal = $idTerrenoPrincipal_param;
		$this->IdTerrenoPatio = $idTerrenoPatio_param;
		$this->IdTurma = $idTurma_param;
	}
	
	/**
	* "Inicializador".
	* @param Array<String,Object>		array_param		Array retornado por uma consulta SQL.
	* @return 
	*/
	protected static function deTupla($array_param){
		return new PlanetaBD(	$array_param['Id'], $array_param['Nome'], $array_param['Aparencia'], $array_param['EhVisitante'], 
								$array_param['IdTerrenoPrincipal'], $array_param['IdTerrenoPatio'], $array_param['Turma']);
	}
	
	/**
	* Salva este objeto no banco de dados.
	* Decide se deve usar inserir ou atualizar.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function salvar($conexao_param=""){
		if($this->Id == self::ID_OBJETO_NAO_SALVO){
			return $this->inserir($conexao_param);
		} else {
			return $this->salvar($conexao_param);
		}
	}

	/**
	* Insere este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function inserir($conexao_param=""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		if($this->IdTerrenoPrincipal == self::ID_OBJETO_NAO_SALVO){
			$this->IdTerrenoPrincipal = Terreno::getIdNovo(TerrenoBD::TIPO_PRINCIPAL);
		}
		if($this->IdTerrenoPatio == self::ID_OBJETO_NAO_SALVO){
			$this->IdTerrenoPatio = Terreno::getIdNovo(TerrenoBD::TIPO_PATIO);
		}
		$conexao->solicitar("INSERT INTO ".self::NOME_TABELA." (Nome, Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio)
							VALUES ('".$this->Nome."', ".$this->Aparencia.", ".$this->EhVisitante.", ".$this->IdTerrenoPrincipal.", ".$this->IdTerrenoPatio.")");
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		} else {
			$this->Id = $conexao->ultimoId();
		}
	}
	
	/**
	* Atualiza este objeto no banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function atualizar($conexao_param=""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		$terrenoPrincipal = Terreno::getPorId($this->IdTerrenoPrincipal);
		$terrenoPatio = Terreno::getPorId($this->IdTerrenoPatio);
		$terrenoPrincipal->atualizar($conexao);
		$terrenoPatio->atualizar($conexao);
		$conexao->solicitar("UPDATE ".self::NOME_TABELA."
							SET Nome = ".$this->Nome.", 
								Aparencia = ".$this->Aparencia.",
								EhVisitante = ".$this->EhVisitante.",
								IdTerrenoPrincipal = ".$this->IdTerrenoPrincipal.",
								IdTerrenoPatio = ".$this->IdTerrenoPatio."
							WHERE Id = ".$this->Id);
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		}
	}

	/**
	* Deleta este objeto do banco de dados.
	* @param conexao		conexao_param		Conexão que será usada. Se não existir, criará nova conexão.
	*/
	public function deletar($conexao_param=""){
		$conexao = ($conexao_param == ""? new conexao() : $conexao_param);
		$terrenoPrincipal = Terreno::getPorId($this->IdTerrenoPrincipal);
		$terrenoPatio = Terreno::getPorId($this->IdTerrenoPatio);
		$terrenoPrincipal->deletar($conexao);
		$terrenoPatio->deletar($conexao);
		$conexao->solicitar("DELETE FROM ".self::NOME_TABELA." WHERE Id = ".$this->Id);
		if($conexao->erro != ""){
			throw new Exception($conexao->erro);
		}
	}
	
		/*
		* Getters.
		* @param String		propriedade_param		A propriedade que deseja-se consultar.
		*/
	public function __get($propriedade_param){
		return $this->$propriedade_param;
	}
	
	
	
	
	
	
	
	
}

?>
