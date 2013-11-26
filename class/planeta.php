<?php
/**
* Classe para representar objetos planeta, que estão associados a turmas e possuem terrenos.
* Torna transparente o uso do banco de dados.
*/

include_once("bd/planetabd.php");

class Planeta extends PlanetaBD{
//dados
	private /*Terreno*/ $terrenoPrincipal;
	private /*Terreno*/ $terrenoPatio;
	private /*Turma*/ $turma;
	
//métodos

		/*
		* Construtores.
		*/
	/**
	* Construtor de objetos que não estão no BD.
	* @param String		nome_param			Nome do planeta.
	* @param int		aparencia_param		PlanetaBD::APARENCIA_GRAMA, PlanetaBD::APARENCIA_LAVA,...
	* @param Boolean	ehVisitante_param	Indica se este terreno é visitante. Não é obrigatório.
	* @return int		Id do planeta criado, que já estará no BD.
	*					Se retornar IObjetoBD::ID_OBJETO_NAO_SALVO, não conseguiu salvar.
	*/
	public static function getIdNovo($nome_param="Planeta sem nome", $aparencia_param=PlanetaBD::APARENCIA_GRAMA, $ehVisitante_param=false){
		$planeta = new PlanetaBD(self::ID_OBJETO_NAO_SALVO, $nome_param, $aparencia_param, $ehVisitante_param);
		
		$planeta->inserir();
		return $planeta->Id;
	}
	/**
	* Construtor de objetos que estão no BD.
	* @param int	id_param		Id no BD do planeta procurado.
	* @return Planeta		O planeta procurado, caso haja. 
	*						Caso contrário, retornará null.
	*/
	public static function getPorId($id_param){
		$conexao = new conexao();
		$conexao->solicitar("SELECT *
							FROM ".PlanetaBD::NOME_TABELA."
							WHERE Id = ".$id_param);
		$resultado = (0 < $conexao->registros? PlanetaBD::deTupla($conexao->resultado) : null);
		return $resultado;
	}

		/*
		* Setters.
		* @return Booleano 	se conseguir salvar, true
		*					senão, false
		*/
	public function setNome($nome_param){
		$this->Nome = $nome_param;
		return $this->atualizar();
	}
	public function setAparencia($aparencia_param){
		$this->Aparencia = $aparencia_param;
		return $this->atualizar();
	}
	public function setEhVisitante($ehVisitante_param){
		$this->EhVisitante = $ehVisitante_param;
		return $this->atualizar();
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